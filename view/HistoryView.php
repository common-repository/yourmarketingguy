<?php

require_once YMG_PATH . '/model/HistoryModel.php';

class HistoryView extends View
{
   public static $_CONF;

   public function __construct($_CONF) {
       
       HistoryView::$_CONF = $_CONF;
    }

    public function add() {
   
        add_submenu_page('yourMarketingGuy_overview', 
                      'History',
                      'History',
                      'administrator', 
                      'yourMarketingGuy_history', 
                      array(
                          'HistoryView',
                          'yourMarketingGuy_display_history'
                          ));
    }

    public function yourMarketingGuy_display_history(){
    
        $historyModel = new HistoryModel(HistoryView::$_CONF);
        
        $data = unserialize(base64_decode($historyModel->getHistory()));
        
        if($data == "")
        {
            echo "No data to display";
        }
        else
        {
            echo "<table>";
            echo "<tr><td>#</td><td>Tip</td><td>Delivered</td><td>Feedback</td></tr>";
            
            foreach($data as $row)
            {
                echo "<tr><td>$row[tip_id]</td><td>$row[de_tip]</td><td>$row[delivered]</td><td><a href=''></a></td></tr>";
            }
            
            echo "</table>";
        }
    }
}