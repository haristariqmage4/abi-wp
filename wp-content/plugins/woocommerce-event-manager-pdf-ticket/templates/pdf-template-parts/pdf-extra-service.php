<?php
add_action('mep_pdf_event_extra_serive_info','mep_display_pdf_event_extra_serive_info',10,3);

function mep_display_pdf_event_extra_serive_info($_event_extra_service,$style, $order_id){
   

    $args = array(
        'post_type'         => 'mep_extra_service',
        'posts_per_page'    => -1,
        'meta_query' => array(
            array(
                'key'       => 'ea_extra_service_order',
                'value'     => $order_id,
                'compare'   => '='
            )
        ),
    );
    $loop = new WP_Query($args);
    $event_extra_service = $loop->posts;

    if($style=='table'){
          foreach($event_extra_service as $extra_serive){
              $id = $extra_serive->ID;
                    ?>
                    <tr>
                        <td><?php   echo get_post_meta($id,'ea_extra_service_name',true); ?></td>
                        <td><?php echo get_post_meta($id,'ea_extra_service_qty',true); ?></td>
                        <td><?php echo wc_price(get_post_meta($id,'ea_extra_service_unit_price',true)); ?></td>
                        <td><?php echo wc_price(get_post_meta($id,'ea_extra_service_total_price',true)); ?></td>
                    </tr>
                    <?php
                }
    }    
    
    if($style=='list'){
        foreach($event_extra_service as $extra_serive){
            $id = $extra_serive->ID;
                    ?>
                    <ul>
                        <li><?php echo get_post_meta($id,'ea_extra_service_name',true); ?></li>
                        <li><?php echo get_post_meta($id,'ea_extra_service_qty',true); ?></li>
                        <li><?php echo wc_price(get_post_meta($id,'ea_extra_service_unit_price',true)); ?></li>
                        <li><?php echo wc_price(get_post_meta($id,'ea_extra_service_total_price',true)); ?></li>
                    </ul>
                    <?php
                }
    }
}