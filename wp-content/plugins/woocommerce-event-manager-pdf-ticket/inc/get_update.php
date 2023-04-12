<?php
if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
require 'plugin-updates/plugin-update-checker.php';
$ExampleUpdateChecker = PucFactory::buildUpdateChecker(
    'http://vaincode.com/update/event/pdf/pdf.json',
    __FILE__
);