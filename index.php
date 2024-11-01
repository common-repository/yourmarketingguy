<?php
/* 
 * 
 * @wordpress-plugin
 * Plugin Name: YourMarketingGuy
 * Description: YourMarketingGuy will provide you with all essential tips to make your blog or websites visible and successful - understandable and easy!
 * Plugin URI:  https://yourguy.marketing/
 * Version: 2.0
 * Author: YourMarketingGuy
 * Author URI: https://yourguy.marketing/
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 * Domain Path: /language
 * Text-Domain: ymg-plugin
 * 
 */

$version = "2.0";

// Script Kiddie protection
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Set up environment
setupEnvironment();

require_once YMG_PATH . '/core/pluginConf.php';
require_once YMG_PATH . '/core/Logger.php';
require_once YMG_PATH . '/core/BackendServer.php';
require_once YMG_PATH . '/core/Farming.php';
require_once YMG_PATH . '/controller/AdminMenuController.php';
require_once YMG_PATH . '/core/DashboardWidget.php';
require_once YMG_PATH . '/model/SettingsModel.php';  
require_once YMG_PATH . '/core/DataSheduler.php';


// Set up Logger -> If smth goes wrong
Logger::$logfile = $_CONF['logFile'];
Logger::$logpath = $_CONF['logPath'];
Logger::$logext = $_CONF['logExtension'];

// Add hook to load language --> Future
add_action('plugins_loaded', 'yourMarketingGuy_loadLanguage');

// Set up hooks -> Install/Uninstall
register_activation_hook( __FILE__ , install );
add_action( 'admin_init', yourMarketingGuypostInstallRedirect );

register_uninstall_hook( __FILE__ , uninstall );

// Add visual elements
$adminController = new AdminMenuController( $_CONF );
$adminController->add();

// Add our Widget to the dashboard
$dashboardWidget = new DashboardWidget( $_CONF );
$dashboardWidget->add();

// Add hooks. 
// If Eula isn't agreed BackendServer won't let anything to our backend.
$d = new DataSheduler( $_CONF );
$d->addOnCommentPublished();
$d->addOnPostPublished();

function setupEnvironment() {

    if( !defined( "YMG_PATH" ) ) 
    {
        define( "YMG_PATH", dirname( __FILE__ ), TRUE );
    }
    
    if( !defined( "YMG_PREFIX" ) )
    {
        define( "YMG_PREFIX", "YMG_" );
    }
    
}

function install() {
    
    add_option( YMG_PREFIX . "installed", TRUE);
    add_option( YMG_PREFIX . "postInstallRedirect", TRUE );
    
    // If the YMGFTUStep option doesn't exist, the customer installs/activates
    // the plugin for the first time.
    if( get_option( YMG_PREFIX . "customerId", FALSE) === FALSE) {
        update_option( YMG_PREFIX . "FTUStep", 1);
        Logger::logInfo(__METHOD__, "User activated plugin for the first time.");
    }
}

function yourMarketingGuypostInstallRedirect() {
    if( get_option( YMG_PREFIX . "postInstallRedirect", FALSE)) {
        delete_option( YMG_PREFIX . "postInstallRedirect" );
        
        wp_redirect("admin.php?page=yourMarketingGuy_overview");
    }
}

function yourMarketingGuy_loadLanguage() {
 $plugin_dir = basename(dirname(__FILE__)) . "/language/";
 load_plugin_textdomain( 'ymg-plugin', false, $plugin_dir );
}

function uninstall()
{
   
    /*
     *  ONLY WP FUNCTIONS BEYOND THIS POINT!
     * 
     *  Because the delete_option destroys the ymg plugin context
     */
    
    delete_option(YMG_PREFIX . "guyName" );    
    delete_option(YMG_PREFIX . "installed" );
    delete_option(YMG_PREFIX . "customerId" );
    delete_option(YMG_PREFIX . "uid" );
    delete_option(YMG_PREFIX . "lastWPOptionsUpdate" );
    delete_option(YMG_PREFIX . "FTUStep" ); 
    delete_option(YMG_PREFIX . "eula" ); 
    delete_option(YMG_PREFIX . "storyCache" ); 
    delete_option(YMG_PREFIX . "currentStory" );  
   
}
?>
