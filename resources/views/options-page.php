<div class="wrap">
    <h1><?php _e( 'Mail', 'innocode-mail-helpers' ) ?></h1>
    <form method="post" action="<?= admin_url( 'options.php' ) ?>">
        <?php settings_fields( Innocode\Mail\Helpers\Plugin::OPTION_GROUP ) ?>
        <?php do_settings_sections(
            Innocode\Mail\Helpers\Plugin::OPTION_GROUP
        ) ?>
        <?php submit_button() ?>
    </form>
</div>
