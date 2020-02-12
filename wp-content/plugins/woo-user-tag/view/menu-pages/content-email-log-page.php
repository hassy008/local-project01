<?php
if ( ! defined( 'ABSPATH' ) ) exit;
	$WutSequence = new \admin\modules\WutSequence();
	$WutCampaign = new \admin\modules\WutCampaign();
	$sentSequences = $WutSequence->get(['status' => 'sent']);
	$nullSequences = $WutSequence->get(['status' => '']);
	
?>
<div class="bootstrap-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<div class="email-log">
					<h3>Email Log</h3>
					<div class="email-log-tabs">
						<!-- Nav tabs -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active"><a href="#upcoming-email" aria-controls="upcoming-email" role="tab" data-toggle="tab">upcoming-email</a></li>
							<li role="presentation"><a href="#send-email" aria-controls="send-email" role="tab" data-toggle="tab">send-email</a></li>
						</ul>

						<!-- Tab panes -->
						<div class="tab-content">
							<div role="tabpanel" class="tab-pane active" id="upcoming-email">
								<div class="upcoming-email-log-table">
									<table class="table table-striped table-bordered">
										<thead>
											<tr>
												<th>User Name</th>
												<th>User Email</th>
												<th>Campaign Title</th>
												<th>Next time</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
										<?php if ($nullSequences) : ?>
										<?php foreach ($nullSequences as $nullSequence) :
										$first_name = get_user_meta($nullSequence->user_id, 'first_name', true);
									$last_name = get_user_meta($nullSequence->user_id, 'last_name', true);
									$trigger_at = date("F j, Y, g:i a", strtotime($nullSequence->trigger_at));
									$campaign = $WutCampaign->getRow(['id' => $nullSequence->campaign_id]);
									$status = $nullSequence->status ? $nullSequence->status : 'not yet sent.';
									?>								
											<tr>
												<td><?= "{$first_name} {$last_name}" ?></td>
												<td><?= $nullSequence->email ?></td>
												<td><?= $campaign->title ?></td>
												<td><?= $trigger_at ?></td>
												<td><?= $status ?></td>
											</tr>
										<?php endforeach; ?>
										<?php else : ?>						
											<tr>
												<td colspan="6">none found</td>
											</tr>
										<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
							<div role="tabpanel" class="tab-pane" id="send-email">
								<div class="send-email-log-table">
									<table class="table table-striped table-bordered">
										<thead>
											<tr>
												<th>User Name</th>
												<th>User Email</th>
												<th>Campaign Title</th>
												<th>Sent at</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
										<?php if ($sentSequences) : ?>
										<?php foreach ($sentSequences as $sentSequence) :
										$first_name = get_user_meta($sentSequence->user_id, 'first_name', true);
									$last_name = get_user_meta($sentSequence->user_id, 'last_name', true);
									$trigger_at = date("F j, Y, g:i a", strtotime($sentSequence->trigger_at));
									$campaign = $WutCampaign->getRow(['id' => $sentSequence->campaign_id]);
									$status = $sentSequence->status ? $sentSequence->status : 'not yet sent.';
									?>								
											<tr>
												<td><?= "{$first_name} {$last_name}" ?></td>
												<td><?= $sentSequence->email ?></td>
												<td><?= $campaign->title ?></td>
												<td><?= $trigger_at ?></td>
												<td><?= $status ?></td>
											</tr>
										<?php endforeach; ?>
										<?php else : ?>						
											<tr>
												<td colspan="6">none found</td>
											</tr>
										<?php endif; ?>

										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>