require('../../css/pages/home.scss');

$(function () {

    let navbar = $('.navbar');

    checkScroll();

    $(window).scroll(function() {
        checkScroll();
    });

    function checkScroll() {
        if ($(window).scrollTop() < 100) {
            navbar.css('background-color', 'transparent');
        } else {
            navbar.css('background-color', '#343a40');
        }
    }


});