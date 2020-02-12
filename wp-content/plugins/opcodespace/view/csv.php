<?php
// WP_User_Query arguments
$args = array(
    'role'           => array('subscriber', 'author'),
    'order'          => 'DESC',
);

// The User Query
$user_query = new WP_User_Query($args);
$admins = $user_query->get_results();

// echo '<pre>';
// print_r($user_query);
// echo '</pre>';
?>

<?php
$OpsWooCrud = new OpsWooCrud();
// $getAll = $OpsWooCrud->search($_GET['search_col'], $_GET['search_term']);
$getAll = $OpsWooCrud->getAll('id');
?>

<?php if (isset($_SESSION['message'])) : ?>
    <div class="alert alert-<?= $_SESSION['type'] ?>">
        <?= $_SESSION['message'] ?>
    </div>
<?php endif;
unset($_SESSION['message']);
unset($_SESSION['type']);
?>

<div>
    <a href="<?= admin_url('admin-post.php?action=users_xlsx'); ?>" class="export-btn"><span class="fa fa-file-excel-o"></span> All Users </a>
    <br>
    <a href="<?= admin_url('admin-post.php?action=employee_xlsx'); ?>" class="export-btn"><span class="fa fa-file-excel-o"></span> All Employee </a>
    <a href="<?= admin_url('admin-post.php?action=generate_employee_xlsx'); ?>" class="export-btn"><span class="fa fa-file-excel-o"></span> Export As CSV</a>
    <a href="<?= admin_url('admin-post.php?action=excel_by_template'); ?>" class="export-btn"><span class="fa fa-file-excel-o"></span> Excel By Template</a>
</div>

<div class="bootstrap-wrapper">

    <!-- SEARCH Option -->
    <div class="search-frm-wrapper">
        <form class="form-inline" action="" style="float: right; margin-bottom: 15px" id="early-bird-registrant-filter">
            <div class="form-group">
                <div class="select-wrapper">
                    <select class="select-style form-control" name="search_col">
                        <option value="first_name" <?= $search_col == 'first_name' ? ' selected' : '' ?>>First Name
                        </option>
                        <option value="last_name" <?= $search_col == 'last_name' ? ' selected' : '' ?>>Last Name
                        </option>
                        <option value="email" <?= $search_col == 'email' ? ' selected' : '' ?>>Email</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="search_term" value="<?= $search_term ?>">
            </div>
            <button type="submit" class="btn btn-danger member-search">Search</button>
        </form>
    </div>
    <!-- SEARCH Option -->
    <?php

    if (isset($_GET['order'])) {
        $order = $_GET['order'];
    } else {
        $order = 'col_name';
    }

    if (isset($_GET['sort'])) {
        $sort = $_GET['sort'];
    } else {
        $sort = 'ASC';
    }
    ?>

    <?php
    #### pagination began
    // $urlPattern = '/dashboard/?section=members&pg=(:num)';


    $urlPattern = '/csv&pg=(:num)';
    $rowPerPage = 10;
    $currentPage = isset($_GET['pg']) ? intval($_GET['pg']) : 1;
    $offset = ($currentPage - 1) * $rowPerPage;

    $args = array(
        'offset'       => $offset,
        'number'       => $rowPerPage,
        'count_total'  => true,
        'fields'       => 'all',
    );
    #### pagination 
    $wp_user_query = new WP_User_Query($args);

    $totalCount = $wp_user_query->get_total();

    $OpsPaginator = new OpsPaginator($totalCount, $rowPerPage, $currentPage, $urlPattern);

    ?>

    <div class="row">
        <div class="col-md-12">
            <div class="hotal-wrapper">
                <div class="hotel-booking-table">
                    <div class="text-right form-group">
                        <button class="btn btn-danger" data-toggle="modal" data-target="#addHotelModal">Add
                            Employee</button>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><a href='?order=id&&sort=$sort'><?php _e('ID', 'opcodespace') ?></a></th>
                                <!-- <th><a href='?order=first_name&&sort=$sort'> First Name </a></th> -->
                                <th> First Name </th>

                                <th>Email</th>
                                <th>home_address</th>
                                <th>Message</th>
                                <th>Notes</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($getAll as $getAllData) : ?>
                                <tr>
                                    <td class="action"><?= $getAllData->id; ?></td>
                                    <td><?= $getAllData->first_name; ?></td>
                                    <td><?= $getAllData->email ?></td>
                                    <td><?= $getAllData->home_address ?></td>
                                    <td><?= $getAllData->messages ?></td>
                                    <td class="action"><span data-toggle="modal" data-target="#viewFlightNoteModal" class="show-flight-notes" data-id="<?= $getAllData->id; ?>"><i class="fa fa-file-text"></i></span></td>
                                    <td><?= $getAllData->date ?></td>

                                    <td class="action">
                                        <span class="edit-hotel-name" data-toggle="modal" data-target="#addHotelModal" data-id="<?= $getAllData->id; ?>"><i class="fa fa-edit"></i></span>
                                        <span class="delete-hotel-name" data-id="<?= $getAllData->id; ?>"><i class="fa fa-trash"></i></span>
                                        <span class="add-note" data-toggle="modal" data-target="#addNote" data-id="<?= $getAllData->id; ?>"><i class="fa fa-file-text"></i>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <?= $OpsPaginator->toHtml(); ?>

                </div>

            </div>
        </div>
    </div>
