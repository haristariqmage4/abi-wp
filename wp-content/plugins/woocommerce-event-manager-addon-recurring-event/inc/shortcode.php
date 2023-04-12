<?php
add_shortcode( 'event-list-recurring', 'mep_recurring_event_list' );
function mep_recurring_event_list($atts, $content=null){
		$defaults = array(
			"cat"					=> "0",
			"org"					=> "0",
			"style"					=> "grid",
			"column"                => 2,
			"cat-filter"			=> "no",
			"org-filter"			=> "no",
			"show"					=> "-1",
			"pagination"			=> "no",
			'sort'                  => 'ASC'    
		);
		$params 					= shortcode_atts($defaults, $atts);
		$cat						= $params['cat'];
		$org						= $params['org'];
		$style						= $params['style'];
		$column                     = $params['column'];		
		$cat_f						= $params['cat-filter'];
		$org_f						= $params['org-filter'];
		$show						= $params['show'];
		$pagination					= $params['pagination'];
		$sort					    = $params['sort'];
		$event_expire_on 			= mep_get_option( 'mep_event_expire_on_datetime', 'general_setting_sec', 'mep_event_start_date');
		$flex_column    = $column;
		    $mage_div_count = 0;
		        $main_div       = $pagination == 'carousal' ? '<div class="mage_grid_box owl-theme owl-carousel"  id="mep-carousel' . $cid . '">' : '<div class="mage_grid_box">';
ob_start();
?>
<div class='mep_event_list'>
        <?php if ($cat_f == 'yes') { 
            /**
             * This is the hook where category filter lists are fired from inc/template-parts/event_list_tax_name_list.php File
             */
            do_action('mep_event_list_cat_names');
         }
        if ($org_f == 'yes') { 
            /**
             * This is the hook where Organization filter lists are fired from inc/template-parts/event_list_tax_name_list.php File
             */
            do_action('mep_event_list_org_names');        
         } ?>

<div class="mep_event_list_sec">
<?php
$now = current_time('Y-m-d H:i:s');

$show_price = mep_get_option( 'mep_event_price_show', 'general_setting_sec', 'yes');
$show_price_label = mep_get_option( 'event-price-label', 'general_setting_sec', 'Price Starts from:');
$paged = get_query_var("paged")?get_query_var("paged"):1;

if($cat>0){
     $args_search_qqq = array (
                     'post_type'        => array( 'mep_events' ),
                     'paged'             => $paged,
                     'posts_per_page'   => $show,
                     'order'             => $sort,
                     'orderby'           => 'meta_value',
                     'meta_key'          => 'event_start_datetime',
                      'tax_query'       => array(
						array(
							'taxonomy'  => 'mep_cat',
							'field'     => 'term_id',
							 'terms'     => $cat
							)
                        )
                );
 }
elseif($org>0){
     $args_search_qqq = array (
                     'post_type'        => array( 'mep_events' ),
                     'posts_per_page'   => $show,
                     'paged'             => $paged,
                     'order'             => $sort,
                     'orderby'           => 'meta_value',
                     'meta_key'          => 'event_start_datetime', 
                     'tax_query'       => array(
						array(
							'taxonomy'  => 'mep_org',
							'field'     => 'term_id',
							'terms'     => $org
						)
                    )

                );
}
 else{
     $args_search_qqq = array (
                     'post_type'         => array( 'mep_events' ),
                     'paged'             => $paged,
                     'posts_per_page'    => $show,
                     'order'             => $sort,
                     'orderby'           => 'meta_value',
                     'meta_key'          => 'event_start_datetime'

                ); 	
 }
echo $main_div;
	$loop = new WP_Query( $args_search_qqq );
	while ($loop->have_posts()) {
	$loop->the_post(); 
	$event_meta 	= get_post_custom(get_the_id());
    $author_terms 	= get_the_terms(get_the_id(), 'mep_org');
	$time 			= strtotime($event_meta['event_start_date'][0].' '.$event_meta['event_start_time'][0]);
  	$newformat 		= date_i18n('Y-m-d H:i:s',$time);
 	$tt     		= get_the_terms( get_the_id(), 'mep_cat');
 	$torg   		= get_the_terms( get_the_id(), 'mep_org'); 	 	
    $org_class 		= mep_get_term_as_class(get_the_id(),'mep_org');
    $cat_class 		= mep_get_term_as_class(get_the_id(),'mep_cat'); 	 	
    $available_seat = mep_get_total_available_seat(get_the_id(), $event_meta);
    $recurring = get_post_meta(get_the_id(), 'mep_enable_recurring', true) ? get_post_meta(get_the_id(), 'mep_enable_recurring', true) : 'no';


                if ($style == 'grid') {
                    if ($column == 2) {
                        $columnNumber = 'two_column';
                    } elseif ($column == 3) {
                        $columnNumber = 'three_column';
                    } elseif ($column == 4) {
                        $columnNumber = 'four_column';
                    } else {
                        $columnNumber = 'two_column';
                    }
                } else {
                    $columnNumber = 'one_column';
                }




$event_more_dates    = get_post_meta(get_the_id(),'mep_event_more_date',true) ? get_post_meta(get_the_id(),'mep_event_more_date',true) : [];
if(sizeof($event_more_dates) > 0 && ($recurring == 'yes' || $recurring == 'no')){
    $md = end($event_more_dates);
    $more_date = $md['event_more_start_date'].' '.$md['event_more_start_time'];
}elseif(sizeof($event_more_dates) > 0 && $recurring == 'everyday'){
    $more_date    = get_post_meta(get_the_id(),'event_end_datetime',true);
}else{
    $more_date    = get_post_meta(get_the_id(),'event_end_datetime',true);
}

        $newformat = date('Y-m-d H:i:s',strtotime($more_date));
if(strtotime(current_time('Y-m-d H:i:s')) < strtotime($newformat)){        
        do_action('mep_event_list_shortcode',get_the_id(),$columnNumber,$style);  
      }
    // }
}
wp_reset_postdata();
if($pagination=='yes'){  mep_event_pagination($loop->max_num_pages);   } ?>
</div>
</div>
</div>
<script>
jQuery(document).ready( function() {
    var containerEl = document.querySelector('.mep_event_list_sec');
    var mixer = mixitup(containerEl);
});
</script>
<?php
$content = ob_get_clean();
return $content;
}
