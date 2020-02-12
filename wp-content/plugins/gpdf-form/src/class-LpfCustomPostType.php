<?php
/**
 * 
 */
class LpfCustomPostType
{
	
	public function init()
	{
        $self = new self();
        add_action('init', array($self, 'gpdf_form_post_types'));
	}


	/*
	*	gpdf-form
	*/

	public function gpdf_form_post_types() 
	{
		$args = array( 
			'labels' => array(
		        'name' => __( 'GPDF Forms' ),
		        'singular_name' => __( 'GPDF Form' )
		    ),
			'public' => true,
			'show_ui' => true,
			'menu_position' => 5,
			'menu_icon' => 'dashicons-media-default',
		    'supports' => array('title')
		);
		
		register_post_type( 'gpdf-form', $args );
	}
}