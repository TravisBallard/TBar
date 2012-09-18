<html>
    <head>
        <title>About TBar</title>
        <?php wp_enqueue_script( 'tbar' ); ?>
        <?php wp_head(); ?>
        <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/inc/lib/tbar/inc/css/tbar.css" />
        <link rel="stylesheet" href="<?php bloginfo( 'stylesheet_directory' ); ?>/inc/lib/tbar/inc/css/tbar.iframe.css" />
    </head>
    <body>
        <div id="tbar-window">
            <div id="tbar-about-window">
                <h1 class="page-title">About TBar</h1>
                <div class="wrap">
                    <div class="content">
                        <p>TBar is a fast and easy way for you to request a personalized quote on WordPress development. Whether it be a theme or a plugin, large or small, I would love to help. Allow me to help make your website even better than you could ever imagine. Take a few moments and let's discuss your needs and let's see what I can do to help you improve your website.</p>
                        <p>If for some reason you decide that you would like to remove TBar from your admin bar, you can <a href="<?php echo admin_url('options-general.php?page=tbar'); ?>" onclick="parent.window.location=jQuery(this).attr('href');">disable it</a> easily</a>. If at any point you find yourself in need of my services and that little light bulb clicks, you can always re-enable TBar and get in touch with me then.</p>
                        <p>TBar is a promotional tool created to show my current and future clients that I am always just a click away.</p>
                        <p>You are using version <?php echo TBar::version(); ?></p>
                    </div>
                    <a href="http://travisballard.com" class="tblink" target="_blank">Visit TravisBallard.com</a>
                    <a href="#" class="tbar-button-2 color blue tbar-close-btn">Close</a>
                </div>
            </div>
        </div>
    </body>
</html>