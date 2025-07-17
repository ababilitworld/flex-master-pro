jQuery(document).ready(function($) {
    var mediaUploader;

    $('#upload-images-button').click(function(e) {
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Images',
            button: {
                text: 'Choose Images'
            },
            multiple: true
        });

        mediaUploader.on('select', function() {
            var attachments = mediaUploader.state().get('selection').map(function(attachment) {
                attachment.toJSON();
                return attachment;
            });
            var imagesList = $('#document-images-preview');
            attachments.forEach(function(attachment) {
                imagesList.append('<li><img src="' + attachment.attributes.url + '" style="max-width: 150px;"><input type="hidden" name="document-images[]" value="' + attachment.id + '"><a href="#" class="remove-image">Remove</a></li>');
            });
        });

        mediaUploader.open();
    });

    $(document).on('click', '.remove-image', function(e) {
        e.preventDefault();
        $(this).closest('li').remove();
    });
});
