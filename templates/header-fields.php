<?php
    global $upme_admin;

    $profile_header_fields = (array) $upme_admin->options['header_fields'];
    $filtered_fields = array('user_pic','user_pass','user_pass_confirm','user_cover_pic');
    $profile_fields = get_option('upme_profile_fields');
    $header_fields_options = array();
    foreach($profile_fields as $k => $field){
        if($field['type'] == 'usermeta' && !in_array($field['meta'],$filtered_fields)){
            $selected = '';
            if(in_array($field['meta'],$profile_header_fields)){
                $selected = 'selected';
            }
            $header_fields_options[$field['meta']] = $field['name'];
        }
    }    

?>

<div class="upme-tab-content" id="upme-header-fields-settings-content" style="display:none;">
    <h3><?php _e('Manage Header Field Settings','upme');?>
        </h3>
        
        
    
    <div id="upme-header-fields-settings" class="upme-header-fields-screens" style="display:block">

        <form id="upme-header-fields-settings-form">
            <table class="form-table" cellspacing="0" cellpadding="0">
                <tbody>
                    <?php
                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'header_fields[]',
                                'header_fields',
                                __('Profile Header Fields', 'upme'),
                                $header_fields_options,
                                __('These fields will be displayed in header section of the profile.', 'upme'),
                                __('Used to highlight the fields in profile view.', 'upme'),
                                array('multiple'=>'','init_value' => '','class'=> 'chosen-admin_setting')
                        );

                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'header_field_display_type',
                                'header_field_display_type',
                                __('Header Field Display Type', 'upme'),
                                array('0'=> __('Display with Label','upme'),'1' => __('Display without Label','upme')),
                                __('Enabele/Disable label for custom fields in profile header.', 'upme'),
                                __('Used to display/hide profile fields label on profile header.', 'upme'),
                                array('class'=> 'chosen-admin_setting')
                        );

                        $upme_admin->add_plugin_module_setting(
                                'select',
                                'header_fields_compact_view',
                                'header_fields_compact_view',
                                __('Header Fields in Compact View', 'upme'),
                                array('0'=> __('No','upme'),'1' => __('Yes','upme')),
                                __('Display/hide header fields in compact profile view. By default, header fields are not visible in compact view', 'upme'),
                                __('Display/hide fields in compact profile view. By default, header fields are not visible in compact view.', 'upme'),
                                array('class'=> 'chosen-admin_setting')
                        );

                        
                    ?>

                    <tr valign="top">
                        <th scope="row"><label>&nbsp;</label></th>
                        <td>
                            <?php 
                                echo UPME_Html::button('button', array('name'=>'save-upme-header-fields-settings', 'id'=>'save-upme-header-fields-settings', 'value'=> __('Save Changes','upme'), 'class'=>'button button-primary upme-save-module-options'));
                                echo '&nbsp;&nbsp;';
                                echo UPME_Html::button('button', array('name'=>'reset-upme-header-fields-settings', 'id'=>'reset-upme-header-fields-settings', 'value'=>__('Reset Options','upme'), 'class'=>'button button-secondary upme-reset-module-options'));
                            ?>
                            
                        </td>
                    </tr>

                </tbody>
            </table>
        
        </form>
        
    </div>     
</div>