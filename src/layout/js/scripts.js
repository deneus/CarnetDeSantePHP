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
});