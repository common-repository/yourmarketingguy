<?php
/*
 * Adds hooks to wordpress to collect data we need to deliver customized tips.
 */

class DataSheduler {
    
    private static $_CONF;
    
    
    public function __construct( $_CONF ) {
        DataSheduler::$_CONF = $_CONF;
        
    }
    
    
    public function addOnPostPublished() { 
        add_action( 'save_post', array( 'DataSheduler', 'yourMarketingGuyOnPostPublished'), 10, 2 );
    }
    
    public function yourMarketingGuyOnPostPublished($id, $post) {
      
        if( $post->post_status == "publish" && in_array($post->post_type, array ('post', 'page'))) {
            $f = new Farming( DataSheduler::$_CONF['backendServerUrl']);
            $f->farmPost($post);
            $f->sendToBackend();
        }
        
    }
    
    public function addOnCommentPublished() {
        add_action('wp_insert_comment',array( 'DataSheduler', 'yourMarketingGuyOnCommentInserted'), 10, 2);
    }
    
    public function yourMarketingGuyOnCommentInserted($id, $comment) {  

        $f = new Farming( DataSheduler::$_CONF['backendServerUrl']);
        $f->farmComment($comment);
        $f->sendToBackend();
    }
    
}