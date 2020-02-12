<?php 
namespace admin;
/**
 * 
 */
class WutHook
{

	public static function init()
	{
		$self = new self;
		add_action('admin_init', array($self, 'customMetaBox'));
		add_action('admin_menu', array($self, 'wutMenu'));
		add_action('woocommerce_product_after_variable_attributes', array($self, 'addToVariationsMetabox'), 10, 3);
		// add_action( 'woocommerce_save_product_variation', array($self, 'saveProductVariation'), 20, 2 );
		// add_action( 'save_post', array($self, 'wutCreateOrUpdateProduct' ), 10, 3);

		/** WOOCOMMERCE ORDER STATUS COMPLETED */
		add_action('woocommerce_order_status_completed', array($self, 'woocommerceOrderStatusCompleted'), 10, 1);
	}

	public function woocommerceOrderStatusCompleted($order_id)
	{
		$order      = wc_get_order($order_id);

		if (empty($order)) {return;}

		# get user info
		$user_id    = $order->get_user_id();
		$user       = get_user_by('id', $user_id);
		if (empty($user)) {return;}
		$user_email = $user->user_email;
		$items      = $order->get_items();

		$inserted_campaign_id = [];

		foreach ($items as $item) {
			# get product info
			$product      = $item->get_product();
			$product_id   = $product->get_id();
			$variation_id = null;

			if ($item->is_type('variable')) {
				$variation_id = $item->get_variation_id();
			}

			$WutTagRules = new modules\WutTagRules();
			$WutCampaign = new modules\WutCampaign();
			$WutSequence = new modules\WutSequence();
			# product tags
			# Variations Tag
			$tag_rules    = $WutTagRules->get(['product_id' => $product_id, 'variation_id' => $variation_id]);
			if (empty($tag_rules)) {continue;}

			foreach ($tag_rules as $tag_rule) {
				$campaigns    = $WutCampaign->getByTagId($tag_rule->tag_id);
				if (empty($campaigns)) {continue;}

				foreach ($campaigns as $campaign) {
					if(in_array($campaign->id, $inserted_campaign_id)) continue;
					$inserted_campaign_id[] = $campaign->id;
					$increase_by = $campaign->delay ." ". strtolower($campaign->delay_type);
					$trigger_at  = date("Y-m-d H:i:s", strtotime("+$increase_by"));

					$data                = [];
					$data['campaign_id'] = $campaign->id;
					$data['trigger_at']  = $trigger_at;
					$data['user_id']     = $user_id;
					$data['email']       = $user_email;

					if (empty($campaign->delay)) {
						$WutMailTags = new \admin\WutMailTags();
						$WutEmail    = new \admin\WutEmail();
						$tags        = $WutMailTags->allUserTags($user_id);
						$short_codes = $WutEmail->mergeShortCode($tags);
						$content     = $WutEmail->mergeReplace($short_codes, $campaign->content);
						$WutEmail->send($user_email, $campaign->subject, $content); 
						$data['status']     = 'sent';
						$data['trigger_at'] = date("Y-m-d H:i:s");

					}
					// $sequence = $WutSequence->updateOrInsert($data, [
					// 	'campaign_id' => $campaign->id,
					// 	'user_id'     => $user_id,
					// ]);
					$sequence = $WutSequence->insert($data);
				}
			}
		}
	}

	/******************************************************************************
	 *
	 *                           PRODUCT CREATE OR UPDATE
	 *
	 *******************************************************************************/

	public function wutCreateOrUpdateProduct($post_id, $post, $update)
	{
		$WutTagRules = new modules\WutTagRules();
		if ($post->post_status != 'publish' || $post->post_type != 'product') {
			return;
		}
		if (!$product = wc_get_product($post)) {
			return;
		}
		if (!esc_sql(isset($_POST['select_product_tag_ids']))) {
			return;
		}
		$product_id = $product->get_id();
		$select_product_tag_id = (esc_sql($_POST['select_product_tag_ids']) === '') ? '' : esc_sql($_POST['select_product_tag_ids']);
		$deselect_product_tag_id = (esc_sql($_POST['deselect_product_tag_ids']) === '') ? '' : esc_sql($_POST['deselect_product_tag_ids']);
		if (is_array($select_product_tag_id)) {
			foreach ($select_product_tag_id as $tag_id) {
				$WutTagRules->updateOrInsert([
					'tag_id' => $tag_id,
					'action' => 'select',
					'product_id' => $product_id,
				], [
					'product_id' => $product_id,
					'tag_id' => $tag_id,
				]);
			}
		}
		if (is_array($deselect_product_tag_id)) {
			foreach ($deselect_product_tag_id as $tag_id) {
				$WutTagRules->updateOrInsert([
					'tag_id' => $tag_id,
					'action' => 'deselect',
					'product_id' => $product_id,
				], [
					'product_id' => $product_id,
					'tag_id' => $tag_id,
				]);
			}
		}
	}

