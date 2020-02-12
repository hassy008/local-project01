<?php

if ( ! defined( 'ABSPATH' ) ) exit;
$WutCampaign = new \admin\modules\WutCampaign();
$campaigns   = $WutCampaign->getAll('created_at');
?>
<div class="bootstrap-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<div class="campain-table">
					<h3>Campaign</h3>
					<div class="text-right form-group">
						<a href="<?= admin_url('admin.php?page=woo_add_campaign'); ?>"" class="add-campain-btn">Add Campaign</a>
					</div>
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Name</th>
								<th>Delay</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
						<?php if (!empty($campaigns)) : ?>
							<?php foreach ($campaigns as $campaign) : ?>
								<tr data-id="<?= $campaign->id ?>">
									<td><?= $campaign->title ?></td>
									<td><?php echo "{$campaign->delay} {$campaign->delay_type}" ?></td>
									<td> <a href='<?= admin_url("admin.php?page=woo_add_campaign&campaign_id={$campaign->id}") ?>' class="edit">Edit</a> | <span class="delete delete-campaign">Delete</span></td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
								<tr>
									<td colspan="3">No campaign data found.</td>
								</tr>
						<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>