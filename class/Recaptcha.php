<?php

namespace Webdesignby;

class Recaptcha{
    
    public $plugin_name = "webdesignby_recaptcha";
    
    protected $_config;
    protected $_site_key    = "";
    protected $_secret_key  = "";
    
    public function __construct( $config = null ) {
        
        if( ! empty($config) ){
            $this->_config = $config;
            if( ! empty($config['site_key'])){
                $this->_site_key = $config['site_key'];
            }
            if( ! empty($config['secret_key'])){
                $this->_secret_key = $config['secret_key'];
            }
        }
        
        if( !empty($this->_secret_key) && !empty($this->_site_key))
        {
            $actions = array(
                'login_enqueue_scripts',
                'login_form',
                'wp_authenticate',
            );

            foreach($actions as $action){
                add_action( $action, array( $this, $action));
            }
        }
        
        add_action('admin_menu', array($this, 'admin_menu') );
        
    }
    
    public function login_enqueue_scripts(){
        
        ?>
        <style type="text/css">
            .wp-login-recaptcha-wrapper{
                margin-bottom:15px;
            }
            form#loginform{
                min-width:302px;
            }
        </style>
        <script type="text/javascript">

            var recaptcha1;
            var onloadCallback = function() {
                
                recaptcha1 = grecaptcha.render('g-recaptcha1', {
                  'sitekey' : '<?php echo $this->_site_key; ?>',
                  'theme' : 'light'
                });

              };
          </script>
        <?php
    }
    
    public function login_form(){
        $g_recaptcha_err = false;
        if( !empty($_GET['g-recaptcha_err']) ){
           $g_recaptcha_err = intval($_GET['g-recaptcha_err']);
        }
        if( $g_recaptcha_err ){
            $message_content = __("Please confirm you are not a robot", "webdesignby-recaptcha") . ".";
            $message = "<div style=\"color:#990000;\">" . $message_content . "</div>";
        }
        ?>
        <div class="wp-login-recaptcha-wrapper">
            <div class="g-recaptcha" id="g-recaptcha1"></div>
        <?php if( ! empty($message)){ echo $message; } ?>
        </div>
          <script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit' async defer></script>
        <?php
    }
    
    public function getRemoteIp(){
        return $_SERVER['REMOTE_ADDR'];
    }

    public function wp_authenticate(){
        if( ! empty($_POST)){
            $g_recaptcha_response = $_POST['g-recaptcha-response'];
            $g_recaptcha_check_url = "https://www.google.com/recaptcha/api/siteverify?secret=" . $this->_secret_key . "&response=" . $g_recaptcha_response . "&remoteip=" . $this->getRemoteIp();
            $g_recaptcha_check = json_decode(file_get_contents($g_recaptcha_check_url));
            if( empty($g_recaptcha_check->success) || !($g_recaptcha_check->success) ){
                 header('Location: wp-login.php?g-recaptcha_err=1');
                exit();
            }
        }
    }
    
    public function admin_menu(){
        new \Webdesignby\OptionsPage;
    }
    
    public static function uninstall(){
        delete_option('webdesignby_recaptcha');
    }
    
}
