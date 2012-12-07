jQuery(document).ready(function() {
	var fileInput = '';
	var onlyOne = $("#only-one");
	
	jQuery('.upload_image_button').click(function() {
		fileInput = jQuery(this).prev('input.dbp_long_input_text');
		formfield = jQuery('#upload_image').attr('name');
		//post_id = jQuery('#post_ID').val();
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});
	jQuery('.upload_image_reset').click(function() {
		jQuery(this).parent().prev('input.dbp_long_input_text').val('');
	});
	window.original_send_to_editor = window.send_to_editor;
	window.send_to_editor = function(html){

		if (fileInput) {
			fileurl = jQuery('img',html).attr('src');
			fileInput.val(fileurl);
			if(onlyOne.length == 1)
				onlyOne.attr("src", fileurl);
			tb_remove();
		} else {
			window.original_send_to_editor(html);
		}};
	});