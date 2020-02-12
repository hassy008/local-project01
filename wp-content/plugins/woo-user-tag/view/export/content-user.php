<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$saved_wut_user_filter_fields = get_option('wut_user_filter_fields');

global $wpdb;
$user_metafields_arr = $wpdb->get_results("SELECT DISTINCT meta_key FROM {$wpdb->prefix}usermeta", ARRAY_N);

$user_metafields = array_map(function ($n) {
    return $n[0];
}, $user_metafields_arr);

$users = get_users();
$user_fields['user_info'] = ['user_email', 'user_login', 'ID', 'user_nicename', 'user_url', 'user_registered', 'display_name'];

$user_fields['meta'] = $user_metafields;
$user_roles = wp_roles()->get_names();

$Practice_areas = [
	'FDRP Med',
	'FDRP Arb',
	'FDRP PC',
	'Academic & Policy',
	'Arbitration',
	'Children & Youth',
	'Consultant & Coaches',
	'Estates & Elder',
	'Finance',
	'Mediation',
	'Parenting Coordination',
	'Therapists & Assessors',
	'Intimate Partner Violence'

];

?>
<div class="export-import-section">
    <form class="" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
        <input type="hidden" name="action" value="wut_export_users">
        <?php wp_nonce_field('wutExportUsers-nonce', 'wutExportUsers'); ?>
        <div class="row form-group">
            <?php if (isset($_SESSION['message'])) : ?>
            <div class="alert alert-<?= $_SESSION['type']; ?>">
                <?= $_SESSION['message']; ?>
            </div>
            <?php unset($_SESSION['message']);
            unset($_SESSION['type']);
        endif; ?>
            <div class="col-md-6">
                <div class="single-group">
                    <label for="">Filter By User ID / Username / Email</label>
                    <select class="form-control filter-by wut_filter_by_username" multiple="multiple" name="usernames[]">
                        <?php foreach ($users as $user) : ?>
                        <option>
                            <?= $user->user_login; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="single-group">
                    <label for="">Filter By User Role</label>
                    <select class="form-control filter-by wut_filter_by_role" multiple="multiple" name="user_roles[]">
                        <?php foreach ($user_roles as $role_name => $user_role) : ?>
                        <option value="<?= $role_name ?>">
                            <?= $user_role; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="single-group">
                    <label for="">Page Number</label>
                    <input type="text" class="form-control wut_filter_by_paged" name="paged">
                </div>
            </div>
            <div class="col-md-3">
                <div class="single-group">
                    <label for="">Users per page</label>
                    <input type="text" class="form-control wut_filter_by_limit" name="limit">
                </div>
            </div>
            <div class="col-md-6">
                <div class="single-group">
                    <label for="">Filter By Date</label>
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control wut_filter_by_start" id="startDate" placeholder="Start Date" name="start">
                            <div class="input-group-addon">To</div>
                            <input type="text" class="form-control wut_filter_by_end" id="EndDate" placeholder="End Date" name="end">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="single-group">
                    <label for="">Filter By Meta</label>
                    <select class="form-control wut_filter_by_meta" name="wut_filter_by_meta">
                        <option value="">---- SELECT ONE ----</option>
                        <?php foreach ($user_fields['meta'] as $meta) : ?>
                        <option><?= $meta; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="single-group">
                    <label for="">Metavalue</label>
                    <input type="text" class="form-control wut_filter_by_meta_value" name="meta">
                </div>
            </div>
           
            <div class="col-md-3">
                <div class="single-group">
                    <label for="">Filter By Practice Areas</label>
                    <select class="form-control wut_filter_by_practice_area" name="wut_filter_by_practice_area">
                        <option value="">---- SELECT ONE ----</option>
                        <?php foreach ($Practice_areas as $Practice_area) : ?>
                        <option><?= $Practice_area; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 20px;">
            <div class="col-md-12">
                <div class="text-center">
                    <div class="ex-em-btns export-preview wut_filter_user_preview">Preview</div>
                    <button type="submit" class="ex-em-btns export-btn wut_filter_user_export">Export</button>
                    <div class="ex-em-btns manage-field-btn" data-toggle="modal" data-target="#manageFieldModal">Manage Field</div>
                </div>
            </div>
        </div>
    </form>
    <div class="data-table-section">
        <div class="row">
            <div class="col-md-12">
                <table id="userTable" class="table table-striped table-bordered">
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
</div>
<!-- Modal Start-->
<div class="modal fade" id="manageFieldModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form class="" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
                <input type="hidden" name="action" value="wut_save_manage_fields">
                <?php wp_nonce_field('wutSaveManageFields-nonce', 'wutSaveManageFields'); ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span class="modal-title">Manage Field</span>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <?php foreach ($user_fields['user_info'] as $field) : ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="<?= $field; ?>" name="wut_user_filter_fields[user_info][]" <?= (array_search($field, $saved_wut_user_filter_fields['user_info']) === false ? '' : 'checked') ?>><b>
                                            <?= $field; ?></b>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php foreach ($user_fields['meta'] as $field) : ?>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" value="<?= $field; ?>" name="wut_user_filter_fields[meta][]" <?= (array_search($field, $saved_wut_user_filter_fields['meta']) === false ? '' : 'checked') ?>> <b>
                                            <?= $field; ?></b>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="ex-em-btns">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal end-     - >                      