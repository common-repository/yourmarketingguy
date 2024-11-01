<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
defined( 'YMG_PATH' ) or die( 'No script kiddies please!' );

require_once YMG_PATH . '/view/View.php';

class EulaView extends View{

   public static $_CONF;

   public function __construct($_CONF) {
       EulaView::$_CONF = $_CONF;
   }

   public function add(){

       add_menu_page( 'Your MarketingGuy', 
                      'Your MarketingGuy',                                          
                      'administrator', 
                      'yourMarketingGuy_overview', 
                      array(
                          'EulaView',
                          'yourMarketingGuy_display_eula'
                          ),
                      'dashicons-businessman',
					  '2.000123451'
                    );
   }

   public function yourMarketingGuy_display_eula()
   {
       // You can put the eula frontend stuff after this point.
       // If the user agrees just request .?eula=yes.
       // The eula flag will be set to true and we can power on our magic
       ?>
	   
       <?php
       /*<style>
	   #wpwrap{background: url(<?php echo plugins_url( 'images/ymg-bg.jpg', __FILE__ ); ?>) no-repeat center center fixed;}
	   </style>
       */?>
       

       
       <div class="ymg">
           <div class="logo">
           <img src="<?php echo plugins_url( 'images/yourmarketingguy-logo.png', __FILE__ ); ?>"/>
           </div>
        
        
           <div class="intro">
                   <h1><?php _e("Welcome to the place where the magic happens","ymg-plugin");?></h1>
               <h2><?php _e("All we need to get started is your permission to access your WordPress data.  This helps us to deliver personalized tips that will only make sense for your page.","ymg-plugin");?></h2><br/>
           
           <a href="?page=yourMarketingGuy_overview&eula=yes"><div class="btn btn-default btn-hover"><?php _e("I want to receive personalized tips","ymg-plugin");?></div></a><br/><br/>
           <small> <?php _e("And agree to the ","ymg-plugin");?> <a href="http://yourguy.marketing/privacy-policy/" target="_blank"><?php _e("Privacy Policy","ymg-plugin");?></a> & <a href="http://yourguy.marketing/t-c/" target="_blank"><?php _e("Terms Of Service","ymg-plugin");?></a></small>
           </div>
        
       </div>          
			
            

       
       
       
       
       
       
       
       <?php
   }
}