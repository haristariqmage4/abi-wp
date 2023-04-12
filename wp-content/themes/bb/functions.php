<?php


add_image_size( 'event-desc', 600, 600, true );
add_image_size( 'wp-post-image', 600, 600, true );

/** Add Single Events Page Outer Custom Wrap OPENING TAG */

function sme_custom_wrap_open(){
    ?>
        <div class="sme-single-event-wrap">
    <?php
}

add_action( 'mep_event_single_page_after_header', 'sme_custom_wrap_open', 9);

/** Add Single Events Page Outer Custom Wrap CLOSING TAG */

function sme_custom_wrap_close(){
    ?>
        </div>
    <?php
}

add_action( 'mep_event_single_template_end', 'sme_custom_wrap_close', 9);



/** Add header to Single Events page (mage-eventpress) **/

function add_cf_header(){
    global $post;
    $header_image='';
?>
    <?php if(get_field('header_image',$post)!='') {
        $header_image=get_field('header_image',$post);
    }else{
        
        $header_image=get_stylesheet_directory_uri().'/assets/img/classes-default.jpg';
    } ?>
    
    <div class="custom-header bg-pop-out">
        <div class="bpo-inner" style="background-image: url(<?php echo $header_image;?>);"></div> 
    </div>
<?php
}

add_action( 'mep_event_single_page_after_header', 'add_cf_header', 10);


/**
 * Add Custom field info in a hidden field to Single Events Page for later usage by js
 */

function add_custom(){
    global $post;
    $is_type_dd=(has_term("dinners-in-the-dark","mep_cat",$post));
    if($is_type_dd){
        ?>
            <div class="cf-data-sme" style="display:none;">
                <?php echo get_field('event_intro',get_queried_object_id()); ?>
            </div>
        <?php
    }

?>
    
<?php
}

add_action( 'mep_event_single_page_after_header', 'add_custom', 11);

function change_design_mep(){

    global $post;
    $is_type_dd=(has_term("dinners-in-the-dark","mep_cat",$post));
    $is_type_cc=(has_term("cooking-classes","mep_cat",$post));
    
    if(true){ ?>
        <script>
            jQuery(document).ready(function(){
               
                jQuery('.cf-data-sme').insertAfter('.mep-default-title').show();
                
                jQuery('.mep-default-feature-cart-sec').wrap(`<div class="mep-cart-wrap"></div>`);
                jQuery('.mep-default-feature-cart-sec').appendTo('.mep-cart-wrap');
                jQuery('.mep-default-feature-date-location').after('<div class="mep-product-info"></div>');
                <?php if($is_type_dd){?>
                    jQuery('.mep-default-feature-date').hide();
                <?php } ?>
                jQuery('.mep-default-feature-image,.mep-default-feature-content').appendTo('.mep-product-info');
                
                jQuery('.mep-default-title,.cf-data-sme,.mep-cart-wrap').addClass('toggle-effect');
                jQuery('.mep-product-info').addClass('toggle-stagger-children');
                jQuery('.mep-default-feature-date-location').addClass('toggle-effect');
            });
        </script>
        <?php if($is_type_cc){ ?>
        <style>
            .mep-default-feature-date-location{
                flex-direction: column;
            }

            .mep-default-feature-time{
                background:initial !important;
            }

            .df-ico{
                display:none !important;
            }


        </style>
        <?php } ?>
    <?php } ?>
    <?php
}

add_action('mep_event_single_template_end','change_design_mep',10);





add_theme_support('woocommerce');


/**
 * single-product.php -------------------------------------------------------------------
 */




/**
 * Add custom header to single-product
 */

add_action( 'woocommerce_before_single_product_summary', 'add_cf_header',18);

/**
 * Remove Breadcrumb
 */
function remove_bc(){
    remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20 );
}

add_action( 'woocommerce_before_main_content','remove_bc', 19 );

/**
 * Remove Sidebar
 */
function remove_sb(){
    remove_action( 'woocommerce_sidebar','woocommerce_get_sidebar', 10);
}

add_action( 'woocommerce_sidebar','remove_sb', 9 );


/**
 * Add grid-wrapper
 */

function add_open_gw(){
?>
    <div class="single-product-gw toggle-stagger-children">
<?php
}

add_action( 'woocommerce_before_single_product_summary', 'add_open_gw',19);

function add_close_gw(){
?>
    </div>
<?php
}

add_action( 'woocommerce_after_single_product_summary', 'add_close_gw',9);

/**
 * Remove Product Meta
 */

function remove_meta(){
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
}

add_action( 'woocommerce_single_product_summary', 'remove_meta', 39);


/**
 * --------------------------------------------------------------------------------------
 */

