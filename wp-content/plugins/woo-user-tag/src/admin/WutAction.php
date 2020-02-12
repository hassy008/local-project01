<?php
namespace admin;
use admin\modules;
/**
 *
 */

class WutAction
{
    public static function init()
    {
        $self = new self;
        # update_wut_category_tag 
        add_action('admin_post_update_wut_category_tag', array($self, 'updateWutCategoryTag'));
        # update_wut_campaign 
        add_action('admin_post_update_wut_campaign', array($self, 'updateWutCampaign'));
        # save_wut_category 
        add_action('admin_post_save_wut_category', array($self, 'saveWutCategory'));
        # save_wut_tag 
        add_action('admin_post_save_wut_tag', array($self, 'saveWutTag'));
        # wut_save_manage_fields 
        add_action('admin_post_wut_save_manage_fields', array($self, 'wutSaveManageFields'));
        # wut_save_order_manage_fields 
        add_action('admin_post_wut_save_order_manage_fields', array($self, 'wutSaveOrderManageFields'));

        add_action( "admin_post_wut_export_users", array($self, 'wutExportUsers') );
        add_action( "admin_post_wut_export_orders", array($self, 'wutExportOrders') );

    }

    public function wutExportOrders()
    {
        if (!esc_sql(isset($_POST['wutExportOrders'])) || !wp_verify_nonce(esc_sql($_POST['wutExportOrders']), 'wutExportOrders-nonce')) {
            die("You are not allowed to submit data.");
        }

        $WutAuthorization       = new WutAuthorization();
        if (!$WutAuthorization->can(get_current_user_id(), 'edit_posts')){
            return false;
        }

        $date = date("Y-m-d H_i_s");
        $filename = "Order-{$date}.csv";
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=$filename");
        header('Pragma: no-cache');
        header('Expires: 0');

        $WutExport         = new WutExport();
		$saved_wut_order_filter_fields = $WutExport->get_updated_order_fields();


        $order_status = (array) wut_sanitize_array($_POST['order_status']);
        $prod_name = (array) wut_sanitize_array($_POST['prod_name']);
        $cat_name = (array) wut_sanitize_array($_POST['cat_name']);
        $order_id = (array) wut_sanitize_array($_POST['order_id']);
        $start = esc_sql($_POST['start']);
		$end = esc_sql($_POST['end']);
        $args_order_ids = [];
        $has_order_ids_by_prod_name = true;

		
		$args = [];
		$orders = [];
		if (!empty($order_status) && !empty( $order_status[0])) {
            $args['status'] = $order_status;
		}
		
        if (!empty( $prod_name) && !empty( $prod_name[0])) {
			foreach ( $prod_name as $prod) {
				$order_ids_by_prod_name = array_values(wut_get_orders_ids_by_product_id($prod));
                $args_order_ids=array_merge( $args_order_ids, $order_ids_by_prod_name);
			}

			if (empty( $args_order_ids)) {
				$has_order_ids_by_prod_name = false;
			}
        }


        if (!empty( $cat_name) && !empty( $cat_name[0]) && $has_order_ids_by_prod_name) {
            $prod_args = array(
                'post_type'             => 'product',
                'post_status'           => 'publish',
                'posts_per_page'        => '-1',
                'tax_query'             => array(
                    array(
                        'taxonomy'      => 'product_cat',
                        'field' => 'term_id', 
                        'terms'         => $cat_name,
                        'operator'      => 'IN'
                    )
                ),
                'return' => 'ids',
            );
			$products = new \WC_Product_Query($prod_args);
			$prod_ids_by_category = $products->get_products();

            if (empty($args_order_ids)) {
                $args_order_ids = array_merge($args_order_ids, $prod_ids_by_category);
			} else {
                $args_order_ids = array_intersect($args_order_ids, $prod_ids_by_category);
            }
        }


        if (!empty( $order_id) && !empty( $order_id[0]) && $has_order_ids_by_prod_name) {
			if (empty( $args_order_ids)) {
				$args_order_ids = array_merge($args_order_ids, $order_id);
			} else {
				$args_order_ids = array_intersect($args_order_ids, $order_id);
            }
        }

		
        $args['post__in'] = $args_order_ids;
        if (!empty($start) && !empty($end)) {
            $args['date_query'] = array(
                array(
                    'after'      => date("Y-m-d H:i:s", strtotime($start)),
                    'before'     => date("Y-m-d H:i:s", strtotime($end)),
                    'inclusive'  => true,
                ),
            );
        }

        $args['posts_per_page'] = '-1';
		$query = new \WC_Order_Query( $args );
        $orders = $query->get_orders();


        $fp = fopen('php://output', 'w');
		if (!empty( $orders)) {
            $count = 1;
			foreach ($orders as $order ) {
            $header = [];
            $body = [];
				foreach ($saved_wut_order_filter_fields as $order_fields_data) {
                    if ($order_fields_data['field_display'] == 1) {
						$header[] = $count == 1 ? $order_fields_data['field_title'] : "";
						$value = get_post_meta($order->get_id(), $order_fields_data['field_key'], true);
						if ( $order_fields_data['field_title'] == 'Id') {
                            $value = $order->get_id();
                        } elseif ( $order_fields_data['field_title'] == 'Status') {
							$value = $order->post_status;
						} elseif ( $order_fields_data['field_title'] == 'Order Date') {
							$value = $order->order_date;
						} elseif ($order_fields_data['field_title'] == 'Customer Note') {
							$value = $order->get_customer_note();
						} elseif ($order_fields_data['field_title'] == 'Method Title (Shipping)') {
							$value = $order-> get_shipping_method();
						}
                        $body[] = $value;
                    }
                }
                if ($count==1) { 
                     fputcsv($fp, (array)$header);
                }
                $count++;
                // echo "<pre>"; print_r($body); echo "</pre>";
                fputcsv($fp, (array) $body);
			}
		}

        fclose($fp);
        exit();
    }
    
