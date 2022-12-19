var $j = jQuery.noConflict();

$j(function(){
    $j('.slick-slider').slick({
        autoplay: true,
        autoplaySpeed: 2000,
        arrows: false,
        fade: true
    });
});