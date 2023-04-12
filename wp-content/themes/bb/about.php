<?php
/*Template Name: About*/
    get_header();
?>


<?php

    Timber::render('about.twig', $GLOBALS['CONTEXT']);

?>

<?php
    get_footer();
?>


