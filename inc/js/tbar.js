var tbar = {
    request_plugin: {
        show_window: function(){
            tbar.modal.show();
            var tbar_window = jQuery('<div>').attr( 'id', 'tbar-window' );
            jQuery( 'body' ).prepend( tbar_window );
            tbar.get_window( 'plugin' );
            return false;
        },
        send: function(){

            tbar.request_plugin.hide_form();
            tbar.loader.show();

            jQuery.ajax({
                url: tbardata.ajaxurl,
                type: 'post',
                dataType: 'json',
                data: {
                    'action'                            : 'tbar_send_email',
                    'tbar_inquiry_type'                 : jQuery( '#inquiry-type' ).val(),
                    'tbar_plugin_contact[name]'         : jQuery( '#name' ).val(),
                    'tbar_plugin_contact[email]'        : jQuery( '#email' ).val(),
                    'tbar_plugin_contact[phone]'        : jQuery( '#phone' ).val(),
                    'tbar_plugin_contact[message]'      : jQuery( '#description' ).val()
                },
                success: function( r ){
                    tbar.loader.hide();

                    if( r.status == 'ok' ){
                        tbar.message.success( r.message );
                        tbar.request_plugin.clear_form();
                    }
                    else
                        tbar.message.error( r.message );

                    setTimeout( function(){
                        tbar.message.remove();
                        tbar.request_plugin.show_form();
                    }, 5000 );
                }
            })
        },
        hide_form: function(){ jQuery( '#tbar-contact-window form' ).fadeOut( 'fast' ); },
        show_form: function(){ jQuery( '#tbar-contact-window form' ).fadeIn( 'fast' ); },
        clear_form: function(){ jQuery( '#tbar-contact-window form input[type=text], #tbar-contact-window form textarea ').val( '' ); }
    },

    request_theme: {
        show_window: function(){
            tbar.modal.show();
            var tbar_window = jQuery('<div>').attr( 'id', 'tbar-window' );
            jQuery( 'body' ).prepend( tbar_window );
            tbar.get_window( 'theme' );
            return false;
        }
    },

    contact: {
        show_window: function(){
            tbar.modal.show();
            var tbar_window = jQuery('<div>').attr( 'id', 'tbar-window' );
            jQuery( 'body' ).prepend( tbar_window );
            tbar.get_window( 'contact' );
            return false;
        }
    },

    about: {
        show_window: function(){
            tbar.modal.show();
            var tbar_window = jQuery('<div>').attr( 'id', 'tbar-window' );
            jQuery( 'body' ).prepend( tbar_window );
            tbar.get_window( 'about' );
            return false;
        }
    },

    modal: {
        show: function(){
            var m = jQuery( '<div>' ).attr( 'id','tbar-modal' ).bind( 'click',
                function(){
                    jQuery( '#tbar-window').fadeOut( 'fast' );
                    jQuery(this).fadeOut( 'slow', function(){
                        jQuery(this).remove();
                    })
                }
            );

            jQuery('body').prepend( m );
            jQuery('#tbar-modal').fadeIn('slow');
        },
        hide: function(){
            jQuery('#tbar-modal, #tbar-window').fadeOut('slow', function(){
                jQuery(this).remove();
            });
        }
    },

    loader: {
        show: function(){ jQuery( '#tbar-contact-window .ajax' ).fadeIn( 'fast' ); },
        hide: function(){ jQuery( '#tbar-contact-window .ajax' ).fadeOut( 'fast' ); }
    },

    get_window: function( type ){
        if( typeof( type ) == 'undefined' )
            type = 'contact';

        tbar.center_window();
        var iframe = jQuery( '<iframe>' ).css({height:'100%',width:'100%',border:'none'}).attr({src:tbardata.ajaxurl + '?action=get_window&window_type=' + type });
        jQuery( '#tbar-window' ).html( iframe ).fadeIn( 'slow' );
    },

    center_window: function(){
        var win = jQuery( '#tbar-window' );
        win.css({
            left: parseInt( ( jQuery( window ).width() / 2 ) - ( win.width() / 2 ) ) + 'px',
            top: parseInt( ( jQuery( window ).height() / 2 ) - ( win.height() / 2 ) ) + 'px'
        });
    },

    message: {
        success: function( msg ){
            var msg_wrap_el = jQuery( '<div>' ).attr( { class: 'success' } );
            var msg_el = jQuery( '<p>' ).html( msg )

            jQuery( '#ajax-reply' ).html( msg_wrap_el.append( msg_el ) ).fadeIn( 'fast' );
        },
        error: function( msg ){
            var msg_wrap_el = jQuery( '<div>' ).attr( { class: 'error' } );
            var msg_el = jQuery( '<p>' ).html( msg )

            jQuery( '#ajax-reply' ).html( msg_wrap_el.append( msg_el ) ).fadeIn( 'fast' );
        },
        remove: function(){
            jQuery( '#ajax-reply' ).fadeOut( 'fast', function(){ jQuery(this).html( '' ); } );
        }
    }
};

jQuery(document).ready( function($){
    $('#wp-admin-bar-tb-contact-link .ab-item').click( function(){ return tbar.contact.show_window(); });
    $('#wp-admin-bar-tb-theme-link .ab-item').click( function(){ return tbar.request_theme.show_window(); });
    $('#wp-admin-bar-tb-plugin-link .ab-item').click( function(){ return tbar.request_plugin.show_window(); });
    $('#wp-admin-bar-tb-about-link .ab-item').click( function(){ return tbar.about.show_window(); });
    $('#wp-admin-bar-tb-plugins-link-default .ab-item').attr( 'class', 'ab-item thickbox' );
    $('.tbar-button-2.submit').click( function(){ tbar.request_plugin.send(); } );
    $('.tbar-close-btn').click( function(){ window.parent.tbar.modal.hide(); } );
});