// function tutsplus_remove_short_desc_product() {
     
//     remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
//     add_action( 'woocommerce_single_product_summary', 'the_content', 20 );
     
// }
// add_action( 'init', 'tutsplus_remove_short_desc_product' );


// function tutsplus_remove_product_long_desc( $tabs ) {
 
//     unset( $tabs['description'] );
//     return $tabs;
// }
// add_filter( 'woocommerce_product_tabs', 'tutsplus_remove_product_long_desc', 98 );

add_action( 'woocommerce_after_shop_loop_item', 'remove_add_to_cart_buttons', 1 );

    function remove_add_to_cart_buttons() {
      if( is_product_category() || is_shop()) { 
        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
      }
    }

// add_filter( 'woocommerce_single_product_zoom_enabled', '__return_false' );
// add_filter( 'woocommerce_single_product_lightbox_enabled', '__return_false' );

// remove_theme_support( 'wc-product-gallery-zoom' );
// remove_theme_support( 'wc-product-gallery-lightbox' );

add_action( 'after_setup_theme', 'yourtheme_setup' );
 
function yourtheme_setup() {
    
    add_theme_support( 'wc-product-gallery-lightbox' );


}


/**
 * Add Menus Here
 */

register_nav_menus(
    array(
        'Main Menu' => __( 'Main Menu' )
    )
);

/**
 * Add Above Menu(s) to Timber Context
 */


function add_to_context( $context ) {
  
    $context['main_menu'] = new \Timber\Menu( 'Main Menu' );

    return $context;
}

add_filter( 'timber/context', 'add_to_context' );


/**
 * Generic Twig Menu Implementation
*/

function renderMenu($menu,$class){
    Timber::render('menu.twig', array(
            'menu_to_show' => $GLOBALS['CONTEXT'][$menu],
            'menu_class' => $class
        )
    );
}

/**
 * Initialize Global Namespace for Twig Use (stores twig context)
 */

$GLOBALS['CONTEXT'] = array();

/**
 * Add Post Thumbnail Support
 */
add_theme_support( 'post-thumbnails' );

/**
 * Useful Helper Functions 
 */

if( !function_exists('get_terms_by_post_type') ){

    function get_terms_by_post_type( $postType = 'post', $taxonomy = 'category'){

        
        
        $get_all_posts = get_posts( array(
            'post_type'     => esc_attr( $postType ),
            'post_status'   => 'publish',
            'numberposts'   => -1
        ) );

        if( !empty( $get_all_posts ) ){

           
            $post_terms = array();
            
            
            foreach( $get_all_posts as $all_posts ){

                $post_terms[] = get_the_terms( $all_posts->ID, esc_attr( $taxonomy ) );

            }

            
            $post_terms_array = array();

            
            foreach($post_terms as $new_arr){
                foreach($new_arr as $arr){

                    
                    $post_terms_array[] = array(
                        'name'      => $arr->name,
                        'term_id'   => $arr->term_id,
                        'slug'      => $arr->slug,
                        'url'       => get_term_link( $arr->term_id )
                    );
                }
            }

            
            $terms = array_unique($post_terms_array, SORT_REGULAR);

            
            return $terms;

        }

    }

}




/**
 * Put all custom styles and scripts in hear for queuing using wp_enqueue_stype and wp_enqueue_script
 */

function customScripts(){

    /**
     * Use $post_slug to conditionally add scripts on the basis of the page slug.
     */
    global $post;
    $post_slug = $post->post_name;



    // wp_enqueue_style(
	// 	'theme-options-style',
	// 	get_stylesheet_directory_uri() . '/themeOptions.php',
	// 	array(),
	// 	filemtime( get_stylesheet_directory() . '/themeOptions.php' )
    // );
    
    //Including fontawesome as we end up using it anyway!

    wp_enqueue_style(
		'fa-style',
		'https://pro.fontawesome.com/releases/v5.10.0/css/all.css'
		
    );

        wp_enqueue_style( 'slick-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.css' );
        wp_enqueue_style( 'slick-theme','https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.min.css');
        wp_enqueue_script( 'gsap-js', "https://scrollmagic.io/assets/js/lib/greensock/TweenMax.min.js", array("jquery"), null, true );
        wp_enqueue_script( 'slick-js','https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',array('jquery'), '', false );

        wp_enqueue_script( 'gsap','https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js',array('jquery'), '', false );

        wp_enqueue_script( 'gsap-st','https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/ScrollTrigger.min.js',array('jquery','gsap'), '', false );

    

}

// Update CSS within in Admin
function admin_style() {
  wp_enqueue_style('admin-styles', get_template_directory_uri().'/admin.css');
}
add_action('admin_enqueue_scripts', 'admin_style');


