<?php

class UPME_Field_Validations{

	public function __construct(){
		
		add_action('wp_ajax_upme_field_validation_load_settings', array($this,'upme_field_validation_load_settings'));
        add_action('wp_ajax_upme_field_validation_save_settings', array($this,'upme_field_validation_save_settings'));
        
	}

	public function upme_field_validation_load_settings(){

		$field = isset($_POST['field_key']) ? sanitize_text_field($_POST['field_key']) : '';
		if($field != ''){
			$validations = (array) get_option('upme_profile_field_validations');
			if(isset($validations[$field])){
				$data = $validations[$field];		

				$result = array('status' => 'success' , 'data' => $data);
			}else{
				$result = array('status' => 'empty', 'msg' => __('No validations found.','upme'));
			}
		}else{
			$result = array('status' => 'error', 'msg' => __('Request failed.','upme'));
		}

		echo json_encode($result);exit;
	}

	public function upme_field_validation_save_settings(){

		$field = isset($_POST['field_key']) ? sanitize_text_field($_POST['field_key']) : '';
		$validations_list = isset($_POST['validations']) ? (array) $_POST['validations'] : array();

		if($field != ''){
			$validations = (array) get_option('upme_profile_field_validations');
			// $validations[$field] = 
			$single_field_validations = array();
			foreach ($validations_list as $key => $value) {
				if($key != '' && $value != '')
					$single_field_validations[$key] = $value;
			}
			$validations[$field] = $single_field_validations;
			update_option('upme_profile_field_validations',$validations);
			$result = array('status' => 'success', 'msg' => __('Validations saved successfully.','upme'));
		}else{
			$result = array('status' => 'error', 'msg' => __('Request failed.','upme'));
		}

		echo json_encode($result);exit;
	}

	public function get_validation_attributes($meta_key){
		$validations = (array) get_option('upme_profile_field_validations');
		if(isset($validations[$meta_key])){
			$validations_str = '';
			foreach ($validations[$meta_key] as $key => $value) {
				switch ($key) {
					case 'min_length':
						$validations_str .= " minlength=".$value." ";
						break;
					
					case 'max_length':
						$validations_str .= " maxlength=".$value." ";
						break;
				}
			}

			return $validations_str;
		}else{
			return;
		}
	}

	public function validate_registration_field_save($meta_key,$field_value){
		global $upme_register,$upme_save;
		$validations = (array) get_option('upme_profile_field_validations');

		if(isset($validations[$meta_key]) && trim($field_value) != ''){
			$validations_str = '';
			foreach ($validations[$meta_key] as $key => $value) {

				switch ($key) {
					case 'min_length':
						if(strlen($field_value) < $value ){
							$upme_register->errors[] = sprintf(__('Please enter minimum of %s characters for %s.', 'upme'),$value,$upme_save->upme_fileds_meta_value_array[$meta_key]);
						}
						break;
					
					case 'max_length':
						if(strlen($field_value) > $value ){
							$upme_register->errors[] = sprintf(__('Please enter maximum of %s characters for %s.', 'upme'),$value,$upme_save->upme_fileds_meta_value_array[$meta_key]);
						
						}
						break;
				}
			}


		}
	}

	public function validate_frontend_field_save($meta_key,$field_value){
		global $upme_register,$upme_save;
		$validations = (array) get_option('upme_profile_field_validations');

		if(isset($validations[$meta_key]) && trim($field_value) != ''){
			$validations_str = '';
			foreach ($validations[$meta_key] as $key => $value) {

				switch ($key) {
					case 'min_length':
						if(strlen($field_value) < $value ){
							$upme_save->errors[$key] = sprintf(__('Please enter minimum of %s characters for %s.', 'upme'),$value,$upme_save->upme_fileds_meta_value_array[$meta_key]);
						}
						break;
					
					case 'max_length':
						if(strlen($field_value) > $value ){
							$upme_save->errors[$key] = sprintf(__('Please enter maximum of %s characters for %s.', 'upme'),$value,$upme_save->upme_fileds_meta_value_array[$meta_key]);
						
						}
						break;
				}
			}


		}
	}

	public function validate_backend_field_save($meta_key,$field_value){
		global $upme_admin,$upme_save;
		$validations = (array) get_option('upme_profile_field_validations');

		if(isset($validations[$meta_key]) && trim($field_value) != ''){
			$validations_str = '';
			foreach ($validations[$meta_key] as $key => $value) {

				switch ($key) {
					case 'min_length':
						if(strlen($field_value) < $value ){
							$upme_admin->errors[$key] = sprintf(__('Please enter minimum of %s characters for %s.', 'upme'),$value,$upme_save->upme_fileds_meta_value_array[$meta_key]);
						}
						break;
					
					case 'max_length':
						if(strlen($field_value) > $value ){
							$upme_admin->errors[$key] = sprintf(__('Please enter maximum of %s characters for %s.', 'upme'),$value,$upme_save->upme_fileds_meta_value_array[$meta_key]);
						
						}
						break;
				}
			}


		}
	}
}

$upme_field_validations = new UPME_Field_Validations();