<?php 
namespace admin;
/**
* 
*/
class WutExport
{

    public function get_updated_order_fields()
    {

        $old_order_fields = $this->get_new_order_fields();

        $new_fields = get_option( 'wut_order_filter_fields');

        if (empty($new_fields)) {
            return maybe_unserialize($old_order_fields);
        }

        $new_fields = maybe_unserialize($new_fields);

        return $new_fields;
    }

    public function get_new_order_fields()
    {

        $order_fields = maybe_serialize($this->order_field_list());

        return $order_fields;
    }

    private function order_field_list()
    {
        $field_list = array(
                array(
                    'field_key' => 'id',
                    'field_display' => 1,
                    'field_title' => 'Id',
                    'field_value' => 'Id',
                ),
                array(
                    'field_key' => 'order_final_status',
                    'field_display' => 1,
                    'field_title' => 'Status',
                    'field_value' => 'Status',
                ),
                array(
                    'field_key' => '_date_created',
                    'field_display' => 1,
                    'field_title' => 'Order Date',
                    'field_value' => 'Order Date',
                ),
                array(
                    'field_key' => '_billing_first_name',
                    'field_display' => 1,
                    'field_title' => 'First Name (Billing)',
                    'field_value' => 'First Name (Billing)',
                ),
                array(
                    'field_key' => '_billing_last_name',
                    'field_display' => 1,
                    'field_title' => 'Last Name (Billing)',
                    'field_value' => 'Last Name (Billing)',
                ),
                array(
                    'field_key' => '_billing_company',
                    'field_display' => 1,
                    'field_title' => 'Company (Billing)',
                    'field_value' => 'Company (Billing)',
                ),
                array(
                    'field_key' => '_billing_address_1',
                    'field_display' => 1,
                    'field_title' => 'Address 1 (Billing)',
                    'field_value' => 'Address 1 (Billing)',
                ),
                array(
                    'field_key' => '_billing_address_2',
                    'field_display' => 1,
                    'field_title' => 'Address 2 (Billing)',
                    'field_value' => 'Address 2 (Billing)',
                ),
                array(
                    'field_key' => '_billing_city',
                    'field_display' => 1,
                    'field_title' => 'City (Billing)',
                    'field_value' => 'City (Billing)',
                ),
                array(
                    'field_key' => '_billing_postcode',
                    'field_display' => 1,
                    'field_title' => 'Postcode (Billing)',
                    'field_value' => 'Postcode (Billing)',
                ),
                array(
                    'field_key' => '_billing_country',
                    'field_display' => 1,
                    'field_title' => 'Country (Billing)',
                    'field_value' => 'Country (Billing)',
                ),
                array(
                    'field_key' => '_billing_state',
                    'field_display' => 1,
                    'field_title' => 'State (Billing)',
                    'field_value' => 'State (Billing)',
                ),
                array(
                    'field_key' => '_billing_email',
                    'field_display' => 1,
                    'field_title' => 'Email (Billing)',
                    'field_value' => 'Email (Billing)',
                ),
                array(
                    'field_key' => '_billing_phone',
                    'field_display' => 1,
                    'field_title' => 'Phone (Billing)',
                    'field_value' => 'Phone (Billing)',
                ),
                array(
                    'field_key' => '_shipping_first_name',
                    'field_display' => 1,
                    'field_title' => 'First Name (Shipping)',
                    'field_value' => 'First Name (Shipping)',
                ),
                array(
                    'field_key' => '_shipping_last_name',
                    'field_display' => 1,
                    'field_title' => 'Last Name (Shipping)',
                    'field_value' => 'Last Name (Shipping)',
                ),
                array(
                    'field_key' => '_shipping_company',
                    'field_display' => 1,
                    'field_title' => 'Company (Shipping)',
                    'field_value' => 'Company (Shipping)',
                ),
                array(
                    'field_key' => '_shipping_address_1',
                    'field_display' => 1,
                    'field_title' => 'Address 1 (Shipping)',
                    'field_value' => 'Address 1 (Shipping)',
                ),
                array(
                    'field_key' => '_shipping_address_2',
                    'field_display' => 1,
                    'field_title' => 'Address 2 (Shipping)',
                    'field_value' => 'Address 2 (Shipping)',
                ),
                array(
                    'field_key' => '_shipping_city',
                    'field_display' => 1,
                    'field_title' => 'City (Shipping)',
                    'field_value' => 'City (Shipping)',
                ),
                array(
                    'field_key' => '_shipping_postcode',
                    'field_display' => 1,
                    'field_title' => 'Postcode (Shipping)',
                    'field_value' => 'Postcode (Shipping)',
                ),
                array(
                    'field_key' => '_shipping_state',
                    'field_display' => 1,
                    'field_title' => 'State (Shipping)',
                    'field_value' => 'State (Shipping)',
                ),
                array(
                    'field_key' => '_shipping_country',
                    'field_display' => 1,
                    'field_title' => 'Country (Shipping)',
                    'field_value' => 'Country (Shipping)',
                ),
                array(
                    'field_key' => '_customer_note',
                    'field_display' => 1,
                    'field_title' => 'Customer Note',
                    'field_value' => 'Customer Note',
                ),
                array(
                    'field_key' => '_shipping_method_title',
                    'field_display' => 1,
                    'field_title' => 'Method Title (Shipping)',
                    'field_value' => 'Method Title (Shipping)',
                ),
                array(
                    'field_key' => '_payment_method_title',
                    'field_display' => 1,
                    'field_title' => 'Payment Method Title',
                    'field_value' => 'Payment Method Title',
                ),
                array(
                    'field_key' => '_cart_discount',
                    'field_display' => 1,
                    'field_title' => 'Cart Discount',
                    'field_value' => 'Cart Discount',
                ),
                array(
                    'field_key' => '_order_tax',
                    'field_display' => 1,
                    'field_title' => 'Order Tax',
                    'field_value' => 'Order Tax',
                ),
                array(
                    'field_key' => '_order_shipping_tax',
                    'field_display' => 1,
                    'field_title' => 'Order Tax (Shipping)',
                    'field_value' => 'Order Tax (Shipping)',
                ),
                array(
                    'field_key' => '_order_total',
                    'field_display' => 1,
                    'field_title' => 'Order Total',
                    'field_value' => 'Order Total',
                ),
                array(
                    'field_key' => '_completed_date',
                    'field_display' => 1,
                    'field_title' => 'Completed Date',
                    'field_value' => 'Completed Date',
                ),
                // array(
                //     'field_key' => 'total_diff_no_product',
                //     'field_display' => 1,
                //     'field_title' => 'Number of different items',
                //     'field_value' => 'Number of different items',
                // ),
                // array(
                //     'field_key' => '_qty',
                //     'field_display' => 1,
                //     'field_title' => 'Total number of items',
                //     'field_value' => 'Total number of items',
                // ),
                array(
                    'field_key' => '_order_key',
                    'field_display' => 1,
                    'field_title' => 'Status Key',
                    'field_value' => 'Status Key',
                ),
                array(
                    'field_key' => '_payment_method',
                    'field_display' => 1,
                    'field_title' => 'Payment Method',
                    'field_value' => 'Payment Method',
                ),
                // array(
                //     'field_key' => '_discount_total',
                //     'field_display' => 1,
                //     'field_title' => 'Order Discount',
                //     'field_value' => 'Order Discount',
                // ),
                array(
                    'field_key' => '_order_key',
                    'field_display' => 1,
                    'field_title' => 'Order Key',
                    'field_value' => 'Order Key',
                ),
                array(
                    'field_key' => '_order_currency',
                    'field_display' => 1,
                    'field_title' => 'Order Currency',
                    'field_value' => 'Order Currency',
                ),
                // array(
                //     'field_key' => 'product_data',
                //     'field_display' => 1,
                //     'field_title' => 'Product Data',
                //     'field_value' => 'Product Data',
                // ),
                // array(
                //     'field_key' => 'coupon_data',
                //     'field_display' => 1,
                //     'field_title' => 'Coupon Data',
                //     'field_value' => 'Coupon Data',
                // ),
                // array(
                //     'field_key' => 'shipping_data',
                //     'field_display' => 1,
                //     'field_title' => 'Shipping Data',
                //     'field_value' => 'Shipping Data',
                // ),
                // array(
                //     'field_key' => 'tax_data',
                //     'field_display' => 1,
                //     'field_title' => 'Tax Data',
                //     'field_value' => 'Tax Data',
                // ),
                // array(
                //     'field_key' => 'fee_data',
                //     'field_display' => 1,
                //     'field_title' => 'Fee Data',
                //     'field_value' => 'Fee Data',
                // ),
                // array(
                //     'field_key' => 'order_custom_fields',
                //     'field_display' => 1,
                //     'field_title' => 'Custom Fields',
                //     'field_value' => 'Custom Fields',
                // ),
                // array(
                //     'field_key' => 'refund_data',
                //     'field_display' => 1,
                //     'field_title' => 'Refund Data',
                //     'field_value' => 'Refund Data',
                // ),
                // array(
                //     'field_key' => 'refund_custom_fields',
                //     'field_display' => 1,
                //     'field_title' => 'Refund Custom Fields',
                //     'field_value' => 'Refund Custom Fields',
                // )
        );
        return $field_list;
    }

    public function get_order_status_name($slug)
    {
        $woo_order_statuses = wc_get_order_statuses();
        return $woo_order_statuses[$slug];
    }
}
 ?>