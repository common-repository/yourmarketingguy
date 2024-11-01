<?php

require_once YMG_PATH . '/model/StoryModel.php';

class StoryView extends View
{
   public static $_CONF;

   public function __construct($_CONF) {
       
       StoryView::$_CONF = $_CONF;
    }
    
    public function add() {
   
        add_submenu_page('yourMarketingGuy_overview', 
                      'Your Workbench',
                      'Your Workbench',
                      'administrator', 
                      'yourMarketingGuy_storyOverview', 
                      array(
                          'StoryView',
                          'yourMarketingGuy_storyOverview'
                          ));
    
    
        add_submenu_page('yourMarketingGuy_storyOverview', 
                      'Overview',
                      'Overview',
                      'administrator', 
                      'yourMarketingGuy_story', 
                      array(
                          'StoryView',
                          'yourMarketingGuy_display_story'
                          ));
    }

    public function yourMarketingGuy_display_story(){
    
        $storyModel = new StoryModel(StoreView::$_CONF);
        
        echo "<h1>Your Stories</h1>";
        
        
        $story = unserialize(base64_decode($storyModel->getStory()));
        
        if($story == "")
        {
            echo "No Story purchased. Visit our Store to buy some or try some for free!";
        }
        else
        {
            echo "#:$story[id]<br>";
            echo "Title: $story[enTitle]<br>";
            echo "Description: $story[enDescription]<br>";
            echo "Price: $story[price]<br>";
            echo "Author:" . $story['author'] . "<br>";
            echo "Tips:" . $story['tipCount'] . "<br>";
            echo "Purchased:" . $story['purchased'] . "<br>";
        }
    }
}