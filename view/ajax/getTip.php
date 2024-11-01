<?php
/*
 * Ajax functions to prevent that the website will stuck on laod the new tip
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action( 'admin_footer', 'getTipActionJavaScript' ); // Write our JS below here
function getTipActionJavaScript() {
    
        $customerId = get_option( YMG_PREFIX . "customerId", FALSE);
        $uid = get_option( YMG_PREFIX . "uid" , FALSE);
    ?>
        <script type="text/javascript" >
        jQuery(document).ready(function($) {

                var data = {
                        'customerId': '<?php echo $customerId; ?>',
                        'uid': '<?php echo $uid; ?>',
                        'action' : 'getTip'
                };

                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                jQuery.post(ajaxurl, data, function(response) {
                        document.write(response);
                });
        });
        </script> <?php
}


//add_action( 'wp_ajax_my_action', 'getTipCallback' );

//function getTipCallback() {
//	global $wpdb; // this is how you get access to the database
//
//	$whatever = intval( $_POST['whatever'] );
//
//	$whatever += 10;
//
//        echo $whatever;
//
//	wp_die(); // this is required to terminate immediately and return a proper response
//}