add_action( 'wp_enqueue_scripts', 'customScripts' );





/**
 * themeCss enqueues style.css and normalize.min.css. The style.css file has its filetime appended as version to make sure it's not cached when it has changed.
 */

function themeCss() {

    // style.css is just being used for WP's purpose. Don't put any actual css in it. Put it in customstyles.css (see below)

	wp_enqueue_style(
		'theme-style',
		get_stylesheet_directory_uri() . '/style.css'
    );
    
    //Add the dynamically generated css file from scss file. Needs this plugin installed and configured:
    //https://wordpress.org/plugins/wp-scss/

    wp_enqueue_style(
		'styles-custom',
        get_stylesheet_directory_uri()."/assets/css/compiled/customstyles.css"
		
		
    );

	

	wp_enqueue_style(
		'styles-normalize',
		"https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css"
		
		
	);

}

add_action( 'wp_enqueue_scripts', 'themeCss' );


/**
 * Removes ?ver= from all static asset files. Comment it when publishing
 */

function remove_css_js_version( $src ) {
    if( strpos( $src, '?ver=' ) ){
        $src = remove_query_arg( 'ver', $src );
    }
        
    //$src=add_query_arg( 'ver_t', rand(1,100), $src );
    return $src;
}
add_filter( 'style_loader_src', 'remove_css_js_version', 9999 );
add_filter( 'script_loader_src', 'remove_css_js_version', 9999 );

add_action('admin_footer', 'webroom_add_css_js_to_admin');
// add_action('wp_footer', 'webroom_add_css_js_to_admin');

function webroom_add_css_js_to_admin() {
  ?>
<script>
	jQuery(document).ready(function ($) {
		const myFunction = () => {
			Date.prototype.getWeek = function(dowOffset) {
				dowOffset = typeof(dowOffset) == 'number' ? dowOffset : 0; //default dowOffset to zero
				var newYear = new Date(this.getFullYear(), 0, 1);
				var day = newYear.getDay() - dowOffset; //the day of week the year begins on
				day = (day >= 0 ? day : day + 7);
				var daynum = Math.floor((this.getTime() - newYear.getTime() - (this.getTimezoneOffset() - newYear.getTimezoneOffset()) * 60000) / 86400000) + 1;
				var weeknum;
				//if the year starts before the middle of a week
				if (day < 1) {
					weeknum = Math.floor((daynum + day - 1) / 7) + 1;
					if (weeknum > 52) {
						nYear = new Date(this.getFullYear() + 1, 0, 1);
						nday = nYear.getDay() - dowOffset;
						nday = nday >= 0 ? nday : nday + 7;
						/*if the next year starts before the middle of
						  the week, it is week #1 of that year*/
						weeknum = nday < 1 ? 1 : 53;
					}
				} else {
					weeknum = Math.floor((daynum + day - 1) / 7);
				}
				return weeknum;
			};
			const setWeek = () => {
				let mon_year = jQuery('div#em_calendar .fc-toolbar.fc-header-toolbar .fc-center h2').text();
				let mon = mon_year.substring(0, mon_year.indexOf(' '));
				let year = mon_year.substring(mon_year.indexOf(' ') + 1);
				let date_spans = jQuery('tbody.fc-body .fc-content-skeleton span.fc-day-number');

				for (let i = 0; i < date_spans.length; i++) {
					mon_year2 = mon_year + ' ' + date_spans[i].textContent;

					let weekNo = new Date(mon_year2);
					weekNo_txt = weekNo.getWeek() + 0;
					//             date_spans[i].textContent = weekNo_txt + '.' + date_spans[i].textContent;
					date_spans[i].textContent = weekNo_txt + '.' + (weekNo.getDay() + 1);
				}
			};

			setWeek();
			
			jQuery('#em_calendar .fc-prev-button').attr('id', 'fc-prev-btn');
			jQuery('#em_calendar .fc-next-button').attr('id', 'fc-next-btn');
			
			let next_btn = document.getElementById('fc-next-btn');
			jQuery(document).on('click','#fc-next-btn',function(){
				jQuery('#em_calendar .fc-prev-button').attr('id', 'fc-prev-btn');
				jQuery('#em_calendar .fc-next-button').attr('id', 'fc-next-btn');  				
				setWeek();
			});
			
			let prev_btn = document.getElementById('fc-prev-btn');
			jQuery(document).on('click','#fc-prev-btn',function(){
				jQuery('#em_calendar .fc-next-button').attr('id', 'fc-next-btn');
				jQuery('#em_calendar .fc-prev-button').attr('id', 'fc-prev-btn');  				
				setWeek();
			});

		};
		setTimeout(myFunction, 8000);
	});
</script>
<?php
  
}