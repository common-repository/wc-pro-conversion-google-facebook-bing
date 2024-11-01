<?php



if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly

}

/**

 * Category

 * @class    Wc_Category_Conversion_Code

 * @category Class

*/

class Wc_Category_Conversion_Code{

	

	/**

	 * Add 3 Textareas to the Add New Product Category page.

	 *

	 * For adding a conversion code to the WordPress admin when

	 * creating new product categories in WooCommerce.

	 *

	*/

	function wc_cat_cn_code_add_fields() {

		wp_nonce_field( basename( __FILE__ ), 'wc_cat_cn_code_add_fields_nonce' );

	?>

    	<div class="form-field">

        	<input type="checkbox" name="add_cn_code" id="add_cn_code">

            <span for="wc-cat-cn-gcode"><?php _e( 'Add Conversion Code', 'wc-cat-cn-code' ); ?></span>

        </div>

        <div id="cn_code_fields">

            <div class="form-field">

                <label for="wc-cat-cn-gcode"><?php esc_html_e( 'Google Conversion Code', 'wc-cat-cn-code' ); ?></label>

                <textarea name="wc_cat_cn_gcode" id="wc_cat_cn_gcode" rows="5" cols="40"></textarea>

                <p class="description"><?php esc_html_e( 'Google tracking conversion sript ', 'wc-cat-cn-code' ); ?></p>

            </div>

            <div class="form-field">

                <label for="wc-cat-cn-bcode"><?php esc_html_e( 'Bing Conversion Code', 'wc-cat-cn-code' ); ?></label>

                <textarea name="wc_cat_cn_bcode" id="wc_cat_cn_bcode" rows="5" cols="40"></textarea>

                <p class="description"><?php esc_html_e( 'Bing tracking conversion sript', 'wc-cat-cn-code' ); ?></p>

            </div>

            <div class="form-field">

                <label for="wc-cat-cn-ycode"><?php esc_html_e( 'Facebook Conversion Code', 'wc-cat-cn-code' ); ?></label>

                <textarea name="wc_cat_cn_ycode" id="wc_cat_cn_ycode" rows="5" cols="40"></textarea>

                <p class="description"><?php esc_html_e( 'Facebook tracking conversion sript', 'wc-cat-cn-code' ); ?></p>

            </div>

        </div>

       

	<?php

	}

	

	/**

	 * Save Product Category With conversion codes

	 * 

	 * @param  int $term_id The term ID of the term to update.

	*/

	

	function wc_cat_cn_code_save_fields( $term_id ) {

		

		if ( ! isset( $_POST['wc_cat_cn_code_add_fields_nonce'] ) || ! wp_verify_nonce( $_POST['wc_cat_cn_code_add_fields_nonce'], basename( __FILE__ ) ) ) {

			return;		

		}

		

		if(isset( $_POST['wc_cat_cn_code_add_fields_nonce'] )){		

			// NEW DATA

			$add_code = $_POST['add_cn_code']; // check box value

			$cat_cn_code = array();			

			$gcode	= wc_cat_cn_sanitize_script($_POST['wc_cat_cn_gcode']);

			$bcode	= wc_cat_cn_sanitize_script($_POST['wc_cat_cn_bcode']);

			$ycode	= wc_cat_cn_sanitize_script($_POST['wc_cat_cn_ycode']);			

			

			if(!empty($gcode)) $cat_cn_code['_cn_gcode'] = $gcode;

			if(!empty($bcode)) $cat_cn_code['_cn_bcode'] = $bcode;

			if(!empty($ycode)) $cat_cn_code['_cn_ycode'] = $ycode;

			

			if($add_code){

				if( !empty($cat_cn_code) ){		

					update_woocommerce_term_meta( $term_id, '_cat_cn_code', $cat_cn_code);		

				}

			}else{

				delete_woocommerce_term_meta( $term_id, '_cat_cn_code' );

			}

		}

		

	}

	/**

	 * Html fields edit Product Category With conversion codes

	 * @param Term Object with term id

	 * @param  Taxonomy = product_cat

	*/

	function wc_cat_cn_code_edit_fields( $term, $taxonomy ) {
		
		

		wp_nonce_field( basename( __FILE__ ), 'wc_cat_cn_code_add_fields_nonce' );

		// get category conversion codes

		$cat_cn_code = get_woocommerce_term_meta( $term->term_id, '_cat_cn_code');	

		// hide fields if there is no conversion codes	

		$display = '';

		if(!$cat_cn_code){

			$display = 'style="display: none;"';

		}	

		if($cat_cn_code):

			//foreach( $cat_cn_codes as $cat_cn_code):

				if (isset($cat_cn_code['_cn_gcode'])) $gcode = $cat_cn_code['_cn_gcode'];

				if (isset($cat_cn_code['_cn_ycode'])) $ycode = $cat_cn_code['_cn_ycode'];

				if (isset($cat_cn_code['_cn_bcode'])) $bcode = $cat_cn_code['_cn_bcode'];

			//endforeach;

		endif;

	?>

        <tr class="form-field term-add-cn-code">

            <th scope="row"><label for="feature-group"><?php _e( 'Add Conversion Code', 'wc-cat-cn-code' ); ?></label></th>

            <td><input type="checkbox" name="add_cn_code" id="edit_cn_code" <?php if($cat_cn_code){ echo "checked='checked'"; }?>></td>

        </tr>

        <script>

			jQuery(document).ready(function(e) {

                // check box checked event in edit category page.

				jQuery("#edit_cn_code").click(function(e) {

					if(jQuery(this).is(':checked')){

						jQuery("tr.hide_cn_code").show();

					}else{

						jQuery("tr.hide_cn_code").hide();

					}   

				});

            });

		</script>



        <tr class="form-field wc-cat-cn-gcode hide_cn_code" <?php echo $display; ?>>

            <th scope="row"><label for="gcode"><?php _e( 'Google Conversion Code', 'wc-cat-cn-code' ); ?></label></th>

            <td><textarea name="wc_cat_cn_gcode" id="wc_cat_cn_gcode" rows="5" cols="40"><?php  echo strip_slashes_script($gcode);   ?></textarea></td>

        </tr>

        <tr class="form-field wc-cat-cn-bcode hide_cn_code" <?php echo $display; ?>>               

            <th scope="row"><label for="bcode"><?php _e( 'Bing Conversion Code', 'wc-cat-cn-code' ); ?></label></th>

            <td><textarea name="wc_cat_cn_bcode" id="wc_cat_cn_bcode" rows="5" cols="40"><?php if(!empty($bcode)) echo strip_slashes_script($bcode);  ?></textarea></td>

        </tr>

        <tr class="form-field wc-cat-cn-ycode hide_cn_code" <?php echo $display; ?>>            

            <th scope="row"><label for="ycode"><?php _e( 'Facebook Conversion Code', 'wc-cat-cn-code' ); ?></label></th>

            <td>

            <textarea name="wc_cat_cn_ycode" id="wc_cat_cn_ycode" rows="5" cols="40"><?php if(!empty($ycode)) echo strip_slashes_script($ycode);  ?></textarea>

            </td>               

        </tr>   

	<?php
	
	

	}

	

	

}



?>