</div>

<!-- Add Note Modal Start-->
<div class="modal fade" id="addNote" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
                <input type="hidden" name="action" value="add_note">
                <input type="text" name="emp_id">

                <?php wp_nonce_field('add_note-nonce', 'add_note'); ?>

                <?php if (isset($_SESSION['message_hotel'])) : ?>
                    <div class="alert alert-<?= $_SESSION['type_hotel'] ?>">
                        <?= $_SESSION['message_hotel'] ?>
                    </div>
                <?php endif;
                unset($_SESSION['message_hotel']);
                unset($_SESSION['type_hotel']); ?>
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span class="modal-title">Add Note</span>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <textarea name="add_note" style="min-height: 150px" class="form-control" placeholder="Add Your Note" required></textarea>
                    </div>
                    <!-- <div class="text-right">
                     <div class="btn btn-danger dashboard-congress-registration-save-note">Save</div>
                 </!-->
                    <div class="form-group text-right">
                        <button class="btn btn-danger" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Add Note Modal end-->


<!-- Add Modal Start-->
<div class="modal fade" id="addHotelModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <span class="modal-title">
                    <?php
                    // echo '<pre>';
                    // print_r($getAll);
                    // echo '</pre>';

                    if (!empty($getAll->id)) {
                        echo "Edit Employee";
                    } else {
                        echo " Add Employeeee";
                    } ?>
                </span>
            </div>
            <div class="modal-body">
                <form class="" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
                    <input type="hidden" name="action" value="add_modal_crud">
                    <input type="text" name="id">

                    <?php wp_nonce_field('add_modal_crud-nonce', 'add_modal_crud'); ?>
                    <?php if (isset($_SESSION['message_hotel'])) : ?>
                        <div class="alert alert-<?= $_SESSION['type_hotel'] ?>">
                            <?= $_SESSION['message_hotel'] ?>
                        </div>
                    <?php endif;
                    unset($_SESSION['message_hotel']);
                    unset($_SESSION['type_hotel']); ?>

                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="fname" required>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" name="lname" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" required>
                            </div>
                            <div class="form-group">
                                <label>Home Address</label>
                                <textarea type="text" class="form-control" name="haddress" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Message</label>
                                <textarea type="text" class="form-control" name="message" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" class="form-control" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label>Notes</label>
                                <input type="text" class="form-control" name="note_update" required>
                            </div>
                            <div class="form-group">
                                <label>date</label>
                                <input type="text" class="form-control" name="date" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button class="btn btn-danger" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Modal end-->







<!-- Add Modal Start-->
<div class="modal fade" id="addNote" tabindex="-1" role="dialog">
    <!--      <div class="modal-dialog" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                         aria-hidden="true">&times;</span></button>
                 <span class="modal-title"> add note
                 </span>
             </div>
             <div class="modal-body">
                 <form class="" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
                     <input type="hidden" name="action" value="add_modal_crud">
                     <input type="hidden" name="id">

                     <?php wp_nonce_field('add_modal_crud-nonce', 'add_modal_crud'); ?>

                     <div class="row">
                         <div class="col-md-6 col-md-offset-3">
                             <div class="form-group">
                                 <label>Notes</label>
                                 <input type="text" class="form-control" name="note_update" required>
                             </div>
                         </div>
                     </div>
                     <div class="form-group text-right">
                         <button class="btn btn-danger" type="submit">Save</button>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div> -->
    <!-- Add Modal end-->



    <!-- View Note Modal Start-->
    <div class="modal fade" id="viewFlightNoteModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <span class="modal-title">Your Notes</span>
                </div>
                <div class="modal-body">
                    <div class="single-notes">
                        <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Atque porro incidunt error nostrum, labore saepe pariatur similique officia voluptatem! Repellendus iure commodi aliquid nemo nisi rerum quasi sunt, ducimus libero!</p>
                    <label>Message</label>
                    <textarea type="text" class="form-control" name="message" required></textarea>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- View Note Modal end-->






    <!--script.js-->
    <script>
        /*members search */
        $('.member-search').click(function(e) {
            e.preventDefault();
            let search_col = $('[name="search_col"]').val();
            let search_term = $('[name="search_term"]').val();

            let urlParams = new URLSearchParams(window.location.search);
            urlParams.set("search_col", search_col);
            urlParams.set("search_term", search_term);
            console.log(urlParams.toString());

            window.location.href = window.location.origin + window.location.pathname + '?' + urlParams.toString();
        })
    </script>
    </script>