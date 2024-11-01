jQuery( document ).ready(function($) {
	
var mediaUploader;	
var plugindir = woodesignerparms.plugindir;	
var $ajaxurl = woodesignerparms.ajaxurl;	
var $deletetext = woodesignerparms.deletetext;
var $errortext = woodesignerparms.errortext;
var $chooseimage = woodesignerparms.chooseimage;
var $pricetext = woodesignerparms.pricetext;
var $imagetext = woodesignerparms.imagetext;
var $colortext = woodesignerparms.colortext;
var $imagefronttext = woodesignerparms.imagefronttext;
var $imagebacktext = woodesignerparms.imagebacktext;
var $insertimage = woodesignerparms.insertimage;


//****OPEN MAIN OPTIONS****//
$('.designer_open_main_option').click(function(e){
	e.preventDefault();
	var action = jQuery(this).data("action");	
	if (action) {
		if ($('.designer_main_'+action).is(":visible")) {
			$('.designer_main_'+action).slideUp();
			$('.designer_open_main_option[data-action="'+action+'"] .toggle-indicator').removeClass('tgl');

		} else {
			$('.designer_main_'+action).slideDown();	
			$('.designer_open_main_option[data-action="'+action+'"] .toggle-indicator').addClass('tgl');
		}
	}
});

$('.designer_delete_variation').click(function(e){
	e.preventDefault();
	var key = jQuery(this).data("key");
	var productid = jQuery(this).data("productid");
	
	if (confirm($deletetext)) {

		jQuery.post($ajaxurl, { key: key, productid: productid, action: "spdfw_delete_variation"}, function(data) {
			if (data == 1) {
				jQuery('.row_designer_variation.item_'+key).remove();
			} else {
				alert($errortext);
			}
		});	
	
	} else {
		console.log('...');
	}
});


$('.designer_delete_addon').click(function(e){
	e.preventDefault();
	var key = jQuery(this).data("key");
	var productid = jQuery(this).data("productid");
	
	if (confirm($deletetext)) {

		jQuery.post($ajaxurl, { key: key, productid: productid, action: "spdfw_delete_addon"}, function(data) {

			if (data == 1) {
				
				jQuery('.row_designer_images.item_'+key).remove();
				
			} else {
				
				alert($errortext);
				
			}
		});	
	
	} else {
		console.log('...');
	}
});


$('#designer_allow_text').change(function() {

  var value = $(this).val();

  if ((value == "1")) {

	$('.designer_allow_text_content').hide(300);
	
	$("#designer_text_price").prop('required',false);

  } else {
	  
	$('.designer_allow_text_content').show(300);
	
	$("#designer_text_price").prop('required',true);
	
  }

});

$('#designer_allow_graphics').change(function() {

  var value = $(this).val();

  if ((value == "1")) {

	$('.designer_allow_graphics_content').hide(300);
	
  } else {
	  
	$('.designer_allow_graphics_content').show(300);
	
  }

});



$('.woo_designer_product_options input[type="checkbox"]').change(function(){
    this.value = (Number(this.checked));
});


//***********Designer Variations - Uploader***************//

$('.designer_images').on('click', '.mainbackground_image', function(e) {


	if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: $chooseimage,
      button: {
      text: $chooseimage
    }, multiple: false });
    mediaUploader.on('select', function() {
		
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      $('.mainbackground_image_input').val(attachment.url);


    });
    mediaUploader.open();  
});	
	
$('.designer_images').on('click', '.main_image', function(e) {


	if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: $chooseimage,
      button: {
      text: $chooseimage
    }, multiple: false });
    mediaUploader.on('select', function() {
		
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      $('.main_image_input').val(attachment.url);


    });
    mediaUploader.open();  
});	

$('.designer_images').on('click', '.upload_image_button', function(e) {
	var num = $(this).data("numitems");
	
	$('.designer_img_'+num).addClass('designerimgactive');
	$('.designer_addon_img_'+num).addClass('designerimgactive_img');
	
	if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: $chooseimage,
      button: {
      text: $chooseimage
    }, multiple: false });
    mediaUploader.on('select', function() {
		
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      $('.designerimgactive').val(attachment.url);
	   $('.designerimgactive_img').attr("src",attachment.url);
	  $(".designer_images input").removeClass("designerimgactive");
	  $('.designer_variation_front_img_'+num).removeClass('designerimgactive_img');

    });
    mediaUploader.open();  
});	

