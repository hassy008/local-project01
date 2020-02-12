<?php 
namespace admin;
/**
* 
*/

class WutShortCode
{

	public static function init()
	{
		$self = new self;
		add_shortcode( 'example',array($self, 'example') );
	}
	
	public function ViewEvents() {
		return wut_get_view(WUT_VIEW_PATH . "content-dropbox-callback.php", compact('var'));
	}

	
}
 ?>