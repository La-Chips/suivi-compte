<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UrlTest extends WebTestCase
{
    private function logClient()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(array('username' => 'clement'));

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        return $client;
    }

    public function testSomething(): void
    {
        $this->newFolder();

        $this->assertResponseIsSuccessful();
    }

    public function newFolder(): void
    {
        $client = $this->logClient();

        $data = ['name' => 'hey', 'rootFolder' => 1];
        $client = static::createClient();
        $client->request('POST', '/some_uri', ['data' => $data], [], [
            'HTTP_X-Requested-With' => 'XMLHttpRequest',
        ]);
    }
}