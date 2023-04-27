<?php
ini_set('display_errors', 1);

/*
Plugin Name: My Mage EventPress Overrides
Plugin URI: https://www.example.com/
Description: Overrides the templates of the Mage EventPress plugin.
Author: John Doe
Version: 1.0
*/

// Plugin code goes here

add_action('bulk_edit_custom_box', 'mep_quick_edit_fields', 10, 2);
function mep_quick_edit_fields($column_name, $post_type)
{
    if ($column_name === 'mep_event_date' && $post_type == 'mep_events') {

        ?>
        <fieldset class="inline-edit-col-center">
            <div class="inline-edit-col">
                <label for="mep_event_ticket">Description</label><textarea name="content" id="mep_event_ticket"
                                                                           cols="30" rows="10"></textarea>
                <label for="mep_event_ticket_price">Event price</label><input type="text" name="mep_event_ticket_price"
                                                                              id="mep_event_ticket_price"/>
            </div>
        </fieldset>
        <?php
    }
}

add_action('save_post', 'save_new_field_value');

function save_new_field_value($post_id)
{
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    remove_action('save_post', 'save_new_field_value');
    $new_field_value = $_REQUEST['content'];
    $mep_event_ticket_price_field = $_REQUEST['mep_event_ticket_price'];
    if ($mep_event_ticket_price_field != ''){
        $mep_event_ticket_type = get_post_meta($post_id, 'mep_event_ticket_type', true);
        $new = [];
        for ($i = 0; $i < count($mep_event_ticket_type); $i++) {
            $option_name_t = isset($mep_event_ticket_type[$i]['option_name_t']) ? $mep_event_ticket_type[$i]['option_name_t'] : '';
            $option_details_t = isset($mep_event_ticket_type[$i]['option_details_t']) ? $mep_event_ticket_type[$i]['option_details_t'] : '';
            $option_qty_t = isset($mep_event_ticket_type[$i]['option_qty_t']) ? $mep_event_ticket_type[$i]['option_qty_t'] : 0;
            $option_default_qty_t = isset($mep_event_ticket_type[$i]['option_default_qty_t']) ? $mep_event_ticket_type[$i]['option_default_qty_t'] : 0;
            $option_rsv_t = isset($mep_event_ticket_type[$i]['option_rsv_t']) ? $mep_event_ticket_type[$i]['option_rsv_t'] : 0;
            $option_qty_t_type = isset($mep_event_ticket_type[$i]['option_qty_t_type']) ? $mep_event_ticket_type[$i]['option_qty_t_type'] : 0;
            $option_sale_end_date = isset($mep_event_ticket_type[$i]['option_sale_end_date']) ? $mep_event_ticket_type[$i]['option_sale_end_date'] : '';
            $option_sale_end_time = isset($mep_event_ticket_type[$i]['option_sale_end_time']) ? $mep_event_ticket_type[$i]['option_sale_end_time'] : '';

            $new[$i]['option_name_t'] = $option_name_t;
            $new[$i]['option_details_t'] = $option_details_t;
            $new[$i]['option_price_t'] = $mep_event_ticket_price_field;
            $new[$i]['option_qty_t'] = $option_qty_t;
            $new[$i]['option_rsv_t'] = $option_rsv_t;
            $new[$i]['option_default_qty_t'] = $option_default_qty_t;
            $new[$i]['option_qty_t_type'] = $option_qty_t_type;
            $new[$i]['option_sale_end_date'] = $option_sale_end_date;
            $new[$i]['option_sale_end_time'] = $option_sale_end_time;
            $new[$i]['option_sale_end_date_t'] = stripslashes(strip_tags($option_sale_end_date . ' ' . $option_sale_end_time));;

        }
        $ticket_type_list = apply_filters('mep_ticket_type_arr_save', $new);

        update_post_meta($post_id, 'mep_event_ticket_type', $ticket_type_list);
    }

    if ($new_field_value == ''){
        return;
    }
    if ($new_field_value != ''){
        $my_post = ['ID' => $post_id, 'post_content' => '<p>' . $new_field_value . '</p>',];
        wp_update_post($my_post);
    }

   // add_action('save_post', 'save_new_field_value');
}