    public function wutSaveOrderManageFields()
    {
        if (!esc_sql(isset($_POST['wutSaveOrderManageFields'])) || !wp_verify_nonce(esc_sql($_POST['wutSaveOrderManageFields']), 'wutSaveOrderManageFields-nonce')) {
            die("You are not allowed to submit data.");
        }

        $WutAuthorization       = new WutAuthorization();
        if (!$WutAuthorization->can(get_current_user_id(), 'edit_posts')){
            return false;
        }

        update_option( 'wut_order_filter_fields', wut_sanitize_array($_POST[ 'wut_order_filter_fields']));
        wp_redirect(esc_url(home_url($_POST['_wp_http_referer'])));
        $_SESSION['message'] ="Manage fields saved";
        $_SESSION['type'] = "success";
        exit();
    }

    public function wutExportUsers()
    {
        if (!esc_sql(isset($_POST['wutExportUsers'])) || !wp_verify_nonce(esc_sql($_POST['wutExportUsers']), 'wutExportUsers-nonce')) {
            die("You are not allowed to submit data.");
        }

        $WutAuthorization       = new WutAuthorization();
        if (!$WutAuthorization->can(get_current_user_id(), 'edit_posts')){
            return false;
        }

        $date = date("Y-m-d H_i_s");
        $filename = "Users-{$date}.csv";
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=$filename");
        header('Pragma: no-cache');
        header('Expires: 0');

        $usernames = (array)wut_sanitize_array($_POST['usernames']);
        $user_roles = (array)wut_sanitize_array($_POST['user_roles']);
        $paged = intval($_POST['paged']);
        $limit = intval($_POST['limit']);
        $start = esc_sql($_POST['start']);
        $end = esc_sql($_POST['end']);
        $meta_field = esc_sql($_POST['wut_filter_by_meta']);
        $meta_value = esc_sql($_POST['meta']);

        $args = [];

        if (!empty($user_roles) && !empty($user_roles[0])) {
            $args['role__in'] = $user_roles;
        }
        if (!empty($usernames) && !empty($usernames[0])) {
            $args['login__in'] = $usernames;
        }
        if (!empty($paged)) {
            $args['offset'] = $paged;
        }
        if (!empty($limit)) {
            $args['number'] = $limit;
        }
        if (!empty($start) && !empty($end)) {
            $args['date_query'] = array(
                array(
                    'after'      => date("Y-m-d H:i:s", strtotime($start)),
                    'before'     => date("Y-m-d H:i:s", strtotime($end)),
                    'inclusive'  => true,
                ),
            );
        }
        if (!empty($meta_field) && !empty($meta_value)) {
            $args['meta_key'] = $meta_field;
            $args['meta_value'] = $meta_value;
        }

        if (!empty($wut_filter_by_practice_area)) {
            $args['meta_query'][] = [
                'key'       => 'areas-practice',
                'value'     => $wut_filter_by_practice_area,
                'compare'   => 'LIKE',
            ];
         }

        $saved_wut_user_filter_fields = get_option('wut_user_filter_fields');

        if (!empty($saved_wut_user_filter_fields['user_info'])) {
            $args['fields'] = array_values($saved_wut_user_filter_fields['user_info']);
        }

        $users = get_users($args);
        
        // echo "<pre>"; print_r($users); echo "</pre>";
        // echo "<pre>"; print_r($args); echo "</pre>";
        $fp = fopen('php://output', 'w');
        if (!empty($saved_wut_user_filter_fields['meta'])) {
            $header = [];

            $count = 1;
            foreach ($users as $user) {

                foreach ($user as $key => $value) {
                    if ($count==1) {
                        $header[] = $key;
                    }
                }
                foreach ($saved_wut_user_filter_fields['meta'] as $field) {
                    if ($count==1 ) {
                        $header[] = $field;
                    }
                    if ($field == 'role') {
                        $user->$field = implode(', ', get_user_by( 'ID', $user->ID )->roles);
                        continue;
                    }

                    $user->$field = get_user_meta($user->ID, $field,true);

                    if(is_array($user->$field)){
                        $user->$field = implode(", ", $user->$field);
                    }
                }
                if ($count==1) {
                    fputcsv($fp, (array)$header);
                }
                $count++;
                fputcsv($fp, (array) $user);
			}
		}

        fclose($fp);
        exit();
	}

