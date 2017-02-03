(function($) {

	var file_frame;
	$('body').on('click', '.grh-upload-thumb', function(e) {

		e.preventDefault();

		var formfield = $('.grh-thumb input');

		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			//file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
			file_frame.open();
			return;
		}

		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			frame: 'select',
			title: 'Set Default Thumbnail',
			multiple: false,
			library: {
				type: 'image'
			},
			button: {
				text: 'Set Thumbnail'
			}
		});

		file_frame.on( 'menu:render:default', function(view) {
	        // Store our views in an object.
	        var views = {};

	        // Unset default menu items
	        view.unset('library-separator');
	        view.unset('gallery');
	        view.unset('featured-image');
	        view.unset('embed');

	        // Initialize the views in our view object.
	        view.set(views);
	    });

		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {

			var attachment = file_frame.state().get('selection').first().toJSON();
			formfield.val(attachment.id);
			$('.grh-thumbnail').html('<img src="' + attachment.sizes.thumbnail.url + '" />');

			$('.grh-thumb').trigger('change_thumb');

		});

		// Finally, open the modal
		file_frame.open();
	});

	$('body').on('click', '.grh-remove-thumb', function(e) {
		e.preventDefault();

		$('.grh-thumb input').val('');
		$('.grh-thumbnail').html('');

		$('.grh-thumb').trigger('change_thumb');
	});

	$('.grh-thumb').on('change_thumb', function() {
		console.log(1);
		var attachment = $(this).find('input').val();
		if( attachment != '' && attachment != 0 ) {
			$(this).addClass('set');
			$('.grh-thumb-options.isset').show();
			$('.grh-thumb-options.notset').hide();
		}else {
			$(this).removeClass('set');
			$(this).addClass('unset');
			$('.grh-thumb-options.isset').hide();
			$('.grh-thumb-options.notset').show();
		}
	}).trigger('change_thumb');

})(jQuery);