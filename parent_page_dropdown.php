<?php
class ParentPageDropDownLimit{
    
    public function __construct(){
        add_filter( 'page_attributes_dropdown_pages_args', array($this,'admin_top_level_pages_only') );
        add_filter( 'quick_edit_dropdown_pages_args', array($this,'admin_top_level_pages_only' )); 
        add_action( 'add_meta_boxes', array($this,'pdd_options_add') );
        add_action( 'save_post', array($this,'pdd_options_save') );
        
        $options = get_option('selevtive_ppd_option_name');
        $this->def_show_noshow = $options['def_show_noshow'];
    }
    
    function admin_top_level_pages_only( $args )  
    {  

         $args1 = array(
            'post_type' => array('page'),
            'meta_query' => array(
               array(
                'key' => 'parent_dropdown',
                'value' => 0,
                'compare' => '=' 
                   )
                 ),
             'posts_per_page'=> -1
        );
            $pages = get_posts( $args1 );
            foreach($pages as $page){
                $exclude = $page->ID.",".$exclude;
            }
            $exclude = rtrim($exclude, ', ');


        $args['exclude'] = $exclude; 
        //echo $exclude;

        return $args;  
    } 
    
    public function pdd_options_add()
    {
        add_meta_box( 'wpi-parent-drop-down', 'WPI Parent Drop Down', array($this,'call_to_pdd_options'), 'page', 'normal', 'high' ); 
    }
    
     public function call_to_pdd_options($post)
    {
        // $post is already set, and contains an object: the WordPress post
        global $post;
        $values = get_post_custom( $post->ID );
        $selected = isset( $values['parent_dropdown'][0] ) ? esc_attr( $values['parent_dropdown'][0] ) : '';
        // We'll use this nonce field later on when saving.
        wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
        ?>


        <p>
            <label for="parent_dropdown">Show in Parent drop down?</label>
            <select name="parent_dropdown" id="show_title">
                <?php if($this->def_show_noshow == "No") { ?>
                <option value="0" <?php if($selected==0 or $selected==null) echo "selected"; ?>>No</option>
                <option value="1" <?php if($selected==1 ) echo "selected"; ?>>Yes</option>
                <?php } else { ?>
                <option value="0" <?php if($selected==0 ) echo "selected"; ?>>No</option>
                <option value="1" <?php if($selected==1 or $selected==null) echo "selected"; ?>>Yes</option>
                <?php } ?>
                
            </select>
        </p>

        

        <?php   
    }
    
     function pdd_options_save( $post_id )
    {
       // Bail if we're doing an auto save
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        // if our nonce isn't there, or we can't verify it, bail
        if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;

        // check if our current user can't edit this post
        if( !current_user_can( 'edit_post' ) ) return;

        // now we can actually save the data
        if( isset( $_POST['parent_dropdown'] ) )
            update_post_meta( $post_id, 'parent_dropdown', esc_attr( $_POST['parent_dropdown'] ) );


    }
    
}
?>
