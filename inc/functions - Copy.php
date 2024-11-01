<?php

/**
 * Sanitize the different google scripts
 *
 * @param  string $details The existing script field.
 * @return string          The sanitized script field
*/
function wc_cat_cn_sanitize_script( $details ) {
	return wp_kses_post( $details );
}

function array_has_duplicates($array) {
   // 
   return count($array) !== count(array_unique($array));
}

function get_conversion_code(){

	if(is_product()){
		global $product;
		$product_id	= $product->id;	
		$key	= '_product_cn_code';
		$product_script = get_post_meta( $product_id, $key );
		if(count( $product_script ) >0 ) return $product_script; // code for specific product
		// code assign to any category
		if(count( $product_script ) == 0){
			$terms = get_the_terms( $product_id, 'product_cat' );
			$key	 = '_cat_cn_code';
			foreach ($terms as $term):
				$product_cat_id = $term->term_id;
				$cat_script = get_term_meta( $product_cat_id, $key);
				if(count( $cat_script ) > 0){
					return $cat_script;
					break;
				}								
			endforeach; // endforeach
		}
	}

	if( is_product_category() ){
		$cat 	= get_queried_object();		
		$cat_id = $cat->term_id; 	 // child category				
		$parent_id = $cat->parent;	// parent category
		$key	 = '_cat_cn_code';
		$cat_script = get_term_meta( $cat_id, $key ); 
		if( count($cat_script) > 0) return $cat_script; // return code of current category
		//return $parent_id;
		if($cat->parent > 0 && count($cat_script) == 0){ // if category has parent category
			// check code of parent category
			$cat_script = get_term_meta( $parent_id, $key);
			if( count($cat_script) > 0) return $cat_script; // return parent category code
		}
	}
	$default_script = get_option('_woocommerce_conversion_default_script');
	
	if(!empty($default_script)) return $default_script;
	
}

function display_conversion_code(){
	$scripts = get_conversion_code();	
	//echo $scripts;
	if( is_assoc($scripts) ){
		$gcode = $scripts['_cn_gcode'];
		$ycode = $scripts['_cn_ycode'];
		$bcode = $scripts['_cn_bcode'];
	}else{
		foreach( $scripts as $script ):
			$gcode = $script['_cn_gcode'];
			$ycode = $script['_cn_ycode'];
			$bcode = $script['_cn_bcode'];
		endforeach;
	}
	
	echo esc_html($gcode);
}
add_action('wp_head', 'display_conversion_code');

function is_assoc($array){
   $keys = array_keys($array);
   return $keys !== array_keys($keys);
}


?>