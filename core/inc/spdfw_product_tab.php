<div id='woodesigner_product_options' class='panel woocommerce_options_panel woo_designer_product_options'>


<h3><a href="#" class="designer_open_main_option" data-action="variations"><?php echo __('Variations / Colors', 'woo-shirt-product-designer'); ?><span class="toggle-indicator" aria-hidden="true"></span></a></h3>

<div class="designer_main_variations designer_hide">

<div class="designer_variations">
 <?php

$saved_designer_variation_front = get_post_meta( get_the_ID(), 'designer_variation_front' )[0]; 
$saved_designer_variation_back = get_post_meta( get_the_ID(), 'designer_variation_back' )[0]; 
$saved_designer_variation_color = get_post_meta( get_the_ID(), 'designer_variation_color' )[0]; 
$saved_designer_variation_prices = get_post_meta( get_the_ID(), 'designer_variation_prices' )[0]; 

if (isset($saved_designer_variation_front)) {
	foreach ($saved_designer_variation_front as $designer_variation_front_image_key => $designer_variation_front_image_value) {
		if (!empty($saved_designer_addon_prices[$designer_variation_front_image_key])) {
			$price = $saved_designer_addon_prices[$designer_variation_front_image_key];
		} else {
			$price = 0;
		}
		$designer_variation_back_image_value = $saved_designer_variation_back[$designer_variation_front_image_key];
		
		echo '<div class="row_designer_variation item_'.$designer_variation_front_image_key.'">';
		echo '<p class="form-field variation_entry_header"><label><b>#'.$designer_variation_front_image_key.'</b></label><a href="#" class="designer_delete_variation" data-productid="'.get_the_ID().'" data-key="'.$designer_variation_front_image_key.'"><span class="dashicons dashicons-trash"></span></a></p>';
		echo '<p class="form-field"><label>'.__('Color', 'woo-shirt-product-designer').'</label><input type="text" value="'.$saved_designer_variation_color[$designer_variation_front_image_key].'" name="designer_variation_color['.$designer_variation_front_image_key.']" class="jscolor" required/></p>';
		echo '<p class="form-field designer_variation_col"><label>'.__('Picture Back', 'woo-shirt-product-designer').'</label><img class="designer_variation_img designer_variation_front_img_'.$designer_variation_front_image_key.'" src="'.$designer_variation_front_image_value.'"/><input type="hidden" value="'.$designer_variation_front_image_value.'" class="designer_variation_front_'.$designer_variation_front_image_key.'" name="designer_variation_front['.$designer_variation_front_image_key.']"  required/><span class="designer_add_variation_front_img dashicons dashicons-edit" data-numitems="'.$designer_variation_front_image_key.'"></span></p>';
		echo '<p class="form-field"><label>'.__('Picture Back', 'woo-shirt-product-designer').'</label><img class="designer_variation_img designer_variation_back_img_'.$designer_variation_front_image_key.'" src="'.$designer_variation_back_image_value.'"/><input type="hidden" value="'.$designer_variation_back_image_value.'" class="designer_variation_back_'.$designer_variation_front_image_key.'" name="designer_variation_back['.$designer_variation_front_image_key.']"  required/><span class="designer_add_variation_back_img dashicons dashicons-edit" data-numitems="'.$designer_variation_front_image_key.'"></span></p>';	
		echo '<p class="form-field"><label>'.__('Price', 'woo-shirt-product-designer').'</label><input type="number" value="'.$saved_designer_variation_prices[$designer_variation_front_image_key].'" placeholder="Preis der Option" name="designer_variation_prices['.$designer_variation_front_image_key.']" class="designer_item_price"></p>';
		echo '</div>';
	}
}
?>
</div>
<a href="#" class="button-primary designer_add_variation"><?php echo __('Add Variation / Color', 'woo-shirt-product-designer'); ?></a>

</div>




<h3><a href="#" class="designer_open_main_option" data-action="addons"><?php echo __('Predefined graphics', 'woo-shirt-product-designer'); ?><span class="toggle-indicator" aria-hidden="true"></span></a></h3>


<div class="designer_main_addons designer_hide">

<?php
if (get_post_meta(get_the_ID(), 'designer_allow_graphics', true ) == "1") {
	$designer_allow_graphics_checkbox_val = "checked";
} else {
	$designer_allow_graphics_checkbox_val = "";
}

?>
 <p class="form-field">
 <label><?php echo __('Allow graphics', 'woo-shirt-product-designer' ); ?></label>
 <input type="checkbox" name="designer_allow_graphics" id="designer_allow_graphics" value="<?php echo get_post_meta(get_the_ID(), 'designer_allow_graphics', true ); ?>" <?php echo $designer_allow_graphics_checkbox_val; ?>/>
 </p>

<span class="designer_allow_graphics_content designer_opened_option" style="display:<?php if ($designer_allow_graphics_checkbox_val == "checked") { echo "block"; } else { echo "none"; } ?>">
<p class="form-field">
	<label><b><?php echo __('Predefined graphics', 'woo-shirt-product-designer'); ?></b></label>
</p>


<div class='options_group designer_images' id='aaaa'>
 <?php
//echo "ID=".get_the_ID();
$saved_designer_addon_images = get_post_meta( get_the_ID(), 'designer_addon_images' )[0]; 
$saved_designer_addon_prices = get_post_meta( get_the_ID(), 'designer_addon_prices' )[0]; 

