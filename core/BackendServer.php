<?php
/*
 * Call the API of our backend through the {@link BackendServer} Class.
 * 
 * Every API Call has its own action. We post it to our backend and get 
 * plain text back.
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once ABSPATH."/wp-includes/http.php";
require_once YMG_PATH . "/model/EulaModel.php";
class BackendServer{
    
    private $backendUrl;
    
    public function __construct($backendUrl) {
        $this->backendUrl = $backendUrl;
    }
    
    private function request($action, $data = FALSE){
        
        // Don't send any data if user hasn't agreed the EULA!
        $eula = new EulaModel();
        if(!$eula->getState()) {
            Logger::logWarning(__METHOD__, "$action can't be executed because user didn't agree the EULA.");
            $response = array();
            $respnse['body'] = "Please agree EULA before requesting tips!";
            return $respnse;
        }
        
        $package = "";
        
        $bodyArray = array( 'action' => $action );
        
        $customerId = get_option( YMG_PREFIX . "customerId", FALSE);
        $uid = get_option( YMG_PREFIX . "uid" , FALSE);
        if($customerId !== FALSE) {
            $bodyArray['customerId'] = $customerId;
            $bodyArray['uid'] = $uid;
        }
        
        if($data !== FALSE)
        {
            $package = serialize($data);
            $bodyArray['data'] = $package;
        }
        
        $response = wp_remote_post($this->backendUrl, 
                                    array(
                                            'method' => 'POST',
                                            'body' => $bodyArray,
                                            'timeout' => 15
                                    ));
        
        return $response;
    }
    
    public function sendData($data, $action = 'sendData'){
        return $this->request( $action, $data);
    }
    
    public function callUrl($action){
        return $this->request($action);
    }
}