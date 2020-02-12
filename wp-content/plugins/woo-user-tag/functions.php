<?php 

function wut_get_orders_ids_by_product_id($product_id, $order_status = array('wc-completed'))
{
  global $wpdb;

  $results = $wpdb->get_col("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value = '$product_id'
    ");

  // and posts.pos t _status IN('" . implode("','",  $order_status) . "' )
  return $results;
}

function wut_get_the_user_ip() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
	//check ip from share internet
	$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
	//to check ip is pass from proxy
	$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
	$ip = $_SERVER['REMOTE_ADDR'];
	}
	return apply_filters( 'wpb_get_ip', $ip );
}

function wut_filter_post($value)
{
  return htmlspecialchars(stripslashes(trim($value)), ENT_QUOTES);
}

function wut_filter($value)
{
  return htmlspecialchars_decode(stripslashes($value), ENT_QUOTES);
}

function wut_filter_array($arr)
{
	$filtered_arr = [];
	foreach ($arr as $key => $value) {
		if(!is_array($value)){
			$value = _filter_post($value);
		}
		$filtered_arr[$key] = $value;
	}
	return $filtered_arr;
}

add_action('init', 'wut_start_session', 1); 
add_action('wp_logout', 'wut_end_session');
add_action('wp_login', 'wut_end_session');	

function wut_start_session() {
	if(!session_id()) {
		session_start();
	}
}

function wut_end_session() {
	session_destroy ();
}

function wut_get_view($file_path, $variables = null){

    if($variables !== null)
    extract($variables, EXTR_PREFIX_SAME, "ops");

    ob_start();
    include "$file_path";
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
}


function wut_sanitize_array( &$array ) {
    foreach ($array as &$value) {
        if( !is_array($value) )
            $value = sanitize_text_field( $value );
        else
            wut_sanitize_array($value);
    }
    return $array;
}