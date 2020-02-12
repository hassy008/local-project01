<?php

/**
 * Get Client IP address
 * @return type
 */
function get_the_user_ip()
{
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return apply_filters('wpb_get_ip', $ip);
}

/**
 * Before inserting into db
 * @param type $value 
 * @return type
 */
function _filter_post($value)
{
	return htmlspecialchars(stripslashes(trim($value)), ENT_QUOTES);
}

/**
 * Printing on browser
 * @param type $value 
 * @return type
 */
function _filter($value)
{
	return htmlspecialchars_decode(stripslashes($value), ENT_QUOTES);
}

/**
 * Before inserting into db
 * @param type $arr 
 * @return type
 */
function _filter_array($arr)
{
	$filtered_arr = [];
	foreach ($arr as $key => $value) {
		if (!is_array($value)) {
			$value = _filter_post($value);
		}
		$filtered_arr[$key] = $value;
	}
	return $filtered_arr;
}


/**
 * @param $file_path
 * @param $variables
 * @return false|string
 */
function get_view($file_path, $variables = null)
{

	if ($variables !== null)
		extract($variables, EXTR_PREFIX_SAME, "ops");

	ob_start();
	include "$file_path";
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

/**
 * @param boolean $success
 * @param string $message
 * @param array $data
 * @return array
 */
function _return($success, $message = "", $data = [])
{
	$data['success'] = $success;
	$data['message'] = $message;

	return $data;
}

//add_action('after_setup_theme', 'remove_admin_bar');

// function remove_admin_bar() {
//     if (!current_user_can('administrator') && !is_admin()) {
//         show_admin_bar(false);
//     }
// }

// // Block Access to /wp-admin for non admins.
// function custom_blockusers_init() {
//     if ( is_user_logged_in() && is_admin() && !current_user_can( 'administrator' ) ) {
//         wp_redirect( home_url() );
//         exit;
//     }
// }
// add_action( 'init', 'custom_blockusers_init' ); // Hook into 'init'



//Custom CSV create in post table
add_action('restrict_manage_posts', 'add_export_button');
function add_export_button()
{
	$screen = get_current_screen();

	if (isset($screen->parent_file) && ('edit.php' == $screen->parent_file)) {
		?>
<input type="submit" name="export_all_posts" id="export_all_posts" class="button button-primary"
    value="Export All Posts">
<script type="text/javascript">
jQuery(function($) {
    $('#export_all_posts').insertAfter('#post-query-submit');
});
</script>
<?php
		}
	}


	add_action('init', 'func_export_all_posts');
	function func_export_all_posts()
	{
		if (isset($_GET['export_all_posts'])) {
			$arg = array(
				'post_type' => 'post',
				'post_status' => 'publish',
				'posts_per_page' => -1,
			);

			global $post;
			$arr_post = get_posts($arg);
			if ($arr_post) {

				header('Content-type: text/csv');
				header('Content-Disposition: attachment; filename="wp.csv"');
				header('Pragma: no-cache');
				header('Expires: 0');

				$file = fopen('php://output', 'w');

				fputcsv($file, array('Post Title', 'URL', 'Author'));

				foreach ($arr_post as $post) {
					setup_postdata($post);
					fputcsv($file, array(get_the_title(), get_the_permalink(), get_the_author_meta('display_name')));
				}

				exit();
			}
		}
	}

	//End Custom CSV create in post table




	###################################################
	//Custom CSV create in post table
	add_action('restrict_manage_posts', 'add_export_csv');
	function add_export_csv()
	{
		$screen = get_current_screen();

		if (isset($screen->parent_file) && ('edit.php' == $screen->parent_file)) {
			?>
<!-- <input type="submit" name="export_all_posts" id="export_all_posts" class="button button-primary" value="Export CSV"> -->
<div class="button button-light"><a href="<?= admin_url('admin-post.php?action=posts_csv'); ?>">Posts CSV</a>
</div>
<?php
	}
}