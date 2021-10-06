$(document).ready(function() {
    if (darkMode()) {
        document.getElementsByTagName('body')[0].classList.add('dark-mode');
    }


    // you may need to change this code if you are not using Bootstrap Datepicker
    $('.js-datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });
});

function darkMode() {
    return true;
}