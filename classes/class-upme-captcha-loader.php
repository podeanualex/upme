<?php

class UPME_captcha_loader
{
    public $load_captcha = false;
    private $captcha_plugin = '';
    public $default_captcha_plugin = 'recaptcha';
    
    public function __construct()
    {
        // Nothing to do here.    
    }
    
    private function load_captcha_plugin_setting($captcha= '')
    {
        // Getting values from database
        $settings = get_option('upme_options');
        
        // Shortcode optionis not given or given blank
        if($captcha == '')
        {
            if(isset($settings['captcha_plugin']) && $settings['captcha_plugin'] != '' && $settings['captcha_plugin'] != 'none')
            {
                $this->load_captcha = true;
                $this->captcha_plugin = $settings['captcha_plugin']; 
            }
            else
            {
                $this->load_captcha = false;
            }
            
        }
        else if($captcha == 'no' || $captcha == 'false')
        {
            $this->load_captcha = false;
        }
        else 
        {
            if($captcha == 'yes' || $captcha == 'true')
            {
                if(isset($settings['captcha_plugin']) && $settings['captcha_plugin'] != '' && $settings['captcha_plugin'] != 'none')
                {
                    $this->load_captcha = true;
                    $this->captcha_plugin = $settings['captcha_plugin']; 
                }
                else
                {
                    $this->load_captcha = false;
                }
            }
            else
            {
                $this->load_captcha = true;
                $this->captcha_plugin = $captcha;
            }
                
        }
        
    }
    
    public function load_captcha($captcha= '',$template = '')
    {
        global $upme,$upme_template_loader,$upme_login_captcha_params;
        
        // Load captcha plugin settings based on shortcode and database values.
        $this->load_captcha_plugin_setting($captcha);
        
        if($this->load_captcha == true)
        {

            $method_name = 'load_'.$this->captcha_plugin;
            
            if(method_exists($this, $method_name))
            {
                $captcha_html = '';
                $captcha_html = $this->$method_name();
                
                if($captcha_html == '')
                {
                    return $this->load_no_captcha_html();
                }
                else
                {
                    $form_text = '';
                    $form_text = $upme->get_option('captcha_label');
                    if($form_text == '')
                        $form_text = __('Human Check','upme');
                    

                    $upme_login_captcha_params['form_text'] = $form_text;
                    $upme_login_captcha_params['captcha_html'] = $captcha_html;
                    $upme_login_captcha_params['captcha_plugin'] = $this->captcha_plugin;

                    ob_start();
                    if($template == ''){
                        $upme_template_loader->get_template_part('login-form-captcha-default');
                    }else{
                        $upme_template_loader->get_template_part('login-form-captcha-' .$template);
                    }
                    
                    $display = ob_get_clean();
                       
                  
                    return $display;
                }
            }
            else
            {
                $captcha_html = '';
                $captcha_html_params = array();
                $captcha_html = apply_filters('upme_captcha_load_'.$this->captcha_plugin,$captcha_html,$captcha_html_params);
                
                if($captcha_html == '')
                {
                    return $this->load_no_captcha_html();
                }
                else
                {
                    $form_text = '';
                    $form_text = $upme->get_option('captcha_label');
                    if($form_text == '')
                        $form_text = __('Human Check','upme');
                    
                    
                    $upme_login_captcha_params['form_text'] = $form_text;
                    $upme_login_captcha_params['captcha_html'] = $captcha_html;
                    $upme_login_captcha_params['captcha_plugin'] = $this->captcha_plugin;

                    ob_start();
                    if($template == ''){
                        $upme_template_loader->get_template_part('login-form-captcha-default');
                    }else{
                        $upme_template_loader->get_template_part('login-form-captcha-' .$template);
                    }
                    $display = ob_get_clean();
                    
                    return $display;
                }
            }
        }
        else
        {
            return $this->load_no_captcha_html();
        }
    }
    
    public function load_no_captcha_html()
    {
        return '<input type="hidden" name="no_captcha" value="yes" />';
    }
    
