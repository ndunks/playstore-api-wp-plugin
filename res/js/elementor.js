// Elementor Hook for Screenshot carousel
jQuery(function($){
    /* window.elementorFrontend.hooks.addAction('frontend/element_ready/global', function(){
        console.log('Elementor Hook Ready'); */
        var carouselHandler = elementorFrontend.elementsHandler.getHandlers('image-carousel.default');
        new carouselHandler($('.elementor-widget-apk-screenshot'));
    //})
})