    public function wutSaveManageFields()
    {
        if (!esc_sql(isset($_POST['wutSaveManageFields'])) || !wp_verify_nonce(esc_sql($_POST['wutSaveManageFields']), 'wutSaveManageFields-nonce')) {
            die("You are not allowed to submit data.");
        }

        $WutAuthorization       = new WutAuthorization();
        if (!$WutAuthorization->can(get_current_user_id(), 'edit_posts')){
            return false;
        }

        update_option('wut_user_filter_fields', wut_sanitize_array($_POST[ 'wut_user_filter_fields']));
        wp_redirect( esc_url(home_url( $_POST['_wp_http_referer']) ));
        $_SESSION['message'] ="Manage fields saved";
        $_SESSION['type'] = "success";
        exit();
    }

    public function updateWutCategoryTag()
    {
        if (!esc_sql(isset($_POST['updateWutCategoryTag'])) || !wp_verify_nonce(esc_sql($_POST['updateWutCategoryTag']), 'updateWutCategoryTag-nonce')) {
            die("You are not allowed to submit data.");
        }

        $WutAuthorization       = new WutAuthorization();
        if (!$WutAuthorization->can(get_current_user_id(), 'edit_posts')){
            return false;
        }

        echo '<pre>'; print_r(wut_sanitize_array($_POST)); echo '</pre>';

        // wp_redirect($_POST['_wp_http_referer']);
        // exit(); 
        
    }

