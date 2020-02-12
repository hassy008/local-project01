<?php
if ( ! defined( 'ABSPATH' ) ) exit;
    $WutTag         = new admin\modules\WutTag();
    $WutCampaign    = new admin\modules\WutCampaign();
	$WutCategory    = new admin\modules\WutCategory();
	$WutMailTags    = new admin\WutMailTags();     
	$merge_tags     = $WutMailTags->allUserTags();
	
	$campaign    = [];
	$campaign_id = null;
	if (esc_sql(isset($_GET['campaign_id']))) {
		$campaign_id = intval(esc_sql($_GET['campaign_id']));
		$campaign    = $WutCampaign->getRow(['id' => $campaign_id]);
		$tag_ids     = explode(",", $campaign->tag_ids);
	}

    $tags           = $WutTag->getAll('id');
?>
<div class="bootstrap-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<div class="campaign-wrapper">
					<div class="form-group">
						<h3>Add Campain</h3>
					</div>
					<?php if (isset($_SESSION['success'])) : ?>
					<div class="alert alert-success"><?= $_SESSION['success'] ?>
					</div>
					<?php unset($_SESSION['success']); endif; ?>
					<?php if (isset($_SESSION['error'])) : ?>
					<div class="alert alert-danger"><?= $_SESSION['error'] ?>
					</div>
					<?php unset($_SESSION['error']); endif; ?>
					<form class="" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
					 method="POST">
						<input type="hidden" name="action" value="update_wut_campaign">
						<input type="hidden" name="id" value="<?= $campaign_id ?>">
						<?php wp_nonce_field('updateWutCampaign-nonce', 'updateWutCampaign');?>
						<div class="single-group">
							<label for="">Campaign Name</label>
							<input type="text" class="form-control" name="title" value="<?= $campaign->title ?>" required>
						</div>
						<div class="single-group">
							<label for="">Goal</label>
							<?php if (!empty($tags)) : ?>
								<select class="form-control select2-style" multiple="multiple" name="tag_ids[]" required>
									<?php foreach ($tags as $tag) :
										$isSelected = '';
										$categoryName = $WutCategory->getNameById($tag->category_id);
										$isSelected   = in_array($tag->id, $tag_ids) ? ' selected' : '';
										?>
										<option value="<?= $tag->id ?>" <?= $isSelected ?>
										><?php echo "{$categoryName} -> {$tag->name}"  ?>
									</option>
								<?php endforeach; ?>
							</select>
							<?php else: ?>
								<p>Tags not found.</p>
							<?php endif; ?>
						</div>
						<div class="single-group">
							<label for="">Delay</label>
							<div class="row">
								<div class="col-sm-4">
									<input type="text" class="form-control" name="delay" value="<?= $campaign->delay ?>" required>
								</div>
								<div class="col-sm-4">
									<select class="form-control select2-style" name="delay_type">
										<option <?= ($campaign->delay_type == "Hour" ? " selected" : "") ?>>Hour</option>
										<option <?= ($campaign->delay_type == "Minute" ? " selected" : "") ?>>Minute</option>
										<option <?= ($campaign->delay_type == "Week" ? " selected" : "") ?>>Week</option>
										<option <?= ($campaign->delay_type == "Month" ? " selected" : "") ?>>Month</option>
									</select>
								</div>
								<div class="col-sm-4">
									<p>(if immidiate just test 0 min)</p>
								</div>
							</div>
						</div>
						<div class="single-group">	
							<label for="">Subject</label>
							<input type="text" class="form-control" name="subject" value="<?= $campaign->subject ?>" required>
						</div>
						<div class="single-group">
							<label for="" class="email-content">Email content</label>
							<?php
							\admin\WutEditor::render($campaign->content, "content");
							?>
						</div>
						<div class="text-right">
							<button class="btn btn-add" type="submit">Save</button>
						</div>
					</form>
				</div>
			</div>
			<div class="col-md-4 col-md-offset-2">
				<div class="short-description-table">
					<div class="table-scroll">
						<table class="table table-bordered">
							<thead>
								<tr>
									<th class="short-list">Short List</th>
									<th class="description">Description</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($merge_tags as $tag ) : ?>
									<tr>
										<td class="short-list">{<?= $tag['meta_key'] ?>}</td>
										<td class="description"><?= ($tag['description'] ? $tag['description'] : 'no description yet.')?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>