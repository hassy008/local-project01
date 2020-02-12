<?php
if ( ! defined( 'ABSPATH' ) ) exit;
	$WutTag         = new admin\modules\WutTag();
	$WutCategory    = new admin\modules\WutCategory();
	$WutTagRules    = new admin\modules\WutTagRules();
	$wut_product_id = get_the_ID();
	$tags           = $WutTag->getAll('id'); 
?> 
<div class="bootstrap-wrapper">
    <input type="hidden" name="wut_product_id" value="<?= $wut_product_id ?>" >
	<div class="container">
		<?php if (!empty($tags)) : ?>
		<div class="form-group">
		    <div class="col-sm-10">
			    <label for="">Select Tags:</label>
				<select class="product-tag-select-multiple form-control" name="select_product_tag_ids[]" multiple="multiple">
					<option value="" disabled> -- SELECT -- </option>
					<?php foreach ($tags as $tag) : 
						$categoryName = $WutCategory->getNameById($tag->category_id);
						$isSelected   = $WutTagRules->getRow(['product_id' => $wut_product_id, 'tag_id' => $tag->id, 'action' => 'select' ]) ? ' selected' : '';
						?>
						<option value="<?= $tag->id ?>" <?= $isSelected ?> ><?php echo "{$categoryName} -> {$tag->name}"  ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>  
		<?php else : ?>
		no tags
		<?php endif; ?>
		<?php if (!empty($tags)) :  ?>
		<div class="form-group">
		    <div class="col-sm-10">
			    <label for="">Deselect Tags:</label>
				<select class="product-tag-deselect-multiple form-control" name="deselect_product_tag_ids[]" multiple="multiple">
					<option value="" disabled> -- SELECT -- </option>
					<?php foreach ($tags as $tag) :  
						$categoryName = $WutCategory->getNameById($tag->category_id);
						$isSelected   = $WutTagRules->getRow(['product_id' => $wut_product_id, 'tag_id' => $tag->id, 'action' => 'deselect']) ? ' selected' : '';
						?>
						<option value="<?= $tag->id ?>" <?= $isSelected ?> ><?php echo "{$categoryName} -> {$tag->name}"  ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<?php else: ?>
		no tags
		<?php endif; ?>
		<br>
		<br>
		<div class="form-group"> 
		    <div class="col-sm-10">
		        <button type="submit" class="btn btn-default form-control">Submit</button>
		    </div>
		</div>		
	</div>
</div>
