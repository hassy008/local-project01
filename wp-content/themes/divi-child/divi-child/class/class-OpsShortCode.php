<?php 
/**
* 
*/

class OpsShortCode
{

	public static function init()
	{
		$self = new self;
		add_shortcode( 'catalogue',array($self, 'catalogue') );
	}
	
	public function catalogue($attr) {
		return get_view(get_stylesheet_directory() . "/template/catalogue.php", compact('attr'));
	}

	
}
 ?>