<?php


################################
/* include "class/CT_TAX_META.php";
add_action('after_setup_theme', array('CT_TAX_META', 'init'));

CT_TAX_META::init();
 */


/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme('storefront');
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if (!isset($content_width)) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';

if (class_exists('Jetpack')) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if (storefront_is_woocommerce_activated()) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if (is_admin()) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if (version_compare(get_bloginfo('version'), '4.7.3', '>=') && (is_admin() || is_customize_preview())) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';

	if (defined('WC_VERSION') && version_compare(WC_VERSION, '3.0.0', '>=')) {
		require 'inc/nux/class-storefront-nux-starter-content.php';
	}
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */




















class MyWidget extends WP_Widget
{

	function __construct()
	{
		$widget_ops = array('description' => __('Use this widget to add one of your custom menu as a link list widget.'));
		parent::__construct('custom_menu_widget-1', __('My name'), $widget_ops);
	}

	function widget($args, $instance)
	{
		// Get menu
		$nav_menu = !empty($instance['nav_menu']) ? wp_get_nav_menu_object($instance['nav_menu']) : false;

		if (!$nav_menu)
			return;

		$instance['title'] = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);



		echo $args['before_widget'];

		if (!empty($instance['title']))
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		wp_nav_menu(array('menu' => $nav_menu));

		echo $args['after_widget'];
	}

	function update($new_instance, $old_instance)
	{
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['nav_menu'] = (int) $new_instance['nav_menu'];
		return $instance;
	}

	function form($instance)
	{
		$title = isset($instance['title']) ? $instance['title'] : '';
		$nav_menu = isset($instance['nav_menu']) ? $instance['nav_menu'] : '';

		// Get menus
		$menus = get_terms('nav_menu', array('hide_empty' => false));

		// If no menus exists, direct the user to go and create some.
		if (!$menus) {
			echo '<p>' . sprintf(__('No menus have been created yet. <a href="%s">Create some</a>.'), admin_url('nav-menus.php')) . '</p>';
			return;
		}
		?>
<p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:') ?></label>
    <input type="text" class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
        name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $title; ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('nav_menu'); ?>"><?php _e('Select Menu:'); ?></label>
    <select id="<?php echo $this->get_field_id('nav_menu'); ?>" name="<?php echo $this->get_field_name('nav_menu'); ?>">
        <?php
						foreach ($menus as $menu) {
							$selected = $nav_menu == $menu->term_id ? ' selected="selected"' : '';
							echo '<option' . $selected . ' value="' . $menu->term_id . '">' . $menu->name . '</option>';
						}
						?>
    </select>
</p>
<?php
		}


		//     wp_register_sidebar_widget(
		//     'custom_menu_widget-1',
		//     'My name',
		//     'WP_Nav_Menu_Widget',
		//     array( 'description' => 'blogroll' )
		// );
	}

	add_action('widgets_init', 'myplugin_register_widgets');

	function myplugin_register_widgets()
	{

		register_widget('MyWidget');
	}





	####################################################
	####################################################
	####################################################
	// Register and load the widget
	function wpb_load_widget()
	{
		register_widget('wpb_widget');
	}

	// Creating the widget 
	class wpb_widget extends WP_Widget
	{

		function __construct()
		{
			parent::__construct(
				'wpb_widget',
				__('Divi Footer', 'wpb_widget_domain'),
				array('description' => __('Divi Footer Widgets', 'wpb_widget_domain'),)
			);
		}

		// Creating widget front-end
		public function widget($args, $instance)
		{
			$title = apply_filters('widget_title', $instance['title']);
			echo $args['before_widget'];
			if (!empty($title))
				echo $args['before_title'] . $title . $args['after_title'];
			echo __('Hello, World!', 'wpb_widget_domain');
			echo $args['after_widget'];
		}

		// Widget Backend 
		public function form($instance)
		{
			if (isset($instance['title'])) {
				$title = $instance['title'];
			} else {
				$title = __('New title', 'wpb_widget_domain');
			}
			?>
<p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Dropdown:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
        name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
</p>
<p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Dropdown:'); ?></label>
    <select class="form-control" value="" name="">
        <?php
						$args = array(
							'posts_per_page'   => -1,
							//'post_type'        => 'et_pb_layout',
						);
						$posts_array = get_posts($args);

						print_r($posts_array);


						?>

        <option value=""></option>
        <option value="">Dropdown</option>
        <option value="">Dropdown</option>


    </select>
</p>
<?php
		}

		// Updating widget replacing old instances with new
		public function update($new_instance, $old_instance)
		{
			$instance = array();
			$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
			return $instance;
		}
	} // Class wpb_widget ends here

	//widgets
	add_action('widgets_init', 'wpb_load_widget');









	####################### MailChimp ##########################
	add_shortcode('mailchimp', 'mailchimp_form');
	function mailchimp_form()
	{
		ob_start();
		?>
<form class="form-inline">
    <div class="form-group mb-2">
        <label for="email" class="sr-only"><?php _e('Email'); ?></label>
        <input type="email" class="form-control-plaintext" id="email">
    </div>
    <button type="button" class="btn btn-primary mb-2 subscribe"><?php _e('Subscribe'); ?></button>
</form>
<?php
	return ob_get_clean();
}




##############
add_action('wp_enqueue_scripts', 'twentynineteen_scripts');
function twentynineteen_scripts()
{
	// Register the script
	wp_register_script('awscript', get_stylesheet_directory_uri() . '/js/awscript.js', array('jquery'));

	// Localize the script with new data
	$script_array = array(
		'ajaxurl' => admin_url('admin-ajax.php'),
		'security' => wp_create_nonce("subscribe_user"),
	);
	wp_localize_script('awscript', 'aw', $script_array);

	// Enqueued script with localized data.
	wp_enqueue_script('awscript');
}

##################################endregion
add_action('wp_ajax_subscribe_user', 'subscribe_user_to_mailchimp');
add_action('wp_ajax_nopriv_subscribe_user', 'subscribe_user_to_mailchimp');

function subscribe_user_to_mailchimp()
{
	check_ajax_referer('subscribe_user', 'security');
	$email = $_POST['email'];
	// $audience_id = 'YOUR_AUDIENCE_ID';
	// $api_key = 'YOUR_API_KEY';
	$audience_id = '6203ed8611';
	$api_key = '60eb37700c77b960892463b1459da9c6-us4';
	$data_center = substr($api_key, strpos($api_key, '-') + 1);
	$url = 'https://' . $data_center . '.api.mailchimp.com/3.0/lists/' . $audience_id . '/members';
	$auth = base64_encode('user:' . $api_key);
	$arr_data = json_encode(array(
		'email_address' => $email,
		'status' => 'subscribed' //pass 'subscribed' or 'pending'
	));

	$response = wp_remote_post(
		$url,
		array(
			'method' => 'POST',
			'headers' => array(
				'Content-Type' => 'application/json',
				'Authorization' => "Basic $auth"
			),
			'body' => $arr_data,
		)
	);

	if (is_wp_error($response)) {
		$error_message = $response->get_error_message();
		echo "Something went wrong: $error_message";
	} else {
		$status_code = wp_remote_retrieve_response_code($response);
		switch ($status_code) {
			case '200':
				echo $status_code;
				break;
			case '400':
				$api_response = json_decode(wp_remote_retrieve_body($response), true);
				echo $api_response['title'];
				break;
			default:
				echo 'Something went wrong. Please try again.';
				break;
		}
	}
	wp_die();
}