var $type="tee",$color="black",$y_pos="front",$nos_icons=0,$nos_text=0,$custom_img=0, $mainimg="", $cal1= "", $cal2 = "", $submit = ""; $mainbackimg="", $ground_price = "", $ajaxurl=woodesignerparms.ajaxurl,$current_varation_back_img = "", $current_varation_front_img = "";




jQuery(document).ready(function($){
	
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				image_icon(e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}


	$("#imgInp").change(function() {
		readURL(this);
	});
	
	$mainimg = jQuery('.designer_change_main').data("mainimg");
	$mainbackimg = jQuery('.designer_change_main').data("mainbackimg");	
	
	//ONLOAD
	jQuery("#preview_front").css('background-image', 'url('+$mainimg+') ') ;
	jQuery("#preview_back").css('background-image', 'url('+$mainbackimg+') ') ;
	//jQuery("#preview_front, #preview_back , #preview_left, #preview_right").css('background-color', 'blue') ;
	jQuery("#preview_front,.color_pick").removeClass('dis_none');
	jQuery("#preview_back,.default_samples,.custom_icon,.custom_font,.T_type").addClass('dis_none') ;
	//jQuery('.modal').css('dispaly','none');

	//ONLOAD OVER
	
	/*==========================SWITCH MENU===========================*/
	jQuery(".sel_type").click(function(){
		if(jQuery(".T_type").hasClass("dis_none") == true) {
			jQuery(".T_type").removeClass('dis_none');
			jQuery(".color_pick,.default_samples,.custom_icon,.custom_font").addClass('dis_none') ;
		} else {
			jQuery(".T_type").addClass('dis_none');
			jQuery(".color_pick,.default_samples,.custom_icon,.custom_font").addClass('dis_none') ;			
		}
	});
	jQuery(".sel_color").click(function(){
		if(jQuery(".color_pick").hasClass("dis_none") == true) {
			jQuery(".color_pick").removeClass('dis_none');
			jQuery(".T_type,.default_samples,.custom_icon,.custom_font").addClass('dis_none') ;
		} else {
			jQuery(".color_pick").addClass('dis_none');
			jQuery(".T_type,.default_samples,.custom_icon,.custom_font").addClass('dis_none') ;			
		}
	});
	jQuery(".sel_art").click(function(){
		if(jQuery(".default_samples").hasClass("dis_none") == true) {
			jQuery(".default_samples").removeClass('dis_none');
			jQuery(".T_type,.color_pick,.custom_icon,.custom_font").addClass('dis_none') ;			
		} else {
			jQuery(".default_samples").addClass('dis_none');
			jQuery(".T_type,.color_pick,.custom_icon,.custom_font").addClass('dis_none') ;			
		}

	});
	jQuery(".sel_custom_icon").click(function(){
		if(jQuery(".custom_icon").hasClass("dis_none") == true) {
			jQuery(".custom_icon").removeClass('dis_none');
			jQuery(".T_type,.color_pick,.default_samples,.custom_font").addClass('dis_none') ;
		} else {
			jQuery(".custom_icon").addClass('dis_none');
			jQuery(".T_type,.color_pick,.default_samples,.custom_font").addClass('dis_none') ;			
		}
	});
	jQuery(".sel_text").click(function(){
		if(jQuery(".custom_font").hasClass("dis_none") == true) {
			jQuery(".custom_font").removeClass('dis_none');
			jQuery(".T_type,.color_pick,.default_samples,.custom_icon").addClass('dis_none') ;
		} else {
			jQuery(".custom_font").addClass('dis_none');
			jQuery(".T_type,.color_pick,.default_samples,.custom_icon").addClass('dis_none') ;			
		}
	});
	
	
	/*=========================SWITCH MENU OVER=====================*/
	/*==========================select type=====================*/
	jQuery("#radio1").click(function(){	//tee
		$type="tee";
		change_it();
		
	});
	jQuery("#radio2").click(function(){	//polo
		$type="polo";
		change_it();
		
	});
	jQuery("#radio3").click(function(){	//hoodie
		$type="hoodie";
		change_it();
	});
	jQuery(".designer_change_main").click(function(){	//hoodie
	$mainimg = jQuery(this).data("mainimg");
	$mainbackimg = jQuery(this).data("mainbackimg");
		$type="hoodie";
		change_it();
	});	
	
	/*==========================select type over=====================*/
	/*==========================select back or front=====================*/
	jQuery("#o_front").click(function(){
		$y_pos="front";
				jQuery("#preview_front").css('background-image', 'url('+$current_varation_front_img+') ') ;
				jQuery("#o_front").attr('src',$current_varation_front_img);
				jQuery("#o_back").attr('src',$current_varation_back_img);
				jQuery("#preview_front").removeClass('dis_none') ;
				jQuery("#preview_back").addClass('dis_none') ;
		
	});
	jQuery("#o_back").click(function(){
		$y_pos="back";
				jQuery("#preview_back").css('background-image', 'url('+$current_varation_back_img+') ') ;
				jQuery("#o_front").attr('src',$current_varation_front_img);
				jQuery("#o_back").attr('src',$current_varation_back_img);
				jQuery("#preview_back").removeClass('dis_none') ;
				jQuery("#preview_front").addClass('dis_none') ;
				console.log($current_varation_front_img);
		
	});
/*==========================select back or front OVER=====================*/
/*==========================select COLOR=====================*/
	jQuery('.woo_designer_save_config').click(function(e){
		
		e.preventDefault();
		
		var product_id = jQuery('.woo_designer_add_to_cart').data("productid");
		
		var key = jQuery('.woo_designer_add_to_cart').data("key");
		
		capture_2(key, product_id);
		
		jQuery('.woo_designer_save_config').hide();
		
		
	});



	jQuery('.designer_change_variation').click(function(){
				//$color="red";
				
				jQuery('.designer_btn_loader').show();
				jQuery('.woo_designer_save_config').addClass('loader');
				jQuery('.woo_designer_save_config').prop('disabled', true);
				
				var color = jQuery(this).data("color");
				var frontimg = jQuery(this).data("frontimg");
				var backimg = jQuery(this).data("backimg");
				var price = jQuery(this).data("price");
				var productid = jQuery(this).data("productid");
				
				$current_varation_front_img = frontimg;
				$current_varation_back_img = backimg;
				$ground_price = price;
				
				//set woocommerce values
				jQuery('input[name="woo_designer_front_img_url"]').val($current_varation_front_img);
				jQuery('input[name="woo_designer_back_img_url"]').val($current_varation_back_img);	
				jQuery('input[name="woo_designer_variation"]').val(color);
				
				//console.log($ground_price);
				//designer_calculator_total_value
				jQuery.post($ajaxurl, { productid: productid, ground_price: $ground_price, action: "spdfw_update_groundprice"}, function(data) {
					
					var datajson = jQuery.parseJSON(data);
					//alert(datajson.total);	
					jQuery('.designer_calculator_groundprice_value').html(datajson.groundprice);
					
					jQuery('.designer_calculator_total_value').html(datajson.total);
					//console.log(datajson);
					jQuery.post($ajaxurl, { productid: productid, action: "spdfw_get_total"}, function(data) {
						jQuery('.designer_calculator_total_value').html(data);
						jQuery('.woo_designer_save_config').prop('disabled', false);
						jQuery('.woo_designer_save_config').removeClass('loader');
						jQuery('.designer_btn_loader').hide();
						//console.log(data);
					});					
				});					
					
				//alert('ok'+color);
				change_variation(frontimg, backimg);
				
				
				
				
	});
	
	//Onload select first variation
	jQuery(document).ready(function($){	
		jQuery('.color_pick .designer_change_variation:first').click();
	});
	
	
	jQuery('#red').click(function(){
				$color="red";
				change_it();
	});
	jQuery('#black').click(function(){
				$color="black";
				change_it();
	});
	jQuery('#white').click(function(){
				$color="white";
				change_it();
	});
	jQuery('#green').click(function(){
				$color="green";
				change_it();
	});
	jQuery('#navy').click(function(){
				$color="navy";
				change_it();
	});
	
	function change_variation(frontimg, backimg){
				jQuery("#preview_back").css('background-image', 'url('+backimg+') ') ;
				jQuery("#preview_front").css('background-image', 'url('+frontimg+') ') ;
				jQuery("#o_front").attr('src',frontimg);
				jQuery("#o_back").attr('src',backimg);
		
	}	
	
	function change_it(){
				jQuery("#preview_back").css('background-image', 'url('+$mainbackimg+') ') ;
				jQuery("#preview_front").css('background-image', 'url('+$mainimg+') ') ;
				jQuery("#o_front").attr('src',$mainimg);
				jQuery("#o_back").attr('src',$mainbackimg);
		
	}
	/*==========================select COLOR OVER=====================*/
/*=====================SAMPLE ICONS========================*/
	jQuery(".sample_icons").click(function(){
		
		jQuery('.designer_btn_loader').show();
		jQuery('.woo_designer_save_config').addClass('loader');
		jQuery('.woo_designer_save_config').prop('disabled', true);
				
		var price = $(this).data("price");
		var id = $(this).data("id");
		var productid = $(this).data("productid");
		var uniqe =  Math.random().toString(36).substring(7);
		//$(this).data("id");
		var $srcimg=$(this).children("img").attr('src');
		
		image_icon($srcimg, productid, id, uniqe);
		
		jQuery.post($ajaxurl, { productid: productid, id: id, price: price, uniqe: uniqe, action: "spdfw_update_session"}, function(data) {
			jQuery('#result').text(data);
			console.log(data);
			jQuery.post($ajaxurl, { productid: productid, action: "spdfw_get_addon_total"}, function(data1) {
				
				//console.log(data);
			
			
				jQuery.post($ajaxurl, { productid: productid, action: "spdfw_get_total"}, function(data) {
					
					//var datajson = jQuery.parseJSON(data);
					//alert(datajson.total);	
					//jQuery('.designer_calculator_groundprice_value').html(datajson.groundprice);
					
					jQuery('.designer_calculator_total_value').html(data);
					
					jQuery('#designer_calculator').html(data1);
					
					jQuery('.designer_btn_loader').hide();
					jQuery('.woo_designer_save_config').removeClass('loader');
					jQuery('.woo_designer_save_config').prop('disabled', false);					
					//console.log(datajson);
				});				

			});		
			
		});
			
	});

	jQuery(".folder_toggle").click(function(){
		$i=$(this).attr('value');
		$folder=$(this).attr('data-folder');
		$.ajax({
			      url: 'http://miniso-at.com/wp-content/plugins/woo-real-booking-system/core/designer/tdesignAPI/control/newcontent.php?folder='+$folder,
			      success: function()
		      	{
		        	jQuery("#toggle_show"+$i ).empty().load("http://miniso-at.com/wp-content/plugins/woo-real-booking-system/core/designer/tdesignAPI/control/newcontent.php?folder="+$folder);
		      	}
	    });
	});
/*=====================SAMPLE ICONS over========================*/

/*
 * Font resiZable
 * 
 * 
 * 
 *
var initDiagonal;
var initFontSize;

$(function() {
    jQuery("#resizable").resizable({
        alsoResize: '#content',
        create: function(event, ui) {
            initDiagonal = getContentDiagonal();
            initFontSize = parseInt(jQuery("#content").css("font-size"));
        },
        resize: function(e, ui) {
            var newDiagonal = getContentDiagonal();
            var ratio = newDiagonal / initDiagonal;
            
            jQuery("#content").css("font-size", initFontSize + ratio * 3);
        }
    });
});

function getContentDiagonal() {
    var contentWidth = jQuery("#content").width();
    var contentHeight = jQuery("#content").height();
    return contentWidth * contentWidth + contentHeight * contentHeight;
}
/*
 * 
 * 
 * 
 */

	jQuery('#apply_text').click(function(){
		
		jQuery('.designer_btn_loader').show();
		jQuery('.woo_designer_save_config').addClass('loader');
		jQuery('.woo_designer_save_config').prop('disabled', true);

					
		var text_val = jQuery("textarea#custom_text").val();
		var price = jQuery(this).data("id");
		var product_id = jQuery(this).data("productid");
		var session_id = jQuery(this).data("key");
		if(!text_val)
			return false;
		
			jQuery("."+$y_pos+"_print").append("<div id=text"+($nos_text)+" class='new_text'  onmouseover='show_delete_btn(this);' onmouseout='hide_delete_btn(this);'><span class='drag_text property_icon'  ></span><div id='text_style' >"+text_val+"</div><span class='delete_text property_icon' data-productid='"+product_id+"' data-id='"+price+"' data-key='"+session_id+"' onClick='delete_text(this);' ></span></div>");
			jQuery( "#text"+($nos_text)+"" ).draggable({ containment: "parent" });
			jQuery( "#text"+($nos_text)+"" ).resizable({
				maxHeight: 480,
				maxWidth: 450,
				minHeight: 60,
				minWidth: 60
			});

				jQuery.post($ajaxurl, { act: 'add', product_id: product_id, session_id: session_id, price: price, action: "spdfw_update_text_prices"}, function(data1) {
					//jQuery('.designer_calculator_total_value').html(data);
					//console.log(data1);
					jQuery.post($ajaxurl, { productid: product_id, action: "spdfw_get_total"}, function(data) {
						jQuery('#designer_calculator_texts').show();
						jQuery('.designer_calculator_texts_output').html(data1);
						jQuery('.designer_calculator_total_value').html(data);
						jQuery('.designer_btn_loader').hide();
						jQuery('.woo_designer_save_config').removeClass('loader');
						jQuery('.woo_designer_save_config').prop('disabled', false);						
						//console.log(data);
					});						

				});			

		var $font_			=jQuery('#custom_text').css("font-family");
		var $font_size		=jQuery('#custom_text').css("font-size");
		var $font_weight	=jQuery('#custom_text').css("font-weight");
		var $font_style		=jQuery('#custom_text').css("font-style");
		var $font_color		=jQuery('#custom_text').css("color");
		//alert($font_u);
		
		
		jQuery("#text"+($nos_text)+" div" ).css("font-family", $font_);
		jQuery("#text"+($nos_text)+" div" ).css("font-size", $font_size);
		jQuery("#text"+($nos_text)+" div" ).css("font-weight", $font_weight);
		jQuery("#text"+($nos_text)+" div" ).css("font-style", $font_style);
		jQuery("#text"+($nos_text)+" div" ).css("color", $font_color);
		jQuery("#text"+($nos_text)).css({'top':'100px','left':'150px'});
		//document.getElementById("text"+($nos_text)+" textarea").style.textDecoration=(""+$font_u+"");
		++$nos_text;
	});
jQuery('.preview_images').click(function(){
	var key = jQuery(this).data("key");
	var product_id = jQuery(this).data("productid");
	capture(key, product_id);
	//jQuery('.modal').addClass('in');
	jQuery('.layer').css('visibility','visible');
	//jQuery('.layer').css('visibility','visible');
	//jQuery('body').css('position','fixed');
	//jQuery('.modal').css({'display':'block','height':'auto'});
	//jQuery('.design_api').css('position', 'fixed');
	//jQuery('.modal').css('overflow', 'scroll');
});

jQuery('.woo_designer_add_to_cartss').click(function(event){
	 event.preventDefault();
	//TODO WAIT 
	console.log(this);
	var key = jQuery(this).data("key");
	var product_id = jQuery(this).data("productid");
	
	var state = capture_to_cart(key, product_id);
	
console.log(state);
//jQuery("#woo_designer_add_to_cart_form").submit();
	console.log('CLICK');
	//document.getElementById("woo_designer_add_to_cart_form").submit();

	//jQuery('.modal').addClass('in');
	//jQuery('.layer').css('visibility','visible');
	//jQuery('.layer').css('visibility','visible');
	//jQuery('body').css('position','fixed');
	//jQuery('.modal').css({'display':'block','height':'auto'});
	//jQuery('.design_api').css('position', 'fixed');
	//jQuery('.modal').css('overflow', 'scroll');
});


  jQuery("#woo_designer_add_to_cart_form2").on('submit', function(e){
console.log('CLICK'+$submit);
e.preventDefault();

	 console.log($cal1+'---'+$cal2);
	var key = jQuery('.woo_designer_add_to_cart').data("key");
	var product_id = jQuery('.woo_designer_add_to_cart').data("productid");
	//capture(key, product_id);	



 var a = capture_to_cart(key, product_id);
 console.log(a);
 console.log($cal1+'---'+$cal2);
 
if ($cal1 == 1 && $cal2 == 1){
	jQuery("#woo_designer_add_to_cart_form").submit();
	console.log('SUBMIT');
} else {
	e.preventDefault();
	console.log('preventDefault');
}
  });

jQuery('.close_img').click(function(){

	
	jQuery('.layer').css('visibility','hidden');
	//jQuery('.layer').css('visibility','hidden');
	//jQuery('body').css('position','relative');
	
});

function capture(session_id, product_id) {
		
	jQuery("#preview_back").removeClass('dis_none') ;
	jQuery("#preview_front").removeClass('dis_none') ;
	jQuery("#image_reply").empty();
	$y_pos="front";
	 html2canvas(jQuery('#preview_front'), {
            onrendered: function(canvas) {
                document.getElementById("image_reply").appendChild(canvas);
				//Set hidden field's value to image data (base-64 string)
				jQuery('#img_front').val(canvas.toDataURL("image/png"));
				var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");  // here is the most important part because if you dont replace you will get a DOM 18 exception.


				jQuery.post($ajaxurl, { site: 'front', product_id: product_id, session_id: session_id, imgBase64: image, action: "spdfw_save_image"}, function(data) {
					//jQuery('.designer_calculator_total_value').html(data);
					console.log(data);
				});	

				jQuery('input[name="woo_designer_front_preview"]').val(image);

            }
        });
	//jQuery('#preview_front').hide();
	//jQuery('#preview_back').show();
    html2canvas(jQuery('#preview_back'), {
            onrendered: function(canvas) {
				//jQuery('#img_back').val(canvas.toDataURL("image/png"));
                document.getElementById("image_reply").appendChild(canvas);
				jQuery('#img_back').val(canvas.toDataURL("image/png"));
				var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");  // here is the most important part because if you dont replace you will get a DOM 18 exception.
				
				jQuery("#preview_back").addClass('dis_none') ;
				jQuery.post($ajaxurl, { site: 'back', product_id: product_id, session_id: session_id, imgBase64: image, action: "spdfw_save_image"}, function(data) {
					//jQuery('.designer_calculator_total_value').html(data);
					//console.log(data);
					//console.log( "OK");
				});	

				jQuery('input[name="woo_designer_back_preview"]').val(image);				
            }
        });
		
		
}

function capture_2(session_id, product_id) {
	
	var r1 = "";
	var r2 = "";
	
	var scrollPos;
    scrollPos = jQuery(window).scrollTop();
    
	//console.log(scrollPos);
	
	jQuery(".designer_api_menu").hide();
	jQuery(".designer_api_options").hide();
	jQuery(".designer_overview").hide();
	jQuery("#view_mode").hide();
	//jQuery('#preview_front, #preview_back').css('background-size', '100% 100%');
	
	jQuery("<div class='designer_api_preview_final' style='float: left; width: 70%;'></div>").insertBefore('.design_api_preview_t');
	jQuery(".designer_api_overlay").show();
	jQuery('.design_api_preview_t').css("opacity", "0");
	jQuery("#preview_back").removeClass('dis_none') ;
	jQuery("#preview_front").removeClass('dis_none') ;
	jQuery("#image_reply").empty();
	
	$y_pos="front";
	 html2canvas(jQuery('#preview_front'), {
		  width: 1200,
  height: 1200,
            onrendered: function(canvas) {
				jQuery(".front_print").hide();	
                document.getElementById("image_reply").appendChild(canvas);
				//Set hidden field's value to image data (base-64 string)
				console.log(canvas.toDataURL("image/png"));
				jQuery('#img_front').val(canvas.toDataURL("image/png"));
				var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");  // here is the most important part because if you dont replace you will get a DOM 18 exception.

				//show ending front image
				jQuery('#preview_front').css("background-image", "url("+canvas.toDataURL("image/png")+")");
					//	jQuery('#preview_front').css("height", "200px"); 
						//jQuery('#preview_front').css("background-size", "contain"); 				
				//jQuery('.designer_api_preview_final').append('<div class="designer_api_preview_final_front" style="background-image:url('+canvas.toDataURL("image/png")+')">');			

				jQuery.post($ajaxurl, { site: 'front', product_id: product_id, session_id: session_id, imgBase64: image, action: "spdfw_save_image"}, function(data) {
					//jQuery('.designer_calculator_total_value').html(data);
					console.log(data);
				});	
				window.scrollTo(0,scrollPos);
				jQuery('input[name="woo_designer_front_preview"]').val(image);
				r1 = 1;
				
				//RENDER SECOND IN FIRST 
				html2canvas(jQuery('#preview_back'), {
					onrendered: function(canvas) {
						//jQuery('#img_back').val(canvas.toDataURL("image/png"));
					   document.getElementById("image_reply").appendChild(canvas);
						jQuery('#img_back').val(canvas.toDataURL("image/png"));
						var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");  // here is the most important part because if you dont replace you will get a DOM 18 exception.
						
								jQuery(".front_print").hide();
							jQuery(".back_print").hide();
						
						jQuery('#preview_back').css("background-image", "url("+canvas.toDataURL("image/png")+")"); 
						//jQuery('#preview_back').css("height", "200px"); 
						//jQuery('#preview_back').css("background-size", "contain"); 

						jQuery('.design_api_preview_t').append('<div class="designer_api_preview_final_back" style="background-image:url('+canvas.toDataURL("image/png")+')">');
						
						//jQuery("#preview_back").addClass('dis_none') ;
						jQuery.post($ajaxurl, { site: 'back', product_id: product_id, session_id: session_id, imgBase64: image, action: "spdfw_save_image"}, function(data) {
							//jQuery('.designer_calculator_total_value').html(data);
							//console.log(data);
							//console.log( "OK");
							jQuery('#woo_designer_add_to_cart_form').show();
							jQuery(".designer_overview").show();
							jQuery(".design_api_preview_t").css('width', '100%');
							
							jQuery(".design_api_preview_t #preview_front").css('width', '48%');
							jQuery(".design_api_preview_t #preview_back").css('width', '48%');
							jQuery(".design_api_preview_t #preview_front").css('float', 'left');
							jQuery(".design_api_preview_t #preview_back").css('float', 'left');
							
							jQuery('.design_api_preview_t').css("opacity", "1");
							jQuery(".designer_overview_overlay").hide();
							jQuery(".designer_api_overlay").hide();
							
					
						});	

						jQuery('input[name="woo_designer_back_preview"]').val(image);
						r2 = 1;	
						window.scrollTo(0,scrollPos);
					}
				});				
				
				
				
            }
        });

		
}


function capture_to_cart(session_id, product_id) {
		
	//jQuery("#preview_back").removeClass('dis_none') ;
	//jQuery("#preview_front").removeClass('dis_none') ;
	//jQuery("#image_reply").empty();
	$y_pos="front";
	 html2canvas(jQuery('#preview_front'), {
            onrendered: function(canvas) {
                document.getElementById("image_reply").appendChild(canvas);
				//Set hidden field's value to image data (base-64 string)
				jQuery('#img_front').val(canvas.toDataURL("image/png"));
				var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");  // here is the most important part because if you dont replace you will get a DOM 18 exception.

				jQuery.post($ajaxurl, { site: 'front', product_id: product_id, session_id: session_id, imgBase64: image, action: "spdfw_save_image"}, function(data) {
					//jQuery('.designer_calculator_total_value').html(data);
					console.log(data);
					$cal1 = 1
				});	

				jQuery('input[name="woo_designer_front_preview"]').val(image);

            }
        });
	//jQuery('#preview_front').hide();
	//jQuery('#preview_back').show();
    html2canvas(jQuery('#preview_back'), {
            onrendered: function(canvas) {
				//jQuery('#img_back').val(canvas.toDataURL("image/png"));
                document.getElementById("image_reply").appendChild(canvas);
				jQuery('#img_back').val(canvas.toDataURL("image/png"));
				var image = canvas.toDataURL("image/png").replace("image/png", "image/octet-stream");  // here is the most important part because if you dont replace you will get a DOM 18 exception.
				
				jQuery("#preview_back").addClass('dis_none') ;
				jQuery.post($ajaxurl, { site: 'back', product_id: product_id, session_id: session_id, imgBase64: image, action: "spdfw_save_image"}, function(data) {
					//jQuery('.designer_calculator_total_value').html(data);
					
					//console.log( "OK");
					$cal2 = 1;
					//jQuery("#woo_designer_add_to_cart_form").submit();
				});	

				jQuery('input[name="woo_designer_back_preview"]').val(image);
 
            }
        });
		
		
}


});

	function image_icon(srcimg, productid, id, uniqe){
			jQuery("."+$y_pos+"_print").append("<div id=icon"+($nos_icons)+" class='new_icon' onmouseover='show_delete_btn(this);' onmouseout='hide_delete_btn(this);'><span data-id='"+id+"' data-uniqe='"+uniqe+"' data-productid='"+productid+"' class='delete_icon property_icon' onClick='delete_icons(this);'></span><img src='"+srcimg+"' width='100%' height='100%' /></div>");
			jQuery( "#icon"+($nos_icons)+"" ).draggable({ containment: "parent" });
			jQuery( "#icon"+($nos_icons)+"" ).resizable({
				maxHeight: 480,
				maxWidth: 450,
				minHeight: 60,
				minWidth: 60
				});
			/**jQuery( "#icon"+($nos_icons)+"" ).rotatable();	**/
				

			jQuery( "#icon"+($nos_icons)+"" ).css({'top':'100px','left':'150px'});
			++$nos_icons;
	}

