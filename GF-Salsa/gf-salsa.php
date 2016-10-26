<?php
/*
 * Plugin Name: Gravity Form Integration with SalsaLabs
 * Description: This plugin allows to register users on Salsalabs using gravity forms.
 * Version: 1.0.0
 * Text Domain: wdm-gf-salsa
 */


add_action('init', 'checkGFDependency');

function checkGFDependency()
{

			if (!class_exists('GFForms')) {
				unset($_GET['activate']);
				deactivate_plugins(plugin_basename(__FILE__));
				add_action('admin_notices', 'salsaAdminNotice', 1);
                
                
            }else{

            	require_once 'includes/class-gs-admin-settings.php';
				new WdmGFSalsa\WdmAdminSettings();

				require_once 'includes/class-gs-user-group-creation.php';
				\GF_Fields::register( new  WdmGFSalsa\GFUserGrps() );

				require_once 'includes/class-gs-admin-group-creation.php';
				\GF_Fields::register( new  WdmGFSalsa\GFAdminGrps() );

				require_once 'includes/class-gs-state-group-creation.php';
				\GF_Fields::register( new  WdmGFSalsa\GFStateGrps() );

            }
}

function salsaAdminNotice()
{
            ?>
         
          <div class='error'><p><?php _e('<strong>Gravity Form</strong> plugin is not active. In order to make <strong>Gravity Form Integration with SalsaLabs</strong> plugin work, you need to install and activate <strong>Gravity Form</strong> first', 'wdm-gf-salsa'); ?></p></div>
     
        <?php
           
}




