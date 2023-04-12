<?php

if ( ! defined('ABSPATH')) exit;  // if direct access 

$stylesheet_dir     = WBTM_PRO_PLUGIN_URL.'templates/pdf-templates/flat/style.css';
$template_dir       = WBTM_PRO_PLUGIN_DIR.'templates/pdf-templates/flat/template.php';

printf( '<link rel="stylesheet" href="%s">', $stylesheet_dir );
include( $template_dir );