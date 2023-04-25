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

//add_action('admin_footer-edit.php', 'custom_bulk_admin_footer');
//global $post_type;
//function custom_bulk_admin_footer()
//{
//    global $post_type;
//    if ('post' === $post_type) {
//        ?>
<!--        <script type="text/javascript">-->
<!--            jQuery(document).ready(function () {-->
<!--                jQuery('<option>').val('your_bulk_action_name').text('Your Bulk Action Label').appendTo('select[name=\'action\'], select[name=\'action2\']');-->
<!--            });-->
<!--        </script>-->
<!--        --><?php
//    }
//}
//
//add_action('admin_action_your_bulk_action_name', 'your_bulk_action_function');
//function your_bulk_action_function()
//{
//    // Your bulk action code here
//}
//
//add_filter('bulk_actions-' . $post_type, 'register_your_bulk_action');
//function register_your_bulk_action($bulk_actions)
//{
//    $bulk_actions['your_bulk_action_name'] = 'Your Bulk Action Label';
//    return $bulk_actions;
//}

add_action('bulk_edit_custom_box', 'mep_quick_edit_fields', 10, 2);
function mep_quick_edit_fields($column_name, $post_type)
{
    if ($column_name === 'mep_event_date' && $post_type == 'mep_events') {

        ?>
        <fieldset class="inline-edit-col-center">
            <div class="inline-edit-col">
                <label for="">Description</label>
                <textarea name="content" id="" cols="30" rows="10"></textarea>
            </div>
        </fieldset>
        <?php
    }
}

add_action('save_post', 'save_new_field_value');

function save_new_field_value($post_id)
{
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    remove_action( 'save_post', 'save_new_field_value' );
    $new_field_value =$_REQUEST['content'];

    $my_post = [
        'ID' => $post_id,
        'post_content' => '<p>'.$new_field_value .'</p>',
    ];
    wp_update_post( $my_post );
    add_action( 'save_post', 'save_new_field_value' );
}
wp_enqueue_script('jquery');
function add_this(){ ?>
<script>
    jQuery(document).ready(function($) {
        console.log('ddd');
        // Open the modal when the button is clicked
        $('#mep_event_ticket').click(function() {

            $('#my-modal').fadeIn();
        });

        // Close the modal when the close button is clicked
        $('.close').click(function() {
            $('#my-modal').fadeOut();
        });
    });
</script>

<?php }

//add_action('admin_enqueue_scripts', 'add_this');
?>
<!--<div class="modal" id="my-modal">-->
<!---->
<!--    <div class="modal-content">-->
<!--        <span class="close">x</span>-->
<!--        <label for="">Description</label>-->
<!--        <textarea name="" id="" cols="30" rows="10"></textarea>-->
<!--    </div>-->
<!--</div>-->
<!---->
<!--<!-- CSS code -->-->
<!--<style>-->
<!--    .modal {-->
<!--        display: none;-->
<!--        position: fixed;-->
<!--        top: 0;-->
<!--        left: 0;-->
<!--        width: 100%;-->
<!--        height: 100%;-->
<!--        background-color: rgba(0, 0, 0, 0.5);-->
<!--        z-index: 9999;-->
<!--    }-->
<!--    .modal-content {-->
<!--        position: absolute;-->
<!--        top: 50%;-->
<!--        left: 50%;-->
<!--        transform: translate(-50%, -50%);-->
<!--        background-color: white;-->
<!--        padding: 20px;-->
<!--        border-radius: 5px;-->
<!--        min-width: 50%;-->
<!--        height: auto;-->
<!--    }-->
<!--    .close {-->
<!--        position: absolute;-->
<!--        top: 5px;-->
<!--        right: 10px;-->
<!--        font-size: 20px;-->
<!--        font-weight: bold;-->
<!--        cursor: pointer;-->
<!--    }-->
<!--</style>-->
