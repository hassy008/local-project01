<?php 
namespace admin;
/**
* 
*/
class WutEditor
{
	
	public static function render($content, $editor_id)
	{
		$editor_id = $editor_id;
		$settings =   array(
		    'wpautop' => true, // use wpautop?
		    'media_buttons' => false, // show insert/upload button(s)
		    //'textarea_name' => $editor_id, // set the textarea name to something different, square brackets [] can be used here
		    'textarea_rows' => get_option('default_post_edit_rows', 6), // rows="..."
		    'tabindex' => '',
		    'editor_css' => '', // intended for extra styles for both visual and HTML editors buttons, needs to include the <style> tags, can use "scoped".
		    'editor_class' => '', // add extra class(es) to the editor textarea
		    'teeny' => false, // output the minimal editor config used in Press This
		    'dfw' => false, // replace the default fullscreen with DFW (supported on the front-end in WordPress 3.4)
		    'tinymce' => true, // load TinyMCE, can be used to pass settings directly to TinyMCE using an array()
		    'quicktags' => true // load Quicktags, can be used to pass settings directly to Quicktags using an array()
		);
		wp_editor( $content, $editor_id, $settings );
	}


}

 ?>