	/******************************************************************************
	 *
	 *                           VARIATION META BOX 
	 *
	 *******************************************************************************/
	public function saveProductVariation($variation_id, $loop)
	{

		$WutTagRules = new modules\WutTagRules();
		$product_id = intval($_POST['wut_product_id']);
		if (esc_sql(isset($_POST['select_product_tag_id'][$loop]))) {
			$select_product_tag_id = (esc_sql($_POST['select_product_tag_id'][$loop])) === '' ? '' : esc_sql($_POST['select_product_tag_id'][$loop]);
			if (is_array($select_product_tag_id)) {
				foreach ($select_product_tag_id as $tag_id) {
					wp_mail('yousuf@opcodespace.com', 'subject', print_r([
						'tag_id' => $tag_id,
						'action' => 'select',
						'product_id' => $product_id,
						'variation_id' => $variation_id
					], true));
					$WutTagRules->updateOrInsert([
						'tag_id' => $tag_id,
						'action' => 'select',
						'product_id' => $product_id,
						'variation_id' => $variation_id
					], [
	        			// 'product_id' => $product_id, 
						'variation_id' => $variation_id
					]);
				}
			} else {
				$WutTagRules->updateOrInsert([
					'tag_id' => $tag_id,
					'product_id' => $product_id,
					'variation_id' => $variation_id
				], [
					'product_id' => $product_id,
					'variation_id' => $variation_id
				]);
			}
	        // update_post_meta( $variation_id, '_select_product_tag_id', $select_product_tag_id );
		}
	}

	public function addToVariationsMetabox($loop, $variation_data, $variation)
	{
		ob_start();
		include WUT_VIEW_PATH . "/metabox/content_product_variation.php";
		$content = ob_get_contents();
		ob_end_clean();

		echo $content;
	}

	/******************************************************************************
	 *
	 *                           PRODUCT META BOX 
	 *
	 *******************************************************************************/

	public function customMetaBox()
	{
		//add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args ); 
		add_meta_box(
			'display_product_details_metaBox',
			'Product Details',
			array($this, 'displayProductDetailsMetaBox'),
			'product',
			'normal',
			'low'
		);
	}

	public function displayProductDetailsMetaBox()
	{
		ob_start();
		include WUT_VIEW_PATH . "/metabox/content_product_details.php";
		$content = ob_get_contents();
		ob_end_clean();

		echo $content;
	}

	/******************************************************************************
	 *
	 *                           WOO USER TAG ADMIN MENU
	 *
	 *******************************************************************************/

	public function wutMenu()
	{
		add_menu_page('Woo User Tag', 'Woo User Tag', 'edit_posts', 'woo_user_tag', array($this, 'displayMainPage'), 'dashicons-admin-network');
		add_submenu_page('woo_user_tag', 'Woo User Tag Categories', 'Categories', 'edit_posts', 'woo_user_tag_categories', array($this, 'displayCategoryPage'));
		add_submenu_page('woo_user_tag', 'Woo User Tags', 'Tags', 'edit_posts', 'woo_user_tags', array($this, 'tagsPage'));
		add_submenu_page('woo_user_tag', 'Woo Add Campaign', 'Add Campaign', 'edit_posts', 'woo_add_campaign', array($this, 'addCampaign'));
		add_submenu_page('woo_user_tag', 'Woo Campaign', 'Campaign', 'edit_posts', 'woo_campaign', array($this, 'campaign'));
		add_submenu_page('woo_user_tag', 'Woo Email', 'Email', 'edit_posts', 'woo_email', array($this, 'email'));
		add_submenu_page('woo_user_tag', 'Woo Email Log', 'Email Log', 'edit_posts', 'woo_email_log', array($this, 'emailLog'));
		add_submenu_page('woo_user_tag', 'Woo Export Import', 'Export/Import', 'edit_posts', 'woo_export_import', array($this, 'exportImport'));
	}

	public function exportImport()
	{
		ob_start();
		require_once WUT_VIEW_PATH . 'export/content-export-import.php';
		$content = ob_get_contents();
		return $content;
	}

	public function emailLog()
	{
		ob_start();
		require_once WUT_VIEW_PATH . 'menu-pages/content-email-log-page.php';
		$content = ob_get_contents();
		return $content;
	}

	public function displayMainPage()
	{
		ob_start();
		require_once WUT_VIEW_PATH . 'menu-pages/content-main-page.php';
		$content = ob_get_contents();
		return $content;
	}

	public function displayCategoryPage()
	{
		ob_start();
		require_once WUT_VIEW_PATH . 'menu-pages/content-category-page.php';
		$content = ob_get_contents();
		return $content;
	}

	public function tagsPage()
	{
		ob_start();
		require_once WUT_VIEW_PATH . 'menu-pages/content-tags-page.php';
		$content = ob_get_contents();
		return $content;
	}

	public function addCampaign()
	{
		ob_start();
		require_once WUT_VIEW_PATH . 'menu-pages/content-add-campaign-page.php';
		$content = ob_get_contents();
		return $content;
	}

	public function campaign()
	{
		ob_start();
		require_once WUT_VIEW_PATH . 'menu-pages/content-campaign-page.php';
		$content = ob_get_contents();
		return $content;
	}

	public function email()
	{
		ob_start();
		require_once WUT_VIEW_PATH . 'menu-pages/content-email-page.php';
		$content = ob_get_contents();
		return $content;
	}

}
?>