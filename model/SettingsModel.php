<?php

/*
 * Default model for our default view
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once YMG_PATH.'/core/BackendServer.php';
class SettingsModel
{
    private $backendUrl;
    function __construct($_CONF) {
        $this->backendUrl = $_CONF['backendServerUrl'];
    }
    
    function getTip()
    {
        $b = new BackendServer($this->backendUrl);
        $response = $b->callUrl('getTip');
        
        if(is_wp_error($response))
        {
            Logger::logError(__METHOD__, $response->get_error_message());
            return "We are currently analyzing your webpage and will deliver the next tip soon!";
        }
        
        return $response['body'];
    }
    
    function getNextTipTimestamp() {
    
        $b = new BackendServer($this->backendUrl);
        $response = $b->callUrl("getNextTipTimestamp");
        
        if(is_wp_error($response))
        {
            Logger::logError(__METHOD__, $response->get_error_message());
            return $response->get_error_message() . " -> " . $this->backendUrl;
        }
        
        return $response['body'];
    }

}