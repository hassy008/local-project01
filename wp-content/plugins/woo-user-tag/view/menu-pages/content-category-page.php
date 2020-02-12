<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$WutCategory     = new \admin\modules\WutCategory();
$categories      = $WutCategory->getAll( 'id' );
?>
<div class="bootstrap-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<div class="categories-table">
				<?php if (isset($_SESSION['success'])) : ?>
					<div class="alert alert-success"><?= $_SESSION['success'] ?></div>
				<?php unset($_SESSION['success']); endif; ?>
				<?php if (isset($_SESSION['error'])) : ?>
					<div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
				<?php unset($_SESSION['error']); endif; ?>
					<h3>User Category</h3> 
					<div class="form-group text-right">
						<div class="btn btn-add btn-add-cagetory" data-toggle="modal" data-target="#addCatModal">Add Category</div>
					</div>
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($categories)) : ?> 
								<?php foreach ($categories as $category) : ?>
									<tr data-id="<?= $category->id ?>">
										<td><?= $category->id ?></td>
										<td class="cat_name"><?= $category->name ?></td>
										<td><span class="edit edit-category-btn text-warning" data-toggle="modal" data-target="#editCatModal">Edit</span> | <span id="catDelete" class="delete text-danger category-delete">Delete</span></td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
									<tr>
										<td colspan="3">no categories found</td>
									</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Start-->
	<div class="modal fade" id="addCatModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
		    <form class="" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
			    <input type="hidden" name="action" value="save_wut_category">
			    <?php wp_nonce_field('saveWutCategory-nonce', 'saveWutCategory');?>			
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<span class="modal-title">heading</span>
					</div>
					<div class="modal-body">             
						<div class="form-group">
							<label for="">Category Name</label>
							<input type="text" class="form-control" name="name">
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-add">save</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- Modal end-->

	<!-- Edit Modal Start-->
	<div class="modal fade" id="editCatModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
		    <form class="" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
			    <input type="hidden" name="action" value="save_wut_category">
			    <input type="hidden" class="edit_category_id" name="id" value="">
			    <?php wp_nonce_field('saveWutCategory-nonce', 'saveWutCategory');?>		
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<span class="modal-title">heading</span>
					</div>
					<div class="modal-body">             
						<div class="form-group">
							<label for="">Category Name</label>
							<input type="text" class="form-control edit_cat_name" name="name">
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-add">Update</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- Edit Modal end-->

</div>