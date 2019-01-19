require('../../css/pages/home.scss');

$(function () {

    /* Start Navbar */
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
    /* End navbar */

    /* Start Smooth scrolling */
    $('.masthead__button').click(function(e) {
        e.preventDefault();
        // scrollTo( $("#content") );
        $([document.documentElement, document.body]).animate({
            scrollTop: $("#content").offset().top
        }, 800);
    });
    /* End Smooth scrolling */

    /* Start Pagination */
    $('body').on('click', '.page-link', function (e) {
        e.preventDefault();

        $.post($(this).attr('href'), function (data) {
            $('#content').html(data);
        })
    })
    /* End Pagination */

});