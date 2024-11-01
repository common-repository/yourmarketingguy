<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
defined( 'YMG_PATH' ) or die( 'No script kiddies please!' );

abstract class View {

    public static $_CONF;
    public abstract function __construct($_CONF);
    public abstract function add();
    
    /*
     * You have to add another function where the HTML magic happens
     * Because every instance of this method needs to have a new method signature
     * the method can't be defined abstract.
     * You also have to copy the "add menu" logic in add
     */
}