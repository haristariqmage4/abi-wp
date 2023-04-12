<?php

    global $post;
    $post_slug = $post->post_name;

    $GLOBALS['CONTEXT'] = Timber::context();
    $GLOBALS['CONTEXT']['post'] = new Timber\Post();
    $GLOBALS['CONTEXT']['page_slug']=$post_slug;
    
    Timber::render('header.twig', $GLOBALS['CONTEXT']);

?>