    public function validate_captcha($captcha_plugin = '')
    {

        if($captcha_plugin == '')
        {
            // No plugin set, returning true
            return true; 
        }
        else
        {
            $method_name = 'validate_'.$captcha_plugin;
            
            if(method_exists($this, $method_name))
            {
                return $this->$method_name();
            }
            else
            {
                $captcha_check_params = array();
                $status = apply_filters('upme_captcha_check_'.$captcha_plugin,true,$captcha_check_params);
                return $status;
                
                //return true;
            }
            
        }
    }
    
    

    /*
     *  Function to Load Captcha by BestWebSoft
     */
    private function load_captchabestwebsoft()
    {
    
        if ( function_exists( 'cptch_register_form' ) ) 
        {

            ob_start();
            if( function_exists( 'cptch_display_captcha_custom' ) ) { 
                echo "<input type='hidden' name='cntctfrm_contact_action' value='true' />"; 
                echo cptch_display_captcha_custom();
            }; 
            if( function_exists( 'cptchpr_display_captcha_custom' ) ) { 
                echo "<input type='hidden' name='cntctfrm_contact_action' value='true' />"; 
                echo cptchpr_display_captcha_custom(); 
            };

            $display = ob_get_clean();
            return $display;
        }   
        else
        {
            return '';
        }    
    }

    /*
     *  Function to validate Captcha by BestWebSoft
     */
    private function validate_captchabestwebsoft()
    {
        if ( ( function_exists( 'cptch_check_custom_form' ) && cptch_check_custom_form() !== true ) 
                || ( function_exists( 'cptchpr_check_custom_form' ) && cptchpr_check_custom_form() !== true ) ){ 
            return false;
        }else{
            return true;
        }

    }


    /*
     *  Function to Load SI Captcha
     */
    private function load_si_captcha()
    {
    
        if ( class_exists( 'ReallySimpleCaptcha' ) ) 
        {

            $captcha_instance = new ReallySimpleCaptcha();
            $captcha_instance->bg = array( 0, 0, 0 );
            $word = $captcha_instance->generate_random_word();
            $prefix = mt_rand();
            return $captcha_instance->generate_image( $prefix, $word );
        }   
        else
        {
            return '';
        }    
    }

    /*
     *  Function to validate SI Captcha
     */
    private function validate_si_captcha()
    {
        if ( class_exists( 'ReallySimpleCaptcha' ) ) 
        {

        }else{
            return true;
        }

    }
    

   /*
    *  Function to Load ReCaptcha
    */
    
    private function load_recaptcha_class()
    {
        
        require_once upme_path . 'classes/class-upme-recaptchalib.php';
    } 
    
    private function load_recaptcha()
    {
        global $upme,$upme_captcha_loader;

        // Getting the Public Key to load reCaptcha
        $public_key = '';
        $public_key = $upme->get_option('recaptcha_public_key');

        if($public_key != '')
        {
            $captcha_code = '';

            // Loading the theme configured in admin.
            //$recaptcha_theme = $upme->get_option('recaptcha_theme');
            $recaptcha_theme = 'upme';

            if($recaptcha_theme == 'upme')
            {
                $theme_code = "<script type=\"text/javascript\"> var RecaptchaOptions = {    theme : 'custom',lang: 'en',    custom_theme_widget: 'recaptcha_widget' };</script>";
                $captcha_code = $this->load_custom_nocaptcharecaptcha($public_key);
            }
            else
            {
                $theme_code = "<script type=\"text/javascript\">var RecaptchaOptions = {theme : '".$recaptcha_theme."', lang:'en'};</script>";
                if(is_ssl()){
                    $captcha_code = recaptcha_get_html($public_key, null, true);
                }else{
                    $captcha_code = recaptcha_get_html($public_key, null);
                }

            }

            return $theme_code.$captcha_code;
        }
        else
        {
            // No public key is not set in admin. So loading no captcha HTML. 
            return $upme_captcha_loader->load_no_captcha_html();
        }
        
    }

