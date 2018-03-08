Dropzone.options.myAwesomeDropzone = {
    //maxFilesize: 2, // MB
    acceptedFiles: '.jpg, .jpeg,  .pdf',
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
    var split = window.location.toString().split('__');
    if (split.length > 1) {
        if ($('#login').val() !== '') {
            $('#loginForm').submit();
        }
    }

    var dynatable = $(".list-of-records").dynatable();

});

