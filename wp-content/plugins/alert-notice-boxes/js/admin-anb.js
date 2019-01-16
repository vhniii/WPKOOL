jQuery(document).ready(function($){
	$('.anb_design_post_option_background_color').wpColorPicker();
	$('.anb_design_post_option_text_color').wpColorPicker();
	$('.anb_design_post_option_link_color').wpColorPicker();
	$('.anb_close_button_color').wpColorPicker();
	$('.anb_close_button_background_color').wpColorPicker();

	var mediaUploader;
	$('#upload-button').click(function(e) {
		e.preventDefault();
		// If the uploader object has already been created, reopen the dialog
		if (mediaUploader) {
			mediaUploader.open();
			return;
		}
		// Extend the wp.media object
		mediaUploader = wp.media.frames.file_frame = wp.media({
			title: 'Choose Image',
			button: {
				text: 'Choose Image'
			}, multiple: false });

		// When a file is selected, grab the URL and set it as the text field's value
		mediaUploader.on('select', function() {
			attachment = mediaUploader.state().get('selection').first().toJSON();
			$('#image_anb_design_background_image').attr('src', attachment.url);
			$('#anb_design_post_option_background_image').val(attachment.url);
		});
		// Open the uploader dialog
		mediaUploader.open();
	});

	$('#reset_logo_upload').click(function() {
		$('#image_anb_design_background_image').attr('src', '');
		$('#anb_design_post_option_background_image').val('');
	});

	function openTab(tabName) {
	    // Declare all variables
	    var i, tabcontent, tablinks;

	    // Get all elements with class="tabcontent" and hide them
	    tabcontent = document.getElementsByClassName("tabcontent");
	    for (i = 0; i < tabcontent.length; i++) {
	        tabcontent[i].style.display = "none";
	    }

	    // Get all elements with class="tablinks" and remove the class "active"
	    tablinks = document.getElementsByClassName("tablinks");
	    for (i = 0; i < tablinks.length; i++) {
	        tablinks[i].className = tablinks[i].className.replace(" active", "");
	    }

	    // Show the current tab, and add an "active" class to the link that opened the tab
	    document.getElementById(tabName).style.display = "block";
	}

	$('body').on('change', '[data-hide-close-options]', function(e) {
		var hiddenOptionVal = $(this).val();
		if (hiddenOptionVal == 'do-nothing') {
			$('#form-field-cancel-for').hide();
		} else {
			$('#form-field-cancel-for').show();
		}
	});

	$('body').on('change', '[data-hide-limitations-options]', function(e) {
		var hiddenOptionVal = $(this).val();
		if (hiddenOptionVal == 'no-limitations') {
			$('#form-field-custom-limitations').hide();
		} else {
			$('#form-field-custom-limitations').show();
		}
	});

	$('body').on('change', '[data-hide-options]', function(e) {
		var hiddenOptionVal = $(this).val();
		if (hiddenOptionVal == 'center') {
			$('.hidden-option').hide();
		} else {
			$('.hidden-option').show();
		}
	});

	$('body').on('click', '[data-opentab]', function(e) {
		var opentab = $(this).data("opentab");
		$('[data-opentab]').removeClass('active');
		$(this).addClass('active');
		openTab(opentab);
	});

});
