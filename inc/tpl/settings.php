<?php $settings = TBar::get_settings(); ?>
<div class="wrap">
    <h2>TBar Settings</h2>
    <form action="options.php" method="post">
        <?php settings_fields( 'tbar' ); ?>
        <table class="form-table">
            <tr>
                <td>
                    <input name="tbar[disabled]" id="toggle" type="checkbox" size="43" value="1" <?php if( isset( $settings['disabled'] ) && intval( $settings['disabled'] == 1 ) )checked( 1, 1 ); ?> />
                    <label for="toggle">Disable TBar</label>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" value="Save Settings" class="button-primary" />
        </p>
    </form>
</div>