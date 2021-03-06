<?php

class UPME_Modules{

	public function __construct(){

		if (is_admin ()) {
            add_action('wp_ajax_save_upme_module_settings', array($this, 'upme_save_module_settings'));
        	add_action('wp_ajax_reset_upme_module_settings', array($this, 'upme_reset_module_settings'));
            
        }

	}

	public function upme_save_module_settings(){
        
        if(!upme_verify_admin_permission())  {                
            echo json_encode(array('status'=>'error')); exit;
        }

        $current_options = get_option('upme_options');

        $this->array_field_options = array(
                'upme-site-lockdown-settings' =>  array('site_lockdown_allowed_pages','site_lockdown_allowed_posts','site_lockdown_status'), 
                'upme-email-general-settings' =>  array('email_from_name','email_from_address','notifications_all_admins','email_content_type_status'),
                'upme-custom-fields-settings' =>  array('help_text_html','profile_collapsible_tabs','profile_collapsible_tabs_display'),
        		'upme-header-fields-settings' =>  array('header_fields','header_field_display_type','header_fields_compact_view'),
                					);
        /* Add the settings for addons through filters */
        $this->array_field_options = apply_filters('upme_module_settings_array_fields',$this->array_field_options);
        
        parse_str($_POST['data'], $setting_data);        
        
        foreach($setting_data as $key=>$value)
                $current_options[$key]=$value;

        if(count($this->array_field_options[upme_post_value('current_tab')]) > 0){
    
            foreach($this->array_field_options[upme_post_value('current_tab')] as $key=>$value)
            {
                
                if(!array_key_exists($value, $setting_data)){
                    if(in_array($value, array('site_lockdown_allowed_pages','site_lockdown_allowed_posts'))){
                        $current_options[$value]='';
                    }else{
                        $current_options[$value]='0';
                    }
                    
                }
                    
            }
        }

        
        
        update_option('upme_options', $current_options);
        echo json_encode(array('status'=>'success')); exit;
	}

	public function upme_reset_module_settings(){
		global $upme_admin;

        if(!upme_verify_admin_permission())  {                
            echo json_encode(array('status'=>'error')); exit;
        }
        
        if(upme_is_post() && upme_is_in_post('current_tab')){

            if(isset($upme_admin->default_module_settings[upme_post_value('current_tab')])){
                $current_options = get_option('upme_options');

                foreach($upme_admin->default_module_settings[upme_post_value('current_tab')] as $key=>$value)
                    $current_options[$key] = $value;
                
                update_option('upme_options', $current_options);
                echo json_encode(array('status'=>'success')); exit;
            }
        }
    }

}

$upme_modules = new UPME_Modules();