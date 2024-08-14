/**
 * WooCommerce Free Shipping Notice Admin JavaScript
 */
jQuery(function($) {
    $('.wfsn-upload-button').click(function(e) {
        e.preventDefault();
        var button = $(this);
        var customUploader = wp.media({
            title: 'Choose Icon',
            button: {
                text: 'Use this icon'
            },
            multiple: false
        });
        customUploader.on('select', function() {
            var attachment = customUploader.state().get('selection').first().toJSON();
            button.prev('input[type="text"]').val(attachment.url);
            button.closest('td').find('.wfsn-icon-preview img').attr('src', attachment.url);
        });
        customUploader.open();
    });

    $('.wfsn-default-button').click(function(e) {
        e.preventDefault();
        var defaultUrl = $(this).data('default');
        $(this).prev().prev('input[type="text"]').val(defaultUrl);
        $(this).closest('td').find('.wfsn-icon-preview img').attr('src', defaultUrl);
    });
});
