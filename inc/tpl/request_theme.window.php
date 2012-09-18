<html>
    <head>
        <title>Request a quote for custom theme development.</title>
        <?php wp_enqueue_script( 'tbar' ); ?>
        <?php wp_head(); ?>
        <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/inc/lib/tbar/inc/css/tbar.css" />
        <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/inc/lib/tbar/inc/css/tbar.iframe.css" />
    </head>
    <body>
        <?php
            global $current_user;
            get_currentuserinfo();
        ?>
        <div id="tbar-window">
            <div id="tbar-contact-window">
                <h1 class="page-title">Request a quote for custom theme development</h1>
                <div class="wrap">
                    <div class="ajax"></div>
                    <div id="ajax-reply"></div>
                    <form action="" method="post">
                        <div class="left">
                            <p class="name-input tbar-input-wrap">
                                <label for="name">Name <span class="required">*</span></label>
                                <input type="text" class="tbar-input" name="plugin[name]" id="name" value="<?php echo isset( $_POST['plugin']['name'] ) && ! empty( $_POST['plugin']['name'] ) ? $_POST['plugin']['name'] : ''; ?>" />
                            </p>
                            <p class="email-input tbar-input-wrap">
                                <label for="email">Email <span class="required">*</span></label>
                                <input type="text" class="tbar-input" name="plugin[email]" id="email" value="<?php echo isset( $current_user->user_email ) && ! empty( $current_user->user_email ) ? $current_user->user_email : ''; ?>" />
                            </p>
                            <p class="phone-input tbar-input-wrap">
                                <label for="phone">Phone</label>
                                <input type="text" class="tbar-input" name="plugin[phone]" id="phone" />
                            </p>
                        </div>
                        <div class="right">
                            <p class="plugin-requirements-input tbar-input-wrap">
                                <label for="description">Message <span class="required">*</span></label>
                                <textarea name="plugin[description]" id="description" cols="50" rows="5"></textarea>
                                <input id="inquiry-type" type="hidden" value="contact" />
                            </p>
                        </div>
                        <div class="tbar-clear"></div>
                        <a href="http://travisballard.com" class="tblink notop" target="_blank">Visit TravisBallard.com</a>
                        <p class="submit tbar-input-wrap notop"><a class="tbar-button-2 color blue submit">Send Email</a></p>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>