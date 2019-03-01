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


    /* Init */
    loadPagination();
    displayBackToTop();

});

function loadPagination(){

    const cardDeck = $('.wrapper');
    let loader = cardDeck.find('#loader');

    // cette variable contient notre offset
    // par défaut à 6 puisqu'on a d'office les 6 premiers éléments au chargement de la page
    let offset = cardDeck.data('number-of-results');
    let counter = offset;
    const url = cardDeck.data('url');
    const totalEntities = cardDeck.data('total-entities');
    const totalPages = Math.ceil(totalEntities / offset);
    let currentPage = 1;

    // on initialise ajaxready à true au premier chargement de la fonction
    $(window).data('ajaxready', true);

    // On teste si ajaxready vaut false, auquel cas on stoppe la fonction
    if ($(window).data('ajaxready') === false) return;

    loader.on('click', function () {
        // lorsqu'on commence un traitement, on met ajaxready à false
        $(window).data('ajaxready', false);

        loader.find('.loader-btn').hide();
        loader.find('.loader-gif').show();

        // puis on fait la requête pour demander les nouveaux éléments
        setTimeout(function () {

            $.post(url + counter, function(data){
                // s'il y a des données
                if (data !== '') {
                    // on les insère juste avant le loader.gif;
                    cardDeck.find($('#loader')).before(data);

                    // enfin on incrémente notre offset de 6 afin que la fois d'après il corresponde toujours
                    counter+= offset;
                    currentPage++;

                    /* une fois tous les traitements effectués,
                     * on remet ajaxready à true
                     * afin de pouvoir rappeler la fonction */
                    $(window).data('ajaxready', true);

                } else {
                    loader.html('<div class="alert alert-warning" role="alert">Oops, il n\'y a plus de contenu.</div>');
                }

                loader.find('.loader-gif').hide();

                if (totalPages !== currentPage) {
                    setTimeout(function () {
                        loader.find('.loader-btn').show();
                    }, 300);
                }

                $("html, body").animate({
                    scrollTop: $('.page-footer').offset().top
                    - $(window).height()
                }, 1000);
            });



        }, 1500);
    });
}

function displayBackToTop () {

    $( document ).one('ajaxComplete', function () {
        $('.cd-top').css('display', 'inline-block');
    })
}