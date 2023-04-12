(function( $ ) {
	'use strict';
//   $( "#pg-woocommerce_purchases" ).tabs(); 
//   $("#pg-woocommerce_reviews").tabs();


setTimeout(
  function() 
  {
    //do something special
    
    if (window.location.href.indexOf("em_search") > -1) {
         
          
        var t ='#pg_group_events'; 
        $('li.pm-profile-tab a').removeClass('active');         
        $(this).addClass('active');
        $('.pg-profile-tab-content').hide();
        $(t).find('.pm-section-content:first').show();
        $('li.hideshow ul').hide();
        $(t).fadeIn('slow');
        return false;
  
     }
    
  }, 500);

     
         
   
})( jQuery );
 
    
