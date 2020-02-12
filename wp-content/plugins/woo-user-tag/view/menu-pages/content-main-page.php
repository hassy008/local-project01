<?php

if ( ! defined( 'ABSPATH' ) ) exit;
?>
<div class="bootstrap-wrapper">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-6">
				<div class="main-pages-btns">
					<div class="form-group">
						<h3>Woo User Tag</h3>
					</div>
					<a href=<?= admin_url() ."admin.php?page=woo_user_tags"?>  class="common-btn main-addtag-btn">Add Tag</a>
					<a href=<?= admin_url() ."admin.php?page=woo_user_tag_categories"?>  class="common-btn main-addcat-btn">Add Category</a>
					<a href=<?= admin_url() ."admin.php?page=woo_add_campaign"?>  class="common-btn main-addcampain-btn">Add Campaign</a>
				</div>
			</div>
		</div>
	</div>
</div>