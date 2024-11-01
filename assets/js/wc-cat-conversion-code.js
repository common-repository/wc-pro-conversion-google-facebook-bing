// custom script
jQuery(document).ready(function(e) {
    
	// checkbox checked event in add category page
	jQuery("#add_cn_code").click(function(e) {
		if(jQuery(this).is(':checked')){
			jQuery("#cn_code_fields").show();
		}else{
			jQuery("#cn_code_fields").hide();
		}   
    });
	
});