<?php



if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly

}

/**

 * Coversion code settings page

 * @class    Wc_Product_Conversion_Code

 * @product Class

*/

class Wc_Conversion_Code_Settings{

	

	

	public function cn_code_settings_callback(){

	

		if(isset($_POST['save_cn_code_settings'])){

			//$pr_res = $this->save_priority_settings($_POST);					

			$pr_res = true;

			if($pr_res){

				$this->save_display_options( $_POST );

				$this->save_default_scripts( $_POST );
				
				$this->save_thankyou_script( $_POST );

				echo '<div id="message" class="updated"><p><strong>Changes has been saved successfully.</strong></p></div>';

			}else{

				//echo '<div id="message" class="error"><p><strong>Error</strong> : Something went wrong ... please try again.</p></div>';

			}												

		}

		echo '<div class="wrap">';

			echo '<h3>Set Options to display code</h3>';

			//echo '<p>Set the priority for the conversion code scripts, Lower the number higher will be the priority.</p>';

			echo '<form method="post" action="" class="cat_settings_form">';

			//echo  $this->priority_code_fields();

			echo 	$this->code_display_option_fields();

			echo  '<h3>Set General Coverstion Code.</h3>';

			echo  '<p>Default scripts will be used for all pages.</p>';

			echo  $this->general_code_fields();
			
			echo  '<h3>Thank you page scripts.</h3>';
			
			echo  '<p>Below code will be added to checkout success page.</p>';
			
			echo $this->thankyou_fields();
			//$thankyou_script = get_option('_woocommerce_conversion_thankyou_script');
			//print_r( $thankyou_script );

			echo  '<input name="save_cn_code_settings" class="button-primary" type="submit" value="Save changes"/>';

			echo '</form>';

		echo '</div>';

	}

	

	public function code_display_option_fields(){

		$default_option = get_option('_wc_conversion_code_diplay_option');

		

		$dropdown = '<table class="form-table">

						<tbody>

							<tr valign="top">

								<th scope="row" class="titledesc"><label>Display Code</label></th>	

								<td>

								<select name="_conversion_code_display">';

								ob_start();

		$dropdown .=		'<option '.selected($default_option, "header").' name="header" value="header">Header</option>

							<option '.selected($default_option, "footer").' name="footer" value="footer">Footer</option>';

		$dropdown .= ob_get_clean();					

							

		$dropdown .= 		'</select>

								</td>

							</tr>

					</table>';	

		return $dropdown;			

	}

	

	public function general_code_fields(){

		$default_script = get_option('_woocommerce_conversion_default_script');

		if(!$default_script) $default_script = array('_cn_gcode'=>'', '_cn_ycode'=>'', '_cn_bcode'=>'');

		

		$html ='<table class="form-table">

				<tbody>

					<tr valign="top">

						<th scope="row" class="titledesc"><label>Google Conversion Code</label></th>

						<td><textarea name="wc_general_cn_gcode" rows="5" cols="40">'.strip_slashes_script($default_script['_cn_gcode']).'</textarea></td>

					</tr>

					<tr valign="top">

						<th scope="row" class="titledesc"><label>Facebook Conversion Code</label></th>

						<td><textarea name="wc_general_cn_ycode" rows="5" cols="40">'.strip_slashes_script($default_script['_cn_ycode']).'</textarea></td>

					</tr>

					<tr valign="top">

						<th scope="row" class="titledesc"><label>Bing Conversion Code</label></th>

						<td><textarea name="wc_general_cn_bcode" rows="5" cols="40">'.strip_slashes_script($default_script['_cn_bcode']).'</textarea></td>

					</tr>

				</tbody>

			  </table>';

		return $html;

	}

	

