<?php

class UPME_Profile_Fields {

    private $user_roles;
    private $upme_profile_statuses;

    function __construct() {
        $this->upme_profile_statuses = array(
                                            'INACTIVE' => __('Inactive','upme'),
                                            'ACTIVE'   => __('Active','upme')
                                        );

        /* UPME Filter for adding custom profile status */
        $upme_custom_profile_statuses = apply_filters('upme_custom_profile_statuses',array());
        $this->upme_profile_statuses = array_merge($this->upme_profile_statuses,$upme_custom_profile_statuses);
        // End Filter

        add_shortcode('upme_profile_field_block',array($this, 'profile_field_block'));
        add_filter('upme_profile_header_fields',array($this,'profile_header_fields'),10,2);
    }

    /* Returns the available mandatory fields for backend profile */
    public function upme_backend_mandatory_fields($upme_settings,$user) {

        $display = '';

        if($upme_settings['profile_view_status'] || current_user_can('manage_options') || current_user_can('manage_upme_options') ){

            $display .= '<tr>';
            $profile_status_label = __('Profile Status','upme');
            $display .= '<th scope="row"><label for="' . $profile_status_label . '">' . $profile_status_label . '</label></th>';

            $current_profile_status = esc_attr(get_user_meta($user->ID , 'upme_user_profile_status', true ));

            $display .= '<td><select class="input" name="upme[upme_user_profile_status]" id="upme_user_profile_status">';
                        foreach ($this->upme_profile_statuses as $status=>$display_status) {
                            $status = trim($status);

                            $display .= '<option value="' . $status . '" ' . selected($current_profile_status, $status, 0) . '>' . $display_status . '</option>';
                        }
            $display .= '</select></td></tr>';
        }

        if($upme_settings['email_two_factor_verification_status'] || current_user_can('manage_options') || current_user_can('manage_upme_options') ){

            $display .= '<tr>';
            $label = __('Email Authentication','upme');
            $display .= '<th scope="row"><label for="' . $label . '">' . $label . '</label></th>';

            $current_profile_status = esc_attr(get_user_meta($user->ID, 'upme_email_two_factor_status', true));

            $display .= '<td><select class="input" name="upme[upme_email_two_factor_status]" id="upme_email_two_factor_status">';
            $display .= '<option value="0" ' . selected($current_profile_status, '0', 0) . '>' . __('Disable','upme') . '</option>';
            $display .= '<option value="1" ' . selected($current_profile_status, '1', 0) . '>' . __('Enable','upme') . '</option>';

            $display .= '</select></td></tr>';

        }

        return $display;
    }

    public function upme_frontend_mandatory_fields($upme_settings,$user_id,$profile_user_id){

        $display = '';

        if($upme_settings['profile_view_status']){

            $current_profile_status = esc_attr(get_user_meta($profile_user_id, 'upme_user_profile_status', true));

            $display .= '<div class="upme-field upme-edit">';
            $display .= '<label class="upme-field-type" for="upme_user_profile_status-' . $profile_user_id . '">';

            $name     = __('Profile Status','upme');

            $display .= '<i class="upme-icon upme-icon-unlock-alt"></i>';
            $display .= '<span>' . apply_filters('upme_edit_profile_label_upme_user_profile_status', $name) . '</span></label>';

            $display .= '<div class="upme-field-value">';
            $display .= '<select class="upme-input " name="upme_user_profile_status-' . $profile_user_id . '" id="upme_user_profile_status-' . $profile_user_id . '" >';
                            foreach ($this->upme_profile_statuses as $status=>$display_status) {
                                $status = trim($status);

                                $display .= '<option value="' . $status . '" ' . selected($current_profile_status, $status, 0) . '>' . $display_status . '</option>';
                            }
            $display .= '</select>';
            $display .= '<div class="upme-clear"></div>';
            $display .= '</div></div>';
        }

        if($upme_settings['email_two_factor_verification_status']){

            $current_profile_status = esc_attr(get_user_meta($profile_user_id, 'upme_email_two_factor_status', true));

            $display .= '<div class="upme-field upme-edit">';
            $display .= '<label class="upme-field-type" for="upme_email_two_factor_status-' . $profile_user_id . '">';

            $name     = __('Email Authentication','upme');

            $display .= '<i class="upme-icon upme-icon-unlock-alt"></i>';
            $display .= '<span>' . apply_filters('upme_edit_profile_label_email_two_factor_status', $name) . '</span></label>';

            $display .= '<div class="upme-field-value">';
            $display .= '<select class="upme-input " name="upme_email_two_factor_status-' . $profile_user_id . '" id="upme_email_two_factor_status-' . $profile_user_id . '" >';
            $display .= '<option value="0" ' . selected($current_profile_status, '0', 0) . '>' . __('Disable','upme') . '</option>';
            $display .= '<option value="1" ' . selected($current_profile_status, '1', 0) . '>' . __('Enable','upme') . '</option>';

            $display .= '</select>';
            $display .= '<div class="upme-clear"></div>';
            $display .= '</div></div>';

        }

        return $display;

    }

