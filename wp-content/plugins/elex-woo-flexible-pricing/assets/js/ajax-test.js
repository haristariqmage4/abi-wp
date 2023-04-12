jQuery(document).ready(function($) {
          
	$('input.variation_id').change( function(){
	if( '' !== $('input.variation_id').val() ) {
		var var_id = $('input.variation_id').val();
		
				var data = {
					action: 'test_response',
					_ajax_nonce: the_ajax_script.elex_wfp_variation_nonce_token,
					var_id: var_id,
				};
				// the_ajax_script.ajaxurl is a variable that will contain the url to the ajax processing file
				 $.post(the_ajax_script.ajaxurl, data, function(response) {
					response = JSON.parse(response)
					 if (response.general_flag === 'yes' ) {
						
						 jQuery('.woocommerce-variation-price').find('.elex-set-min-price').remove();
							if(response['value']==''){
								response['value'] = 0;
							}
						 jQuery('.woocommerce-variation-price').append('<p></p>' + '<div class="wrap-validation elex-set-min-price" >' +
							'<label class="custom-min-price-validation" for="custom_price_field_variation"> ' + response.label + ' (' + response.currency_symbol +')' + '</label>' +
							'<input type="number" step="any" min="0" class="custom_price_field_variation_'+response['var_id']+'" value="' +response['value'] + '" id="custom_price_field_variation_'+response['var_id']+'" name="custom_price_field_variation_'+response['var_id']+'"  />' +
							'<small class="description_product">*'+  response['desc'] +'</small></div>' ) 
							}
					
					 					
				 });
				 return false;
		   
		};
	});
});