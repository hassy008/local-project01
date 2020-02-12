<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Before inserting into db
 * @param type $value 
 * @return type
 */
function lpf_filter_post($value)
{
  return htmlspecialchars(stripslashes(trim($value)), ENT_QUOTES);
}

/**
 * Printing on browser
 * @param type $value 
 * @return type
 */
function lpf_filter($value)
{
  return htmlspecialchars_decode(stripslashes($value), ENT_QUOTES);
}

/**
 * Before inserting into db
 * @param type $arr 
 * @return type
 */
function lpf_filter_array($arr)
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

/**
 * @param $file_path
 * @param $variables
 * @return false|string
 */
function lpf_get_view($file_path, $variables = null){

    if($variables !== null)
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
function lpf_return($success, $message = "", $data = [])
{
    $data['success'] = $success;
    $data['message'] = $message;

    return $data;
}

function lpf_sanitize_array( &$array ) {
    foreach ($array as &$value) {
        if( !is_array($value) )
            $value = sanitize_text_field( $value );
        else
            lpf_sanitize_array($value);
    }
    return $array;
}