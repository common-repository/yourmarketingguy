<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once YMG_PATH.'/core/BackendServer.php';
class HistoryModel
{
    private $backendUrl;
    function __construct($_CONF) {
        $this->backendUrl = $_CONF['backendServerUrl'];
    }
    
    public function getHistory()
    {
        $b = new BackendServer($this->backendUrl);
        $response = $b->callUrl("getHistory");
        
        if(is_wp_error($response))
        {
            Logger::logError(__METHOD__, $response->get_error_message());
            return "";
        }
        
        return $response['body'];
    }
}