require('../../css/pages/show_trick.scss');

$(function () {
    // Get the modal
    var modal = document.getElementById('image-modal');

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = $('.trick-image');
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");
    img.on('click', function () {
        modal.style.display = "block";
        modalImg.src = this.src;
        captionText.innerHTML = this.alt;
    });

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close-modal")[0];

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    };

    let index = 0;
    $('[data-action="show-medias"]').on('click', function () {
        index++;
       $('.gallery').fadeToggle();

       // if even
       if (index % 2 === 0) {
           $(this).text('Afficher les photos & videos')
       } else {
           $(this).text('Masquer les photos & videos')
       }

    });

    $(document).ready(onResize);
    $(window).resize(onResize);

    function onResize () {
        if (isMobile()) {
            $('.gallery').fadeIn();
        } else {
            $('.gallery').fadeOut();
        }
    }
    
    function isMobile() {
        return ($( window ).width() > 575);
    }
});