//print_r($saved_designer_addon_images);
if ($saved_designer_addon_images) {
	foreach ($saved_designer_addon_images as $designer_addon_image_key => $designer_addon_image_value) {
		if (!empty($saved_designer_addon_prices[$designer_addon_image_key])) {
			$price = $saved_designer_addon_prices[$designer_addon_image_key];
		} else {
			$price = 0;
		}
		//echo $price;
		echo '<div class="row_designer_images item_'.$designer_addon_image_key.'">';
		echo '<p class="designer_images_header"><b>#'.$designer_addon_image_key.'</b><span class="dashicons dashicons-trash designer_delete_addon" data-key="'.$designer_addon_image_key.'" data-productid="'.get_the_ID().'"></span></p>';
		echo '<p class="designer_image_col"><label style="display:none;">Bild</label><img class="designer_addons_img designer_addon_img_'.$designer_addon_image_key.'" src="'.$designer_addon_image_value.'"/><input type="hidden" value="'.$designer_addon_image_value.'" class="designer_img_'.$designer_addon_image_key.'" name="designer_addon_images['.$designer_addon_image_key.']"  /><span class="upload_image_button dashicons dashicons-edit" data-numitems="'.$designer_addon_image_key.'"></span></p>';
		echo '<p class=""><label style="display:none;">Preis</label><input type="number" value="'.$price.'" placeholder="Preis der Option" name="designer_addon_prices['.$designer_addon_image_key.']" class="designer_item_price"></p>';
		echo '</div>';
	}
 //print_r($saved_designer_addon_images);
}
?>

</div>

<a href="#" class="button-primary designer_add_option_image"><?php echo __('Add graphic', 'woo-shirt-product-designer'); ?></a>


</span>

</div>


<h3><a href="#" class="designer_open_main_option" data-action="textinput"><?php echo __('Text input', 'woo-shirt-product-designer'); ?> <span class="toggle-indicator" aria-hidden="true"></span></a></h3>

<div class="designer_main_textinput designer_hide">

	<?php
	if (get_post_meta(get_the_ID(), 'designer_allow_text', true ) == "1") {
		$designer_allow_text_checkbox_val = "checked";
	} else {
		$designer_allow_text_checkbox_val = "";
	}

	?>

	 <p class="form-field">
	 <label><?php echo __('Allow text', 'woo-shirt-product-designer' ); ?></label>
	 <input type="checkbox" name="designer_allow_text" id="designer_allow_text" value="<?php echo get_post_meta(get_the_ID(), 'designer_allow_text', true ); ?>" <?php echo $designer_allow_text_checkbox_val; ?>/>
	 </p>


	<span class="designer_allow_text_content designer_opened_option" style="display:<?php if (isset($designer_allow_text_checkbox_val)) { if ($designer_allow_text_checkbox_val == "checked") { echo "block"; } else { echo "none"; } } else { echo "none";} ?>">
	 <?php
					
		woocommerce_wp_text_input(
		array(
		  'id' => 'designer_text_price',
		  'label' => __( 'Surcharge for text', 'woo-shirt-product-designer' ),
		  'placeholder' => '',
		  'desc_tip' => 'true',
		  'type' => 'number', 
		  'description' => __( 'Surcharge for the text', 'woo-shirt-product-designer' ),
		  'type' => 'text'
		)
		);
	 ?>	
	</span>

</div>


<h3><a href="#" class="designer_open_main_option" data-action="customuploades"><?php echo __('Graphic Upload', 'woo-shirt-product-designer'); ?><span class="toggle-indicator" aria-hidden="true"></span></a></h3>

<div class="designer_main_customuploades designer_hide">
	
	<?php
	if (get_post_meta(get_the_ID(), 'designer_allow_custom_upload', true ) == "1") {
		$designer_allow_custom_upload = "checked";
	} else {
		$designer_allow_custom_upload = "";
	}

	?>

	 <p class="form-field">
	 <label><?php echo __('Allow graphic upload', 'woo-shirt-product-designer' ); ?></label>
	 <input type="checkbox" name="designer_allow_custom_upload" id="designer_allow_custom_upload" value="<?php echo get_post_meta(get_the_ID(), 'designer_allow_custom_upload', true ); ?>" <?php echo $designer_allow_custom_upload; ?>/>
	 </p>	
	<!-- <p class="form-field">
	 <label>Bild Vorderseite</label>
	 <input class="main_image_input" type="text" name="main_image" value="<?php //echo $designer_main_image; ?>"  /><input  class="main_image" type="button" class="button-primary" value="Insert Image" />
	</p>
	<p class="form-field">
	<label>Bild Rückseite</label>
	 <input class="mainbackground_image_input" type="text" name="mainbackground_image" value="<?php //echo $designer_mainbackground_image; ?>" /><input  class="mainbackground_image" type="button" class="button-primary" value="Insert Image" />
	</p>
-->

</div>

<p>
<b><?php echo __('IMPORTANT', 'woo-shirt-product-designer'); ?>:&nbsp;</b>
<?php echo __('Be sure to set a regular price under "General" otherwise the product cannot be purchased.', 'woo-shirt-product-designer'); ?><br />
<?php echo __('Set the price to 0 or take the price of the cheapest variation.', 'woo-shirt-product-designer'); ?>
</p>

<p>
<b><?php echo __('Are the features not enough for you', 'woo-shirt-product-designer'); ?>?&nbsp;</b>
<?php echo __('No problem - in the PRO VERSION you have much more possibilities to customize the designer/configurator.', 'woo-shirt-product-designer'); ?><br />
<?php echo __('All details about the PRO VERSION can be found on the <a href="https://wordpress.org/plugins/woo-shirt-product-designer/" target="_blank">WordPress Plugin Page</a>.', 'woo-shirt-product-designer'); ?>
</p>
 </div>
 