function delete_icons(e){
	
		jQuery('.designer_btn_loader').show();
		jQuery('.woo_designer_save_config').addClass('loader');
		jQuery('.woo_designer_save_config').prop('disabled', true);	
	
		var productid = jQuery(e).data("productid");
		var id = jQuery(e).data("id");
		var uniqe = jQuery(e).data("uniqe");
		jQuery(e).parent('.new_icon').remove();
		//console.log(productid);
		//console.log(id);
			jQuery.post($ajaxurl, { productid: productid, id: id, uniqe: uniqe, action: "spdfw_remove_addon"}, function(data1) {
				
				//console.log(data);
				jQuery.post($ajaxurl, { productid: productid, id: id, action: "spdfw_get_total"}, function(data) {
					jQuery('#designer_calculator').html(data1);
					jQuery('.designer_calculator_total_value').html(data);
					
					jQuery('.designer_btn_loader').hide();
					jQuery('.woo_designer_save_config').removeClass('loader');
					jQuery('.woo_designer_save_config').prop('disabled', false);					
					
					//console.log(data);
				});				
			});			
		--$nos_icons;
	}
	function show_delete_btn(e){
	
		jQuery(e).children('.property_icon').show();
	}
	function hide_delete_btn(e){
	
		jQuery(e).children('.property_icon').hide();
	}
	
	/*=============================================*/
function delete_text(f){

		jQuery('.designer_btn_loader').show();
		jQuery('.woo_designer_save_config').addClass('loader');
		jQuery('.woo_designer_save_config').prop('disabled', true);
					
			var price = jQuery(f).data("id");
			var product_id = jQuery(f).data("productid");
			var session_id = jQuery(f).data("key");
			
				jQuery.post($ajaxurl, { product_id: product_id, session_id: session_id, price: price, act: 'remove', action: "spdfw_update_text_prices"}, function(data1) {
					//jQuery('.designer_calculator_total_value').html(data);
					//console.log(data1);
	
					jQuery.post($ajaxurl, { productid: product_id, action: "spdfw_get_total"}, function(data) {
						jQuery('#designer_calculator_texts').show();
						jQuery('.designer_calculator_texts_output').html(data1);	
						jQuery('.designer_calculator_total_value').html(data);

						jQuery('.designer_btn_loader').hide();
						jQuery('.woo_designer_save_config').removeClass('loader');
						jQuery('.woo_designer_save_config').prop('disabled', false);
						
						//console.log(data);
					});					
				});	
				
			jQuery(f).parent('.new_text').remove();
			--$nos_icons;
			
			
	}

	
		
/**Rotate**/

