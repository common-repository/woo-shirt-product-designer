<?php

 /*

 * Plugin Name: Shirt Product Designer for WooCommerce

 * Version: 1.0.4

 * Plugin URI: http://www.mlfactory.de

 * Description: Simple / Modern / Fast T-Shirt - Product Designer for WooCommerce with a lot nice features.

 * Author: Michael Leithold

 * Author URI: https://profiles.wordpress.org/mlfactory/

 * Requires at least: 4.0

 * Tested up to: 5.5.1

 * License: GPLv2 or later

 * Text Domain: woo-shirt-product-designer
 
 * Domain Path: /languages/
 
*/


 
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


add_action( 'plugins_loaded', 'spdfw_textdomain');

function spdfw_textdomain(){

    $loadfiles = load_plugin_textdomain('woo-shirt-product-designer', false, 
    basename( dirname( __FILE__ ) ) . '/languages/' );


}


if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	
add_action( 'init', 'spdfw_product_type_register' );

}

function spdfw_product_type_register() {

  class WC_Product_Woodesigner extends WC_Product {
			
    public function __construct( $product ) {
        $this->product_type = 'woodesigner';
	parent::__construct( $product );
    }
  }
}	

//*******************************//
//*********BACKEND PART*********//
//*******************************//
class spdfw_backend {
	
 
    public static function init() {
		
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		
		add_action( 'admin_menu', __CLASS__ . '::spdfw_admin_menu' );
			
		add_action('admin_footer',  __CLASS__ . '::spdfw_enable_tabs');
		
		add_action( 'admin_enqueue_scripts',  __CLASS__ . '::spdfw_admin_js' );
		
		} else {
			
		add_action( 'admin_notices',  __CLASS__ . '::spdfw_woocommerce_disabled' );			
			
		}
		
	}
	

	public static function spdfw_woocommerce_disabled() {
		?>
		<div class="update-nag notice">
			<p><?php __( 'WooCommerce T-Shirt Product Designer require WooCommerce. Please install WooCommerce.', 'woo-shirt-product-designer' ); ?></p>
		</div>
		<?php
	}
	
	
	public static function spdfw_admin_js() {
		
        $parms = array(
            'plugindir' => plugin_dir_url(__FILE__),
            'ajaxurl' => admin_url('admin-ajax.php'),
			'deletetext' => __('Do you really want to delete?', 'woo-shirt-product-designer'),
			'errortext' => __('An error has occurred. The record was not deleted.', 'woo-shirt-product-designer'),
			'chooseimage' => __('Select image', 'woo-shirt-product-designer'),
			'pricetext' => __('Price', 'woo-shirt-product-designer'),
			'imagetext' => __('Image', 'woo-shirt-product-designer'),
			'colortext' => __('Color', 'woo-shirt-product-designer'),
			'imagefronttext' => __('Image Front', 'woo-shirt-product-designer'),
			'imagebacktext' => __('Image Back', 'woo-shirt-product-designer'),
			'insertimage' => __('Select image', 'woo-shirt-product-designer')
        );

        wp_register_script('spdfw_admin_js', plugin_dir_url(__FILE__).'/core/js/woo-designer-admin.js');
		
        wp_localize_script('spdfw_admin_js', 'woodesignerparms', $parms); 
		
        wp_enqueue_script('spdfw_admin_js'); 	
		
		wp_enqueue_script('spdfw_admin_jscolor_js',  plugin_dir_url(__FILE__).'/core/js/jscolor.js', array('jquery'));	
		
		wp_enqueue_style('spdfw_admin_css', plugin_dir_url(__FILE__).'/core/css/woo-designer-admin.css');
		
	}	
		
	
	public static function spdfw_enable_tabs() {

		if ('product' != get_post_type()) :
			return;
		endif;
		?>
		<script type='text/javascript'>
			jQuery(document).ready(function () {
				//for Price tab
				jQuery('.product_data_tabs .general_tab').addClass('show_if_variable_bulk').show();
				jQuery('#general_product_data .pricing').addClass('show_if_variable_bulk').show();
				//for Inventory tab
				jQuery('.inventory_options').addClass('show_if_variable_bulk').show();
				jQuery('#inventory_product_data ._manage_stock_field').addClass('show_if_variable_bulk').show();
				jQuery('#inventory_product_data ._sold_individually_field').parent().addClass('show_if_variable_bulk').show();
				jQuery('#inventory_product_data ._sold_individually_field').addClass('show_if_variable_bulk').show();
			});
		</script>
		<?php

	}

	public static function spdfw_admin_menu(){
		
		add_submenu_page( 'woocommerce', 'WooCommerce T-Shirt Product Designer Responsive', 'Woo Designer', 'manage_options', 'woo-designer-settings', 'spdfw_admin_settings' ); 
			
		function spdfw_admin_settings() {
			include(plugin_dir_path(__FILE__).'core/inc/spdfw_backend.php');
		}
		
	}
	
}

spdfw_backend::init();




