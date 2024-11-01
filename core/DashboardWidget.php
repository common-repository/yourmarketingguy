<?php

/*
 * Where the dashboard will be generated.
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
defined( 'YMG_PATH' ) or die( 'No script kiddies please!' );

require_once YMG_PATH . '/model/SettingsModel.php';
class DashboardWidget {
    
    private static $_CONF;
    public function __construct( $_CONF) {
        
        DashboardWidget::$_CONF = $_CONF;
        
        
    }
    
    public function add() {
        add_action( 'wp_dashboard_setup', array( 'DashboardWidget', 'addYourMarketingGuyDashboardWidget' ) );
    }
    
    public function addYourMarketingGuyDashboardWidget() {
        
        wp_add_dashboard_widget(
                                'YourMarketingGuyDashoardWidget',
                                __('YourMarketingGuy - your personalized tip of the day','ymg-plugin'),
                                array( 'DashboardWidget' , 'displayYourMarketingGuyDashboardWidget' )
                                );
        
    }
    
    public function displayYourMarketingGuyDashboardWidget() {
        
        // Check if data already sent the last 7 days
        $lastUpdate = get_option(YMG_PREFIX . "lastWPOptionsUpdate", FALSE );
        
        if( !$lastUpdate || (time() - $lastUpdate) > DashboardWidget::$_CONF['wpOptionsUpdateInterval'])
        {
                // Farm options
               $f = new Farming( DashboardWidget::$_CONF['backendServerUrl'], DashboardWidget::$_CONF['wpOptionsWhitelist'] );
               $f->farmOptions();
               $f->sendToBackend();     

               update_option(YMG_PREFIX .  "lastWPOptionsUpdate", time() );                
            

        }

        $settingsModel = new SettingsModel(DashboardWidget::$_CONF);
        
        
        // Put our tips
        echo "<img src=\"". plugins_url( 'view/images/logo-dashboard.png', dirname(__FILE__) ) ."\" style=\"float:left;margin-right:15px;height:60px;\"/>";
        echo "<div style=\"min-height:60px\">" . $settingsModel->getTip() . "</div>";
        
    }
}