<?php

class SupportView extends View
{
   public static $_CONF;

   public function __construct($_CONF) {
       
       SupportView::$_CONF = $_CONF;
    }

    public function add() {
   
        add_submenu_page('yourMarketingGuy_overview', 
                      'Support',
                      'Support',
                      'administrator', 
                      'yourMarketingGuy_support', 
                      array(
                          'SupportView',
                          'yourMarketingGuy_display_support'
                          ));
    }
    
    public function yourMarketingGuy_display_support()
    {
       ?>
       
       <div class="container">
       
       <div class="support-left">
       		<a class="twitter-timeline" data-height="750" href="https://twitter.com/YourMKGuy">Tweets by YourMKGuy</a> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
       </div>
       
       <div class="support-right">
       		<a href="https://twitter.com/YourMKGuy" class="twitter-follow-button" data-size="large" data-show-count="false">Follow @YourMKGuy</a>
            <br/><br/>
            <a href="https://twitter.com/intent/tweet?screen_name=YourMkGuy" class="twitter-mention-button" data-size="large" data-related="MrPGworks" data-show-count="false">Tweet to @YourMkGuy</a>
            <br/><br/>
            <a href="mailto:hello@yourguy.marketing">hello@yourguy.marketing</a>
       </div>
       
       
       </div>
       
       <?php
    }
}