<?php
if (!defined('ABSPATH')) {
    die;
}

// Frontend Submit Addon

function mep_frontend_registration($post)
{ ?>
<div class="mefs_venue group-field">
    <label class="attach-label"><?php _e('Event Attendee Registration Form', 'mep-form-builder') ?></label>
    <div class="group-field-inner">
        <div class="mefs_field_group">
            <div class="mefs-with-label">
                <label class="mefs_ck_switch">
                    <input type="checkbox" name="mep_full_name" id="mep_full_name"
                        <?php echo (isset($post) && get_post_meta($post->ID, 'mep_full_name', true) == '1'  ? 'checked' : '') ?>>
                    <span class="mefs_ck_slider round"></span>
                </label>
                <label for="mep_name_label"><?php _e('Full Name', 'mep-form-builder') ?></label>
            </div>
            <input id="mep_name_label" type="text" name="mep_name_label"
                value="<?php echo mefs_get_post_data($post, 'mep_name_label') ?>"
                placeholder="<?php _e('Enter Label Text Here Default is: Enter Your Name', 'mep-form-builder') ?>">
        </div>
        <div class="mefs_field_group">
            <div class="mefs-with-label">
                <label class="mefs_ck_switch">
                    <input type="checkbox" name="mep_reg_email" id="mep_reg_email"
                        <?php echo (isset($post) && get_post_meta($post->ID, 'mep_reg_email', true) == '1'  ? 'checked' : '') ?>>
                    <span class="mefs_ck_slider round"></span>
                </label>
                <label for="mep_email_label"><?php _e('Email Address', 'mep-form-builder') ?></label>
            </div>
            <input id="mep_email_label" type="email" name="mep_email_label"
                value="<?php echo mefs_get_post_data($post, 'mep_email_label') ?>"
                placeholder="<?php _e('Enter Label Text Here Default is: Enter Your Email', 'mep-form-builder') ?>">
        </div>
        <div class="mefs_field_group">
            <div class="mefs-with-label">
                <label class="mefs_ck_switch">
                    <input type="checkbox" name="mep_reg_phone" id="mep_reg_phone"
                        <?php echo (isset($post) && get_post_meta($post->ID, 'mep_reg_phone', true) == '1'  ? 'checked' : '') ?>>
                    <span class="mefs_ck_slider round"></span>
                </label>
                <label for="mep_email_label"><?php _e('Phone Number', 'mep-form-builder') ?></label>
            </div>
            <input id="mep_phone_label" type="text" name="mep_phone_label"
                value="<?php echo mefs_get_post_data($post, 'mep_phone_label') ?>"
                placeholder="<?php _e('Enter Label Text Here Default is: Enter Your Phone', 'mep-form-builder') ?>">
        </div>
        <div class="mefs_field_group">
            <div class="mefs-with-label">
                <label class="mefs_ck_switch">
                    <input type="checkbox" name="mep_reg_gender"
                        <?php echo (isset($post) && get_post_meta($post->ID, 'mep_reg_gender', true) == '1'  ? 'checked' : '') ?>>
                    <span class="mefs_ck_slider round"></span>
                </label>
                <label for="mep_gender_label"><?php _e(' Gender', 'mep-form-builder') ?></label>
            </div>
            <input id="mep_gender_label" type="text" name="mep_gender_label"
                value="<?php echo mefs_get_post_data($post, 'mep_gender_label') ?>"
                placeholder="<?php _e('Enter Label Text Here Default is: Gender', 'mep-form-builder') ?>">
        </div>
        <div class="mefs_field_group" style="flex-basis: 100%">
            <div class="mefs-with-label">
                <label class="mefs_ck_switch">
                    <input type="checkbox" name="mep_reg_address" id="mep_reg_address"
                        <?php echo (isset($post) && get_post_meta($post->ID, 'mep_reg_address', true) == '1'  ? 'checked' : '') ?>>
                    <span class="mefs_ck_slider round"></span>
                </label>
                <label for="mep_email_label"><?php _e('Address', 'mep-form-builder') ?></label>
            </div>
            <label for="mep_address_label"></label>
            <input id="mep_address_label" type="text" name="mep_address_label"
                value="<?php echo mefs_get_post_data($post, 'mep_address_label') ?>"
                placeholder="<?php _e('Enter Label Text Here Default is: Enter Your Address', 'mep-form-builder') ?>">
        </div>
        <div class="mefs_field_group" style="flex-basis: 100%">
            <div class="mefs-with-label">
                <label class="mefs_ck_switch">
                    <input type="checkbox" name="mep_reg_tshirtsize"
                        <?php echo (isset($post) && get_post_meta($post->ID, 'mep_reg_tshirtsize', true) == '1'  ? 'checked' : '') ?>>
                    <span class="mefs_ck_slider round"></span>
                </label>
                <label
                    for="mep_email_label"><?php _e('T-Shirt Size (Input Tshirts size, separetd by comma (M,L,XL) )', 'mep-form-builder') ?></label>
            </div>
            <input type="text" name="mep_reg_tshirtsize_list"
                value="<?php echo mefs_get_post_data($post, 'mep_reg_tshirtsize_list') ?>" style="margin-bottom:1px">
            <input id="mep_tshirt_label" type="text" name="mep_tshirt_label"
                value="<?php echo mefs_get_post_data($post, 'mep_tshirt_label') ?>"
                placeholder="<?php _e('Enter Label Text Here Default is: Select TShirt Size', 'mep-form-builder') ?>">
        </div>
        <div class="mefs_field_group">
            <div class="mefs-with-label">
                <label class="mefs_ck_switch">
                    <input type="checkbox" name="mep_reg_designation" id="mep_reg_designation"
                        <?php echo (isset($post) && get_post_meta($post->ID, 'mep_reg_designation', true) == '1'  ? 'checked' : '') ?>>
                    <span class="mefs_ck_slider round"></span>
                </label>
                <label for="mep_desg_label"><?php _e('Designation', 'mep-form-builder') ?></label>
            </div>
            <input id="mep_desg_label" type="text" name="mep_desg_label"
                value="<?php echo mefs_get_post_data($post, 'mep_desg_label') ?>"
                placeholder="<?php _e('Enter Label Text Here Default is: Designation', 'mep-form-builder') ?>">
        </div>
        <div class="mefs_field_group">
            <div class="mefs-with-label">
                <label class="mefs_ck_switch">
                    <input type="checkbox" name="mep_reg_website" id="mep_reg_website"
                        <?php echo (isset($post) && get_post_meta($post->ID, 'mep_reg_website', true) == '1'  ? 'checked' : '') ?>>
                    <span class="mefs_ck_slider round"></span>
                </label>
                <label for="mep_website_label"><?php _e('Website', 'mep-form-builder') ?></label>
            </div>
            <input id="mep_website_label" type="text" name="mep_website_label"
                value="<?php echo mefs_get_post_data($post, 'mep_website_label') ?>"
                placeholder="<?php _e('Enter Label Text Here Default is: Enter Your Website', 'mep-form-builder') ?>">
        </div>
        <div class="mefs_field_group">
            <div class="mefs-with-label">
                <label class="mefs_ck_switch">
                    <input type="checkbox" name="mep_reg_veg" id="mep_reg_veg"
                        <?php echo (isset($post) && get_post_meta($post->ID, 'mep_reg_veg', true) == '1'  ? 'checked' : '') ?>>
                    <span class="mefs_ck_slider round"></span>
                </label>
                <label for="mep_veg_label"><?php _e('Vegetarian', 'mep-form-builder') ?></label>
            </div>
            <input id="mep_veg_label" type="text" name="mep_veg_label"
                value="<?php echo mefs_get_post_data($post, 'mep_veg_label') ?>"
                placeholder="<?php _e('Enter Label Text Here Default is: Vegetarian?', 'mep-form-builder') ?>">
        </div>
        <div class="mefs_field_group">
            <div class="mefs-with-label">
                <label class="mefs_ck_switch">
                    <input type="checkbox" name="mep_reg_company"
                        <?php echo (isset($post) && get_post_meta($post->ID, 'mep_reg_company', true) == '1'  ? 'checked' : '') ?>>
                    <span class="mefs_ck_slider round"></span>
                </label>
                <label for="mep_company_label"><?php _e('Company Name', 'mep-form-builder') ?></label>
            </div>
            <input id="mep_company_label" type="text" name="mep_company_label"
                value="<?php echo mefs_get_post_data($post, 'mep_company_label') ?>"
                placeholder="<?php _e('Enter Label Text Here Default is: Enter Your Company', 'mep-form-builder') ?>">
        </div>

        <div class="mefs_field_group" style="flex-basis: 100%">
            <div class="additional-form-builder-inner">
                <?php
                    $mep_form_builder_data = (($post) ? get_post_meta($post->ID, 'mep_form_builder_data', true) : '');
                    
                    if($mep_form_builder_data) {
                        foreach( $mep_form_builder_data as $item ) : ?>
                            <div class="additional-form-builder-field">
                                <span class="mep-additional-field-close" title="Close">x</span>
                                <label class="mep-additional-label"><?php _e('Additional Field', 'mep-form-builder') ?></label>
                                <div class="additioanl-field-item">
                                    <input type="text" name="mep_fbc_label[]"
                                        placeholder="<?php _e('Field Label', 'mep-form-builder') ?>" value="<?php echo $item['mep_fbc_label'] ;?>">
                                    <span class="mefs_field_info"><?php _e('Field Label', 'mep-form-builder') ?></span>
                                </div>
                                <div class="additioanl-field-item">
                                    <input type="text" name="mep_fbc_id[]"
                                        placeholder="<?php _e('Unique ID', 'mep-form-builder') ?>" value="<?php echo $item['mep_fbc_id'] ;?>">
                                    <span class="mefs_field_info" style="color:#cd2653;font-weight:700"><?php _e('This field must not be
                                        empty, Otherwise data will not save into database,', 'mep-form-builder') ?></span>
                                </div>
                                <div class="additioanl-field-item">
                                    <select name="mep_fbc_type[]">
                                            <option value=""><?php _e('Select Type', 'mep-form-builder') ?></option>
                                            <option value="text" <?php echo ($item['mep_fbc_type'] == 'text') ? 'selected' : '' ?>><?php _e('Text Box', 'mep-form-builder') ?></option>
                                            <option value="date" <?php echo ($item['mep_fbc_type'] == 'date') ? 'selected' : '' ?>><?php _e('Date', 'mep-form-builder') ?></option>
                                            <option value="textarea" <?php echo ($item['mep_fbc_type'] == 'textarea') ? 'selected' : '' ?>><?php _e('Textarea', 'mep-form-builder') ?></option>
                                            <option value="checkbox" <?php echo ($item['mep_fbc_type'] == 'checkbox') ? 'selected' : '' ?>><?php _e('Check Box', 'mep-form-builder') ?></option>
                                            <option value="select" <?php echo ($item['mep_fbc_type'] == 'select') ? 'selected' : '' ?>><?php _e('Dropdown Box', 'mep-form-builder') ?></option>
                                    </select>
                                    <span class="mefs_field_info"><?php _e('Type of field', 'mep-form-builder') ?></span>
                                </div>
                                <div class="additioanl-field-item">
                                    <input type="text" name="mep_fbc_dp_data[]"
                                        placeholder="<?php _e('Value', 'mep-form-builder') ?>" value="<?php echo (isset($item['mep_fbc_dp_data']) ? $item['mep_fbc_dp_data'] : '') ;?>">
                                    <span class="mefs_field_info"><?php _e('Field value', 'mep-form-builder') ?></span>
                                </div>
                            </div>
                        <?php endforeach;
                    }
                ?>
                <div class="additional-form-builder-field empty-form-builder-content">
                    <span class="mep-additional-field-close" title="Close">x</span>
                    <label class="mep-additional-label"><?php _e('Additional Field', 'mep-form-builder') ?></label>
                    <div class="additioanl-field-item">
                        <input type="text" name="mep_fbc_label[]"
                            placeholder="<?php _e('Field Label', 'mep-form-builder') ?>">
                        <span class="mefs_field_info"><?php _e('Field Label', 'mep-form-builder') ?></span>
                    </div>
                    <div class="additioanl-field-item">
                        <input type="text" name="mep_fbc_id[]"
                            placeholder="<?php _e('Unique ID', 'mep-form-builder') ?>">
                        <span class="mefs_field_info" style="color:#cd2653;font-weight:700"><?php _e('This field must not be
                            empty, Otherwise data will not save into database,', 'mep-form-builder') ?></span>
                    </div>
                    <div class="additioanl-field-item">
                        <select name="mep_fbc_type[]">
                            <<option value=""><?php _e('Select Type', 'mep-form-builder') ?></option>
                                <option value="text" selected=""><?php _e('Text Box', 'mep-form-builder') ?></option>
                                <option value="date"><?php _e('Date', 'mep-form-builder') ?></option>
                                <option value="textarea"><?php _e('Textarea', 'mep-form-builder') ?></option>
                                <option value="radio"><?php _e('Radio Box', 'mep-form-builder') ?></option>
                                <option value="checkbox"><?php _e('Check Box', 'mep-form-builder') ?></option>
                                <option value="select"><?php _e('Dropdown Box', 'mep-form-builder') ?></option>
                        </select>
                        <span class="mefs_field_info"><?php _e('Type of field', 'mep-form-builder') ?></span>
                    </div>
                    <div class="additioanl-field-item">
                        <input type="text" name="mep_fbc_dp_data[]"
                            placeholder="<?php _e('Value', 'mep-form-builder') ?>">
                        <span class="mefs_field_info"><?php _e('Field value', 'mep-form-builder') ?></span>
                    </div>
                </div>
            </div>
            <button class="mefs-btn repeat-btn repeat-btn-form-builder"><span>+</span>
                <?php _e('Add New Field', 'mep-form-builder') ?></button>
        </div>
    </div>
</div>
<?php
}

add_action('mefs_registration_field', 'mep_frontend_registration');