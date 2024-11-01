<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
defined( 'YMG_PATH' ) or die( 'No script kiddies please!' );

class EulaModel{
    
    function __construct() {
        
        if(! get_option(YMG_PREFIX."eula"))
        {
            add_option(YMG_PREFIX."eula",FALSE);
        }
    }
    
    function accept(){
        update_option(YMG_PREFIX."eula",TRUE);
    }
    
    function checkIfAccepted() {
        $eula =  $_GET['eula'];
        
        if($eula == "yes") {
            $this->accept();
        }
    }
    
    function getState() {
        return get_option(YMG_PREFIX."eula",FALSE);
    }
}