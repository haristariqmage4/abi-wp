jQuery(document).ready(function($) {
	jQuery(document.body).on('adding_to_cart', function(e,button,data = Array()){
			  id = button.attr('data-product_id');
			  price = jQuery('#custom_price_field_'+id).val();
			  nonce = jQuery('#elex_custom_field_nonce_'+id).val();
			  data.custom_price = price;
			  data.elex_wfp_custom_price_field_nonce = nonce;
				
return data;
});
   

});