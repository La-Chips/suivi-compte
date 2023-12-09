
function darkMode() {
    return true;
}



function toggle_drawer(){
    if (document.getElementById("drawer").classList.contains("active")){
        document.getElementById("drawer").classList.remove("active");
    } else {
        document.getElementById("drawer").classList.add("active");
    }
}

$(document).ready(function() {
    if (darkMode()) {
        document.getElementsByTagName('body')[0].classList.add('dark-mode');
    }


    // // you may need to change this code if you are not using Bootstrap Datepicker
    $('.js-datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });
});
