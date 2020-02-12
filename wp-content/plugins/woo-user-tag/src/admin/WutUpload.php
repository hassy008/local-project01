<?php
namespace admin;

/**
 *
 */
class WutUpload
{

    public static function customMediaSideloadImage($image_url = '', $post_id = false)
    {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        $tmp = download_url($image_url);
        // Set variables for storage
        // fix file filename for query strings
        preg_match('/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $image_url, $matches);
        $file_array['name']     = basename($matches[0]);
        $file_array['tmp_name'] = $tmp;
        // If error storing temporarily, unlink
        if (is_wp_error($tmp)) {
            @unlink($file_array['tmp_name']);
            $file_array['tmp_name'] = '';
        }
        $time = current_time('mysql');
        $file = wp_handle_sideload($file_array, array('test_form' => false), $time);
        if (isset($file['error'])) {
            return new WP_Error('upload_error', $file['error']);
        }
        $url        = $file['url'];
        $type       = $file['type'];
        $file       = $file['file'];
        $title      = preg_replace('/\.[^.]+$/', '', basename($file));
        $parent     = (int) absint($post_id) > 0 ? absint($post_id) : 0;
        $attachment = array(
            'post_mime_type' => $type,
            'guid'           => $url,
            'post_parent'    => $parent,
            'post_title'     => $title,
            'post_content'   => '',
        );
        $id = wp_insert_attachment($attachment, $file, $parent);
        if (!is_wp_error($id)) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
            $data = wp_generate_attachment_metadata($id, $file);
            wp_update_attachment_metadata($id, $data);
        }
        return $id;
    }

    public static function downloadAndStore($url, $vendor, $order_id)
    {

        if(empty($url)) return false;

        $ch = curl_init();
        $source = $url;
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec ($ch);
        curl_close ($ch);

        $year = date("y");
        $month = date("m");
        $folder = "/uploads/{$vendor}/{$year}/{$month}/{$order_id}/";
        $destination = WP_CONTENT_DIR . $folder;

        if(wp_mkdir_p($destination)){
            
	        $new_file_name = wp_unique_filename( $destination, basename(strtok($url, '?')));

	        $destination_file = $destination . $new_file_name;

	        $file = fopen($destination_file, "w+");
        	fputs($file, $data);
        	fclose($file);

        	return WP_CONTENT_URL . $folder . $new_file_name;
    	}
    }
}
