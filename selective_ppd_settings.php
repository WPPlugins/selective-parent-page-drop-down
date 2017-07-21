<?php
class selectivePpdSettings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Selective parent Page Drop Down', 
            'manage_options', 
            'selevtive-ppd-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'selevtive_ppd_option_name' );
        ?>
        <div class="wrap">
            <?php screen_icon(); ?>
            <h2>Selective Parent Page Drop Down Plugin Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'selective_pdd_option_group' );   
                do_settings_sections( 'selevtive-ppd-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'selective_pdd_option_group', // Option group
            'selevtive_ppd_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Defaut Behavior', // Title
            array( $this, 'print_section_info' ), // Callback
            'selevtive-ppd-setting-admin' // Page
        );  
    
        add_settings_field(
            'def_show_noshow', 
            'Default behavior (Whether to show or hide the pages by default)', 
            array( $this, 'def_behavior_callback' ), 
            'selevtive-ppd-setting-admin', 
            'setting_section_id'
        ); 
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['def_show_noshow'] ) )
            $new_input['def_show_noshow'] = sanitize_text_field( $input['def_show_noshow'] );
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }
    


    /** 
     * Get the settings option array and print one of its values
     */
    public function def_behavior_callback()
    {
       
        $def_show_noshow=esc_attr($this->options['def_show_noshow']);
        ?>
        <select id="def_show_noshow" name="selevtive_ppd_option_name[def_show_noshow]">
            <option <?php if($def_show_noshow=="Yes") echo "selected"  ?>>Yes</option>
            <option <?php if($def_show_noshow=="No") echo "selected"  ?>>No</option>
        </select>
        <?php
    }

}

if( is_admin() )
    $selective_ppd_settings = new selectivePpdSettings();