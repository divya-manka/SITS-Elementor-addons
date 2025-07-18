jQuery(function($) {
    setInterval(function () {
        let templateId = $('select[name="sitsel_template_id"]').val();
        let $editBtn = $('#sitsel-template-edit-link');
        if (templateId) {
            $editBtn.attr('href', '/wp-admin/post.php?post=' + templateId + '&action=elementor').show();
        } else {
            $editBtn.hide();
        }
    }, 1000);
});
