<?php



if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly

}

/**

 * Product

 * @class    Wc_Product_Conversion_Code

 * @product Class

*/

class Wc_Product_Conversion_Code{

	

	function __construct(){

		// action hook to add meta box in product page

		add_action( 'add_meta_boxes', array($this, 'wc_product_cn_add_metabox') );

		// save meta values

		add_action( 'save_post', array($this, 'wc_product_cn_save_meta'), 10, 3 );

	}

	/**

	 * Add Meta box in woocommerce add product page

	 * @ Conversion codes meta box for specific product

	*/

	public function wc_product_cn_add_metabox(){

		add_meta_box("prod-cn-code", "Specific Conversion Code", array($this,"wc_product_cn_metabox_callback"), "product", "advanced", "high", null);

	}

	

	/**

	 * callback function for metabox

	 * @ conversion code fields

	*/

	public function wc_product_cn_metabox_callback( $object ){

		wp_nonce_field( basename( __FILE__ ), 'wc_prod_cn_code_metabox_nonce' );

		$product_cn_codes = get_post_meta( $object->ID, '_product_cn_code');

		if($product_cn_codes):

			foreach( $product_cn_codes as $product_cn_code):

				if (isset($product_cn_code['_cn_gcode'])) $gcode = $product_cn_code['_cn_gcode'];

				if (isset($product_cn_code['_cn_ycode'])) $ycode = $product_cn_code['_cn_ycode'];

				if (isset($product_cn_code['_cn_bcode'])) $bcode = $product_cn_code['_cn_bcode'];

			endforeach;

		endif;

		if($product_cn_codes){

			$display = 'style="display: block;"';

		}else{

			$display = '';

		}	

	?>

    	<div class="product_cn_metabox">

            <div class="form-field">

                <input type="checkbox" name="add_cn_code" id="add_cn_code" <?php if($product_cn_codes){ echo "checked='checked'"; } ?>>

                <span for="wc-cat-cn-gcode"><?php _e( 'Add Specific Conversion Code', 'wc-cat-cn-code' ); ?></span>

            </div>

            <div id="cn_code_fields" <?php echo $display; ?>>

                <div class="form-field">

                    <label for="wc-cat-cn-gcode"><?php esc_html_e( 'Google Conversion Code', 'wc-cat-cn-code' ); ?></label>

                    <textarea name="wc_prod_cn_gcode" id="wc_prod_cn_gcode" rows="5" cols="40"><?php if(!empty($gcode)) echo strip_slashes_script($gcode); ?></textarea>

                    <p class="description"><?php esc_html_e( 'Google tracking conversion sript ', 'wc-cat-cn-code' ); ?></p>

                </div>

                <div class="form-field">

                    <label for="wc-cat-cn-bcode"><?php esc_html_e( 'Bing Conversion Code', 'wc-cat-cn-code' ); ?></label>

                    <textarea name="wc_prod_cn_bcode" id="wc_prod_cn_bcode" rows="5" cols="40"><?php if(!empty($bcode)) echo strip_slashes_script($bcode); ?></textarea>

                    <p class="description"><?php esc_html_e( 'Bing tracking conversion sript', 'wc-cat-cn-code' ); ?></p>

                </div>

                <div class="form-field">

                    <label for="wc-cat-cn-ycode"><?php esc_html_e( 'Facebook Conversion Code', 'wc-cat-cn-code' ); ?></label>

                    <textarea name="wc_prod_cn_ycode" id="wc_prod_cn_ycode" rows="5" cols="40"><?php if(!empty($ycode)) echo strip_slashes_script($ycode); ?></textarea>

                    <p class="description"><?php esc_html_e( 'Facebook tracking conversion sript', 'wc-cat-cn-code' ); ?></p>

                </div>

        	</div>

        </div>

    <?php	

	}	

	

	/**

	 * save meta values

	 * @param Post id

	 * @param post object

	 * @param update post if old post;

	*/	

	function wc_product_cn_save_meta( $post_id, $post, $update ){

		

		if ( ! isset( $_POST['wc_prod_cn_code_metabox_nonce'] ) || ! wp_verify_nonce( $_POST['wc_prod_cn_code_metabox_nonce'], basename( __FILE__ ) ) ) {

			return;		

		}

		

		if(isset( $_POST['wc_prod_cn_code_metabox_nonce'] )){		

			$add_code = $_POST['add_cn_code']; // check box value

			if($add_code){			

				$prod_cn_code = array();			

				$gcode	= wc_cat_cn_sanitize_script($_POST['wc_prod_cn_gcode']);

				$bcode	= wc_cat_cn_sanitize_script($_POST['wc_prod_cn_bcode']);

				$ycode	= wc_cat_cn_sanitize_script($_POST['wc_prod_cn_ycode']);

				if(!empty($gcode)) $prod_cn_code['_cn_gcode'] = $gcode;

				if(!empty($bcode)) $prod_cn_code['_cn_bcode'] = $bcode;

				if(!empty($ycode)) $prod_cn_code['_cn_ycode'] = $ycode;

				update_post_meta( $post_id, '_product_cn_code', $prod_cn_code );

			}else{

				delete_post_meta( $post_id, '_product_cn_code');

			}

		}

		

	}

	

}



?>