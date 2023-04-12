<?php
/*Template Name: Page*/
    get_header();
?>


<?php

    global $post;
    
    $is_dd=false;

    
    if($post->slug=="dinners-in-the-dark"){
        $is_dd=true;
    }
    
    $GLOBALS['CONTEXT']['is_dd']=$is_dd;
    
    Timber::render('page.twig', $GLOBALS['CONTEXT']);

?>

<?php
    get_footer();
?>


