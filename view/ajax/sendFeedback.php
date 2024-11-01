<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action( 'admin_footer', 'sendFeedbackThumbActionJavaScript' ); // Write our JS below here
function sendFeedbackThumbActionJavaScript() {

        $customerId = get_option( YMG_PREFIX . "customerId", FALSE);
        $uid = get_option( YMG_PREFIX . "uid" , FALSE);
        $thumbsUp = $_GET['thumbsUp'];
    ?>
        <script type="text/javascript" >
        jQuery(document).ready(function($) {

                var data = {
                        'customerId': '<?php echo $customerId; ?>',
                        'uid': '<?php echo $uid; ?>',
                        'action' : 'sendFeedbackThumb',
                        'thumbsUp' : <?php echo $thumbsUp; ?> 
                };

                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(ajaxurl, data, function(response) {
                        document.write(response);
                });
        });
        </script> <?php
}

