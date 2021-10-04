document.getElementById('upload_files_files').addEventListener('change', () => {

    document.getElementsByName('upload_files')[0].submit();

}
);

document.getElementById('new_folder_input').addEventListener('keypress', (e) => {

    if(e.target.value == "" && (e.key == 'Enter' || e.key == 'NumpadEnter')){
        e.target.parentNode.classList.add('hide');
    }else if(e.key == 'Enter' || e.key == 'NumpadEnter') {

        $.ajax({
            url: "/folder/new",
            method: 'POST',
            data: { name : e.target.value , root : rootFolder}
          }).done(function(data) {
            e.target.parentNode.classList.add('hide');
          });
    }

}
);

// $(document).on('click', function (event) {
//     let newFolder = docu
//     console.log(event.target);
//     document.getElementById('new_folder_container').classList.add('hide');
 

// });

function newFolder() {
    document.getElementById('new_folder_container').classList.remove('hide');
    document.getElementById('new_folder_input').focus();
}