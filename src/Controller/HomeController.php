<?php

namespace App\Controller;

use App\Entity\LastImport;
use App\Repository\CategorieRepository;
use App\Repository\FilterRepository;
use App\Repository\LigneRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use DOMXPath;
use DOMDocument;
use App\Entity\Ligne;
use App\Entity\Etapes;
use App\Entity\Filter;
use App\Entity\Statut;
use App\Entity\Categorie;
use JetBrains\PhpStorm\NoReturn;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Range;


class HomeController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/', name: 'home')]
    public function index(Request $request,LigneRepository $ligneRepository,SessionInterface $session,UserRepository $userRepository,CategorieRepository $categorieRepository): Response
    {
        $session->set('userID',$this->security->getUser()->getId());
        $session->set('user',$this->security->getUser());
        $sort = $request->query->get('sort');
        $order = $request->query->get('order');
        if ($order == null) {
            $sort = 'date';
            $order = 'DESC';
        }
        $user = $this->getUser();
        $lignes = $user->getLignesCreated($sort, $order);
        $to_filter = $ligneRepository->findBy(['categorie' => null]);
        $du = $ligneRepository->findBy(['statut' => 1]);
        $to_pay = $ligneRepository->findBy(['statut' => 2]);
        $categories = $categorieRepository->findBy(['User' => $this->getUser()], array('libelle' => 'ASC'));

        // $du_total = $ligneRepository->sumDu($user)[0]['total'];
        // $to_pay_total = $ligneRepository->sumToPay($user)[0]['total'];
        // $sum = $ligneRepository->sum($user)[0]['total'];
        // $sum = round($sum, 2);

        $du_total = 0;
        $to_pay_total = 0;
        $sum = 0;



        return $this->render('home/index.html.twig', [
            'active' => 'home',
            'categories' => $categories,
            'to_filter' => $to_filter,
            'lignes' => $lignes,
            'to_pay' => $to_pay,
            'du' => $du,
            'sum' => $sum,
            'total_to_pay' => $to_pay_total,
            'total_du' => $du_total,
            'sort' => $sort,
            'order' => $order,
        ]);
    }

    #[Route('/resume/{year}', name: 'resume')]
    public function resume($year,CategorieRepository $categorieRepository, LigneRepository $ligneRepository)
    {
        $categories = $categorieRepository->findBy(['User'=> $this->getUser()], array('libelle' => 'ASC'));
        $sumByMonthYear =[];
        foreach ($ligneRepository->getMonth($year,$this->getUser()) as $month) {
            $sumByMonthYear +=[$month['month']=>$ligneRepository->sumByMonth($month['month'],$year,$this->getUser())];
        }
        $sumByMonthByCat = $ligneRepository->sumByMonthByCat($year,$this->getUser());
        $years = $ligneRepository->getYears();
        $sumByMonthByCat = $this->MonthsToMois($sumByMonthByCat);
        $sumByMonthYear = $this->MonthsToMois($sumByMonthYear);
        return $this->render('home/resume.html.twig', [
            'active' => 'resume',
            'years' => $years,
            'year' => $year,
            'categories' => $categories,
            'sumByMonth' => $sumByMonthByCat,
            'sumByMonthYear' => $sumByMonthYear,
        ]);
    }
    #[Route('/resume/see/{year}/{month}', name: 'resume.see')]
    public function see($year, $month, Request $request , LigneRepository $ligneRepository)
    {
        $month = $this->MoisToMonth($month);
        $sort = $request->query->get('sort');
        $order = $request->query->get('order');
        if($sort == null){
            $sort = 'categorie';
        }
        $lignes = $ligneRepository->findByMonth($year, $month, $sort, $order,$this->getUser());
        $sum = $ligneRepository->sumByMonth($month,$year,$this->getUser());

        return $this->render('home/see.html.twig', [
            'lignes' => $lignes,
            'sort' => $sort,
            'order' => $order,
            'sum' => $sum
        ]);
    }

    #[Route('/line/set/status/{id}/{statut}', name: 'line.set.statut')]
    public function setStatut($id, $statut)
    {
        $em = $this->getDoctrine()->getManager();
        $line = $this->getDoctrine()->getRepository(Ligne::class)->find($id);
        $statut = $this->getDoctrine()->getRepository(Statut::class)->find($statut);

        $line->setStatut($statut);

        $etape = new Etapes($line, $statut);
        $em->persist($etape);
        $em->flush();
        return $this->redirectToRoute('home');
    }


    #[Route('/import/{option}', name: 'import')]
    public function import($option, Request $request,EntityManagerInterface $entityManager,CategorieRepository $categorieRepository, LigneRepository $ligneRepository, FilterRepository $filterRepository): Response
    {

        switch ($option) {
            case 'HTML':
                $this->importLinesFromHTML($request->request->get('HTML'));
                break;

            case 'XLS':
                $this->importLinesFromXls($request->files->get('xls'));

                break;
            default:

                break;
        }
        

      
        $this->sync($entityManager,$categorieRepository,$ligneRepository,$filterRepository);
        return $this->redirectToRoute('home');
    }


    #[Route('/sync', name: 'sync')]
    public function sync(EntityManagerInterface $entityManager,CategorieRepository $categorieRepository, LigneRepository $ligneRepository, FilterRepository $filterRepository): Response
    {
        $em = $entityManager;

        $lignes = $ligneRepository->findBy(['categorie' => null, 'user' => $this->getUser()]);
        $filters = $filterRepository->findBy(['user' => $this->getUser()]);
        $revenu = $categorieRepository->findBy(['User'=>$this->getUser(),'libelle'=>'Revenu']);
        foreach ($lignes as $ligne) {

            if ($ligne->getMontant() > 0 and !empty($revenu)) {
                $ligne->setCategorie($revenu[0]);
                $em->flush();
                continue;
            }
            foreach ($filters as $filter) {
                $libelle = strtolower($ligne->getLibelle());
                $kw = strtolower($filter->getKeyword());

                if (str_contains($libelle, $kw)) {
                    $ligne->setCategorie($filter->getCategorie());
                    $em->flush();
                }
            }
        }


        return $this->redirectToRoute('settings');
    }
    #[Route('/categoriser', name: 'categoriser')]
    public function categoriser(Request $request)
    {
        foreach ($request->request as $key => $value) {
            $ligne = explode('_', $key)[1];
            $ligne = $this->getDoctrine()->getRepository(Ligne::class)->find($ligne);
            $categorie = $this->getDoctrine()->getRepository(Categorie::class)->find($value);

            $ligne->setCategorie($categorie);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->redirectToRoute('home');
    }


    #[NoReturn] #[Route('/export', name: 'export')]
    public function export(Request $request): Response
    {
        $lignes = $this->getDoctrine()->getRepository(Ligne::class)->findAll();

        $file = fopen('comptes.csv', 'w');
        foreach ($lignes as $ligne) {
            $array = array(
                $ligne->getDate()->format('d/m/Y'),
                $ligne->getType(),
                $ligne->getLibelle(),
                $ligne->getMontant()
            );

            fputcsv($file, $array, ';');
        }
        fclose($file);
        die();
    }
    private function MonthsToMois($months): array
    {
        $mois = [];
        for ($i = 0; $i < sizeof($months); $i++) {
            if (isset($months['January'])) {
                $mois['Janvier'] = $months['January'];
            }
            if (isset($months['February'])) {
                $mois['Février'] = $months['February'];
            }
            if (isset($months['March'])) {
                $mois['Mars'] = $months['March'];
            }
            if (isset($months['April'])) {
                $mois['Avril'] = $months['April'];
            }
            if (isset($months['May'])) {
                $mois['Mai'] = $months['May'];
            }
            if (isset($months['June'])) {
                $mois['Juin'] = $months['June'];
            }
            if (isset($months['July'])) {
                $mois['Juillet'] = $months['July'];
            }
            if (isset($months['August'])) {
                $mois['Août'] = $months['August'];
            }
            if (isset($months['September'])) {
                $mois['Septembre'] = $months['September'];
            }
            if (isset($months['October'])) {
                $mois['Octobre'] = $months['October'];
            }
            if (isset($months['November'])) {
                $mois['Novembre'] = $months['November'];
            }
            if (isset($months['December'])) {
                $mois['Décembre'] = $months['December'];
            }
        }
        return $mois;
    }
    private function MoisToMonth($mois) {
        switch ($mois)
        {
            case "Janvier":
                return 'January';
            case "Février":
                return 'February';
            case "Mars":
                return 'March';
            case "Avril":
                return 'April';
            case "Mai":
                return 'May';
            case "Juin":
                return 'June';
            case "Juillet":
                return 'July';
            case "Août":
                return 'August';
            case "Septembre":
                return 'September';
            case "Octobre":
                return 'October';
            case "Novembre":
                return 'November';
            case "Décembre":
                return 'December';
        }

    }

    public function findMatchLinesXls($dataArray): int|string|null
    {
        foreach ($dataArray as $key =>  $data) {
            $date = $data['A'];
            $date = date_create_from_format('d/m/Y', $date, new \DateTimeZone('Europe/paris'));

            $type = explode("\n", $data['B'])[0];
            $libelle = explode("\n", $data['B']);
            array_shift($libelle);
            $libelle = implode("\n", $libelle);


            if ($data['C'] == null) {
                $montant = floatval($data['D']);
            } else {
                $montant = floatval($data['C']) * -1;
            }
            $ligne = new Ligne();
            $ligne->setDate($date);
            $ligne->setLibelle($libelle);
            $ligne->setType($type);
            $ligne->setMontant($montant);
            $ligne->setDateInsert(new \DateTime('now', new \DateTimeZone('Europe/paris')));

            $exist = $this->getDoctrine()->getRepository(Ligne::class)->exist($ligne);
            if ($exist) {
                return $key;
            }
        }
        return null;
    }

    public function importLinesFromXls($file)
    {
        $em = $this->getDoctrine()->getManager();

        $dir = $this->getParameter('xls_dir');
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = $originalFilename . '.xlsx';
        $path = $dir . '/' . $filename;

        $file->move($dir, $filename);
        $reader = new Xlsx();
        $spreadsheet = $reader->load($path);
        $worksheet = $spreadsheet->getActiveSheet();

        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn();

        $range = 'A11:' . $highestColumn . $highestRow;

        $dataArray = $spreadsheet->getActiveSheet()
            ->rangeToArray(
                $range,     // The worksheet range that we want to retrieve
                NULL,        // Value that should be returned for empty cells
                TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                TRUE         // Should the array be indexed by cell row and cell column
            );

        $match = $this->findMatchLinesXls($dataArray);

        if ($match != null) {
            $range = $range = 'A11:' . $highestColumn . $match;
            $dataArray = $spreadsheet->getActiveSheet()
                ->rangeToArray(
                    $range,     // The worksheet range that we want to retrieve
                    NULL,        // Value that should be returned for empty cells
                    TRUE,        // Should formulas be calculated (the equivalent of getCalculatedValue() for each cell)
                    TRUE,        // Should values be formatted (the equivalent of getFormattedValue() for each cell)
                    TRUE         // Should the array be indexed by cell row and cell column
                );
        }
        foreach ($dataArray as $data) {
            $date = $data['A'];
            $date = date_create_from_format('d/m/Y', $date, new \DateTimeZone('Europe/paris'));

            $type = explode("\n", $data['B'])[0];
            $type = trim($type);
            $libelle = explode("\n", $data['B']);
            array_shift($libelle);
            $libelle = implode("\n", $libelle);


            if ($data['C'] == null) {
                $montant = floatval($data['D']);
            } else {
                $montant = floatval($data['C']) * -1;
            }

            $ligne = new Ligne();
            $ligne->setDate($date);
            $ligne->setLibelle($libelle);
            $ligne->setType($type);
            $ligne->setMontant($montant);
            $ligne->setDateInsert(new \DateTime('now', new \DateTimeZone('Europe/paris')));
            $ligne->setUser($this->getUser());
            $ligne->setOrigine(1); // 1 = import , automatic
            $em->persist($ligne);
            $em->flush();

        }

    }

    public function importLinesFromHTML($html)
    {
        $em = $this->getDoctrine()->getManager();
        $html = str_replace('id="dateOperation"', '', $html);
        $html = str_replace('id="dateValeur"', '', $html);
        $html = str_replace('id="montant"', '', $html);
        $html = str_replace('id="libelleOperation "', '', $html);
        $html = str_replace('id="libelleOperation"', '', $html);

        $doc = new DOMDocument();
        $doc->loadHTML($html);


        foreach ($doc->getElementsByTagName('li') as $line) {
            $ligne = new Ligne();

            foreach ($line->childNodes as $items) {
                if ($items->nodeName == "a") {
                    foreach ($items->childNodes as $item) {
                        $value = trim($item->nodeValue);
                        if (!empty($value)) {
                            $class = $item->getAttribute('class');

                            if (str_contains($class, 'date')) {
                                $date = utf8_decode($value);
                                $date = $this->convert_date_fr($date);
                                $date = new \DateTime($date);
                                $ligne->setDate($date);
                            } elseif (str_contains($class, 'Operation-value-')) {
                                $montant = substr($value, 0, -8);

                                $montant = str_replace('+', '', $montant);
                                $montant = str_replace(' ', '', $montant);
                                $montant = str_replace(chr(194) . chr(160), '', $montant);
                                $montant = str_replace(',', '.', $montant);

                                $ligne->setMontant((float)$montant);
                            } elseif (str_contains($class, 'main')) {
                                $type = $item->childNodes[1]->nodeValue;
                                $libelle = $item->childNodes[3]->nodeValue;

                                $ligne->setType($type);
                                $ligne->setLibelle($libelle);
                            }
                        }
                    }
                }
            }
            $ligne->setUser($this->getUser());
            $ligne->setOrigine(1); // 1 = import , automatic
            $em->persist($ligne);
            $em->flush();
        }
    }

    function convert_date_fr($date, $format_in = 'j F Y', $format_out = 'Y-m-d'): string
    {
        // French to english month names
        $months = array(
            'janvier' => 'january',
            'février' => 'february',
            'mars' => 'march',
            'avril' => 'april',
            'mai' => 'may',
            'juin' => 'june',
            'juillet' => 'july',
            'août' => 'august',
            'septembre' => 'september',
            'octobre' => 'october',
            'novembre' => 'november',
            'décembre' => 'december',
        );

        // List of available formats for date
        $formats_list = array('d', 'D', 'j', 'l', 'N', 'S', 'w', 'z', 'S', 'W', 'M', 'F', 'm', 'M', 'n', 't', 'A', 'L', 'o', 'Y', 'y', 'H', 'a', 'A', 'B', 'g', 'G', 'h', 'H', 'i', 's', 'u', 'v', 'F', 'e', 'I', 'O', 'P', 'T', 'Z', 'D', 'c', 'r', 'U');

        // We get separators between elements in $date, based on $format_in
        $split = str_split($format_in);
        $separators = array();
        $_continue = false;
        foreach ($split as $k => $s) {
            if ($_continue) {
                $_continue = false;
                continue;
            }
            // For escaped chars (like "\h")
            if ($s == '\\' && isset($split[$k + 1])) {
                $separators[] = '\\' . $split[$k + 1];
                $_continue = true;
                continue;
            }
            if (!in_array($s, $formats_list)) {
                $separators[] = $s;
            }
        }

        // Translate month name
        $tmp = preg_split('/(' . implode('|', array_map(function ($v) {
            if ($v == '/') {
                return '\/';
            }
            return str_replace('\\', '\\\\', $v);
        }, $separators)) . ')/', $date);

        foreach ($tmp as $k => $v) {
            $v = mb_strtolower($v, 'UTF-8');
            if (isset($months[$v])) {
                $tmp[$k] = $months[$v];
            }
        }

        // Re-construct the date
        $imploded = '';
        foreach ($tmp as $k => $v) {
            $imploded .= $v . (isset($separators[$k]) ? str_replace('\\', '', $separators[$k]) : '');
        }

        return DateTime::createFromFormat($format_in, $imploded)->format($format_out);
    }
    private function MoisToMonths($mois)
    {
        $months = [];
        for ($i = 0; $i < sizeof($mois); $i++) {
            if (isset($mois['Janvier'])) {
                $month['January'] = $mois['Janvier'];
            }
            if (isset($mois['Février'])) {
                $month['February'] = $mois['Février'];
            }
            if (isset($mois['Mars'])) {
                $month['March'] = $mois['Mars'];
            }
            if (isset($mois['Avril'])) {
                $month['April'] = $mois['Avril'];
            }
            if (isset($mois['Mai'])) {
                $month['May'] = $mois['Mai'];
            }
            if (isset($mois['Juin'])) {
                $month['June'] = $mois['Juin'];
            }
            if (isset($mois['Juillet'])) {
                $month['July'] = $mois['Juillet'];
            }
            if (isset($mois['Août'])) {
                $month['August'] = $mois['Août'];
            }
            if (isset($mois['Septembre'])) {
                $month['September'] = $mois['Septembre'];
            }
            if (isset($mois['Octobre'])) {
                $month['October'] = $mois['Octobre'];
            }
            if (isset($mois['Novembre'])) {
                $month['November'] = $mois['Novembre'];
            }
            if (isset($mois['Décembre'])) {
                $month['December'] = $mois['Décembre'];
            }
        }
        return $months;
    }
}