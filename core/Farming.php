<?php

/*
 * Functions to collect and send neccessary data to our backend.
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class Farming{
    
    private $farmedData = array();
    private $backendUrl = "";
    private $whitelist;
    
    public function __construct($backendUrl, $whitelist = FALSE) {
        $this->backendUrl = $backendUrl;
        $this->whitelist = $whitelist;
    }
    
    public function farmOptions( ) {
        $options = array();
        
        // Grab only options from the whitelist
        foreach( $this->whitelist as $entry) {
            $temp = get_option( $entry , "");
            
            // Check if they need to be serialized
            if(is_array($temp) ) {
                $options[$entry] = serialize($temp);
            }
            else {
                $options[$entry] = $temp;
            }
            
        }
        
        
        $this->farmedData['wp_options'] = $options;
    }
    
    public function farmComment($comment) {

        $this->farmedData['wp_comments'] = array(
                                            array(
                                                    'content' => $comment->comment_content,
                                                    'post_id' => $comment->comment_post_ID,
                                                    'comment_id' => $comment->comment_ID,
                                                    'comment_parent' => $comment->comment_parent,
                                                    'comment_date' => $comment->comment_date,
                                                    'comment_approved' => $comment->comment_approved
                                                )
                                                );
    }
    
    public function farmComments(){
        $args = array( 'status' => 'approve' );
        
        $data = get_comments( $args );
        $comments = array();
        
        foreach($data as $comment){
            $c = array(
                        'content' => $comment->comment_content,
                        'post_id' => $comment->comment_post_ID,
                        'comment_id' => $comment->comment_ID,
                        'comment_parent' => $comment->comment_parent,
                        'comment_date' => $comment->comment_date,
                        'comment_approved' => $comment->comment_approved
                        
                      );
            array_push( $comments, $c );
        }
        if(count( $comments ) > 0)
        {
            $this->farmedData['wp_comments'] = $comments;
        }
    }
    
    private function getTags( $postID ) {
        $temp = get_the_terms( $postID, 'post_tag' );
        $tags = "";
        
        if( $temp && !is_wp_error( $temp ) ) {
            foreach( $temp as $tag) {
                $tags .= $tag->name . ",";
            }
            // Cut off last comma
            if(strlen( $tags) > 0) {
                $tags = substr($tags, 0,-1);   
            }
        }
        return $tags;
    }
    
    private function getCategories( $postID ) {
        $post_categories = wp_get_post_categories( $postID );
        $cats = "";
        
        foreach($post_categories as $c){
                $cat = get_category( $c );
                
                $cats .= $cat->slug . ",";
        }
        
        if(strlen($cats) > 0) {
            $cats = substr($cats, 0,-1);   
        }
        
        return $cats;
    }
    
    public function farmPost($post) {
        
        $post_status = $post->post_status;
        
        // Don't commit private or trash posts to our backend
        if( in_array($post_status, array('trash', 'private'))) {
            return;
        }
        
        $public = in_array($post_status, array( 'publish' ));
        
        // get user name by id
        $author = get_userdata( $post->post_author );
        
        $this->farmedData['wp_posts'] = array(
                                            array(
                                                'content' => (($public)? $post->post_content:""),
                                                'post_id' => $post->ID,
                                                'title' => (($public)?$post->post_title:""),
                                                'tags' => (($public)? $this->getTags($post->ID) :""),
                                                'category' => (($public)? $this->getCategories($post->ID):""),
                                                'post_excerpt' => (($public)? $post->post_excerpt:""),
                                                'post_status' => $post->post_status,
                                                'post_date' => $post->post_date,
                                                'post_name' => $post->post_name,
                                                'post_parent' => $post->post_parent,
                                                'guid' => get_the_guid($post->ID),
                                                'post_type' => $post->post_type,
                                                'post_author' => $author->first_name . " " . $author->last_name
                                             )
                                              );
    }
    
    public function farmPosts(){
        
        // Get Posts
        $args = array( 
                'post_status' => array( 'publish' ),
                'numberposts' => -1,
                'has_password' => FALSE
            );
        $data = get_posts( $args );
        $posts = array();
        foreach( $data as $post ):
            $public = in_array($post->post_status, array( 'publish' ));
             // get user name by id
             $author = get_userdata( $post->post_author );
             $p = array(
                        'content' => (($public)? $post->post_content:""),
                        'post_id' => $post->ID,
                        'title' => (($public)?$post->post_title:""),
                        'tags' => (($public)? $this->getTags($post->ID) :""),
                        'category' => (($public)? $this->getCategories($post->ID):""),
                        'post_excerpt' => (($public)? $post->post_excerpt:""),
                        'post_status' => $post->post_status,
                        'post_date' => $post->post_date,
                        'post_name' => $post->post_name,
                        'post_parent' => $post->post_parent,
                        'guid' => get_the_guid($post->ID),
                        'post_type' => $post->post_type,
                        'post_author' => $author->first_name . " " . $author->last_name
                      );
            array_push( $posts, $p );
        endforeach;
        
        // Get Pages
        $args2 = array( 'post_status' => array( 'publish'));
        $data2 = get_pages( $args2 );
        foreach( $data2 as $page ) {
            $public = in_array( $page->post_status, array( 'publish', 'inherit' ));
             // get user name by id
             $author = get_userdata( $post->post_author );
            $p = array(
                        'content' => (($public) ? $page->post_content:""),
                        'post_id' => $page->ID,
                        'title' => (($public)?$page->post_title:""),
                        'tags' => (($public)? $this->getTags($page->ID) :""),
                        'category' => (($public)? $this->getCategories($page->ID):""),
                        'post_excerpt' => (($public)? $page->post_excerpt:""),
                        'post_status' => $page->post_status,
                        'post_date' => $page->post_date,
                        'post_name' => $page->post_name,
                        'post_parent' => $page->post_parent,
                        'guid' => get_the_guid($page->ID),
                        'post_type' => $page->post_type,
                        'post_author' => $author->first_name . " " . $author->last_name
                      );
            
            array_push($posts, $p);
        }
        if(count( $posts )>0)
        {
            $this->farmedData['wp_posts'] = $posts;
        }
        wp_reset_postdata();
    }
   
    // This method should only be called once! 
    public function sendToBackend( ) {
        $b = new BackendServer( $this->backendUrl );
        $data = base64_encode( serialize( $this->farmedData ) ) ;
        $response = $b->sendData( $data );
        
        if(is_wp_error($response))
        {
            Logger::logError(__METHOD__, $response->get_error_message() . " -> " . $this->backendUrl);
            return $response->get_error_message() . " -> " . $this->backendUrl;
        }
        else
        {
            return $response['body'];
        }
        
    }
}
