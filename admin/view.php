 <div class="wrap">

    <form action="options.php" method="post">
        <?php settings_fields( 'wpsd_settings' ); ?>
        <?php  do_settings_sections( 'wp-stripe-donation' ); ?>
        <?php submit_button(); ?>
    </form>
</div>