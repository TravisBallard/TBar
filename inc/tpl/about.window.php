<html>
    <head>
        <title>About TBar</title>
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
            <div id="tbar-about-window">
                <h1 class="page-title">About TBar</h1>
                <div class="wrap">
                    <div class="content">
                        <p>TBar is a fast and easy way for you to request a personalized quote on WordPress development. Whether it be a theme or a plugin, large or small. I would love to help. Allow me to help make your website even better than you could ever imagine. Let's take a few moments to discuss your needs and how I will be able to improve your site.</p>
                        <p>If for some reason you decide you would like to remove TBar from your admin bar easily. Simply go to Settings/TBar in your WordPress admin panel.</p>
                        <p>TBar is a promotional tool created to show my current and future clients that I am always just a click away. Your needs are my first concern.</p>
                        <p>You are using version <?php echo TBar::version(); ?></p>
                    </div>
                    <a href="http://travisballard.com" class="tblink" target="_blank">Visit TravisBallard.com</a>
                    <a href="#" class="tbar-button-2 color blue tbar-close-btn">Close</a>
                </div>
            </div>
        </div>
    </body>
</html>