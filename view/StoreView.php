<?php

require_once YMG_PATH . '/model/StoryModel.php';

class StoreView extends View
{
   public static $_CONF;

   public function __construct($_CONF) {
       
       StoreView::$_CONF = $_CONF;
    }

    public function add() {
   
        add_submenu_page('yourMarketingGuy_overview', 
                      'Library',
                      'Library',
                      'administrator', 
                      'yourMarketingGuy_store', 
                      array(
                          'StoreView',
                          'yourMarketingGuy_display_store'
                          ));
    }

    public function yourMarketingGuy_display_store(){
    
        $storyModel = new StoryModel(StoreView::$_CONF);
        
        $data = unserialize(base64_decode($storyModel->getStories()));
        
        if($data == "" || empty($data))
        {
            echo "No data to display";
        }
        else
        {
			echo '<div class="container"><div id="stories" class="va-container">';
			echo '<div class="va-nav"><span class="va-nav-prev" style="background:transparent url('. plugins_url( 'images/prev.png', __FILE__ ). ') no-repeat center center;">'.__('Previous',"ymg-plugin").'</span><span class="va-nav-next"  style="background:url('. plugins_url( 'images/next.png', __FILE__ ). ');">'.__('Next',"ymg-plugin").'</span></div>';
			echo '<div class="va-wrapper">';
			
            foreach($data as $row)
            {
               
                if($row['purchased'] == 1)
                {
                        
                    if($storyModel->hasStartedOver($row['id']))
                    {
                        $action_url = "admin.php?page=yourMarketingGuy_story&storyAction=recoverStory&storyId=$row[id]"; 
			$action_name = __("Continue","ymg-plugin");		 
                    }
                    else
                    {
                        $action_url = "admin.php?page=yourMarketingGuy_story&storyAction=startingOver&storyId=$row[id]"; 
			$action_name = __("Start","ymg-plugin");  
                    }
                }
                else
                {
                    if($row['price'] == "0.0")
                    {
                        // Yayy it's for free
                        $action_url = "admin.php?page=yourMarketingGuy_story&storyAction=purchaseStory&storyId=$row[id]";
                        $action_name = __("Free","ymg-plugin");  
                    }
                    else
                    {
                        $action_url = "admin.php?page=yourMarketingGuy_store&storyAction=purchaseStory&storyId=$row[id]";
                        $action_name = __("Purchase","ymg-plugin");
                    }
                }
				
				
				echo '<div class="va-slice va-slice-'. $row[id] .'" style="background:#000 url(http://yourguy.marketing/plugin/images/stories/'. $row[id] .'.jpg) no-repeat center center;background-size: cover;"><h3 class="va-title">'. $row[title] .'</h3>';
				echo '<div class="va-content">';
				echo '<p>'. nl2br($row[description]) .'</p><a href="'. $action_url .'"><div class="btn btn-default">'. $row[price] .'€ - '. $action_name .'</div></a></div></div>';
            }
            
            echo '</div></div></div>';
			echo '
			<script type="text/javascript">
			jQuery(function() {
				jQuery("#stories").vaccordion({
					accordionW		: 800,
					accordionH		: 650,
					visibleSlices	: 6,
					expandedHeight	: 450,
					animOpacity		: 0.1,
					contentAnimSpeed: 100
				});
			});
		</script>';
        }
    }
}