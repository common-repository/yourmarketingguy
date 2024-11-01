<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
defined( 'YMG_PATH' ) or die( 'No script kiddies please!' );

require_once YMG_PATH . '/view/View.php';
require_once YMG_PATH . '/model/FTUModel.php';
class FTUView1 extends View{

   public static $_CONF;

   public function __construct($_CONF) {
       FTUView1::$_CONF = $_CONF;
   }

   public function add(){

       add_menu_page( 'Your MarketingGuy', 
                      'Your MarketingGuy', 
                      'administrator', 
                      'yourMarketingGuy_overview', 
                      array(
                          'FTUView1',
                          'yourMarketingGuy_display_FTU1'
                          ),
                      'dashicons-businessman',
					  '2.000123451'
                    );
       add_action( 'admin_print_scripts', array( $this, 'your_marketing_guy_auto_tour_script' ),100);
   }
   
   public function your_marketing_guy_auto_tour_script()
   {       
       
        $loc_get_tip =  __("Every day, this box has a new tip for you.","ymg-plugin");
        $loc_time_next_tip =  __("The counter tells you when a new tip will be ready. Meanwhile work on the current one to improve your website.","ymg-plugin");
        $loc_toplevel_page_yourMarketingGuy_overview =  __("Click here everytime you login to WordPress to check out the latest tip.","ymg-plugin");
        $loc_whats_next =  __("Got it. What's next?","ymg-plugin");
        $loc_end_tour =  __("End tour","ymg-plugin");
        
       echo "<script type='text/javascript'>
	   var tour = new Tour({
		  steps: [
		  {
			element: \"#get-tip\",
			content: \"$loc_get_tip\",
			placement: 'bottom'
		  },
		  {
			element: \"#time-next-tip\",
			content: \"$loc_time_next_tip\",
			placement: 'top'
		  },
		  {
			element: \"#toplevel_page_yourMarketingGuy_overview\",
			content: \"$loc_toplevel_page_yourMarketingGuy_overview\",
			placement: 'right'
		  },
		],
		template: \"<div class='popover tour'><div class='arrow'></div><div class='popover-content'></div><div class='popover-navigation'><button class='btn btn-default btn-next' data-role='next'>$loc_whats_next</button><button class='btn btn-default btn-end' data-role='end'>$loc_end_tour</button></nav></div>\",
		backdrop: true,
		backdropContainer: 'wpbody',
		storage: false
		});
	   
		   jQuery(document).ready(function() {
			jQuery.timeago.settings.allowFuture = true;  
	  		jQuery(\"time.timeago\").timeago();
					
			tour.init();
			tour.start(true);
			
			jQuery(\"#start-tour\").click(function() {
				tour.start(true);	
			});
			
		   });
		   
	   </script>";
   }

   public function yourMarketingGuy_display_FTU1()
   {
        $f = new FTUModel( FTUView1::$_CONF);         

        $userId = get_option( YMG_PREFIX . "customerId", FALSE );  
        if( !$userId )
        {
            $serverData = $f->createNewUser();
            $userId = $serverData['id'];
            $guyName = $serverData['guyName'];

            if($userId !== FALSE) {
                
            add_option( YMG_PREFIX . "customerId", $userId);
            add_option( YMG_PREFIX . "uid", $f->uid);
            add_option( YMG_PREFIX . "guyName", $guyName);
            update_option( YMG_PREFIX . "FTUStep", 2);
             $f->initialDataCommit($userId);    
            }
        }
       $s = new SettingsModel(FTUView1::$_CONF);
       ?>
       
        <div class="ymg">
           <div class="logo">
           <img src="<?php echo plugins_url( 'images/yourmarketingguy-logo.png', __FILE__ ); ?>"/>
           </div>
        
        
           <div class="intro">
                   <h1><?php _e("YourMarketingGuy helps you being successful online","ymg-plugin");?></h1>
               <h2><?php _e("He does this by providing useful and simple tips that will bring you closer to success, step by step.","ymg-plugin");?></h2>
           </div>
        
        
           	<div class="tip" >
           	<h2><?php _e("Your personalized tip of the day","ymg-plugin"); ?></h2>
            <span class="tip-text" id="get-tip"><?php echo $s->getTip(); ?></span>	
           	</div>

			<div class="find-out"> 
               <small id="time-next-tip"><?php _e("Next tip available in", "ymg-plugin");?><br/><time class="timeago" datetime="<?php echo $s->getNextTipTimestamp(); ?>"><?php echo $s->getNextTipTimestamp(); ?></time></small><br/><br/>
            <?php _e("You are using our Essential Plan (one tip per day) - Uprade now", "ymg-plugin");?>
            </div>               
			
            

            <div class="workflow">
            <hr/><br/>
            
            <?php _e("Find out how we work on your success", "ymg-plugin");?><br/>
            
               <div class="box">
               		<img src="<?php echo plugins_url( 'images/icon-checking-public-data.png', __FILE__ ); ?>"/><br/><?php _e("Checking public data", "ymg-plugin");?><br/><br/>
               
               		<small><?php _e("YourMarketingGuy takes a look at all public data of your WordPress hosted website or blog and compares it to our “ideal scenario”.", "ymg-plugin");?></small>
               </div>
               <div class="box">
               		<img src="<?php echo plugins_url( 'images/icon-providing-tips.png', __FILE__ ); ?>"/><br/><?php _e("Providing tips", "ymg-plugin");?><br/><br/>
               		<small><?php _e("Once YourMarketingGuy analyzed your website or blog, it get’s back to you with easy to implement and understandable tips on how to make it more successful step by step.", "ymg-plugin");?></small>
               </div>
               <div class="box">
            		<img src="<?php echo plugins_url( 'images/icon-rocking-website.png', __FILE__ ); ?>"/><br/><?php _e("Rocking website","ymg-plugin");?><br/><br/>
               		<small><?php _e("We believe that the internet should be a success-engine for everyone, not just digital natives. YourMarketingGuy works together with you to make this happen.", "ymg-plugin");?></small>   
               </div><br/>
            <a class="btn btn-default btn-margin btn-hover" id="start-tour"><?php _e("Start Tour", "ymg-plugin");?></a> <a href="http://yourguy.marketing" class="btn btn-default btn-margin btn-hover" target="_blank"><?php _e("Support", "ymg-plugin");?></a> <a href="https://www.facebook.com/yourmkguy" class="btn btn-default btn-margin btn-hover" target="_blank"><?php _e("Facebook", "ymg-plugin"); ?></a> <a href="https://twitter.com/yourmkguy" class="btn btn-default btn-margin btn-hover" target="_blank"><?php _e("Twitter","ymg-plugin");?></a><br/>
            </div> 
            <hr/>
       </div>
       <?php
   }
}