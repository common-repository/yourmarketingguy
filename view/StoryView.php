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
                      'yourMarketingGuy_story', 
                      array(
                          'StoryView',
                          'yourMarketingGuy_display_Story'
                          ));
    }

    public function yourMarketingGuy_display_Story(){
    
        $storyModel = new StoryModel(StoreView::$_CONF);
        $currentStory = $storyModel->getCurrentStory();
        $step = $storyModel->getCurrentStep();
                
		echo '<div class="ymg"><div class="logo"><img src="'. plugins_url( 'images/yourmarketingguy-logo.png', __FILE__ ) .'"/></div>';
        echo '<div class="intro">';
		
        // If user hasn't selected any story
        if($currentStory === FALSE)
        {
            echo "<h1><a href='admin.php?page=yourMarketingGuy_store'>".__("Please start over with a story","ymg-plugin"). "</a></h1>";
        }
        else
        {
			$postId = $storyModel->getCurrentPost();
			
			$story = unserialize(base64_decode($storyModel->getStory($currentStory)));
			$posts = unserialize(base64_decode($storyModel->getPosts()));    
				
            echo "<form action='admin.php' method='GET'>";
            echo "<input type='hidden' name='storyAction' value='updateStepAndPost'>";
            echo "<input type='hidden' name='step' value='1'>";
            echo "<input type='hidden' name='page' value='yourMarketingGuy_story'>";
            echo "<select name='postId'>";
            foreach($posts as $post)
            {
                Logger::logError("SotryView::62", "Delivered:".$post['id']."Got".$postId);
                if($post['id'] == $postId)
                {

                    echo "<option value='$post[id]' selected>$post[title]</option>";
                }
                else
                {
                    echo "<option value='$post[id]'>$post[title]</option>";
                }
            }
            echo "</select>";

            echo "<input type='submit' name='performAction' value='".__("Start story","ymg-plugin")."' class='btn btn-default btn-margin btn-hover'>";
			echo "<style>.progressbar li {width: ". 100/$story[tipCount] ."%;</style>";
			
			echo '<script>
			jQuery( document ).ready(function() {
				jQuery(".step-click").click(function() {
					window.location.href = "admin.php?page=yourMarketingGuy_story&storyAction=updateStepAndPost&step="+ jQuery(this).attr("data-step") + "&postId=" + jQuery(this).attr("data-post");
					});
			});
			
			</script>';
			
			echo '</div>';
			
			if(!isset($_GET[step]))
				{
				$_GET[step] = $step;	
				}
			
			/* echo '<div class="story-prev">'; 
			if($storyModel->hasPreviousStep())
                {
                    echo "<a href='admin.php?page=yourMarketingGuy_story&step=". ($_GET[step] - 1) ."'><div><img src='". plugins_url( 'images/prev-step.png', __FILE__ )."'/></div></a>";
                }  
			echo'</div>';
			
			echo '<div class="story-next">';
			if($storyModel->hasNextStep())
                {
                    echo "<a href='admin.php?page=yourMarketingGuy_story&step=". $_GET[step] + 1 ."'><div><img src='". plugins_url( 'images/next-step.png', __FILE__ )."'/></div></a>";
                }
			echo  '</div>';*/
			
			
			echo '<div class="story-steps"><ul class="progressbar">';
			
		
			
			
			for($i = 1; $i <= $story[tipCount];$i++)
				{
				if($_GET[step] >= $i)
					{
					echo "<li class=\"step-click active\" data-step=\"$i\" data-post=\"$postId\"></li>";	
					}
					else
					{	
					echo "<li class=\"step-click\" data-step=\"$i\" data-post=\"$postId\"></li>";	
					}
				}
		  
		  	echo '</ul></div>';
			
			
			
			echo '<class="ymg">';
			
			
            if($postId === FALSE)
            {
                echo "<h2>" . __("Please select a post first","ymg-plugin") . "</h2>";
            }
            else
            {
				
                $tip = $storyModel->getCurrentStoryTip();
				
                echo "</div><div class='tip'><h2>";
				//echo __("Your personalized tip","ymg-plugin");
				echo "</h2><span class='tip-text'>$tip<br/><br/>";
				
                $done = $storyModel->checkStoryTip();
                echo (($done == 1)?("Please work harder!"):("You've done a great job."));
				
            }
        
		echo '</div></div>';
		}
    }
}
