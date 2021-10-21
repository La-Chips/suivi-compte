document.getElementById('upload_files_files').addEventListener('change', () => {

    document.getElementsByName('upload_files')[0].submit();

});

document.getElementById('new_folder_input').addEventListener('keypress', (e) => {

    if (e.target.value == "" && (e.key == 'Enter' || e.key == 'NumpadEnter')) {
        e.target.parentNode.classList.add('hide');
    } else if (e.key == 'Enter' || e.key == 'NumpadEnter') {
        $.ajax({
            url: "/folder/new/item",
            method: 'POST',
            data: { name: e.target.value, root: rootFolder }
        }).done(function(data) {

            e.target.parentNode.classList.add('hide');
        });
    }

});


function dragStart(event) {}

function dragOverEnter(event) {
    event.preventDefault();
    event.target.classList.add('drag-over');

}

function dragOverOut(event) {
    event.preventDefault();
    event.target.classList.remove('drag-over');
}

function drop(event) {
    console.log(event);
}


// $(document).on('click', function (event) {
//     let newFolder = docu
//     console.log(event.target);
//     document.getElementById('new_folder_container').classList.add('hide');


// });

function newFolder() {
    document.getElementById('new_folder_container').classList.remove('hide');
    document.getElementById('new_folder_input').focus();
}

function addDropListener(element) {

    element.addEventListener("drop", function(event) {
        console.log(event);
        // Empêche l'action par défaut (ouvrir comme lien pour certains éléments)
        event.preventDefault();
        // Déplace l'élément traîné vers la cible du drop sélectionnée
        if (event.target.className == "dropzone") {
            event.target.style.background = "";
            dragged.parentNode.removeChild(dragged);
            event.target.appendChild(dragged);
        }

    });

}

let array = document.getElementsByClassName('folder');
for (let index = 0; index < array.length; index++) {
    let element = array[index];
    addDropListener(element);

}