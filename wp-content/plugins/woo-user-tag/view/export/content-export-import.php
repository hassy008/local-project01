<?php
if ( ! defined( 'ABSPATH' ) ) exit;$tab = esc_sql($_GET['tab']); ?>
<div class="bootstrap-wrapper">
	<div class="container-fluid">
		<div class="topbar-tab">
			<a href="<?php echo admin_url("/admin.php?page=woo_export_import") ?>" class="tabitems <?= empty($tab) ? 'active' : '' ?>">User</a>
			<a href="<?php echo admin_url("/admin.php?page=woo_export_import&tab=order") ?>" class="tabitems <?= $tab == 'order' ? 'active' : '' ?>">Orders</a>
		</div>

			<?php 
			if('order' == esc_sql($_GET['tab']))
				include_once("content-order.php"); 
			else
			include_once("content-user.php"); 
			?>
	</div>
</div>