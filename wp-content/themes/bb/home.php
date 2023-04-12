<?php
/*Template Name: Home*/
    get_header();
?>


<?php

    Timber::render('home.twig', $GLOBALS['CONTEXT']);

?>

<?php
    get_footer();
?>


