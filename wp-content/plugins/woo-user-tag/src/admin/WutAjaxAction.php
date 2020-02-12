<?php

namespace admin;

class WutAjaxAction
{
    public static function init()
    {
        $self = new self();

        add_action(' wp_ajax_wut_delete_campaign', array($self, 'wutDeleteCampaign'));
        add_action('wp_ajax_wut_delete_category', array($self, 'wutDeleteCategory'));
        add_action('wp_ajax_wut_delete_tag', array($self, 'wutDeleteTag'));

        add_action('wp_ajax_save_wut_tag_rules', array($self, 'saveWutTagRules'));
        add_action('wp_ajax_save_variation_wut_tag_rules', array($self, 'saveVariationWutTagRules'));
        add_action('wp_ajax_wut_email_config_save', array($self, 'wutEmailConfigSave'));

        add_action('wp_ajax_wut_filter_users', array($self, 'wutFilterUsers'));
        add_action('wp_ajax_wut_filter_orders', array($self, 'wutFilterorders'));

    }

    public function wutFilterorders()
    {
        if (!esc_sql(isset($_POST['ajax_nonce'])) || !wp_verify_nonce(esc_sql($_POST['ajax_nonce']), 'ajax_nonce')) {
            die("You are not allowed to submit data.");
        }

        $WutAuthorization       = new WutAuthorization();
        if (!$WutAuthorization->can(get_current_user_id(), 'edit_posts')){
            return false;
        }

        $WutExport = new WutExport();
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

        if (!empty($order_status) && !empty($order_status[0])) {
            $args['status'] = $order_status;
        }

        if (!empty($prod_name) && !empty($prod_name[0])) {
            foreach ($prod_name as $prod) {
                $order_ids_by_prod_name = array_values(wut_get_orders_ids_by_product_id($prod));
                $args_order_ids = array_merge($args_order_ids, $order_ids_by_prod_name);
            }

            if (empty($args_order_ids)) {
                $has_order_ids_by_prod_name = false;
            }
        }

        if (!empty($cat_name) && !empty($cat_name[0]) && $has_order_ids_by_prod_name) {
            $prod_args = array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'posts_per_page' => '-1',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'term_id',
                        'terms' => $cat_name,
                        'operator' => 'IN',
                    ),
                ),
                'return' => 'ids',
            );

            $products = new \WC_Product_Query($prod_args);
            $prod_ids_by_category = $products->get_products();

            // if (empty($args_order_ids)) {
            // 	$args_order_ids = array_merge($args_order_ids, $prod_ids_by_category);
            // } else {
            $args_order_ids = array_intersect($args_order_ids, $prod_ids_by_category);
            // }
        }

        if (!empty($order_id) && !empty($order_id[0]) && $has_order_ids_by_prod_name) {
            if (empty($args_order_ids)) {
                $args_order_ids = array_merge($args_order_ids, $order_id);
            } else {
                $args_order_ids = array_intersect($args_order_ids, $order_id);
            }
        }

        $args['post__in'] = $args_order_ids;

        if (!empty($start) && !empty($end)) {
            $args['date_query'] = array(
                array(
                    'after' => date('Y-m-d H:i:s', strtotime($start)),
                    'before' => date('Y-m-d H:i:s', strtotime($end)),
                    'inclusive' => true,
                ),
            );
        }

        $args['posts_per_page'] = '-1';

        // echo "<pre>"; print_r( $args_order_ids); echo "</pre>";

        if ($has_order_ids_by_prod_name) {
            $query = new \WC_Order_Query($args);
            $orders = $query->get_orders();
        }

        $header_txt = '<tr><td></td></tr>';
        $body_txt = "<tr><td style='color:tomato'><b>No data found for given search criteria<b></td></tr>";

        if (!empty($orders)) {
            $header_txt = '';
            $body_txt = '';
            $count = 1;
            foreach ($orders as $order) {
                $body_txt .= '<tr>';
                $header_txt .= $count == 1 ? '<tr>' : '';
                foreach ($saved_wut_order_filter_fields as $order_fields_data) {
                    if ($order_fields_data['field_display'] == 1) {
                        $header_txt .= $count == 1 ? "<th>{$order_fields_data['field_title']}</th>" : '';
                        $value = get_post_meta($order->get_id(), $order_fields_data['field_key'], true);

                        if ($order_fields_data['field_title'] == 'Id') {
                            $value = $order->get_id();
                        } elseif ($order_fields_data['field_title'] == 'Status') {
                            $value = $order->post_status;
                        } elseif ($order_fields_data['field_title'] == 'Order Date') {
                            $value = $order->order_date;
                        } elseif ($order_fields_data['field_title'] == 'Customer Note') {
                            $value = $order->get_customer_note();
                        } elseif ($order_fields_data['field_title'] == 'Method Title (Shipping)') {
                            $value = $order->get_shipping_method();
                        }
                        $body_txt .= "<td>{$value}</td>";
                    }
                }
                $body_txt .= '</tr>';
                $header_txt .= $count == 1 ? '</tr>' : '';
                ++$count;
            }
        }
        wp_send_json(['thead' => $header_txt, 'tbody' => $body_txt]);
    }

    public function wutFilterUsers()
    {
        if (!esc_sql(isset($_POST['ajax_nonce'])) || !wp_verify_nonce(esc_sql($_POST['ajax_nonce']), 'ajax_nonce')) {
            die("You are not allowed to submit data.");
        }

        $WutAuthorization       = new WutAuthorization();
        if (!$WutAuthorization->can(get_current_user_id(), 'edit_posts')){
            return false;
        }

        $usernames = esc_sql($_POST['usernames']);
        $user_roles = (array) wut_sanitize_array($_POST['user_roles']) ;
        $paged = intval( $_POST['paged']);
        $limit = intval($_POST['limit']);
        $start = esc_sql($_POST['start']);
        $end =esc_sql( $_POST['end']);
        $meta_field = esc_sql($_POST['meta_field']);
        $meta_value = esc_sql($_POST['meta_value']);

        $wut_filter_by_practice_area = esc_sql($_POST['wut_filter_by_practice_area']);

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
                    'after' => date('Y-m-d H:i:s', strtotime($start)),
                    'before' => date('Y-m-d H:i:s', strtotime($end)),
                    'inclusive' => true,
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

        if (empty($saved_wut_user_filter_fields['user_info'])) {
			$saved_wut_user_filter_fields['user_info'] = ['user_email', 'user_login', 'ID', 'user_nicename', 'user_url', 'user_registered', 'display_name'];
        }
        $args['fields'] = array_values($saved_wut_user_filter_fields['user_info']);
        // echo '<pre>'; var_dump($args); echo '</pre>';
        // echo '<pre>'; var_dump($saved_wut_user_filter_fields); echo '</pre>';
        $users = get_users($args);

        $header_txt = '';
        $body_txt = '';
        $count = 1;
        foreach ($users as $user) {
            $body_txt .= '<tr>';
            $header_txt .= $count == 1 ? '<tr>' : '';
            foreach ($user as $key => $value) {
                $header_txt .= $count == 1 ? "<th>$key</th>" : '';
                $body_txt .= "<td>{$value}</td>";
            }
            foreach ($saved_wut_user_filter_fields['meta']	as $field) {
                if ($field == 'role') {
                    $header_txt .= $count == 1 ? "<th>$field</th>" : '';
                    $user->$field = implode(', ', get_user_by( 'ID', $user->ID )->roles);
                    $body_txt .= "<td>{$user->$field}</td>";
                    continue;
                }
                $header_txt .= $count == 1 ? "<th>$field</th>" : '';
                $user->$field = get_user_meta($user->ID, $field, true);

                if(is_array($user->$field)){
                    $user->$field = implode(", ", $user->$field);
                }

                $body_txt .= "<td>{$user->$field}</td>";
            }
            $body_txt .= '</tr>';
            $header_txt .= $count == 1 ? '</tr>' : '';
            ++$count;
        }
        
        wp_send_json(['thead' => $header_txt, 'tbody' => $body_txt]);
    }

    public function wutEmailConfigSave()
    {
        if (!esc_sql(isset($_POST['ajax_nonce'])) || !wp_verify_nonce(esc_sql($_POST['ajax_nonce']), 'ajax_nonce')) {
            die("You are not allowed to submit data.");
        }

        $WutAuthorization       = new WutAuthorization();
        if (!$WutAuthorization->can(get_current_user_id(), 'edit_posts')){
            return false;
        }

        $from_name = esc_sql($_POST['from_name']);
        $reply_name = esc_sql($_POST['reply_name']);
        $from_email = sanitize_email($_POST['from_email']);
        $reply_email = sanitize_email($_POST['reply_email']);

        update_option('wut_email_from_name', $from_name);
        update_option('wut_email_reply_name', $reply_name);
        update_option('wut_email_from_email', $from_email);
        update_option('wut_email_reply_email', $reply_email);

        wp_send_json_success(['message' => 'Email configurations saved.']);
    }

    public function saveWutTagRules()
    {
        if (!esc_sql(isset($_POST['ajax_nonce'])) || !wp_verify_nonce(esc_sql($_POST['ajax_nonce']), 'ajax_nonce')) {
            die("You are not allowed to submit data.");
        }

        $WutAuthorization       = new WutAuthorization();
        if (!$WutAuthorization->can(get_current_user_id(), 'edit_posts')){
            return false;
        }

        $WutTagRules = new modules\WutTagRules();
        $type = esc_sql($_POST['type']);
        $data = [];
        $data['action'] = esc_sql($_POST['wut_action']);
        $data['tag_id'] = intval($_POST['tag_id']);
        $data['product_id'] = intval($_POST['product_id']);

        if ($type == 'insert') {
            $WutTagRules->updateOrInsert($data, [
                'product_id' => $data['product_id'],
                'tag_id' => $data['tag_id'],
                'ref_no' => '',
            ]);
        } elseif ($type == 'delete') {
            $WutTagRules->delete($data);
        }
    }

    public function saveVariationWutTagRules()
    {
        if (!esc_sql(isset($_POST['ajax_nonce'])) || !wp_verify_nonce(esc_sql($_POST['ajax_nonce']), 'ajax_nonce')) {
            die("You are not allowed to submit data.");
        }

        $WutAuthorization       = new WutAuthorization();
        if (!$WutAuthorization->can(get_current_user_id(), 'edit_posts')){
            return false;
        }

        $WutTagRules = new modules\WutTagRules();
        $type = esc_sql($_POST['type']);
        $data = [];
        $data['action'] = esc_sql($_POST['wut_action']);
        $data['tag_id'] = intval($_POST['tag_id']);
        $data['product_id'] = intval($_POST['product_id']);
        $data['ref_no'] = intval($_POST['variation_id']);

        if ($type == 'insert') {
            $WutTagRules->updateOrInsert($data, [
                'product_id' => $data['product_id'],
                'tag_id' => $data['tag_id'],
                'ref_no' => $data['ref_no'],
            ]);
        } elseif ($type == 'delete') {
            $WutTagRules->delete($data);
        }
    }

    public function wutDeleteCategory()
    {
        if (!esc_sql(isset($_POST['ajax_nonce'])) || !wp_verify_nonce(esc_sql($_POST['ajax_nonce']), 'ajax_nonce')) {
            die("You are not allowed to submit data.");
        }

        $WutAuthorization       = new WutAuthorization();
        if (!$WutAuthorization->can(get_current_user_id(), 'edit_posts')){
            return false;
        }

        $id = intval($_POST['id']);
        if (empty($id)) {
            wp_send_json_error(['mesage' => 'something went wrong.']);
        }

        $WutCategory = new modules\WutCategory();
        $return = $WutCategory->delete(['id' => $id]);
        wp_send_json_success(['mesage' => 'Successfully deleted.']);
    }

    public function wutDeleteTag()
    {
        if (!esc_sql(isset($_POST['ajax_nonce'])) || !wp_verify_nonce(esc_sql($_POST['ajax_nonce']), 'ajax_nonce')) {
            die("You are not allowed to submit data.");
        }

        $WutAuthorization       = new WutAuthorization();
        if (!$WutAuthorization->can(get_current_user_id(), 'edit_posts')){
            return false;
        }

        $id = intval($_POST['id']);
        if (empty($id)) {
            wp_send_json_error(['mesage' => 'something went wrong.']);
        }

        $WutTag = new modules\WutTag();
        $return = $WutTag->delete(['id' => $id]);
        wp_send_json_success(['mesage' => 'Successfully deleted.']);
    }

    public function wutDeleteCampaign()
    {
        if (!esc_sql(isset($_POST['ajax-nonce'])) || !wp_verify_nonce(esc_sql($_POST['ajax-nonce']), 'ajax_nonce')) {
            die("You are not allowed to submit data.");
        }

        $WutAuthorization       = new WutAuthorization();
        if (!$WutAuthorization->can(get_current_user_id(), 'edit_posts')){
            return false;
        }

        echo '<pre>';
        print_r(wut_sanitize_array($_GET));
        echo '</pre>';
        die;
        $id = intval($_POST['id']);
        if (empty($id)) {
            wp_send_json_error(['mesage' => 'something went wrong.']);
        }

        $WutCampaign = new modules\WutCampaign();
        $return = $WutCampaign->delete(['id' => $id]);
        wp_send_json_success(['mesage' => 'Successfully deleted.']);
    }
}