$('.designer_variations').on('click', '.designer_add_variation_front_img', function(e) {
	var num = $(this).data("numitems");
	console.log('NUM='+num);
	$('.designer_variation_front_'+num).addClass('designerimgactive');
	$('.designer_variation_front_img_'+num).addClass('designerimgactive_img');
	
	if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: $chooseimage,
      button: {
      text: $chooseimage
    }, multiple: false });
    mediaUploader.on('select', function() {
		
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      $('.designerimgactive').val(attachment.url);
	  $('.designerimgactive_img').attr("src",attachment.url);

	  $(".designer_variations input").removeClass("designerimgactive");
	$(".designer_variations img").removeClass("designerimgactive_img");
    });
    mediaUploader.open();  
});	

$('.designer_variations').on('click', '.designer_add_variation_back_img', function(e) {
	var num = $(this).data("numitems");
	
	$('.designer_variation_back_'+num).addClass('designerimgactive');
	$('.designer_variation_back_img_'+num).addClass('designerimgactive_img');

	if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: $chooseimage,
      button: {
      text: $chooseimage
    }, multiple: false });
    mediaUploader.on('select', function() {
		
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      $('.designerimgactive').val(attachment.url);
	  $('.designerimgactive_img').attr("src",attachment.url);
	  $(".designer_variations input").removeClass("designerimgactive");
	  $(".designer_variations img").removeClass("designerimgactive_img");
	  

    });
    mediaUploader.open();  
});





	$('.designer_add_variation').click(function(e) {
		var numItems = jQuery('.designer_variations .designer_variation_col').length+1;
		e.preventDefault();
		

		$(".designer_variations").append('<p class="form-field"><label>'+$colortext+'</label><input type="text" name="designer_variation_color['+numItems+']" class="jscolor" required/></p>');
		$(".designer_variations").append('<p class="form-field designer_variation_col"><label>'+$imagefronttext+'</label><img class="designer_variation_img designer_variation_front_img_'+numItems+'" src="'+plugindir+'/core/img/placeholder.png"/><input type="hidden" class="designer_variation_front_'+numItems+'" name="designer_variation_front['+numItems+']"  required/><input class="designer_add_variation_front_img" data-numitems="'+numItems+'" type="button" class="button-primary" value="'+$insertimage+'" /></p>')
		$(".designer_variations").append('<p class="form-field"><label>'+$imagebacktext+'</label><img class="designer_variation_img designer_variation_back_img_'+numItems+'" src="'+plugindir+'/core/img/placeholder.png"/><input type="hidden" class="designer_variation_back_'+numItems+'" name="designer_variation_back['+numItems+']"  required/><input class="designer_add_variation_back_img" data-numitems="'+numItems+'" type="button" class="button-primary" value="'+$insertimage+'" /></p>')		
		$(".designer_variations").append('<p class="form-field"><label>'+$pricetext+'</label><input type="number" value="0" placeholder="Preis der Option" name="designer_variation_prices['+numItems+']" class="designer_item_price"></p>')
		
	
		  jscolor.installByClassName("jscolor");


	});
	
	$('.designer_add_option_image').click(function(e) {
		var numItems = jQuery('.designer_images .designer_image_col').length+1;
		e.preventDefault();
		$(".designer_images").append('<p class="form-field designer_image_col"><label>'+$imagetext+'</label><img class="designer_addons_img designer_addon_img_'+numItems+'" src="'+plugindir+'/core/img/placeholder.png"/><input type="hidden" class="designer_img_'+numItems+'" name="designer_addon_images['+numItems+']"  required/><input class="upload_image_button" data-numitems="'+numItems+'" type="button" class="button-primary" value="'+$insertimage+'" /></p>')
		$(".designer_images").append('<p class="form-field"><label>'+$pricetext+'</label><input type="number" value="0" placeholder="'+$pricetext+'" name="designer_addon_prices['+numItems+']" class="designer_item_price"></p>')
	
	});
	
  var mediaUploader;
  $('#upload_image_button').click(function(e) {
    e.preventDefault();
      if (mediaUploader) {
      mediaUploader.open();
      return;
    }
    mediaUploader = wp.media.frames.file_frame = wp.media({
      title: $chooseimage,
      button: {
      text: $chooseimage
    }, multiple: false });
    mediaUploader.on('select', function() {
      var attachment = mediaUploader.state().get('selection').first().toJSON();
      $('#background_image').val(attachment.url);
    });
    mediaUploader.open();
  });
  
  


});