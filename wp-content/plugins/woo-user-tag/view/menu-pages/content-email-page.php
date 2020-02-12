<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$WutEmail = new admin\WutEmail();
$emailConfig = $WutEmail->config();
?>
<div class="bootstrap-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<div class="campain-email">
					<h3>Email Configuration</h3>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="">From Name</label>
								<input type="text" class="form-control wut_email_from_name" value="<?= $emailConfig['from_name'] ?>">
							</div>
							<div class="form-group">
								<label for="">Replay to Name</label>
								<input type="text" class="form-control wut_email_reply_name" value="<?= $emailConfig['reply_name'] ?>">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="">From Email</label>
								<input type="email" class="form-control wut_email_from_email" value="<?= $emailConfig['from_email'] ?>">
							</div>
							<div class="form-group">
								<label for="">Replay to Email</label>
								<input type="email" class="form-control wut_email_reply_email" value="<?= $emailConfig['reply_email'] ?>">
							</div>
						</div>
					</div>
					<div class="text-right">
						<div class="btn btn-add wut_email_save">Save</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>