    public function load_custom_nocaptcharecaptcha($public_key='')
        {
            wp_register_script('upme_nocaptcha_script', 'https://www.google.com/recaptcha/api.js' );
            wp_enqueue_script('upme_nocaptcha_script');

            return '<div class="g-recaptcha" data-sitekey="' . $public_key . '"></div>
                    <noscript>
                      <div>'.__('Please enable Javascript in your web browser.','upme').'
                      <div>
                        <div style="width: 302px; height: 422px; position: relative;">
                          <div style="width: 302px; height: 422px; position: absolute;">
                            <iframe src="https://www.google.com/recaptcha/api/fallback?k=your_site_key"
                                    frameborder="0" scrolling="no"
                                    style="width: 302px; height:422px; border-style: none;">
                            </iframe>
                          </div>
                        </div>
                        <div style="width: 300px; height: 60px; border-style: none;
                                       bottom: 12px; left: 25px; margin: 0px; padding: 0px; right: 25px;
                                       background: #f9f9f9; border: 1px solid #c1c1c1; border-radius: 3px;">
                          <textarea id="g-recaptcha-response" name="g-recaptcha-response"
                                       class="g-recaptcha-response"
                                       style="width: 250px; height: 40px; border: 1px solid #c1c1c1;
                                              margin: 10px 25px; padding: 0px; resize: none;" >
                          </textarea>
                        </div>
                      </div>
                    </noscript>';

        }
        
        public function load_nocaptcha_recaptcha_class(){
            require_once upme_path . 'lib/ReCaptcha/Response.php';
            require_once upme_path . 'lib/ReCaptcha/RequestParameters.php';
            require_once upme_path . 'lib/ReCaptcha/RequestMethod.php';
            require_once upme_path . 'lib/ReCaptcha/RequestMethod/Post.php';
            require_once upme_path . 'lib/ReCaptcha/RequestMethod/Socket.php';
            require_once upme_path . 'lib/ReCaptcha/RequestMethod/SocketPost.php';
            require_once upme_path . 'lib/ReCaptcha/ReCaptcha.php';
        }
    
   /*
    *  Function to Validate ReCaptcha
    */
    
    private function validate_recaptcha()
    {
        global $upme;
        $this->load_nocaptcha_recaptcha_class();

        // Getting the Private Key to validate reCaptcha
        $private_key = '';
        $private_key = $upme->get_option('recaptcha_private_key');


        if($private_key != '')
        {
            if (upme_is_in_post('g-recaptcha-response'))
            {
                $recaptcha = new \ReCaptcha\ReCaptcha($private_key);
                $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
                if ($resp->isSuccess())
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return true;
            }    
        }
        else
        {
            // Private key is not set in admin
            return true;
        }
    }
    
    private function load_custom_recaptcha($public_key='')
    {

        $reCaptcha_server = RECAPTCHA_API_SERVER;
        if(is_ssl()){
            $reCaptcha_server = RECAPTCHA_API_SECURE_SERVER;
        }

        return '<div id="recaptcha_widget">
                        <div id="recaptcha_image_holder">
                            <div id="recaptcha_image" class="upme-captcha-img"></div>
                            <div class="recaptcha_text_box">
                                <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" class="text" placeholder="' . __('Enter Verification Words','upme') .'" />
                            </div>
                        </div>
                        <div id="recaptcha_control_holder">
                            <a href="javascript:Recaptcha.switch_type(\'image\');" title="' . __('Load Image','upme') .'"><i class="upme-icon upme-icon-camera"></i></a>
                            <a href="javascript:Recaptcha.switch_type(\'audio\');" title="' . __('Load Audio','upme') .'"><i class="upme-icon upme-icon-volume-up"></i></a>
                            <a href="javascript:void(0);" id="recaptcha_reload_btn" onclick="Recaptcha.reload();" title="' . __('Refresh Image','upme') .'"><i class="upme-icon upme-icon-refresh"></i></a>
                        </div> 
                </div>

                 <script type="text/javascript" src="'.$reCaptcha_server.'/challenge?k='.$public_key.'"></script>
                 <noscript>
                   <iframe src="'.$reCaptcha_server.'/noscript?k='.$public_key.'" height="300" width="500" frameborder="0"></iframe>
                   <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
                   <input type="hidden" name="recaptcha_response_field" value="manual_challenge">
                 </noscript>';
    }
    

}

$upme_captcha_loader = new UPME_captcha_loader();