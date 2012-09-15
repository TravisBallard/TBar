<?php
    /**
    *   Promotional admin bar icon menu for Travis Ballard Design
    *   Travis Ballard - http://travisballard.com
    *   admin@ansimation.net
    */
    class TBar
    {
        const email_address = 'admin@ansimation.net';

        /**
        * magic
        *
        * @param mixed $position
        * @return TBar
        */
        public function __construct( $position = 50 )
        {
            add_action( 'init', array( $this, 'init' ) );
            add_action( 'admin_init', array( $this, 'init' ) );
            add_action( 'admin_bar_menu', array( $this, 'add_menu_items' ), intval( $position ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
            add_action( 'wp_print_styles', array( $this, 'enqueue_styles' ) );
            add_action( 'admin_print_scripts', array( $this, 'enqueue_scripts' ) );

            add_action( 'wp_ajax_get_window', array( $this, 'get_window' ) );
            add_action( 'wp_ajax_nopriv_get_window', array( $this, 'get_window' ) );

            add_action( 'wp_ajax_tbar_send_email', array( $this, 'ajax_send_email' ) );
            add_action( 'wp_ajax_nopriv_tbar_send_email', array( $this, 'ajax_send_email' ) );
        }

        /**
        * init
        *
        */
        public function init()
        {
            $this->register_scripts();
            $this->localize_scripts();
            $this->register_styles();
            $this->enqueue_styles();
        }

        /**
        * register scripts
        *
        */
        public function register_scripts(){
            wp_register_script( 'tbar', sprintf( '%s/inc/js/tbar.js', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) ), array( 'jquery' ), '1.0', 0 );
            wp_localize_script( 'tbar', 'tbardata', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
        }

        /**
        * register styles
        *
        */
        public function register_styles(){
            wp_register_style( 'tbar', sprintf( '%s/inc/css/tbar.css', str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, dirname( __FILE__ ) ) ), array(), '1.0' );
        }

        /**
        * enqueue scripts
        *
        */
        public function enqueue_scripts()
        {
            wp_enqueue_script( 'tbar' );
            wp_enqueue_script( 'thickbox' );
        }

        /**
        * enqueue styles
        *
        */
        public function enqueue_styles(){
            wp_enqueue_style( 'tbar' );
            wp_enqueue_style( 'thickbox' );
        }

        /**
        * localize scripts
        *
        */
        public function localize_scripts(){

        }

        /**
        * add menu items to admin bar
        *
        * @param mixed $wp_admin_bar
        */
        public function add_menu_items( $wp_admin_bar )
        {
            # TB Icon
            $wp_admin_bar->add_node( array(
                'id' => 'tb-link',
                'parent' => false,
                'title' => 'TB',
                'href' => false,
                'meta' => array( 'title' => 'Travis Ballard Design: Freelance WordPress Consulting &amp; Development' )
            ) );

            # Request Plugin Quote
            $wp_admin_bar->add_node( array(
                'id' => 'tb-plugin-link',
                'parent' => 'tb-link',
                'title' => 'Request Plugin Quote',
                'href' => '#',
                'meta' => array( 'title' => 'Request a quote for custom WordPress plugin development.' )
            ) );

            # Request Theme Quote
            $wp_admin_bar->add_node( array(
                'id' => 'tb-theme-link',
                'parent' => 'tb-link',
                'title' => 'Request Theme Quote',
                'href' => '#',
                'meta' => array( 'title' => 'Request a quote for custom WordPress theme design and/or development.' )
            ) );

            # link to my website
            $wp_admin_bar->add_node( array(
                'id' => 'tb-site-link',
                'parent' => 'tb-link',
                'title' => 'Visit TravisBallard.com',
                'href' => 'http://travisballard.com',
                'meta' => array( 'title' => 'Check out my home on the intarnets.', 'target' => '_blank' )
            ) );

            # my plugins
            $wp_admin_bar->add_node( array(
                'id' => 'tb-plugins-link',
                'parent' => 'tb-link',
                'title' => 'Travis\' WordPress Plugins',
                'href' => '',
                'meta' => array( 'title' => 'My WordPress Plugins' )
            ) );

            # loop through plugins and add them to 'my plugins' menu
            if( false !== ( $plugins = $this->get_my_plugins() ) )
            {
                foreach( $plugins as $plugin )
                {
                    $wp_admin_bar->add_node( array(
                        'id' => sprintf( 'tb-%s-link', sanitize_title( $plugin->name ) ),
                        'parent' => 'tb-plugins-link',
                        'title' => $plugin->name,
                        'href' => admin_url( sprintf( '/plugin-install.php?tab=plugin-information&plugin=%s&TB_iframe=true&width=600&height=550', $plugin->slug ) ),
                        'meta' => array( 'title' => $plugin->name )
                    ) );
                }
            }

            # contact me
            $wp_admin_bar->add_node( array(
                'id' => 'tb-contact-link',
                'parent' => 'tb-link',
                'title' => 'Contact Me',
                'href' => '#',
                'meta' => array( 'title' => 'Send me an email' )
            ) );
        }

        /**
        * Get list of my plugins from the WordPress.org API and store them in a transient for 12 hours
        *
        */
        public function get_my_plugins()
        {
            if( false === ( $plugins = get_transient( 'tb_plugins' ) ) )
            {
                $request = wp_remote_post( 'http://api.wordpress.org/plugins/info/1.0/', array(
                    'timeout' => 15,
                    'body' => array(
                        'action' => 'query_plugins',
                        'request' => serialize( (object)array( 'per_page' => -1, 'author' => 'ansimation' ) )
                    )
                ) );

                if( ! is_wp_error( $request ) )
                {
                   $result = unserialize( $request['body'] );
                   if( isset( $result->plugins ) && is_array( $result->plugins ) && count( $result->plugins ) > 0 ){
                       set_transient( 'tb_plugins', $result->plugins, 60*60*12 );
                       return $result->plugins;
                   }
                }
                else
                    return false;
            }
            else
                return $plugins;
        }

        /**
        * get popup window content
        *
        */
        public function get_window()
        {
            if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'get_window' && isset( $_REQUEST['window_type'] ) )
            {
                ob_start();
                switch( $_REQUEST['window_type'] )
                {
                    case 'contact' : self::load( 'contact.window' ); break;
                    case 'plugin' : self::load( 'request_plugin.window' ); break;
                    case 'theme' : self::load( 'request_theme.window' ); break;
                }
                die( ob_get_clean() );
            }
        }

        /**
        * load a template file from tbar_dir/inc/tpl/
        *
        * @param string $file
        */
        public static function load( $file )
        {
            $file = sprintf( '%s/inc/tpl/%s.php', dirname( __FILE__ ), $file );
            if( file_exists( $file ) && is_readable( $file ) )
                include( $file );
            else
            {
                if( ! is_readable( $file ) && file_exists( $file ) )
                    trigger_error( sprintf( 'Unable to load template file %s. File is not readable. Check permissions and try again.', $file ), E_USER_WARNING );
                elseif( ! file_exists( $file ) )
                    trigger_error( sprintf( 'Unable to load template file %s. File does no exist.', $file ), E_USER_WARNING );
            }
        }

        /**
        * handle sending of emails from tbar
        *
        */
        public function ajax_send_email()
        {
            require_once( 'inc/contact.class.php' );

            if( isset( $_POST['tbar_plugin_contact'] ) )
            {
                $r = new TBarContact( $_POST['tbar_plugin_contact'], $_POST['tbar_inquiry_type'], array( 'name', 'email', 'message' ), self::email_address );
                if( $r->is_error() )
                    die( json_encode( (object)array( 'status' => 'error', 'message' => $r->get_error_message() ) ) );
                else
                    die( json_encode( (object)array( 'status' => 'ok', 'message' => 'Thanks for contacting me. I will be in touch shortly. -Travis' ) ) );
            }
        }
    }

    if( ! isset( $tbar ) || ! is_a( $tbar, 'TBar' ) )
        $tbar = new TBar();