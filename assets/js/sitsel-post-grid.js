(function($) {
  $(window).on('elementor:init', function() {
    elementor.hooks.addAction('panel/open_editor', function(panel, model, view) {
      // Wait until panel is fully ready
      setTimeout(function () {
        const editButton = view.$el.find('.sitsel-edit-template');
        if (!editButton.length) return;

        editButton.on('click', function () {
          const templateId = $(this).data('template-id');
          if (!templateId) {
            alert('Please select a template first.');
            return;
          }

          // Elementor edit URL format
          const editUrl = `/wp-admin/post.php?post=${templateId}&action=elementor`;
          window.open(editUrl, '_blank');
        });
      }, 100); // Slight delay to ensure elements are present
    });
  });
})(jQuery);
