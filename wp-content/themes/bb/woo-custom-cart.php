<?php 
/* Template Name: Custom Cart */
get_header();

$GLOBALS['CONTEXT'] = Timber::context();
$GLOBALS['CONTEXT']['post'] = new Timber\Post();
$GLOBALS['CONTEXT']['woocommerce_cart']=do_shortcode('[woocommerce_cart]');

Timber::render('woo-custom-cart.twig', $GLOBALS['CONTEXT']);

get_footer();