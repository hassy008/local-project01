<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$WutTag          = new \admin\modules\WutTag();
$tags            = $WutTag->getAll( 'id' );
$WutCategory     = new \admin\modules\WutCategory();
// $WutCategoryTag  = new \admin\modules\WutCategoryTag();
$categories      = $WutCategory->getAll( 'id' );
?>
<div class="bootstrap-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<div class="tags-table">
				<?php if (isset($_SESSION['success'])) : ?>
					<div class="alert alert-success"><?= $_SESSION['success'] ?></div>
				<?php unset($_SESSION['success']); endif; ?>
				<?php if (isset($_SESSION['error'])) : ?>
					<div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
				<?php unset($_SESSION['error']); endif; ?>
					<h3>User Tag</h3>  
					<div class="form-group text-right">
						<div class="btn btn-add btn-add-cagetory" data-toggle="modal" data-target="#addtagModal">Add Tag</div>
					</div>
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Category</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($tags)) : ?> 
								<?php foreach ($tags as $tag) : 
									// $category_name = '';
									// $CategoryTag   = $WutCategoryTag->getRow(['tag_id' => $tag->id]);
									$category_name = $WutCategory->getNameById($tag->category_id);
									?>
							<tr data-id="<?= $tag->id ?>" data-category_id="<?= $tag->category_id ?>">
								<td><?= $tag->id ?></td>
								<td class="tag_name"><?= $tag->name ?></td>
								<td><?= $category_name ?></td>
								<td><span class="edit edit-tag-btn text-warning" data-toggle="modal" data-target="#edittagModal">Edit</span> | <span id="tagDelete" class="delete text-danger tag-delete">Delete</span></td>
							</tr>
								<?php endforeach; ?>
							<?php else: ?>
									<tr>
										<td colspan="4">no tags found</td>
									</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Start-->
	<div class="modal fade" id="addtagModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
		    <form class="" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
			    <input type="hidden" name="action" value="save_wut_tag">
			    <?php wp_nonce_field('saveWutTag-nonce', 'saveWutTag');?>	
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<span class="modal-title">heading</span>
					</div>
					<div class="modal-body">   
						<div class="form-group">
							<label for="">Category</label>
							<select name="category_id" id="" class="addCategory">
							<?php if (!empty($categories)) : ?> 
								<?php foreach ($categories as $category) : ?>
								<option value="<?= $category->id ?>"><?= $category->name ?></option>
								<?php endforeach; ?>
								<?php else: ?>
										no categories found
								<?php endif; ?>
							</select>
						</div>          
						<div class="form-group">
							<label for="">Tag Name</label>
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
	<div class="modal fade" id="edittagModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
		    <form class="" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST">
			    <input type="hidden" name="action" value="save_wut_tag">
			    <input type="hidden" class="edit_tag_id" name="id" value="">
			    <?php wp_nonce_field('saveWutTag-nonce', 'saveWutTag');?>
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<span class="modal-title">heading</span>
					</div>
					<div class="modal-body">             
						<div class="form-group">
							<label for="">Category</label>
							<select name="category_id" id="" class="addCategory edit-tag-cat-select">
							<?php if (!empty($categories)) : ?> 
								<?php foreach ($categories as $category) : ?>
								<option value="<?= $category->id ?>"><?= $category->name ?></option>
								<?php endforeach; ?>
								<?php else: ?>
										no categories found
								<?php endif; ?>
							</select>
						</div>             
						<div class="form-group">
							<label for="">Tag Name</label>
							<input type="text" class="form-control edit_tag_name" name="name">
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