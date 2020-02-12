<?php

if ( ! defined( 'ABSPATH' ) ) exit;
$WutExport         = new admin\WutExport();
$saved_wut_order_filter_fields = $WutExport->get_updated_order_fields();

$prod_args          = array('post_type' => 'product', 'posts_per_page' => -1);

$cat_args = array(
    'taxonomy'     => 'product_cat',
    'show_count'   => 0,
    'posts_per_page' => '-1'
);

$order_args = array(
    'return' => 'ids',
    'posts_per_page' => '-1'
);

$woo_order_statuses = wc_get_order_statuses();

$products           = get_posts($prod_args);

$show_count   = 0;

$categories = get_categories($cat_args);

$query = new WC_Order_Query($order_args);
$order_ids = $query->get_orders();

?>

<div class="export-import-section">
    <form class="" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
        <input type="hidden" name="action" value="wut_export_orders">
        <?php wp_nonce_field('wutExportOrders-nonce', 'wutExportOrders'); ?>
        <div class="row form-group">
            <?php if (isset($_SESSION['message'])) : ?>
            <div class="alert alert-<?= $_SESSION['type'] ?>">
                <?= $_SESSION['message'] ?>
            </div>
            <?php unset($_SESSION['message']);
            unset($_SESSION['type']);
        endif; ?>
            <div class="col-md-6">
                <div class="single-group">
                    <label for="">Filter By Order Status</label>
                    <select class="form-control filter-by wut_filter_order_by_order_status" multiple="multiple" name="order_status[]">
                        <?php foreach ($woo_order_statuses as $woo_order_status_slug => $woo_order_status_name) : ?>
                        <option value="<?= $woo_order_status_slug ?>"><?= $woo_order_status_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="single-group">
                    <label for="">Filter By Product ID / Name</label>
                    <select class="form-control filter-by wut_filter_order_by_prod_name" multiple="multiple" name="prod_name[]">
                        <?php foreach ($products as $product) : ?>
                        <option value="<?= $product->ID ?>"><?= $product->post_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row form-group">
            <div class="col-md-6">
                <div class="single-group">
                    <label for="">Filter By Product Category</label>
                    <select class="form-control filter-by wut_filter_order_by_cat_name" multiple="multiple" name="cat_name[]">
                        <?php foreach ($categories as $category) : ?>
                        <option value="<?= $category->term_id ?>"><?= $category->cat_name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="single-group">
                    <label for="">Filter By Order Id</label>
                    <select class="form-control filter-by wut_filter_order_by_order_id" multiple="multiple" name="order_id[]">
                        <?php foreach ($order_ids as $order_id) : ?>
                        <option value="<?= $order_id ?>"><?= $order_id ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="single-group">
                    <label for="">Filter By Date</label>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control wut_filter_order_by_start_date" id="startDate" placeholder="Start Date" name="start_date">
                            <div class="input-group-addon">To</div>
                            <input type="text" class="form-control wut_filter_order_by_end_date" id="EndDate" placeholder="End Date" name="end_date">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <div class="ex-em-btns export-preview wut_filter_order_preview">Preview</div>
                    <button type="submit" class="ex-em-btns export-btn">Export</button>
                    <div class="ex-em-btns manage-field-btn" data-toggle="modal" data-target="#orderManageField">Manage Field</div>
                </div>
            </div>
        </div>
        <div class="data-table-section">
            <div class="row">
                <div class="col-md-12">
                    <table id="orderTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>User Role</th>
                                <th>User Email</th>
                                <th>User Name</th>
                                <th>Website</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal Start-->
<div class="modal fade" id="orderManageField" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form class="" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
                <input type="hidden" name="action" value="wut_save_order_manage_fields">
                <?php wp_nonce_field('wutSaveOrderManageFields-nonce', 'wutSaveOrderManageFields'); ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span class="modal-title">Manage Field</span>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <?php $count = 0;
                        foreach ($saved_wut_order_filter_fields as $order_fields_data) : ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="hidden" name="wut_order_filter_fields[<?= $count ?>][field_display]" value="0">
                                        <input type="checkbox" value="1" name="wut_order_filter_fields[<?= $count ?>][field_display]" <?= ($order_fields_data['field_display'] == 1 ? 'checked' : '') ?>> <b><?= esc_html($order_fields_data['field_title']) ?></b>
                                        <input type="hidden" name="wut_order_filter_fields[<?= $count ?>][field_key]" value="<?= $order_fields_data['field_key'] ?>">
                                        <input type="hidden" name="wut_order_filter_fields[<?= $count ?>][field_title]" value="<?= $order_fields_data['field_title'] ?>">
                                        <input type="hidden" name="wut_order_filter_fields[<?= $count ?>][field_value]" value="<?= $order_fields_data['field_value'] ?>">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <?php $count++;
                    endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="ex-em-btns">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal end--> 