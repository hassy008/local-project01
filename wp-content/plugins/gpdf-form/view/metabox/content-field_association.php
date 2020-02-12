<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$apiData            = (new LPFAddOn)->get_plugin_settings();
$form_id           = esc_sql($_GET['g_form_id']) ? esc_sql($_GET['g_form_id']) : null;
$template_id       = esc_sql($_GET['template_id']) ? esc_sql($_GET['template_id']) : null;
$forms             = GFAPI::get_forms();
$fields            = [];
$templateFields    = [];
$LpfApi            = new LpfApi;
$tokenResponse     = $LpfApi->generateToken();

$assoc_arr =  (new LpfFieldsAssociation)->getRow(['form_id' => $form_id]);
$assc_fields = unserialize($assoc_arr->arr);

if (!$tokenResponse['success']) : ?>
	<div class="alert alert-warning"><?= $tokenResponse['message'] ?>. Save your Email and API key <a href=" <?= esc_url(admin_url('admin.php?page=gf_settings&subview=gpdf_setting')  ) ?> ">here</a>  </div>
<?php return; endif;

$token             = $tokenResponse['token'];

$templatesResponse = $LpfApi->getTemplates($token);


if (!$templatesResponse['success']) : ?>
    <div class="alert alert-warning"><?= $templatesResponse['message'] ?>. Upload your templates <a href=" <?= esc_url('https://linkpdfform.com/my-account/') ?> ">here</a> </div>
    <?php return; endif;
$templates         = $templatesResponse["templates"];
if (!empty($template_id)) {
	$templateFieldResponse     = $LpfApi->getTemplateFields($token, $template_id);
	$templateFields            = $templateFieldResponse['fields'];
}

?>
<div class="gf-pdf-form">
	<div class="bootstrap-wrapper">
		<div class="container-fluid">
			<div class="row">
                <?php
                 if (empty($apiData['Apikey']) || empty($apiData['ApiEmail'])) :?>
                <div class="alert alert-danger">
                    Please provide App secret and Email. If you don't have please register <a href="<?=  esc_url(home_url('login')) ?>">here</a>
                </div>
                <?php
                 endif;
                ?>
				<div class="col-md-6">
					<!-- PDF File Fields -->
					<?php if (!empty($templates)) :  ?>
						<div class="form-group">
							<label for="lpf-template-list">Select PDF File:</label>
							<select class="form-control chzn-select" id="lpf-template-list">
								<option value="">Select one file</option>
								<?php foreach ($templates as $template) : 
									$isSelectedtemplate = '';
									if ($template->id == $template_id ) {
										$isSelectedtemplate = ' selected';
									}
									?>
									<option value="<?= esc_attr($template->id) ?>" <?= esc_attr($isSelectedtemplate) ?>><?= esc_attr($template->name) ?></option>
								<?php endforeach; ?>
							</select>
						</div>	
						<?php else: ?>			
							<div class="alert alert-warning"><?= esc_attr($templatesResponse['message']) ?></div>
						<?php endif; ?>	
					</div>
				</div>
				<!-- Gravity Form Fields -->
				<div class="row">
					<div class="col-md-6">
						<?php if (!empty($forms)) :  ?>
							<div class="form-group">
								<label for="lpf-g-form-list">Select Form:</label>
								<select class="form-control chzn-select" id="lpf-g-form-list">
									<option value="">Select one form</option>
									<?php foreach ($forms as $form) : 
										$isSelectedForm = '';
										if ($form['id'] == $form_id ) {
											$fields     = $form['fields'];
											$isSelectedForm = ' selected';
										}
										?>
										<option value="<?= esc_attr($form['id']) ?>" <?= esc_attr($isSelectedForm) ?>><?= esc_attr($form['title']) ?></option>
									<?php endforeach; ?>
								</select>
							</div>	
							<?php else: ?>			
								<div class="alert alert-warning">No gravity forms found.</div>
							<?php endif; ?>	
							<?php if (!empty($form_id) && !empty($fields)) : ?>
								<input type="hidden" name="form_id" value="<?= esc_attr($form_id) ?>">
								<input type="hidden" name="template_id" value="<?= esc_attr($template_id) ?>">
								<div class="form-group">
									<div class="form-group">
										<label for="g-form-field-list">Select Field:</label>
									</div>
									<ol>
										<?php foreach ($fields as $field) : ?>	
											<div class="form-group row">
											<label for="<?=esc_attr($field['id'])?>" class="col-sm-6 control-label">
												<li data-g-form-field-id="<?=esc_attr($field['id'])?>"><?= esc_attr($field['label']) ?></li>
											</label>
											<div class="col-sm-5">
												<select class="form-control chzn-select" id="lpf-template-fields" name="data[arr][<?= $field['id'] ?>][pdf_field_name]">
													<?php foreach ($templateFields as $templateField) : ?>
														<option class="template_field_name" <?= $assc_fields[$field['id']]['pdf_field_name'] == $templateField ? ' selected' : '' ?> ><?= esc_attr($templateField) ?></option>
													<?php endforeach; ?>
												</select>
											</div>
											</div>						
										<?php endforeach; ?>
									</ol>
								</div>
						<?php endif; ?>						
					</div>
				</div>
			</div>
		</div>
</div>
<script>

</script>