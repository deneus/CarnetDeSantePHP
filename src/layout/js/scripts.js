Dropzone.options.myAwesomeDropzone = {
    //maxFilesize: 2, // MB
    acceptedFiles: '.jpg',
};

( function ( $ ) {
    // Initialize Slidebars
    var controller = new slidebars();
    controller.init();
    $('.close-menu').css('display', 'none');

    $('.open-menu').on('click', function (event){
        event.stopPropagation();
        event.preventDefault();

        controller.open('main-menu');
        $('.open-menu').css('display', 'none');
        $('.close-menu').css('display', 'block');
    }) ;

    $('.close-menu').on('click', function (event){
        event.stopPropagation();
        event.preventDefault();

        controller.close('main-menu');
        $('.open-menu').css('display', 'block');
        $('.close-menu').css('display', 'none');
    }) ;

} ) ( jQuery );

$( document ).ready(function() {
    // Documentation; https://www.dynatable.com/
    var dynatable = $("#listOfNotes").dynatable();

    $( "#dob" ).datepicker();

    new Clipboard('#copyToClipboardBtn')

    // Animation of the forms.
    $('.loginForm, .registerForm, .registerPost').animate({ top: '+=30em' }, 600, 'easeOutBack');

    // Auto submit the login when you have a login parameter set.
    if (findLoginParameter() !== null){
        $('#login').val(findLoginParameter());
        $('#loginForm').submit();
    }
});

/**
 * Find the login from the url after the # sign.
 * @returns {*}
 */
function findLoginParameter() {
    var query = window.location.toString().split('%%');
    var parameters = query[1].split('=');
    if (parameters[0] == 'login' && parameters[1].length > 0) {
        return parameters[1]
    }
    else {
        return null;
    }
}