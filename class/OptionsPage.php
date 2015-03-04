<?php

namespace Webdesignby;

if( ! class_exists('Webdesignby\OptionsPage')) {

    class OptionsPage{

        public $page_title = "reCaptcha Settings";
        public $menu_title = "reCaptcha";
        public $capabilites = "manage_options";
        public $slug = "webdesignby-recaptcha";

        function __construct() {
            add_options_page( $this->page_title, $this->menu_title, $this->capabilites, $this->slug, array( $this, 'settings_page' ) );
        }

        function  settings_page () {

                    if( ! empty($_POST) ){
                        check_admin_referer( 'process' );
                        update_option('webdesignby_recaptcha', $_POST['webdesignby_recaptcha']);
                        $message = "<div id=\"setting-error-settings_updated\" class=\"updated settings-error\"> 
                                    <p><strong>" . __('Settings saved', 'webdesignby-recaptcha') . "</strong></p></div>";
                    }
                    $opt = get_option('webdesignby_recaptcha');

                    if( ! empty($message))
                        echo $message;
                    ?>
                    <h1><?php echo __('reCaptcha Settings', 'webdesignby-recaptcha'); ?></h1>
                     <p>Generate a new site key and secret at:<br /><strong><a href="https://www.google.com/recaptcha/admin" target="_blank">https://www.google.com/recaptcha/admin</a></strong></p>
                    <form name="form" action="" method="post">
                    <?php echo wp_nonce_field('process'); ?>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th><label for="webdesignby_recaptcha[g_site_key]"><?php echo __('Site Key', 'webdesignby-recaptcha'); ?>:</label></th>
                                <td><input name="webdesignby_recaptcha[g_site_key]" id="g_site_key" type="text" class="regular-text code" value="<?php echo trim($opt['g_site_key']); ?>" /></td>
                            </tr>
                            <tr>
                                <th><label for="webdesignby_recaptcha[g_secret_key]"><?php echo __('Secret Key', 'webdesignby-recaptcha'); ?>:</label></th>
                                <td><input name="webdesignby_recaptcha[g_secret_key]" id="g_secret_key" type="text" class="regular-text code" value="<?php echo trim($opt['g_secret_key']); ?>" /></td>
                            </tr>
                        </tbody>
                    </table>
                    <p class="submit">
                        <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save Changes', 'webdesignby-recaptcha'); ?>">
                    </p>
                    </form>
                    <?php
            }

    }

}