    public function profile_field_block($atts,$content){
        global $upme_options;
        extract( shortcode_atts( array(
                'key'   => '' ,
              ), $atts ) );

        $user_id = (int) upme_get_user_id_by_profile_url();
        if($user_id == 0 && is_user_logged_in() ){
            $user_id = get_current_user_id();
        }

        $upme_settings = $upme_options->upme_settings;
        $upme_date_format = (string) isset($upme_settings['date_format']) ? $upme_settings['date_format'] : 'mm/dd/yy';


        $profile_fields = get_option('upme_profile_fields');

        $tag = $key;
        $status = 0;
        $display = '';

        foreach ($profile_fields as $key => $profile_field) {
            if(isset($profile_field['meta']) && $profile_field['meta'] == $tag && $status == '0'){
                $field_type = $profile_field['field'];
                $status = 1;
                $display = '';
                switch ($field_type) {
                    case 'text':
                        $display = get_user_meta($user_id,$profile_field['meta'],true);
                        if($profile_field['meta'] == 'user_url'){
                            $display = '<a rel="external nofollow" target="_blank" href="' . $display . '">'.$display.'</a>';
                        }
                        break;

                    case 'fileupload':
                        if($key == 'user_pic' && get_user_meta( $user_id , $key, TRUE) == ''){
                            $display = get_avatar($user_id, 50);
                        }else{
                            $display = '<img style="width:100%" src="' . get_user_meta( $user_id ,$profile_field['meta'], true) . '" alt="" />';

                        }
                        break;

                    case 'textarea':
                        $display = get_user_meta($user_id,$profile_field['meta'],true);
                        break;

                    case 'select':
                        $display = get_user_meta($user_id,$profile_field['meta'],true);
                        break;

                    case 'radio':
                        $display = get_user_meta($user_id,$profile_field['meta'],true);
                        break;

                    case 'checkbox':
                        $display = get_user_meta($user_id,$profile_field['meta'],true);
                        break;

                    case 'password':
                        $display = get_user_meta($user_id,$profile_field['meta'],true);
                        break;

                    case 'datetime':
                        $date_time_value = get_user_meta($user_id,$profile_field['meta'],true);
                        $display = upme_date_format_to_custom($date_time_value, $upme_date_format);

                        break;

                    case 'video':
                        $video_url = get_user_meta($user_id,$profile_field['meta'],true);

                        $player_details = upme_video_type_css($video_url);
                        $player_url = upme_video_url_customizer($video_url);

                        $display .= '<div class="upme-video-container">';
                        $display .= '<iframe  width="' . $player_details['width'] . '" height="' . $player_details['height'] . '" src="' . $player_url . '" frameborder="0" allowfullscreen ></iframe>';
                        $display .= '</div>';

                        break;

                    case 'soundcloud':
                        $soundcloud_url = get_user_meta($user_id,$profile_field['meta'],true);
                        $sound_cloud_player = upme_sound_cloud_player($soundcloud_url);

                        $display .= '<div class="upme-sound-container upme-sound-cloud-container">';
                        $display .= $sound_cloud_player;
                        $display .= '</div>';
                        break;


                }
            }

        }
        return $display;
    }

    public function profile_header_fields($display,$params ){
        global $upme_options,$upme;
        extract($params);


        if($view == '' || $upme_options->upme_settings['header_fields_compact_view'] == '1'){
            $fields = get_option('upme_profile_fields');
            $field_names = array();
            $date_fields = array();
            foreach($fields as $field){
                if($field['type'] == 'usermeta' && isset($field['meta'])){
                    $field_names[$field['meta']] = $field['name'];
                }

                if(isset($field['field']) && $field['field'] == 'datetime'){
                    $date_fields[] = $field['meta'];
                }

            }

            $header_fields = isset($upme_options->upme_settings['header_fields']) ? $upme_options->upme_settings['header_fields'] : array() ;

            $header_fields_display = isset($upme_options->upme_settings['header_fields_display_type']) ? $upme_options->upme_settings['header_fields_display_type'] : '0';

            if(is_array($header_fields)){
                foreach($header_fields as $header_field){
                    if($header_field != '0'){
                        $value = get_user_meta($id,$header_field,true);
                        if($header_field == 'user_url'){
                            $value = "<a href='".$value."' >".$value."</a>";
                        }

                        if(in_array($header_field, $date_fields)){
                                if('' != $value){
                                    $upme_settings = $upme_options->upme_settings;
                                    $upme_date_format = (string) isset($upme_settings['date_format']) ? $upme_settings['date_format'] : 'mm/dd/yy';
                                    // echo "<pre>";echo $header_field;exit;
                                    $value = upme_date_format_to_custom($value, $upme_date_format);
                                }
                        }

                        $profile_value_params = array('user_id' => $id, 'meta' => $field['meta'] );
                        $value = apply_filters('upme_profile_header_field_value_' . $field['meta'], $upme->get_user_name($id), $profile_value_params);

                        if($header_fields_display == '0'){
                            $display .= "<p><strong>".$field_names[$header_field]." : </strong>".$value."</p>";
                        }else{
                            $display .= "<p>".$value."</p>";
                        }


                    }
                }
            }
        }

        return $display;
    }
}

$upme_profile_fields = new UPME_Profile_Fields();
