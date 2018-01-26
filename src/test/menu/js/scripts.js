

( function ( $ ) {
    // Initialize Slidebars
    var controller = new slidebars();
    controller.init();

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