//*******************************//
//*********FRONTEND PART*********//
//*******************************//
class spdfw_frontend {
	
	
	 public static function init() {
		
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		 
		add_action('wp_enqueue_scripts',__CLASS__ . '::spdfw_load_scripts');

		add_action('woocommerce_after_single_product_summary',__CLASS__ . '::spdfw_load_designer', 10); 
		
		add_action('init', __CLASS__ . '::spdfw_session');
		
		add_filter( 'woocommerce_get_item_data', __CLASS__ . '::spdfw_cart_infos', 10, 2 );

		add_filter( 'woocommerce_data_stores',  __CLASS__ . '::spdfw_datastore' );		
		
		add_action( 'wp_ajax_spdfw_update_session', __CLASS__ . '::spdfw_update_session' );
		add_action( 'wp_ajax_nopriv_spdfw_update_session', __CLASS__ . '::spdfw_update_session' ); 

		add_action( 'wp_ajax_spdfw_get_addon_total', __CLASS__ . '::spdfw_get_addon_total' );
		add_action( 'wp_ajax_nopriv_spdfw_get_addon_total', __CLASS__ . '::spdfw_get_addon_total' );
		
		add_action( 'wp_ajax_spdfw_get_total', __CLASS__ . '::spdfw_get_total' );
		add_action( 'wp_ajax_nopriv_spdfw_get_total', __CLASS__ . '::spdfw_get_total' );
		
		add_action( 'wp_ajax_woo_designer_remove_addon', __CLASS__ . '::spdfw_remove_addon' );
		add_action( 'wp_ajax_nopriv_woo_designer_remove_addon', __CLASS__ . '::spdfw_remove_addon' );

		add_action( 'wp_ajax_spdfw_update_groundprice', __CLASS__ . '::spdfw_update_groundprice' );
		add_action( 'wp_ajax_nopriv_spdfw_update_groundprice', __CLASS__ . '::spdfw_update_groundprice' );

		add_action( 'wp_ajax_spdfw_save_image', __CLASS__ . '::spdfw_save_image' );
		add_action( 'wp_ajax_nopriv_spdfw_save_image', __CLASS__ . '::spdfw_save_image' );
		
		add_action( 'wp_ajax_spdfw_delete_variation', __CLASS__ . '::spdfw_delete_variation' );
		add_action( 'wp_ajax_nopriv_spdfw_delete_variation', __CLASS__ . '::spdfw_delete_variation' );		

		add_action( 'wp_ajax_spdfw_delete_addon', __CLASS__ . '::spdfw_delete_addon' );
		add_action( 'wp_ajax_nopriv_spdfw_delete_addon', __CLASS__ . '::spdfw_delete_addon' );	
		
		add_action( 'wp_ajax_spdfw_update_text_prices', __CLASS__ . '::spdfw_update_text_prices' );
		add_action( 'wp_ajax_nopriv_spdfw_update_text_prices', __CLASS__ . '::spdfw_update_text_prices' );		
		
		add_action('woocommerce_before_calculate_totals',  __CLASS__ . '::spdfw_before_totals');
		
		add_filter( 'woocommerce_add_cart_item_data',  __CLASS__ . '::spdfw_add_item_data', 99, 2 );

		add_filter( 'product_type_selector', __CLASS__ . '::spdfw_product_type_add' );
		
		add_filter( 'woocommerce_product_data_tabs', __CLASS__ . '::spdfw_product_tab' );
		
		add_action( 'woocommerce_product_data_panels', __CLASS__ . '::spdfw_product_tab_product_tab_content' );
		
		add_action( 'woocommerce_process_product_meta', __CLASS__ . '::spdfw_add_item_meta' );
		
		add_action('woocommerce_add_order_item_meta', __CLASS__ . '::spdfw_add_item_order_meta',1,2);

		}
		
	}
	
	public static function spdfw_add_item_order_meta( $item_id, $values ) {
		//print_r($values);
		//die();
		global $woocommerce,$wpdb;
		  
			if( !empty( $values['_woo_designer_front_img'] ) ) {
				wc_add_order_item_meta($item_id, __( 'Front', 'woo-shirt-product-designer' ), '<a href="'.$values['_woo_designer_front_img'].'" target="_blank"><img src="'.$values['_woo_designer_front_img'].'" class="woo_designer_img_cart"/></a>');
			}
	
			if( !empty( $values['_woo_designer_back_img'] ) ) {
				wc_add_order_item_meta($item_id, __( 'Back', 'woo-shirt-product-designer' ), '<a href="'.$values['_woo_designer_back_img'].'" target="_blank"><img src="'.$values['_woo_designer_back_img'].'" class="woo_designer_img_cart"/></a>');
			}	
			
			if( !empty( $values['_woo_designer_size'] ) ) {
				wc_add_order_item_meta($item_id, __( 'Size', 'woo-shirt-product-designer' ), $values['_woo_designer_size']);
			}	

			if( !empty( $values['_woo_designer_groundprice'] ) ) {
				wc_add_order_item_meta($item_id, __( 'Base price', 'woo-shirt-product-designer' ), wc_price($values['_woo_designer_groundprice']));
			}	

			if( !empty( $values['_woo_designer_texts_total'] ) ) {
				wc_add_order_item_meta($item_id, __( 'Texts', 'woo-shirt-product-designer' ), wc_price($values['_woo_designer_texts_total']));
			}	
			
			if( !empty( $values['_woo_designer_addons'] ) ) {
				$addons = "";
				foreach ($values['_woo_designer_addons'] as $data) {
					if (isset($data)) {
						foreach ($data as $session_woo_designer_addons_key => $value) {
						
								if ($session_woo_designer_addons_key == "price") {
									$addons = $addons+$value;
								
								}
							
						}
					}
				}
				
				wc_add_order_item_meta($item_id, __( 'Graphics', 'woo-shirt-product-designer' ), wc_price($addons));
				
			}
	
	}
	
	public static function spdfw_add_item_meta( $post_id ){
		
		if (isset($post_id)) {
			if( !empty( $_POST['designer_addon_images'] ) ) {
				$designer_addon_images = array_map( 'sanitize_text_field', wp_unslash( $_POST['designer_addon_images'] ));
				update_post_meta( $post_id, 'designer_addon_images', $designer_addon_images  );
			}

			if( !empty( $_POST['designer_addon_prices'] ) ) {
				$designer_addon_prices = array_map( 'sanitize_text_field', wp_unslash( $_POST['designer_addon_prices'] ));
				update_post_meta( $post_id, 'designer_addon_prices', $designer_addon_prices  );
			}
			
			if( !empty( $_POST['designer_variation_front'] ) ) {
				$designer_variation_front = array_map( 'sanitize_text_field', wp_unslash( $_POST['designer_variation_front'] ));
				update_post_meta( $post_id, 'designer_variation_front', $designer_variation_front  );
			}
			
			if( !empty( $_POST['designer_variation_back'] ) ) {
				$designer_variation_back = array_map( 'sanitize_text_field', wp_unslash( $_POST['designer_variation_back'] ));		
				update_post_meta( $post_id, 'designer_variation_back', $designer_variation_back  );
			}
			
			if( !empty( $_POST['designer_variation_color'] ) ) {
				$designer_variation_color = array_map( 'sanitize_text_field', wp_unslash( $_POST['designer_variation_color'] ));
				update_post_meta( $post_id, 'designer_variation_color', $designer_variation_color  );	
			}
			
			if( !empty( $_POST['designer_variation_color'] ) ) {
				$designer_variation_prices = array_map( 'sanitize_text_field', wp_unslash( $_POST['designer_variation_prices'] ));
				update_post_meta( $post_id, 'designer_variation_prices', $designer_variation_prices  );		
			}

			if( !empty( $_POST['designer_base_price'] ) ) {
				$designer_base_price = sanitize_text_field( $_POST['designer_base_price'] );
				update_post_meta( $post_id, 'designer_base_price', $designer_base_price  );
			}

			if( !empty( $_POST['designer_text_price'] ) ) {
				$designer_text_price = sanitize_text_field( $_POST['designer_text_price'] );
				update_post_meta( $post_id, 'designer_text_price', $designer_text_price  );
			}
				
			$designer_allow_text_checkbox = isset( $_POST['designer_allow_text'] ) ? '1' : '0';
			update_post_meta($post_id, 'designer_allow_text', $designer_allow_text_checkbox );
			
			$designer_allow_graphics_checkbox = isset( $_POST['designer_allow_graphics'] ) ? '1' : '0';
			update_post_meta( $post_id, 'designer_allow_graphics', $designer_allow_graphics_checkbox  );
			
			$designer_allow_custom_upload = isset( $_POST['designer_allow_custom_upload'] ) ? '1' : '0'; ;
			update_post_meta( $post_id, 'designer_allow_custom_upload', $designer_allow_custom_upload  );
		}
	}	 
	 
	 
	public static function spdfw_product_tab_product_tab_content() {
		include(plugin_dir_path(__FILE__).'core/inc/spdfw_product_tab.php');
	}	 
	 
	 
	public static function spdfw_product_tab( $tabs) {
			
		$tabs['woodesigner'] = array(
		  'label'	 => __( 'Woo Designer', 'woo-shirt-product-designer' ),
		  'target' => 'woodesigner_product_options',
		  'class'  => 'show_if_woodesigner_product',
		 );
		return $tabs;
	}	 
	 
	
	public static function spdfw_product_type_add( $types ){
		$types[ 'woodesigner' ] = __( 'Woo Designer', 'woo-shirt-product-designer' );

		return $types;	
	}	 
	
	
	public static function spdfw_delete_addon() {	 
	 
		$key = sanitize_text_field($_POST['key']);
		
		$productid = sanitize_text_field($_POST['productid']);
		
		if (isset($key) && isset($productid)) {
		
			//Unset addon
			$designer_addon_images = get_post_meta( $productid, 'designer_addon_images', true  );
			
			if (isset($designer_addon_images[$key])) {
				
				unset($designer_addon_images[$key]);
				
			}
			
			update_post_meta( $productid, 'designer_addon_images', $designer_addon_images);
			
			
			//Unset designer_addon_prices
			$designer_addon_prices = get_post_meta( $productid, 'designer_addon_prices', true  );
	
			if (isset($designer_addon_prices[$key])) {
				
				unset($designer_addon_prices[$key]);
				
			}
			
			update_post_meta( $productid, 'designer_addon_prices', $designer_addon_prices);	

			echo 1;
			
		} else {
			
			echo 0;
			
		}
		
		wp_die();
		
	}


	public static function spdfw_delete_variation() {	 
	 
		$key = sanitize_text_field($_POST['key']);
		
		$productid = sanitize_text_field($_POST['productid']);
		
		if (isset($key) && isset($productid)) {
			
			//Unset designer_variation_front
			$designer_variation_front = get_post_meta( $productid, 'designer_variation_front', true  );
			
			if (isset($designer_variation_front[$key])) {
				
				unset($designer_variation_front[$key]);
				
			}
			
			update_post_meta( $productid, 'designer_variation_front', $designer_variation_front);
			
			
			//Unset designer_variation_back
			$designer_variation_back = get_post_meta( $productid, 'designer_variation_back', true  );
	
			if (isset($designer_variation_back[$key])) {
				
				unset($designer_variation_back[$key]);
				
			}
			
			update_post_meta( $productid, 'designer_variation_back', $designer_variation_back);	

			//Unset designer_variation_color
			$designer_variation_color = get_post_meta( $productid, 'designer_variation_color', true  );
	
			if (isset($designer_variation_color[$key])) {
				
				unset($designer_variation_color[$key]);
				
			}
			
			update_post_meta( $productid, 'designer_variation_color', $designer_variation_color);

			//Unset designer_variation_prices
			$designer_variation_prices = get_post_meta( $productid, 'designer_variation_prices', true  );
	
			if (isset($designer_variation_prices[$key])) {
				
				unset($designer_variation_prices[$key]);
				
			}
			
			update_post_meta( $productid, 'designer_variation_prices', $designer_variation_prices);			
			
			echo 1;
			
			//print_r(get_post_meta( $productid, 'designer_variation_front', true  ));
		} else {
			
			echo 0;
			
		}
		
		wp_die();
		
	}


	public static function spdfw_update_text_prices() {	 
	 
		$act = sanitize_text_field($_POST['act']);
		
		$session_id = sanitize_text_field($_POST['session_id']);
		
		$product_id = sanitize_text_field($_POST['product_id']);	
		
		$price = sanitize_text_field($_POST['price']);

		if (isset($act) && isset($session_id) && isset($product_id) && isset($price)) {
		
			if ($act == 'add') {
				
				if (isset($_SESSION['woo_designer_text_total_'.$product_id])) {
					
					$_SESSION['woo_designer_text_total_'.$product_id] = $_SESSION['woo_designer_text_total_'.$product_id]+$price;
					
				} else {
					
					$_SESSION['woo_designer_text_total_'.$product_id] = $price;
					
				}
				
				echo wc_price($_SESSION['woo_designer_text_total_'.$product_id]);
				
			}
			if ($act == 'remove') {
				
				if (isset($_SESSION['woo_designer_text_total_'.$product_id])) {
					
					$_SESSION['woo_designer_text_total_'.$product_id] = $_SESSION['woo_designer_text_total_'.$product_id]-$price;
					
				} 
				
				echo wc_price($_SESSION['woo_designer_text_total_'.$product_id]);			
			}
		
		}
		
		wp_die();
		
	}
	 
	 
	public static function spdfw_save_image() {
		
		$img = sanitize_text_field($_POST['imgBase64']);
		
		$session_id = sanitize_text_field($_POST['session_id']);
		
		
		$product_id = sanitize_text_field($_POST['product_id']);
		$site = sanitize_text_field($_POST['site']);
		
		
		if (isset($img) && isset($session_id) && isset($product_id)) {
			
			$img = str_replace('data:image/png;base64,', '', $img);
			
			$img = str_replace('data:image/octet-stream;base64,', '', $img);
			
			$img = str_replace(' ', '+', $img);
			
			//Remove last char -  its a 0
			$img = substr($img, 0, -1);
			
			$fileData = base64_decode($img);
			
			//saving
			$upload_dir = wp_upload_dir();
			
			$dir = $upload_dir['basedir'] . '/woo-designer/';
			
			if (!file_exists($dir)) {
				
				mkdir($dir, 0777, true);
				
			}

			if (!file_exists($dir . $session_id)) {
				
				mkdir($dir . $session_id, 0777, true);
				
			}			
			
			$fileName =  $dir . $session_id.'/'.$product_id.'_'.$site.'.png';
			
			file_put_contents($fileName, $fileData);
			
			echo $img;
			
		} else {
			echo "ERROR";
		}
		wp_die();
	}
	
	
	
	public static function spdfw_add_item_data( $cart_item_data, $product_id ) {

		if ($_POST) {
			
		global $woocommerce;
		
		$_product = wc_get_product( $product_id );
		$product_type = $_product->get_type();
		
			if ($product_type != 'woodesigner') {
				return;
			}		
		
			$new_value = array();
			
			$unique_key = uniqid(rand(), true);
			
			if (isset($_POST['woo_designer_front_img_url'])) {
				
				$frontimg = sanitize_text_field($_POST['woo_designer_front_img_url']);
				
			} else {
				
				$frontimg = "";
				
			}
			
			if (isset($_POST['woo_designer_back_img_url'])) {
				
				$backimg = sanitize_text_field($_POST['woo_designer_back_img_url']);
				
			} else {
				
				$backimg = "";
				
			}	
			
			if (isset($_POST['size'])) {
				
				$size = sanitize_text_field($_POST['size']);
				
			} else {
				
				$size = "";
				
			}	
			
			//Rename image 
			if (file_exists(wp_upload_dir()['basedir'] .'/woo-designer/'. session_id().'/'.$product_id.'_front.png')){
				rename(wp_upload_dir()['basedir'] .'/woo-designer/'. session_id().'/'.$product_id.'_front.png', wp_upload_dir()['basedir'] .'/woo-designer/'. session_id().'/'.$product_id.'_'.$unique_key.'_front.png');	
				rename(wp_upload_dir()['basedir'] .'/woo-designer/'. session_id().'/'.$product_id.'_back.png', wp_upload_dir()['basedir'] .'/woo-designer/'. session_id().'/'.$product_id.'_'.$unique_key.'_back.png');	
			}

			$upload_dir = wp_upload_dir();
			$dir = $upload_dir['basedir'] . '/woo-designer/';
			$img_front = wp_get_upload_dir()['baseurl'] .'/woo-designer/'. session_id().'/'.$product_id.'_'.$unique_key.'_front.png';
			$img_back = wp_get_upload_dir()['baseurl'] .'/woo-designer/'. session_id().'/'.$product_id.'_'.$unique_key.'_back.png';
			
			$new_value['_designer_unique_price'] = $_SESSION['woo_designer_total_'.$product_id];

			$new_value['_woo_designer_front_img_url'] = $frontimg;

			$new_value['_woo_designer_size'] = $size;

			$new_value['_woo_designer_back_img_url'] = $backimg;

			$new_value['_woo_designer_front_img'] = $img_front;

			$new_value['_woo_designer_back_img'] = $img_back;

			$new_value['_woo_designer_unique_key'] = $unique_key;

			if (isset($_SESSION['woo_designer_addons'])) {
				$new_value['_woo_designer_addons'] = $_SESSION['woo_designer_addons'];
			} else {
				$new_value['_woo_designer_addons'] = 0;
			}
			
			if (isset($_SESSION['woo_designer_groundprice'])) {
				$new_value['_woo_designer_groundprice'] = $_SESSION['woo_designer_groundprice'];
			} else {
				$new_value['_woo_designer_groundprice'] = 0;
			}			
			
			if (isset($_SESSION['woo_designer_text_total_'.$product_id])) {
				$new_value['_woo_designer_texts_total'] = $_SESSION['woo_designer_text_total_'.$product_id];
			} else {
				$new_value['_woo_designer_texts_total'] = 0;
			}			
			
			
			if(empty($cart_item_data)) {
				return $new_value;
			} else {
				return array_merge($cart_item_data, $new_value);
			}
			   

		}
	}	 

	public static function spdfw_before_totals( $cart_object ) {
		 $cart_items = $cart_object->cart_contents;
		 
		 
		 
		if (isset($_POST['woo_designer_front_img_url'])) {
			$frontimg = sanitize_text_field($_POST['woo_designer_front_img_url']);
		} else {
			$frontimg = "";
		}
		
		if (isset($_POST['woo_designer_back_img_url'])) {
			$backimg = sanitize_text_field($_POST['woo_designer_back_img_url']);
		} else {
			$backimg = "";
		}	
		
		  if ( ! empty( $cart_items ) ) {
		 
			foreach ( $cart_items as $key => $value ) {
			  if ($value['data']->product_type == "woodesigner") {
				$value['data']->set_price( $value['_designer_unique_price'] );
			  }
			}

		  }
	}


	public static function spdfw_cart_infos( $item_data, $cart_item ) {

		$_product = wc_get_product( $cart_item['product_id'] );
		$product_type = $_product->get_type();
			if ($product_type != 'woodesigner') {
				return;
			}	
		if ( empty( $cart_item['_woo_designer_back_img'] ) ) {
			//return $item_data;
		}
		
		if ( !empty( $cart_item['_woo_designer_front_img'] ) ) {
			
			$item_data[] = array(
				'key'     => __( 'Front', 'woo-shirt-product-designer' ),
				'value'   => '<a href="'.wc_clean( $cart_item['_woo_designer_front_img'] ).'" target="_blank"><img src="'.wc_clean( $cart_item['_woo_designer_front_img']).'"/></a>',
				'display' => '',
			);
		
		}		
		
		if ( !empty( $cart_item['_woo_designer_back_img'] ) ) {
			
			$item_data[] = array(
				'key'     => __( 'Back', 'woo-shirt-product-designer' ),
				'value'   => '<a href="'.wc_clean( $cart_item['_woo_designer_back_img'] ).'" target="_blank"><img src="'.wc_clean( $cart_item['_woo_designer_back_img']).'"/></a>',
				'display' => '',
			);
		
		}
		
		if ( !empty( $cart_item['_woo_designer_size'] ) ) {
			
			$item_data[] = array(
				'key'     => __( 'Size', 'woo-shirt-product-designer' ),
				'value'   => strtoupper($cart_item['_woo_designer_size']),
				'display' => '',
			);
		
		}		
		
		if ( !empty( $cart_item['_woo_designer_groundprice'] ) ) {
			
			$item_data[] = array(
				'key'     => __( 'Base price', 'woo-shirt-product-designer' ),
				'value'   => wc_price($cart_item['_woo_designer_groundprice']),
				'display' => '',
			);
			
		}	
		
		
		if ( !empty( $cart_item['_woo_designer_addons'] ) ) {

			$addons = "";
			foreach ($cart_item['_woo_designer_addons'] as $data) {
				if (isset($data)) {
					foreach ($data as $session_woo_designer_addons_key => $value) {
						if (isset($session_woo_designer_addons_key)) { {
							if ($session_woo_designer_addons_key == "price") {
								$addons = intval($addons)+intval($value);							
							}
						}
					}
				}
			}
			}

			$item_data[] = array(
				'key'     => __( 'Graphics', 'woo-shirt-product-designer' ),
				'value'   => wc_price($addons),
				'display' => '',
			);
			
		}


		if ( !empty( $cart_item['_woo_designer_texts_total'] ) ) {
		
			$item_data[] = array(
				'key'     => __( 'Texts', 'woo-shirt-product-designer' ),
				'value'   => wc_price($cart_item['_woo_designer_texts_total']),
				'display' => '',
			);
		
		}	
		
	 
		return $item_data;
	}


	 
	public static function spdfw_datastore( $stores ) {

		require_once plugin_dir_path(__FILE__) . '/core/inc/spdfw_datastore_class.php';
		
		$stores['product'] = 'Woo_Designer_Data_Store_CPT';

		return $stores;
	}	 



	public static function spdfw_update_groundprice() {
		$groundprice = sanitize_text_field($_POST['ground_price']);
		$productid = sanitize_text_field($_POST['productid']);
		
		if (isset($groundprice)) {
			
			$_SESSION['woo_designer_groundprice'] = $groundprice;
				
				if (isset($_SESSION['woo_designer_addons'])) {
					
					$addons_total = 0;
					
					foreach ($_SESSION['woo_designer_addons'] as $session_woo_designer_addons_key => $session_woo_designer_addons_value) {
						
						if ($session_woo_designer_addons_value['productid'] == $productid) {

							$addons_total = 0;
							
							foreach ($_SESSION['woo_designer_addons'] as $session_woo_designer_addons_key => $session_woo_designer_addons_value) {
								
								if ($session_woo_designer_addons_value['productid'] == $productid ) {
									
									$addons_total = $addons_total+$session_woo_designer_addons_value['price'];
																		
								}
							
							}

							$total = $addons_total+$groundprice;
							
						}
						
					}
				} else {
					
					$total = $groundprice;
					
				}
				
			if (!isset($total)) {
				$total = 0;
			}	
				
			echo json_encode(array("groundprice" => wc_price($groundprice), "total" => wc_price($total)));
		}
		wp_die(); 
	}
	
	public static function spdfw_remove_addon() {
		
		if (isset($_POST['id']) && isset($_POST['productid'])) {
			
			$productid = sanitize_text_field($_POST['productid']);
		
			$id = sanitize_text_field($_POST['id']);
			
			$uniqe = sanitize_text_field($_POST['uniqe']);
			
				if (isset($_SESSION['woo_designer_addons'])) {
					
					$addons_total = 0;
					
					foreach ($_SESSION['woo_designer_addons'] as $session_woo_designer_addons_key => $session_woo_designer_addons_value) {
						
						if ($session_woo_designer_addons_value['productid'] == $productid && $session_woo_designer_addons_value['id'] == $id && $session_woo_designer_addons_value['uniqe'] == $uniqe) {
							
							unset($_SESSION['woo_designer_addons'][$session_woo_designer_addons_key]);
							
							$addons_total = 0;
							
							foreach ($_SESSION['woo_designer_addons'] as $session_woo_designer_addons_key => $session_woo_designer_addons_value) {
								
								if ($session_woo_designer_addons_value['productid'] == $productid ) {
									
									$addons_total = $addons_total+$session_woo_designer_addons_value['price'];
									
									$output .= '<div class="" data-id="'.$session_woo_designer_addons_value['productid'].'">'.wc_price($session_woo_designer_addons_value['price']).'</div>';
									
								}
							
							}
							
							echo $output;
						}
						
					}
					
				}

		} else {
			
			echo "Error";
			
		}		
		wp_die(); 
	}
	
	public static function spdfw_session() {
		
	  if( !headers_sent() && !session_id() ) {
		  
		session_start();
		
	  }
	  
	}
	
	
	public static function spdfw_get_total() {
		
		if (isset($_POST['productid'])) {
			
			$output = "";
			
			$productid = sanitize_text_field($_POST['productid']);
			
			$groundprice = $_SESSION['woo_designer_groundprice'];
			
				if (isset($_SESSION['woo_designer_addons'])) {
					
					$addons_total = 0;
					
					foreach ($_SESSION['woo_designer_addons'] as $session_woo_designer_addons_key => $session_woo_designer_addons_value) {
						
						if ($session_woo_designer_addons_value['productid'] == $productid) {

							$addons_total = 0;
							
							foreach ($_SESSION['woo_designer_addons'] as $session_woo_designer_addons_key => $session_woo_designer_addons_value) {
								
								if ($session_woo_designer_addons_value['productid'] == $productid ) {
									
									$addons_total = $addons_total+$session_woo_designer_addons_value['price'];
																		
								}
							
							}
							
						}
						
					}
					
					//add text price
					if (isset($_SESSION['woo_designer_text_total_'.$productid])) {
						
						$text_prices = $_SESSION['woo_designer_text_total_'.$productid];
						
					} else {
						
						$text_prices = 0;
						
					}		
					
					$total = $addons_total+$groundprice+$text_prices;
					
					$_SESSION['woo_designer_total_'.$productid] = $total;
					
				} else {
					
					if (isset($_SESSION['woo_designer_text_total_'.$productid])) {
						
						$text_prices = $_SESSION['woo_designer_text_total_'.$productid];
						
					} else {
						
						$text_prices = 0;
						
					}	
					
					$total = $groundprice+$text_prices;
					
					$_SESSION['woo_designer_total_'.$productid] = $total;					
				
				}			
			
			echo wc_price($total);

		}
		
		wp_die(); 
		
	}
	
	
	public static function spdfw_get_addon_total() {
		
		if (isset($_POST['productid'])) {
			
			$output = "";
			
			$productid = sanitize_text_field($_POST['productid']);
			
				if (isset($_SESSION['woo_designer_addons'])) {
					
					$addons_total = 0;
					
					foreach ($_SESSION['woo_designer_addons'] as $session_woo_designer_addons_key => $session_woo_designer_addons_value) {
						
						if ($session_woo_designer_addons_value['productid'] == $productid ) {
							
							$addons_total = $addons_total+$session_woo_designer_addons_value['price'];
							
							$output .= '<div class="" data-id="'.$session_woo_designer_addons_value['productid'].'">'.wc_price($session_woo_designer_addons_value['price']).'</div>';
							
						}
					
					}
					
					echo $output;
					
				}

		} else {
			
			echo "Error";
			
		}
		
		wp_die();
		
	}
	
	public static function spdfw_update_session() {
		
		if (isset($_POST['id']) && isset($_POST['productid']) && isset($_POST['price']) && isset($_POST['uniqe'])) {
			
			$productid = sanitize_text_field($_POST['productid']);
			
			$id = sanitize_text_field($_POST['id']);
			
			$uniqe = sanitize_text_field($_POST['uniqe']);
			
			$price = sanitize_text_field($_POST['price']);
			
			$_SESSION['woo_designer_addons'][] = array("productid" => $productid,"id" => $id, "price" => $price, "uniqe" => $uniqe );

			echo "Ok";
			
		} else {
			
			echo "Error";
		}
		
		wp_die();
		
	}	 
	 
	 
	 
	public static function spdfw_load_designer() {
		 
		$product_id = get_the_ID();
		
		$_product = wc_get_product( $product_id );
		
		if( $_product->is_type( 'woodesigner' ) ) {

		//*** ON FIRST LOAD CLEAR DATA FOR THIS PRODUCT ID ***//
		if (isset($_SESSION['woo_designer_addons'])) {
			
			foreach ($_SESSION['woo_designer_addons'] as $session_woo_designer_addons_key => $session_woo_designer_addons_value) {
				
				if ($session_woo_designer_addons_value['productid'] == get_the_ID() ) {
					
					unset($_SESSION['woo_designer_addons'][$session_woo_designer_addons_key]);
					
				}
				
			}
			
		}

		//*** CLEAR SESSION DATA FOR TEXT PRICES **//
		if (isset($_SESSION['woo_designer_text_total_'.get_the_ID()])) {
			
			unset($_SESSION['woo_designer_text_total_'.get_the_ID()]);
			
		}
		?>

		<div class="container design_api_container" style="width: 100%;">
			<div class="design_api">
			<div class="designer_api_overlay">
			<div class="designer_api_overlay_inner">
				<p><?php echo __('Please wait... A preview will be generated.','woo-shirt-product-designer'); ?></p>
				<p><?php echo __('This can take 20-30 seconds depending on the workload.','woo-shirt-product-designer'); ?></p>
				<img src="<?php echo plugin_dir_url(__FILE__).'/core/designer/tdesignAPI/images/loading.gif'; ?>" class="designer_loader"/>
			</div>
			</div>
				<!--=============================================================-->
				<div id="menu" class="designer_api_menu">
				<div class="designer_api_menu_overlay"></div>
				
					<div class="menu_option sel_type">
					</div>
					
					<div class="menu_option sel_color">
						<i class="fa fa-paint-brush fa-2x"></i>
					</div>
					
					<?php if(get_post_meta(get_the_ID(), 'designer_allow_graphics', true )) { ?>
					<?php if(get_post_meta(get_the_ID(), 'designer_allow_graphics', true ) == "1") { ?>			
					<div class="menu_option sel_art">
						<i class="fa fa-camera-retro fa-2x"></i>
					</div>
					<?php } ?> 
					<?php } ?> 		
					
					<?php if(get_post_meta(get_the_ID(), 'designer_allow_custom_upload', true )) { ?>
					<?php if(get_post_meta(get_the_ID(), 'designer_allow_custom_upload', true ) == "1") { ?>			
					<div class="menu_option sel_custom_icon">
						<i class="fa fa-upload fa-2x"></i>
					</div>
					<?php } ?> 
					<?php } ?>	
					
					<?php if(get_post_meta(get_the_ID(), 'designer_allow_text', true )) { ?>
					<?php if(get_post_meta(get_the_ID(), 'designer_allow_text', true ) == "1") { ?>
					<div class="menu_option sel_text">
						<i class="fa fa-font fa-2x"></i>
					</div>
					<?php } ?> 
					<?php } ?> 
					
				</div>
				<!--=============================================================-->
				<div id="options" class="designer_api_options">
				
				<div class="designer_api_options_overlay"></div>
				
					<div class="T_type woo_designer">
					
					<?php 
					$first_designer_variation_front = get_post_meta( get_the_ID(), 'designer_variation_front' )[0][1]; 
					$first_designer_variation_back = get_post_meta( get_the_ID(), 'designer_variation_back' )[0][1]; 	
					$first_designer_variation_prices = get_post_meta( get_the_ID(), 'designer_variation_prices' )[0][1]; 			
					//TODO BASE PRICE !

						if (!empty($first_designer_variation_front)) {
						?>
						
						<div id="radio0" class="designer_change_main" data-mainimg="<?php echo $first_designer_variation_front; ?>" data-mainbackimg="<?php echo $first_designer_variation_back; ?>" class="designer_change_main" ><img src="<?php echo $first_designer_variation_front; ?>" width="100%" height="100%" />	</div>				
						
						<?php }	?>
					
						<div style="display: none;" id="radio1" class="designer_change_main" ><img src="<?php echo plugin_dir_url(__FILE__) . '/core/designer/tdesignAPI/images/menu_icons/submenu/tee.jpg'; ?>" width="100%" height="100%" /></div>
						
						<div id="radio3" style="display: none;" ><img src="<?php echo plugin_dir_url(__FILE__) . '/core/designer/tdesignAPI/images/menu_icons/submenu/hoodie.jpg'; ?>" width="100%" height="100%" /></div>
				
					</div>

					<div class="color_pick">
					<?php
						$saved_designer_variation_front = get_post_meta( get_the_ID(), 'designer_variation_front' )[0];
						
						$saved_designer_variation_back = get_post_meta( get_the_ID(), 'designer_variation_back' )[0]; 
						
						$saved_designer_variation_color = get_post_meta( get_the_ID(), 'designer_variation_color' )[0]; 
						
						$saved_designer_variation_prices = get_post_meta( get_the_ID(), 'designer_variation_prices' )[0]; 
						
						if (isset($saved_designer_variation_front)) {
							
							foreach ($saved_designer_variation_front as $designer_variation_front_image_key => $designer_variation_front_image_value) {
								
								$color = $saved_designer_variation_color[$designer_variation_front_image_key];
								
								echo '<div class="color_radio_div designer_change_variation" style="background:#'.$color.'" data-color="'.$color.'" data-productid="'.get_the_ID().'" data-price="'.$saved_designer_variation_prices[$designer_variation_front_image_key].'" data-frontimg="'.$designer_variation_front_image_value.'" data-backimg="'.$saved_designer_variation_back[$designer_variation_front_image_key].'" title="'.strip_tags(wc_price($saved_designer_variation_prices[$designer_variation_front_image_key])).'"></div>';
							
							}
						
						}
					?>
					</div>
					
					<div class="default_samples">	
					<?php
						$saved_designer_addon_images = get_post_meta( get_the_ID(), 'designer_addon_images' )[0];
						
						$saved_designer_addon_prices = get_post_meta( get_the_ID(), 'designer_addon_prices' )[0]; 

						if (isset($saved_designer_addon_images)) {

							foreach ($saved_designer_addon_images as $key => &$value) {
								
								if (!empty($saved_designer_addon_prices[$key])) {
		
								$option_price = $saved_designer_addon_prices[$key];
								
								} else {
									
									$option_price = 0;
									
								}
								
								
								
								if (strpos($value,'.png') !== false) {
									
									echo '<div class="sample_icons" data-uniqe="'.uniqid(rand()).'" data-productid="'.get_the_ID().'" data-price="'.$option_price.'" data-id="'.$key.'"><img src="'.$value. '" width="100%" height="100%" data-option-price="'.$option_price.'" title="'.strip_tags(wc_price($option_price)).'"/></div>' ;
								
								} elseif(strpos($value,'.gif') === false) {
									
									echo '<div class="sample_icons" data-uniqe="'.uniqid(rand()).'" data-productid="'.get_the_ID().'" data-price="'.$option_price.'" data-id="'.$key.'"><img src="'.$value. '" width="100%" height="100%" data-option-price="'.$option_price.'" title="'.strip_tags(wc_price($option_price)).'"/></div>' ;
								
								} else {
									
									echo '<div class="sample_icons" data-uniqe="'.uniqid(rand()).'" data-productid="'.get_the_ID().'" data-price="'.$option_price.'" data-id="'.$key.'"><img src="'.$value. '" width="100%" height="100%" data-option-price="'.$option_price.'" title="'.strip_tags(wc_price($option_price)).'"/></div>' ;
								
								}
							}
							
						} else {
							
							?>
							
							<div><?php echo __('No predefined graphics found', 'woo-shirt-product-designer'); ?>.</div>
							
							<?php
						
						}
					?>
					</div>
					
					<div class="custom_icon">
						<form id="form1" runat="server" class="designer_upload_custom_clipart">
							<span class="btn btn-default btn-file">
								<?php echo __('Select file...', 'woo-shirt-product-designer'); ?>
								<input type='file' id="imgInp" />
							</span>

						</form>
					</div>

					<div class="custom_font">
						<select id="fs" onchange="changeFont(this.value);">
							<option value="arial"><?php echo __('Arial', 'woo-shirt-product-designer'); ?></option>
							<option value="Nosifer, cursive"><?php echo __('Nosifer', 'woo-shirt-product-designer'); ?></option>
							<option value="League Script, cursive"><?php echo __('League Script', 'woo-shirt-product-designer'); ?></option>
							<option value="Yellowtail, cursive"><?php echo __('Yellowtail', 'woo-shirt-product-designer'); ?></option>
							<option value="Permanent Marker, cursive"><?php echo __('Permanent Marker', 'woo-shirt-product-designer'); ?></option>
							<option value="Codystar, cursive"><?php echo __('Codystar', 'woo-shirt-product-designer'); ?></option>
							<option value="'Eater', cursive"><?php echo __('Eater', 'woo-shirt-product-designer'); ?></option>
							<option value="Molle, cursive"><?php echo __('Molle', 'woo-shirt-product-designer'); ?></option>
							<option value="Snowburst One, cursive"><?php echo __('Snowburst One', 'woo-shirt-product-designer'); ?></option>
							<option value="Shojumaru, cursive"><?php echo __('Shojumaru', 'woo-shirt-product-designer'); ?></option>
							<option value="Frijole, cursive"><?php echo __('Frijole', 'woo-shirt-product-designer'); ?></option>
							<option value="Gloria Hallelujah, cursive"><?php echo __('Gloria Hallelujah', 'woo-shirt-product-designer'); ?></option>
							<option value="Calligraffitti, cursive"><?php echo __('Calligraffitti', 'woo-shirt-product-designer'); ?></option>
							<option value="verdana"><?php echo __('Verdana', 'woo-shirt-product-designer'); ?></option>
							<option value="impact"><?php echo __('Impact', 'woo-shirt-product-designer'); ?></option>
							<option value="ms comic sans"><?php echo __('MS Comic Sans', 'woo-shirt-product-designer'); ?></option>
						</select>
						<input type="color" name="favcolor" onChange="changeColor(this.value);" class="designer_text_color_select" placeholder="Color Name" />
						<div class="font_styling">

							<span id="bold_button" onclick="b();"><b><?php echo __('B', 'woo-shirt-product-designer'); ?></b></span>
							
							<span id="italic_button" onclick="i();"><i><?php echo __('I', 'woo-shirt-product-designer'); ?></i></span>

							<select id="font_size" onchange="changeFontSize(this.value);">
								<?php for($i=10;$i<=140;$i+=2){ ?>									
									<option value="<?php echo $i; ?>px"><?php echo $i; ?><?php echo __('px', 'woo-shirt-product-designer'); ?></option>
								<?php } ?>
							</select>
							
						</div>
						
						<textarea id="custom_text" placeholder="Create Your Custom Text..."></textarea>
						<button type="button" class="btn btn-primary" id="apply_text" data-key="<?php echo session_id(); ?>" data-productid="<?php echo get_the_ID(); ?>" data-id="<?php echo get_post_meta( get_the_ID(), 'designer_text_price', true  );?>">
							<?php echo __('Add', 'woo-shirt-product-designer'); ?>
						</button>

					</div>
				</div>
				<!--=============================================================-->
				<!--=========================preview start====================================-->

				<div id='preview_t' class="design_api_preview_t">
					<div class="design_api_preview_inner">
						<div id="preview_front">
							<div class="front_print">

							</div>
						</div>
						<div id="preview_back">
							<div class="back_print">

							</div>
						</div>
					</div>
				</div>
				<!--=============================================================-->
				<!--======================view start=======================================-->

				<div id='view_mode'>
					<div  class="mode">
						<span class="mode_img_wrap">
							<img id="o_front" class="o_front_designer" data-frontimg="" src="<?php echo $first_designer_variation_front; ?>" width="100%" height="80%" />
						</span>
						<p><?php echo __('Front', 'woo-shirt-product-designer'); ?></p>
					</div>
					<div  class="mode">
						<span class="mode_img_wrap">
							<img id="o_back" src="<?php echo $first_designer_variation_back; ?>" width="100%" height="80%" />
						</span>
						<p><?php echo __('Back', 'woo-shirt-product-designer'); ?></p>
					</div>
					<div class="mode designer_hide">
						<span class="mode_img_wrap">
							<i class="fa fa-binoculars fa-4x preview_images" id="preview_images" data-productid="<?php echo get_the_ID(); ?>" data-key="<?php echo session_id(); ?>" data-toggle="modal" data-target=".woo-designer-preview-modal"></i>
						</span>
					</div>
				</div>
				<!--=====================View Ends========================================-->
				<div id="overview" class="designer_overview">
				<div class="designer_overview_overlay"></div>
					<div class="designer_overview_inner">

						<div id="designer_overview_content">
						
						<div id="designer_calculator_groundprice">
						<b><?php echo __('Base price', 'woo-shirt-product-designer'); ?>:</b>
						<div class="designer_calculator_groundprice_value"><?php echo wc_price(0); ?></div>
						</div>
						

					<?php if(get_post_meta(get_the_ID(), 'designer_allow_graphics', true )) { ?>
					<?php if(get_post_meta(get_the_ID(), 'designer_allow_graphics', true ) == "1") { ?>		
					
						<b><?php echo __('Graphics', 'woo-shirt-product-designer'); ?>:</b>
						<div id="designer_calculator">
						<?php
							if (isset($_SESSION['woo_designer_addons'])) {
								
								$addons_total = 0;
								
								foreach ($_SESSION['woo_designer_addons'] as $session_woo_designer_addons_key => $session_woo_designer_addons_value) {
									
									if ($session_woo_designer_addons_value['productid'] == get_the_ID()) {
										
										$addons_total = $addons_total+$session_woo_designer_addons_value['price'];
										
										?>
											
											<div class="" data-id="<?php echo $session_woo_designer_addons_value['productid']; ?>"><?php echo $session_woo_designer_addons_value['id']; ?>-<?php echo wc_price($session_woo_designer_addons_value['price']); ?></div>
										
										<?php
									
									}
								
								}
						?>
						
						<div class="woo_designer_addon_subtotal">
						
							<?php echo wc_price($addons_total); ?>
							
						</div>
						
						<?php } ?>
						
						</div>	
						
					<?php } ?>
					<?php } ?>
						

					<?php if(get_post_meta(get_the_ID(), 'designer_allow_text', true )) { ?>
					<?php if(get_post_meta(get_the_ID(), 'designer_allow_text', true ) == "1") { ?>
					
						<b><?php echo __('Texts', 'woo-shirt-product-designer'); ?>:</b>
						<div id="designer_calculator_texts">
							<div class="designer_calculator_texts_output"><?php echo wc_price(0); ?></div>
						</div>
						
						<div id="designer_calculator_total">
							<b><?php echo __('Total', 'woo-shirt-product-designer'); ?>:</b>
							<div class="designer_calculator_total_value"><?php echo wc_price(0); ?></div>
						</div>	
						
					<?php } ?>
					<?php } ?>
					
					</div>

					<button class="woo_designer_save_config"><span class="designer_btn_loader"></span><?php echo __('Save', 'woo-shirt-product-designer'); ?> <i class="fa fa-check"></i></button>
					
						<form action="" method="post" id="woo_designer_add_to_cart_form" style="display: none;">
							<input name="add-to-cart" type="hidden" value="<?php the_ID(); ?>" />
							<select name="size" class="woo_designer_size" required>
								<option value=""><?php echo __('Select size...', 'woo-shirt-product-designer'); ?></option>
								<option value="xs"><?php echo __('XS', 'woo-shirt-product-designer'); ?></option>
								<option value="s"><?php echo __('S', 'woo-shirt-product-designer'); ?></option>
								<option value="m"><?php echo __('M', 'woo-shirt-product-designer'); ?></option>
								<option value="l"><?php echo __('L', 'woo-shirt-product-designer'); ?></option>
								<option value="xl"><?php echo __('XL', 'woo-shirt-product-designer'); ?></option>
								<option value="xxl"><?php echo __('XXL', 'woo-shirt-product-designer'); ?></option>
							</select>
							<input name="quantity" type="number" value="1" min="1" class="woo_designer_quantity"/>
							<input type="hidden" name="woo_designer_front_img_url" class="woo_designer_cart_front_img_url"/>
							<input type="hidden" name="woo_designer_back_img_url" class="woo_designer_cart_back_img_url"/>
							<input type="hidden" name="woo_designer_variation" class="woo_designer_variation"/>
							<input type="hidden" name="woo_designer_front_preview" class="woo_designer_front_preview"/>
							<input type="hidden" name="woo_designer_back_preview" class="woo_designer_back_preview"/>
							<button type="submit" class="btn submit woo_designer_add_to_cart" data-key="<?php echo session_id(); ?>" data-productid="<?php echo get_the_ID(); ?>" ><?php echo __('Buy'); ?> <i class="fa  fa-shopping-cart"></i></button>
						</form>
					
					</div>
				</div>
			</div>

			<div class="layer designer_modal_preview">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close close_img" data-dismiss="modal">
							<span aria-hidden="true">&times;</span><span class="sr-only close_img"></span>
						</button>
						<h4 class="modal-title"><?php echo __('Preview', 'woo-shirt-product-designer'); ?></h4>
					</div>
					<div class="modal-body">
						<div id="image_reply"></div>
						<div class="modal-footer">
							<div class="row">
									<div class="col-md-12">
										<button type="button" class="btn btn-default close_img" data-dismiss="modal">
											<?php echo __('Close', 'woo-shirt-product-designer'); ?>
										</button>
									</div>
							</div>
						</div>
					</div>

				</div>
			</div>

		</div>



		<?php
		}	 

	}
	 
	public static function spdfw_load_scripts() {
		
		if (class_exists('WooCommerce')) {
			if (is_product()) {
				global $post;
				$post_id = $post->ID;
				$product = wc_get_product($post_id);
				$type    = $product->get_type();	
						
				if( $type == "woodesigner" ){

					wp_enqueue_script( 'wspdfw_html2canvas', plugin_dir_url(__FILE__) . '/core/designer/tdesignAPI/js/html2canvas.js');
					
					wp_enqueue_script( 'spdfw_jqueryform', plugin_dir_url(__FILE__) . '/core/designer/tdesignAPI/js/jquery.form.js');
						
					wp_enqueue_script( 'jquery-ui-widget' );
					
					wp_enqueue_script( 'jquery-ui-mouse' );
					
					wp_enqueue_script( 'jquery-ui-draggable' );
					
					wp_enqueue_script( 'jquery-ui-resizable' );
					
					wp_enqueue_script( 'jquery-touch-punch' );
					
					$parms = array(
						'plugindir' => plugin_dir_url(__FILE__),
						'ajaxurl' => admin_url('admin-ajax.php'),
						'deletetext' => __('Do you really want to delete?', 'woo-shirt-product-designer'),
						'errortext' => __('An error has occurred. The record was not deleted.', 'woo-shirt-product-designer'),
						'chooseimage' => __('Select image', 'woo-shirt-product-designer'),
						'pricetext' => __('Price', 'woo-shirt-product-designer'),
						'imagetext' => __('Image', 'woo-shirt-product-designer'),
						'colortext' => __('Color', 'woo-shirt-product-designer'),
						'imagefronttext' => __('Image Front', 'woo-shirt-product-designer'),
						'imagebacktext' => __('Image Back', 'woo-shirt-product-designer'),
						'insertimage' => __('Select image', 'woo-shirt-product-designer')
					);					

					wp_register_script('spdfw_mainapp', plugin_dir_url(__FILE__).'/core/designer/tdesignAPI/js/mainapp.js');
					
					wp_localize_script('spdfw_mainapp', 'woodesignerparms', $parms); 
					
					wp_enqueue_script('spdfw_mainapp');					
							
					wp_enqueue_style( 'spdfw_jquery_ui', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
							
					wp_enqueue_style( 'spdfw_main_css', plugin_dir_url(__FILE__) . '/core/designer/tdesignAPI/css/jquery-ui.css' );
					
					wp_enqueue_style( 'spdfw_fontawesome', plugin_dir_url(__FILE__) . '/core/designer/tdesignAPI/css/api.css' );
					
					wp_enqueue_style( 'spdfw_gfonts', 'https://fonts.googleapis.com/css?family=Nosifer|League+Script|Yellowtail|Permanent+Marker|Codystar|Eater|Molle:400italic|Snowburst+One|Shojumaru|Frijole|Gloria+Hallelujah|Calligraffitti|Tangerine|Monofett|Monoton|Arbutus|Chewy|Playball|Black+Ops+One|Rock+Salt|Pinyon+Script|Orbitron|Sacramento|Sancreek|Kranky|UnifrakturMaguntia|Creepster|Pirata+One|Seaweed+Script|Miltonian|Herr+Von+Muellerhoff|Rye|Jacques+Francois+Shadow|Montserrat+Subrayada|Akronim|Faster+One|Megrim|Cedarville+Cursive|Ewert|Plaster' );

					$designer_inline_script = '
					
						function changeval() {
							$total = parseInt($("#small").val()) + parseInt($("#medium").val()) + parseInt($("#large").val()) + parseInt($("#xlarge").val()) + parseInt($("#xxlarge").val());
							//alert($total);
							jQuery(\'.small\').val($("#small").val());
							jQuery(\'.medium\').val($("#medium").val());
							jQuery(\'.large\').val($("#large").val());
							jQuery(\'.xlarge\').val($("#xlarge").val());
							jQuery(\'.xxlarge\').val($("#xxlarge").val());
							jQuery(\'.total\').val($total);
						}
						function changeval2() {
							$total = parseInt($("#small2").val()) + parseInt($("#medium2").val()) + parseInt($("#large2").val()) + parseInt($("#xlarge2").val()) + parseInt($("#xxlarge2").val());
							//alert($total);
							jQuery(\'.small\').val($("#small2").val());
							jQuery(\'.medium\').val($("#medium2").val());
							jQuery(\'.large\').val($("#large2").val());
							jQuery(\'.xlarge\').val($("#xlarge2").val());
							jQuery(\'.xxlarge\').val($("#xxlarge2").val());
							jQuery(\'.total\').val($total);
						}
						function b() {
							jQuery(\'#custom_text\').toggleClass(\'bold_text\');
							jQuery("#bold_button").toggleClass(\'box-shadow\', \'0 0 10px inset #3c3c3c\');
						}
						function i() {
							jQuery(\'#custom_text\').toggleClass(\'italic_text\');
						}
						function changeFont(_name) {
							jQuery(\'#custom_text\').css("font-family", _name);
						}
						function changeFontSize(_size) {
							jQuery(\'#custom_text\').css("font-size", _size);
						}
						function changeColor(_color) {
							jQuery(\'#custom_text\').css("color", _color);
						}
						function readURL(input) {
								if (input.files && input.files[0]) {
									var reader = new FileReader();            
									reader.onload = function (e) {
							
										jQuery("."+$y_pos+"_print").append("<div id=\'c_icon"+($custom_img)+"\' class=\'new_icon\'><span class=\'delete_icon\' onClick=\'delete_icons(this);\' ></span><img src=\'#\' id=\'c_img"+$custom_img+"\' width=\'100%\' height=\'100%\' /></div>");
										jQuery( "#c_icon"+($custom_img)+"" ).draggable({ containment: "parent" });
										jQuery( "#c_icon"+($custom_img)+"" ).resizable({
											maxHeight: 480,
											maxWidth: 450,
											minHeight: 60,
											minWidth: 60
										});		
									
									
									jQuery("#c_img"+($custom_img)+"").attr(\'src\', e.target.result);
									++$custom_img;
									};
									reader.readAsDataURL(input.files[0]);
								}
						}	
						jQuery("#imgInp").change(function() {
							readURL(this);
						});			
					';

					wp_register_script( 'spdfw_designer_functions', '' );
					wp_enqueue_script( 'spdfw_designer_functions' );
					wp_add_inline_script( 'spdfw_designer_functions', $designer_inline_script );
				
				}
			}
		}	
	}	
}

spdfw_frontend::init();

