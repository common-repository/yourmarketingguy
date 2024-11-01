<?php

/*
 * Controller to add the our view(s) to the wordpress admin panel
 * 
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
defined( 'YMG_PATH' ) or die( 'No script kiddies please!' );

    
    require_once YMG_PATH . '/view/View.php';
    require_once YMG_PATH . '/view/FTUView1.php';
    require_once YMG_PATH . '/view/SettingsView.php';     
    require_once YMG_PATH . '/view/EulaView.php';     
    require_once YMG_PATH . '/view/HistoryView.php'; 
    //require_once YMG_PATH . '/view/StoryOverviewView.php'; 
    require_once YMG_PATH . '/view/StoryView.php';       
    require_once YMG_PATH . '/view/StoreView.php';     
    require_once YMG_PATH . '/view/SupportView.php';     
    require_once YMG_PATH . '/model/EulaModel.php';
    require_once YMG_PATH . '/model/StoryModel.php';
    
    class AdminMenuController
    {
        public static $_CONF;
        public function __construct($_CONF) {
            AdminMenuController::$_CONF =$_CONF;
        }

        public function add()
        {            
            
            add_action( 'admin_enqueue_scripts', array( $this, 'your_marketing_guy_load_css' ));
            add_action( 'admin_enqueue_scripts', array( $this, 'your_marketing_guy_load_scripts' ));
            add_action(
                           'admin_menu', 
                           array(
                               'AdminMenuController',
                               'yourMarketingGuyAdminMenuAction'
                               )
                           );

        }
            
        public function your_marketing_guy_load_css()
        {
            wp_register_style( 'bootstrap_standalone_css', plugin_dir_url( __FILE__ ) . '../view/css/bootstrap-tour-standalone.min.css');
            wp_register_style( 'ymg_css', plugin_dir_url( __FILE__ ) . '../view/css/ymg.css');
            
            wp_enqueue_style( 'bootstrap_standalone_css' );
            wp_enqueue_style( 'ymg_css' );           
        }
        public function your_marketing_guy_load_scripts()
        {
            $localization = array(
                'ago' => __('ago',"ymg-plugin"),
                'from_now' => __('from now',"ymg-plugin"),
                'any_moment_now' => __('any moment now',"ymg-plugin"),
                'less_than_a_minute' => __('less than a minute',"ymg-plugin"),
                'about_a_minute' => __('about a minute',"ymg-plugin"),
                'd_minutes' => __('%d minutes',"ymg-plugin"),
                'about_an_hour' => __('about an hour',"ymg-plugin"),
                'about_d_hours' => __('about %d hours',"ymg-plugin"),
                'a_day' => __('a day',"ymg-plugin"),
                'd_days' => __('%d days',"ymg-plugin"),
                'about_a_month' => __('about a month',"ymg-plugin"),
                'd_months' => __('%d months',"ymg-plugin"),
                'about_a_year' => __('about a year',"ymg-plugin"),
                'd_years' => __('%d years',"ymg-plugin")
            );
            
            wp_register_script( 'jquery_timeago_js', plugin_dir_url( __FILE__ ) . '../view/js/jquery.timeago.js', array( 'jquery'));
            wp_register_script( 'bootstrap_tour_js', plugin_dir_url( __FILE__ ) . '../view/js/bootstrap-tour-standalone.min.js');
			wp_register_script( 'jquery_easing', plugin_dir_url( __FILE__ ) . '../view/js/jquery.easing.1.3.js');
			wp_register_script( 'jquery_mousewheel', plugin_dir_url( __FILE__ ) . '../view/js/jquery.mousewheel.js');
			wp_register_script( 'jquery_vaccordion', plugin_dir_url( __FILE__ ) . '../view/js/jquery.vaccordion.js');
            
            wp_localize_script(
                    'jquery_timeago_js',
                    'ymg',
                    $localization);
            
            wp_enqueue_script( 'bootstrap_tour_js' );
            wp_enqueue_script( 'jquery_timeago_js' );
			wp_enqueue_script( 'jquery_easing' );
			wp_enqueue_script( 'jquery_mousewheel' );
			wp_enqueue_script( 'jquery_vaccordion' );
            
        }
		
            public function yourMarketingGuyAdminMenuAction()
            {
                // Initiate EulaModel and check if user agreed the EULA (via GET)
                $eulaModel = new EulaModel();
                if(!$eulaModel->getState())
                {
                    $eulaModel->checkIfAccepted();
                }
                
                // Check again if it is agreed now.
                // If not, print the eula again.
                if(!$eulaModel->getState())
                {
                        $entry1 = new EulaView(AdminMenuController::$_CONF);
                        $entry1->add();                        
                        return;              
                }
                
                // USER HAS TO ACCEPT THE EULA TO GO BEHIND THIS POINT
                
                // Use StoryModel to check if user has started over at all
                // if not, don't display the StoryView
                $storyModel = new StoryModel(AdminMenuController::$_CONF);
                
                
                // Get Step of the FTU process, if not existing it'll print the
                // default view.
                switch(get_option( YMG_PREFIX . "FTUStep", 3)) {
                    case 1:
                        $entry1 = new FTUView1(AdminMenuController::$_CONF);
                        $entry1->add();
                        //$entry2 = new HistoryView(AdminMenuController::$_CONF);
                        //$entry2->add();
                        
                        
                        $entry3 = new StoreView(AdminMenuController::$_CONF);
                        $entry3->add();
                         
                         // Check if the customer has already selected a Story
                        // and a tip or not
                        //$entry4 = new StoryOverviewView(AdminMenuController::$_CONF);
                        //$entry4->add();
                        
                        if($storyModel->hasStartedOverAtAll()) {
                            $entry5 = new StoryView(AdminMenuController::$_CONF);
                            $entry5->add();
                        }
                        
                        
                        $entry6 = new SupportView(AdminMenuController::$_CONF);
                        $entry6->add();
                        break;
                    /*case 2:
                     * IN CASE OF A SECOND FTU SCREEN                      
                        break;*/
                    default:
                        $entry1 = new SettingsView(AdminMenuController::$_CONF);
                        $entry1->add();                        
                        
                        //$entry2 = new HistoryView(AdminMenuController::$_CONF);
                        //$entry2->add();
                        
                        
                        $entry3 = new StoreView(AdminMenuController::$_CONF);
                        $entry3->add();
                         
                         // Check if the customer has already selected a Story
                        // and a tip or not
                        //$entry4 = new StoryOverviewView(AdminMenuController::$_CONF);
                        //$entry4->add();
                        
                        if($storyModel->hasStartedOverAtAll()) {
                            $entry5 = new StoryView(AdminMenuController::$_CONF);
                            $entry5->add();
                        }
                        
                        
                        $entry6 = new SupportView(AdminMenuController::$_CONF);
                        $entry6->add();
                        break;
                }

            }
    }
