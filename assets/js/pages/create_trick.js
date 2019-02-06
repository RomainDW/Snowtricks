require('../../css/pages/create_trick.scss');

let $addVideoLink = $('<a href="#" class="add_tag_link">Ajouter une vidéo</a>');
let $addImageLink = $('<a href="#" class="add_tag_link">Ajouter une image</a>');

let $newVideoLinkLi = $('<li></li>').append($addVideoLink);
let $newImageLinkLi = $('<li></li>').append($addImageLink);

jQuery(document).ready(function() {
    let $videoCollectionHolder = $('ul.videos');
    let $imageCollectionHolder = $('ul.images');

    $videoCollectionHolder.find('li').each(function() {
        $videoCollectionHolder($(this));
    });

    $imageCollectionHolder.find('li').each(function() {
        $imageCollectionHolder($(this));
    });

    // add the "add a video" anchor and li to the tags ul
    $videoCollectionHolder.append($newVideoLinkLi);

    // add the "add an image" anchor and li to the tags ul
    $imageCollectionHolder.append($newImageLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $videoCollectionHolder.data('index', $videoCollectionHolder.find(':input').length);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $imageCollectionHolder.data('index', $imageCollectionHolder.find(':input').length);

    $addVideoLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new video form (see code block below)
        addForm($videoCollectionHolder, $newVideoLinkLi);
    });

    $addImageLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new image form (see code block below)
        addForm($imageCollectionHolder, $newImageLinkLi);
    });
});

function addForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    let prototype = $collectionHolder.data('prototype');

    // get the new index
    let index = $collectionHolder.data('index');

    // Replace '$$name$$' in the prototype's HTML to
    // instead be a number based on how many items we have
    let newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a video" link li
    let $newFormLi = $('<li></li>').append(newForm);

    $newLinkLi.before($newFormLi);

    addFormDeleteLink($newFormLi);
}

function addFormDeleteLink($formLi) {
    let $removeFormButton = $('<button type="button">Supprimer</button>');
    $formLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $formLi.remove();
    });
}