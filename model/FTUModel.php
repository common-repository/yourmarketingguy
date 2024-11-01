<?php

/*
 * Model to generate client-side user data (such as an uid) and call the createUser API function
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once YMG_PATH.'/core/Farming.php';
require_once YMG_PATH.'/core/BackendServer.php';

class FTUModel {
    
    private $_CONF;
    public $uid;
    public function __construct($_CONF) {
        $this->_CONF = $_CONF;
        $this->generateUid();
    }
    
    public function initialDataCommit( ) {
        $f = new Farming($this->_CONF['backendServerUrl'], $this->_CONF['wpOptionsWhitelist']);
        $f->farmComments();
        $f->farmPosts();
        $f->farmOptions();
        $f->sendToBackend( );
    }
    
    private function generateUid() {
    
       $this->uid = md5( get_option("siteurl") . time() );
    }
    
    public function createNewUser() {
        
        $currentUser = wp_get_current_user(); 
        $pluginData = get_plugin_data( YMG_PATH . "/index.php" );
        
        $customerData = array(
                                'email' => $currentUser->user_email,
                                'name' => $currentUser->user_firstname . " " . $currentUser->user_lastname,
                                'domain' => get_option( 'siteurl', FALSE),
                                'installedVersion' => $pluginData['Version'],
                                'productId' => '0',
                                'language' => get_option( 'WPLANG', ""), // An empty language means en_EN and that is our default language
                                'uid' => $this->uid
                             );       
            
        
        $b = new BackendServer( $this->_CONF['backendServerUrl'] );
        $data = base64_encode( serialize( $customerData ) ) ;
        
        $response = $b->sendData( $data, "addCustomer");
        
        if(is_wp_error($response))
        {
            return FALSE;
            
        } 
        $responseData = unserialize($response['body']);
                
        return $responseData;
        
    }
}