	public function priority_code_fields(){	

		$priority_settings = get_option('_woocommerce_coversion_code_priority');

		$html ='<table class="form-table priority-fields">

				<tbody>

					<tr valign="top">

						<th scope="row" class="titledesc"><label>Product Priority</label></th>

						<td><input type="number" name="wc_prod_cn_priority" class="" maxlength="3" min="1" value="'.$priority_settings['_product_cn_code'].'"><p class="description">Higher Priority</p></td>

					</tr>

					<tr valign="top">

						<th scope="row" class="titledesc"><label>Category Priority</label></th>

						<td><input type="number" name="wc_cat_cn_priority" class="" maxlength="3" min="1" value="'.$priority_settings['_cat_cn_code'].'"></td>

					</tr>

					<tr valign="top">

						<th scope="row" class="titledesc"><label>General Priority</label></th>

						<td><input type="number" name="wc_general_cn_priority" class="" maxlength="3" min="1" value="'.$priority_settings['_woocommerce_conversion_default_script'].'"></td>

					</tr>

				</tbody>

			  </table>';

		return $html;	  

    

	}

	

	public function save_priority_settings($post){

		

		if(isset($post['save_cn_code_settings'])){

			$priority_settings = array();

			$priority_settings['_product_cn_code']	= $post['wc_prod_cn_priority'];

			$priority_settings['_cat_cn_code']		= $post['wc_cat_cn_priority'];

			$priority_settings['_woocommerce_conversion_default_script']= $post['wc_general_cn_priority'];

			if(array_has_duplicates($priority_settings)) return false;				

			update_option( '_woocommerce_coversion_code_priority', $priority_settings );

			return true;

						

		}

		

	}

	

	public function save_default_scripts( $post ){

		if(isset($post['save_cn_code_settings'])){

			$default_script		= array();

			$default_script['_cn_gcode']	= wc_cat_cn_sanitize_script($post['wc_general_cn_gcode']);

			$default_script['_cn_ycode']	= wc_cat_cn_sanitize_script($post['wc_general_cn_ycode']);

			$default_script['_cn_bcode']	= wc_cat_cn_sanitize_script($post['wc_general_cn_bcode']);			

			$res = update_option( '_woocommerce_conversion_default_script', $default_script );

			return true;

			

		}

	}

	

	public function save_display_options( $post ){

		if(isset($post['save_cn_code_settings'])){

			$res = update_option( '_wc_conversion_code_diplay_option', $post['_conversion_code_display'] );

			return true;

		}

	}
	
	public function thankyou_fields(){
		
		$thankyou_script = get_option('_woocommerce_conversion_thankyou_script');
		
		
		
		$html ='<table class="form-table">

				<tbody>

					<tr valign="top">

						<th scope="row" class="titledesc"><label>Google Conversion Code</label></th>

						<td><textarea name="wc_thankyou_cn_gcode" rows="5" cols="40">'.strip_slashes_script($thankyou_script['_cn_gcode']).'</textarea></td>

					</tr>

					<tr valign="top">

						<th scope="row" class="titledesc"><label>Facebook Conversion Code</label></th>

						<td><textarea name="wc_thankyou_cn_ycode" rows="5" cols="40">'.strip_slashes_script($thankyou_script['_cn_ycode']).'</textarea></td>

					</tr>

					<tr valign="top">

						<th scope="row" class="titledesc"><label>Bing Conversion Code</label></th>

						<td><textarea name="wc_thankyou_cn_bcode" rows="5" cols="40">'.strip_slashes_script($thankyou_script['_cn_bcode']).'</textarea></td>

					</tr>

				</tbody>

			  </table>';
			  
			  return $html;
	}
	
	public function save_thankyou_script( $post){
		
		if(isset($post['save_cn_code_settings'])){
			
			$thankyou_script		= array();

			$thankyou_script['_cn_gcode']	= wc_cat_cn_sanitize_script($post['wc_thankyou_cn_gcode']);

			$thankyou_script['_cn_ycode']	= wc_cat_cn_sanitize_script($post['wc_thankyou_cn_ycode']);

			$thankyou_script['_cn_bcode']	= wc_cat_cn_sanitize_script($post['wc_thankyou_cn_bcode']);	
					
			if( !empty( $thankyou_script['_cn_gcode'] )  || !empty( $thankyou_script['_cn_ycode'] ) || $thankyou_script['_cn_bcode'] ){
				$res = update_option( '_woocommerce_conversion_thankyou_script', $thankyou_script );
			}else{
				delete_option( '_woocommerce_conversion_thankyou_script' );
			}

			return true;
		}
		
	}
	
	public function print_thankyou_script( $order_id ){
		
		$thankyou_script = get_option('_woocommerce_conversion_thankyou_script');
		
	}
	

	

}



?>