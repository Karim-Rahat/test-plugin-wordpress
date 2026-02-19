(function ($) {
    $(document).ready(function () {
        $('.cvs_upload_media').click(function (e) {
            e.preventDefault();

            const inputField = $(this).data('input-field');
            const displayField = $(this).data('display-field');

            let image = wp.media({
                title: 'Upload Image',
                multiple: false
            }).open()
                .on('select', function (e) {
                    let uploaded_image = image.state().get('selection').first();
                    let image_url = uploaded_image.toJSON().url;
                    // let urlParts = image_url.split('wp-content');
                    // let safeUrl = '/wp-content' + urlParts[1];

                    $(inputField).val(image_url);
                    $(displayField).attr("src", image_url);
                });
        });

        $("#contestant_add_btn").click(function () {
            $("#contestant_add_area").slideToggle('fast');
        });

        $("#contest_edit_btn").click(function () {
            $("#contest_edit_area").slideToggle('fast');
        })

        $(".contestant_delete_btn").click(function () {
            let action = $(this).data('action');
            if (!action) {
                action = 'contestant';
            }

            return confirm(`Are you sure?\nYou want to delete this ${action}?`);
        });

        $(".contest_delete_btn").click(function () {
            return confirm("Are you sure?\nYou want to delete this contest?");
        });

        $("#cvs_reset_settings").click(function () {
            let confirm = prompt("Are you sure?\nplease write \"Confirm\"");

            return confirm.trim().toLowerCase() === 'confirm';
        });

    })
})(jQuery);