    public function updateWutCampaign()
    {
        if (!esc_sql(isset($_POST['updateWutCampaign'])) || !wp_verify_nonce(esc_sql($_POST['updateWutCampaign']), 'updateWutCampaign-nonce')) {
            die("You are not allowed to submit data.");
        }

        $WutAuthorization       = new WutAuthorization();
        if (!$WutAuthorization->can(get_current_user_id(), 'edit_posts')){
            return false;
        }

        $data                 = [];
        $id                   = intval($_POST['id']);
        $data['tag_ids']      = implode(",", $_POST['tag_ids']);
        $data['title']        = esc_sql($_POST['title']);
        $data['delay']        = esc_sql($_POST['delay']);
        $data['delay_type']   = esc_sql($_POST['delay_type']);
        $data['subject']      = esc_sql($_POST['subject']);
        $data['content']      = wut_sanitize_array($_POST['content']);

        $WutCampaign          = new modules\WutCampaign();
        if (empty($id)) {
            $id = $WutCampaign->insert($data );
        } else {
            $WutCampaign->update($data, ['id' => $id] );
        }

        if ($id !== false) {
            $_SESSION['success'] = "Campaign Saved.";
            wp_redirect($_POST['_wp_http_referer']);
            exit(); 
        }
        $_SESSION['error'] = "Something went wrong.";
        wp_redirect($_POST['_wp_http_referer']);
        exit(); 
    }  

    public function saveWutCategory()
    {
        if (!esc_sql(isset($_POST['saveWutCategory'])) || !wp_verify_nonce(esc_sql($_POST['saveWutCategory']), 'saveWutCategory-nonce')) {
            die("You are not allowed to submit data.");
        }

        $WutAuthorization       = new WutAuthorization();
        if (!$WutAuthorization->can(get_current_user_id(), 'edit_posts')){
            return false;
        }

        // echo '<pre>'; print_r($_POST); echo '</pre>';
        $data            = [];
        $data['name']    = esc_sql($_POST['name']);
        $id      = intval($_POST['id']);

        $WutCategory     = new modules\WutCategory();

        if (!empty($id)) {
            $return = $WutCategory->update( $data, [ 'id' => $id ] );
        } else {
            $return = $WutCategory->insert($data );            
        }

        if ($return === false ) {
            $_SESSION['error'] = "Something went wrong.";
            wp_redirect($_POST['_wp_http_referer']);
            exit(); 
        }

        $_SESSION['success'] = "Category Saved.";
        wp_redirect($_POST['_wp_http_referer']);
        exit(); 
    }     

    public function saveWutTag()
    {
        if (!esc_sql(isset($_POST['saveWutTag'])) || !wp_verify_nonce(esc_sql($_POST['saveWutTag']), 'saveWutTag-nonce')) {
            die("You are not allowed to submit data.");
        }

        $WutAuthorization       = new WutAuthorization();
        if (!$WutAuthorization->can(get_current_user_id(), 'edit_posts')){
            return false;
        }

        // echo '<pre>'; print_r($_POST); echo '</pre>';
        $data                = [];
        $data['name']        = esc_sql($_POST['name']);
        $id                  = intval($_POST['id']);
        $data['category_id'] = intval($_POST['category_id']);

        $WutTag          = new modules\WutTag();
        // $WutCategoryTag  = new modules\WutCategoryTag();

        if (!empty($id)) {
            $return = $WutTag->update( $data, [ 'id' => $id ] );
        } else {
            $id = $WutTag->insert($data );            
        }

        if (empty($id) ) {
            $_SESSION['error'] = "Something went wrong.";
            wp_redirect(esc_url($_POST['_wp_http_referer']));
            exit(); 
        }

        // $WutCategoryTag->updateOrInsert(
        //     ['category_id' => $category_id, 'tag_id' => $id],
        //     ['tag_id' => $id]
        // );

        $_SESSION['success'] = "Category Saved.";
        wp_redirect(esc_url($_POST['_wp_http_referer']));
        exit(); 
    }    
}
