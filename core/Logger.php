<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
defined( 'YMG_PATH' ) or die( 'No script kiddies please!' );

class Logger{
    
    public static $logfile = "";
    public static $logpath = "";
    public static $logext = "";
    public static $endl = PHP_EOL;
    public static $MAX_FILE_SIZE = 1048576; // 10Kb 
    
    private static function createLogfile()
    {
        $logfile = Logger::$logpath . Logger::$logfile . Logger::$logext;
        $date = LOGGER::timestamp();
        
        $str =  "##############################################################" . Logger::$endl;
        $str .= "#                                                            #" . Logger::$endl;
        $str .= "# Your Marketing Guy                                         #" . Logger::$endl;
        $str .= "# Plugin Logfile                                             #" . Logger::$endl;
        $str .= "#                                                            #" . Logger::$endl;
        $str .= "# Created: $date                               #" . Logger::$endl;
        $str .= "# Customer-ID: " . get_option(YMG_PREFIX . "customerId", "Not defined") . "   #". Logger::$endl;
        $str .= "##############################################################" . Logger::$endl;
        
        // Checks if log dir is existing and creates it if not
        if ( !file_exists( Logger::$logpath )) {
            mkdir( Logger::$logpath );
        }
        
        file_put_contents( $logfile, $str );
    }
    
    private static function timestamp()
    {
        return date('Y-m-d H:i:s');
    }
    
    private static function log($level, $source,$msg)
    {
        $logfile = Logger::$logpath . Logger::$logfile . Logger::$logext;
        
        if(!file_exists($logfile) ||
           filesize($logfile) > Logger::$MAX_FILE_SIZE)
        {
            Logger::createLogfile();
        }
        
        file_put_contents($logfile, LOGGER::timestamp() . " [$level] $source: $msg" . Logger::$endl, FILE_APPEND);
        flush();
    }
    
    public static function logError($source,$msg)
    {
        LOGGER::log("Error",$source,$msg);
    }
    public static function logDebug($source,$msg)
    {
        LOGGER::log("Debug",$source,$msg);
    }
    public static function logInfo($source,$msg)
    {
        LOGGER::log("Info",$source,$msg);
    }    
    public static function logWarning($source,$msg)
    {
        LOGGER::log("Warning",$source,$msg);
    }
    
}