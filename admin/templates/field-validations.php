<?php 
    // global $uaio_settings_data; 
    // extract($uaio_settings_data);


    // $header_fields = $header_fields_list;
    // $tab_fields = $tab_fields_list;

    $filtered_fields = array('user_pic','user_pass','user_pass_confirm');
    $allowed_field_types = array('text','textarea');

    $profile_fields = get_option('upme_profile_fields');

?>

<form method="post" action="">
<table class="form-table">
    
                <tr>
                    <th><label for=""><?php _e('Profile Field','uaio'); ?></label></th>
                    <td style="width:500px;">
                    <select id="upme_profile_validation_field"  name="upme_profile_validation_field" class="chosen-admin_setting">
                        <option value="0" ><?php echo __('Please Select','upme') ; ?></option>
                        <?php foreach($profile_fields as $k => $field){
                            if($field['type'] == 'usermeta' && !in_array($field['meta'],$filtered_fields) && in_array($field['field'],$allowed_field_types)){
                                
                        ?>
                        <option placeholder="<?php echo __('Please Select','upme') ; ?>"  value="<?php echo $field['meta']; ?>" ><?php echo $field['name']; ?></option>
                                                    
                        <?php
                            }            
                        }
                        ?>
                    </select>
                    <span id="upme_profile_validation_field_loading"></span>
                </td>
                </tr>
                <tr data-validation-type="min_length" class="upme_field_validation_setting upme_field_validation_setting_min_length">
                    <th><label for=""><?php _e('Min Length','uaio'); ?></label></th>
                    <td style="width:500px;">
                    <input class="upme_field_validation_setting_value" type="text" id="upme_field_validation_min_length" name="upme_field_validation_min_length" />           
                    
                </td>
                </tr>
                <tr data-validation-type="max_length" class="upme_field_validation_setting upme_field_validation_setting_max_length">
                    <th><label for=""><?php _e('Max Length','uaio'); ?></label></th>
                    <td style="width:500px;">
                    <input class="upme_field_validation_setting_value" type="text" id="upme_field_validation_max_length" name="upme_field_validation_max_length" />           
                    
                </td>
                </tr>
                
    
    
</table>
    <input type='button' value='<?php echo __('Save','upme'); ?>' name='upme_field_validation_save' id='upme_field_validation_save' class='button button-primary' /> 
</form>