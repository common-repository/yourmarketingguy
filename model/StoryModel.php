<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
require_once YMG_PATH.'/core/BackendServer.php';

abstract class WPOptionKeys
{
    const storyCache = "YMG_storyCache"; 
    const currentStory = "YMG_currentStory";
}
class StoryModel
{
    private $backendUrl;
    
    function __construct($_CONF) {
        $this->backendUrl = $_CONF['backendServerUrl'];
        if(! get_option(WPOptionKeys::storyCache))
        {
            add_option(WPOptionKeys::storyCache,  array());
        }
        
        if(! get_option(WPOptionKeys::currentStory))
        {
            add_option(WPOptionKeys::currentStory,  array());
        }
     
        $this->checkAction();
    }
    
    function checkAction()
    {
        if(!$_GET['storyAction'])
        {
            return;
        }
        
        switch($_GET['storyAction'])
        {
            case "startingOver":
                if($_GET['storyId'])
                {
                    if($this->hasStartedOver($_GET['storyId']))
                    {
                        $this->recoverStory($_GET['storyId']);
                    }
                    else
                    {
                        $this->startingOver($_GET['storyId']);
                    }
                }
                break;
            case "recoverStory":
                if($_GET['storyId'])
                {
                    $this->recoverStory($_GET['storyId']);
                }
                break;
            case "updateStepAndPost":
                if($_GET['step'] && $_GET['postId'])
                {
                    $this->updateCurrentStory($_GET['step'], $_GET['postId']);
                }
                break;
            case "nextStep":
                $this->nextStep();
                break;
            case "previousStep":
                $this->previousStep();
                break;
            case "firstStep":
                $this->firstStep();
                break;
            case "lastStep":
                $this->lastStep();
                break;
            // Store Actions
            case "purchaseStory":
                if($_GET['storyId'])
                {
                    $price = 0.0;
                    $this->purchaseStory($_GET['storyId'], "7H15_5t0rY_isNt_4_S4l3", $price);
                    if($price == 0.0)
                    {
                        $this->startingOver($_GET['storyId']);
                    }
                    
                }
                break;
            default:
                break;
        }
        
    }
    
    function getPurchasedStories()
    {
        $b = new BackendServer($this->backendUrl);
        $response = $b->callUrl("getPurchasedStories");
        
        if(is_wp_error($response))
        {
            Logger::logError(__METHOD__, $response->get_error_message());
            return "";
        }
        
        return $response['body'];
    }
    
    function getStories()
    {
        $b = new BackendServer($this->backendUrl);
        $response = $b->callUrl("getStories");
        
        if(is_wp_error($response))
        {
            Logger::logError(__METHOD__, $response->get_error_message());
            return "";
        }
        
        return $response['body'];
    }
    
    function getStory($storyId)
    {
        $data = array(
            "storyId" => $storyId,
        );
        
        $b = new BackendServer( $this->backendUrl );
        
        $response = $b->sendData( $data, "getStory");
        
        if(is_wp_error($response))
        {
            return FALSE;
            
        }  
        
        return $response["body"];
    }
    
    function getStoryTip($storyId, $step, $postId)
    {
        $storyData = array(
            "storyId" => $storyId,
            "step" => $step,
            "postId" => $postId
        );
        
        $b = new BackendServer( $this->backendUrl );
        //$data = base64_encode( serialize( $storyData ) ) ;
        
        $response = $b->sendData( $storyData, "getStoryTip");
        
        if(is_wp_error($response))
        {
            return FALSE;
            
        }  
        
        return unserialize(base64_decode($response["body"]));
    }
    
    function getPosts()
    {
        $b = new BackendServer( $this->backendUrl );
        $response = $b->callUrl("getPosts");
        
        if(is_wp_error($response))
        {
            Logger::logError(__METHOD__, $response->get_error_message());
            return "";
        }
        
        return $response['body'];
    }
    
