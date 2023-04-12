<?php 
/* Template Name: Custom Checkout */
get_header();

$GLOBALS['CONTEXT'] = Timber::context();
$GLOBALS['CONTEXT']['post'] = new Timber\Post();
$GLOBALS['CONTEXT']['woocommerce_checkout']=do_shortcode('[woocommerce_checkout]');

Timber::render('woo-custom-checkout.twig',$GLOBALS['CONTEXT']);

get_footer();