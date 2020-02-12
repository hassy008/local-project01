<?php
if ( ! defined( 'ABSPATH' ) ) exit;
    $WutTag         = new admin\modules\WutTag();
    $WutCategory    = new admin\modules\WutCategory();
    $WutTagRules    = new admin\modules\WutTagRules();
    $tags           = $WutTag->getAll('id');
    $wut_product_id = get_the_ID();   
?>
<div class="variable_custom_field">
    <input type="hidden" name="wut_product_id" value="<?= $wut_product_id ?>" >
    <input type="hidden" name="wut_variation_id" value="<?= $variation->ID ?>" >
    <p class="form-row form-row-full">
        <?php if (!empty($tags)) :  ?>
        <div class="form-group">
            <div class="col-sm-10">
                <label for="">Select Tags:</label>
                <select class="variable-product-tag-select-multiple form-control" name="select_product_tag_id[<?php echo $loop; ?>]" multiple="multiple">
                    <option value="" disabled> -- SELECT -- </option>
                    <?php foreach ($tags as $tag) : 
                        $categoryName = $WutCategory->getNameById($tag->category_id);
                        $isSelected = $WutTagRules->getRow(['variation_id' => $variation->ID, 'product_id' => $wut_product_id, 'tag_id' => $tag->id, 'action' => 'select']) ? ' selected' : '';
						
                        ?>
                        <option value="<?= $tag->id ?>"  <?= $isSelected ?> ><?php echo "{$categoryName} -> {$tag->name}"  ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>  
		<?php else : ?>
		no tags
        <?php endif; ?>

    </p>
    <p class="form-row form-row-full">
        <?php if (!empty($tags)) : ?>
        <div class="form-group">
            <div class="col-sm-10">
                <label for="">Deselect Tags:</label>
                <select class="variable-product-tag-deselect-multiple form-control" name="deselect_product_tag_id[<?php echo $loop; ?>]" multiple="multiple">
                    <option value="" disabled> -- SELECT -- </option>
                    <?php foreach ($tags as $tag) :
                        $categoryName = $WutCategory->getNameById($tag->category_id);
                        $isSelected = $WutTagRules->getRow(['variation_id' => $variation->ID, 'product_id' => $wut_product_id, 'tag_id' => $tag->id, 'action' => 'deselect']) ? ' selected' : '';
						
                        ?>
                        <option value="<?= $tag->id ?>" <?= $isSelected ?> ><?php echo "{$categoryName} -> {$tag->name}"  ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
		<?php else : ?>
		no tags
        <?php endif; ?>

    </p>
</div>
<script>
    $('.variable-product-tag-deselect-multiple, .variable-product-tag-select-multiple').select2();
</script>