    function purchaseStory($storyId, $purchaseToken, $price)
    {
        $purchaseData = array(
            "storyId" => $storyId,
            "purchaseToken" => $purchaseToken,
            "price" => $price
        );
        
        $b = new BackendServer( $this->backendUrl );
        //$data = base64_encode( serialize( $purchaseData ) ) ;
        
        $response = $b->sendData( $purchaseData, "purchaseStory");
        
        if(is_wp_error($response))
        {
            return FALSE;
            
        }  
        return $response["body"];
    }
    
    function checkStoryTip()
    {
        $storyId = $this->getCurrentStory();
        $step = $this->getCurrentStep();
        $postId = $this->getCurrentPost();
        
        if($storyId  === FALSE || 
           $step === FALSE || 
           $postId === FALSE)
        {
            return FALSE;
        }
        
        $storyData = array(
            "storyId" => $storyId,
            "step" => $step,
            "postId" => $postId
        );
        
        $b = new BackendServer( $this->backendUrl );
        //$data = base64_encode( serialize( $storyData ) ) ;
        
        $response = $b->sendData( $storyData, "checkStoryTip");
        
        if(is_wp_error($response))
        {
            return FALSE;    
        }  
        //print_r("Server response:" . $response["body"]);
        return $response["body"];
        
    }
    
    function getCurrentStoryTip()
    {
        $storyId = $this->getCurrentStory();
        $step = $this->getCurrentStep();
        $postId = $this->getCurrentPost();
        
        
        if($storyId === FALSE || $step === FALSE || $getStoryTip === FALSE)
        {
            return FALSE;
        }
        else
        {
            return $this->getStoryTip($storyId, $step, $postId);
        }
    }
    
    function getCurrentStory()
    {
        $current = get_option(WPOptionKeys::currentStory,  array());
        
        if(!array_key_exists('storyId', $current)) 
        {
            return FALSE;
        }
        
        return $current['storyId'];
    }
    
    function getCurrentStep()
    {
        $current = get_option(WPOptionKeys::currentStory,  array());
        
        if(!array_key_exists('step', $current)) 
        {
            return FALSE;
        }
        
        return $current['step'];
    }
    
    function getCurrentPost()
    {
        $current = get_option(WPOptionKeys::currentStory,  array());
        
        if(!array_key_exists('postId', $current)) 
        {
            return FALSE;
        }
        
        return $current['postId'];
    }
    
    function updateCurrentStory($step, $postId)
    {
        // Load stored values from wp_options
        $cache = get_option(WPOptionKeys::storyCache,  array());
        $currentStory = get_option(WPOptionKeys::currentStory,  array());
        Logger::logError(__METHOD__, "Vorhher");
        Logger::logError(__METHOD__, print_r($cache, TRUE));
        Logger::logError(__METHOD__, print_r($currentStory, TRUE));

        $storyId = $this->getCurrentStory();
        if($storyId == -1)
        {
            return FALSE;
        }
        
        // Manipulate them
        $cache[$storyId] = array('step' => $step, 'postId' => $postId);
        $currentStory['step'] = $step;
        Logger::logError(__METHOD__, print_r($postId,TRUE));
        $currentStory['postId'] = $postId;
        
        Logger::logError(__METHOD__, "Nachher");
        Logger::logError(__METHOD__, print_r($cache, TRUE));
        Logger::logError(__METHOD__, print_r($currentStory, TRUE));
        
        // Store them back
        update_option(WPOptionKeys::storyCache,$cache);
        update_option(WPOptionKeys::currentStory, $currentStory);
    }
    
    function hasStartedOverAtAll()
    {
        return !empty(get_option(WPOptionKeys::currentStory,  array()));
    }
    function hasStartedOver($storyId)
    {
        
        return key_exists(intval($storyId), get_option(WPOptionKeys::storyCache,  array()) );
    }
    
    function hasNextStep()
    {
        $currentStory = get_option(WPOptionKeys::currentStory,  array());
        $storyDetails = unserialize(base64_decode($this->getStory($currentStory['storyId'])));

        return intval($currentStory['step']) < intval($storyDetails['tipCount']);
    }
    
