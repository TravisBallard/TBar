<?php

    /**
    *   Handle TBar contact form submission
    *   Travis Ballard
    *   admin@anismation.net
    */
    class TBarContact
    {
        private $to = false; # if false, we use get_bloginfo( 'admin_email' )
        private $subject = false; # if false, subject is "%name% has sent you a message"

        private $error = false;

        # contact object
        private $contact;

        # required fields
        private $required_fields = false;

        # type of inquiry. plugin/theme/other
        private $inquiry_type = false;


        /**
        * magic
        *
        * @param mixed $contact_arr - submitted data
        * @param mixed $inquiry_type - type of inquiry, plugin, theme, other
        * @param mixed $required_fields - required field names
        * @param mixed $from - from email address
        * @param mixed $to - to email address
        * @param mixed $subject - subject line
        * @return Contact or WP_Error
        */
        public function __construct( $contact_arr, $inquiry_type, $required_fields = array(), $to = false )
        {
            $this->contact = (object)$contact_arr;
            $this->inquiry_type = $inquiry_type;
            $this->required_fields = $required_fields;
            $this->from = $from;
            $this->to = $to;

            switch( $this->inquiry_type )
            {
                case 'plugin' : $this->subject = 'New Plugin Development Request'; break;
                case 'theme' : $this->subject = 'New Theme Development Request'; break;
                case 'contact' : $this->subject = sprintf( '%s has sent you a message using TBar', $this->contact->name ); break;
                default: $this->subject = sprintf( '%s has sent you a message using TBar - Unknown Inquiry Type', $this->contact->name ); break;
            }

            if( $from === false ) $this->from = get_bloginfo( 'admin_email' );
            if( $to === false ) $this->to = get_bloginfo( 'admin_email' );
            if( $subject === false ) $this->subject = sprintf( '%s has sent you a message', esc_attr( $this->contact->name ) );

            if( ! $this->required_fields_exist() ){
                $this->error = new WP_Error( 'invalid', 'Fields marked with a * are required.' );
                return false;
            }

            if( ! $this->is_valid_email() ){
                $this->error = new WP_Error( 'invalid', 'The email address you entered is invalid.' );
                return false;
            }

            if( ! $this->is_error() )
                $this->send_email();
        }

        /**
        * make sure all required fields exist
        *
        */
        public function required_fields_exist()
        {
            foreach( $this->required_fields as $required_key ){
                if( ! array_key_exists( $required_key, (array)$this->contact ) || empty( $this->contact->$required_key ) )
                    return false;
            }
            return true;
        }

        /**
        * make sure supplied email address is valid
        *
        */
        public function is_valid_email()
        {
            if( ! is_email( $this->contact->email ) )
                return false;

            return true;
        }

        /**
        * prepare email template to be sent
        *
        */
        public function prepare_email()
        {
            ob_start();
            switch( $this->inquiry_type )
            {
                case 'plugin'   : self::load( 'email/plugin-request.email' ); break;
                case 'theme'    : self::load( 'email/theme-request.email' ); break;
                case 'other'    : self::load( 'email/contact.email' ); break;
                default         : self::load( 'email/contact.email' ); break;
            }

            return str_replace(
                array(
                    '%name%',
                    '%email%',
                    '%message%',
                    '%website%',
                    '%phone%'
                ),
                array(
                    isset( $this->contact->name ) && ! empty( $this->contact->name ) ? ucwords( strtolower( $this->contact->name ) ) : 'unknown',
                    isset( $this->contact->email ) && ! empty( $this->contact->email ) ? $this->contact->email : 'unknown',
                    isset( $this->contact->message ) && ! empty( $this->contact->message ) ? stripslashes( $this->contact->message ) : 'unknown',
                    sprintf( '%s (%s)', get_bloginfo( 'name' ), site_url( '/' ) ),
                    isset( $this->contact->phone ) && ! empty( $this->contact->phone ) ? $this->contact->phone : '',
                ),
                ob_get_clean()
            );
        }

        /**
        * send email
        *
        */
        public function send_email()
        {
            global $current_user;
            get_currentuserinfo();

            $from = isset( $current_user->data->user_email ) && ! empty( $current_user->data->user_email ) ? $current_user->data->user_email : get_bloginfo( 'admin_url' );

            $headers =  sprintf( 'From: %s', $from ) . "\r\n" .
                        sprintf( 'Reply-To: %s <%s>', ucwords( strtolower( $this->contact->name ) ), $this->contact->email ) . "\r\n" .
                        'X-Mailer: PHP/' . phpversion();

             if( ! wp_mail( $this->to, $this->subject, $this->prepare_email(), $headers ) ){
                 $this->error = new WP_Error( 'phpmailer', $GLOBALS['phpmailer']->ErrorInfo );
             }
        }

        /**
        * load a template file from inc/
        *
        * @param mixed $file
        */
        public static function load( $file )
        {
            $file = sprintf( '%s/tpl/%s.php', dirname( __FILE__ ), $file );
            if( file_exists( $file ) && is_readable( $file ) )
                include( $file );
            else
            {
                if( ! is_readable( $file ) && file_exists( $file ) )
                    trigger_error( sprintf( 'Unable to load template file %s. File is not readable. Check permissions and try again.', $file ), E_USER_WARNING );
                elseif( ! file_exists( $file ) )
                    trigger_error( sprintf( 'Unable to load template file %s. File does not exist.', $file ), E_USER_WARNING );
            }
        }

        /**
        * check if is error or not
        *
        */
        public function is_error(){
            return (bool)$this->error;
        }

        /**
        * get error message
        *
        */
        public function get_error_message(){
            return is_wp_error( $this->error ) ? $this->error->get_error_message() : $this->error;
        }
    }