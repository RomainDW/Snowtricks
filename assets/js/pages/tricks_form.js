require('../../css/pages/tricks_form.scss');

$(function () {
    initForm($('#trick_form_images'), '<i class="fas fa-plus"></i> Image');
    initForm($('#trick_form_videos'), '<i class="fas fa-plus"></i> Vidéo');
    $("div[id^='trick_form_images_'], div[id^='trick_form_videos_']").each(function () {
        if ($(this).attr('id').indexOf('errors') === -1) {
            $(this).addClass('sub-form');
            initDelete($(this));
        }
    });

    inputFilePlaceholder();
});


function initForm(collectionHolder, addFormLinkContent) {
    let addFormLink = $('<button class="add-content btn btn-primary">' + addFormLinkContent + '</button>');
    let newAddFormLink = collectionHolder.append(addFormLink);

    collectionHolder.data('index', collectionHolder.find(':input').length);

    addFormLink.on('click', function (e) {
        e.preventDefault();
        addForm(collectionHolder, newAddFormLink);
    });
}
function addForm(collectionHolder, newAddFormLink) {
    let prototype = collectionHolder.data('prototype');
    let index = collectionHolder.data('index');
    let newForm = prototype.replace(/__name__/g, index);
    let newFormWrapper = $('<div>').append(newForm).addClass('sub-form');

    collectionHolder.data('index', index + 1);

    newAddFormLink.find('.add-content').before(newFormWrapper);
    initDelete(newFormWrapper);
    inputFilePlaceholder();
}
function initDelete(form) {
    let removeFormLink = $('<button class="remove-content btn btn-danger"><i class="far fa-trash-alt"></i>Supprimer</a>');
    form.append(removeFormLink);
    removeFormLink.on('click', function(e) {
        e.preventDefault();
        form.remove();
    });
}

function inputFilePlaceholder() {
    // Input file
    $('input[type="file"]').on('change', function(){
        var that = $(this);
        var file_name = that[0].files[0].name;
        that.siblings('label').text(file_name);
    });
}