    function hasPreviousStep()
    {
        $currentStory = get_option(WPOptionKeys::currentStory,  array());

        return intval($currentStory['step']) > 1;
    }
    
    function firstStep()
    {
          // Load stored values from wp_options
          $cache = get_option(WPOptionKeys::storyCache, array());
          $currentStory = get_option(WPOptionKeys::currentStory,  array());


          $storyId = $this->getCurrentStory();
          if($storyId === FALSE)
          {
              return FALSE;
          }

          // Manipulate them
          $cache[$storyId]['step'] = 1;
          $currentStory['step']= 1;


          // Store them back
          update_option(WPOptionKeys::storyCache,$cache);
          update_option(WPOptionKeys::currentStory, $currentStory);  
    }
    
    function lastStep()
    {
          // Load stored values from wp_options
          $cache = get_option(WPOptionKeys::storyCache,  array());
          $currentStory = get_option(WPOptionKeys::currentStory,  array());


          $storyId = $this->getCurrentStory();
          if($storyId === FALSE)
          {
              return FALSE;
          }

          $storyDetails = unserialize(base64_decode($this->getStory($currentStory['storyId'])));
          // Manipulate them
          $cache[$storyId]['step'] = $storyDetails['tipCount'];
          $currentStory['step']= $storyDetails['tipCount'];


          // Store them back
          update_option(WPOptionKeys::storyCache,$cache);
          update_option(WPOptionKeys::currentStory, $currentStory);  
    }
    
    function nextStep()
    {
        if($this->hasNextStep())
        {
          // Load stored values from wp_options
          $cache = get_option(WPOptionKeys::storyCache,  array());
          $currentStory = get_option(WPOptionKeys::currentStory,  array());


          $storyId = $this->getCurrentStory();
          if($storyId === FALSE)
          {
              return FALSE;
          }

          // Manipulate them
          $cache[$storyId]['step']++;
          $currentStory['step']++;


          // Store them back
          update_option(WPOptionKeys::storyCache,$cache);
          update_option(WPOptionKeys::currentStory, $currentStory);
        }
    }
    
    function previousStep()
    {
        if($this->hasPreviousStep())
        {
          // Load stored values from wp_options
          $cache = get_option(WPOptionKeys::storyCache,  array());
          $currentStory = get_option(WPOptionKeys::currentStory,  array());


          $storyId = $this->getCurrentStory();
          if($storyId === FALSE)
          {
              return FALSE;
          }

          // Manipulate them
          $cache[$storyId]['step']--;
          $currentStory['step']--;


          // Store them back
          update_option(WPOptionKeys::storyCache,$cache);
          update_option(WPOptionKeys::currentStory, $currentStory); 
        }
    }
    
    
    function recoverStory($storyId)
    {
        // Load stored values from wp_options
        $cache = get_option(WPOptionKeys::storyCache,  array());
        $currentStory = get_option(WPOptionKeys::currentStory,  array());
        
        // Modify 
        $currentStory['storyId'] = $storyId;
        $currentStory['step'] = $cache[$storyId]['step'];
        $currentStory['postId'] = $cache[$storyId]['postId'];
        

        // Store them back
        update_option(WPOptionKeys::storyCache,$cache);
        update_option(WPOptionKeys::currentStory, $currentStory);
    }
    
    function startingOver($storyId)
    {
        // Load stored values from wp_options
        $cache = get_option(WPOptionKeys::storyCache,  array());
        $currentStory = get_option(WPOptionKeys::currentStory,  array());
        
        // Manipulate them
        
        // If he just started over with the story
        $cache[$storyId] = array('step' => 1, 'postId' => FALSE);
        $currentStory['storyId'] = $storyId;
        $currentStory['step'] = 1;
        $currentStory['postId'] = FALSE;
        
        // Store them back
        update_option(WPOptionKeys::storyCache,$cache);
        update_option(WPOptionKeys::currentStory, $currentStory);
    }
}