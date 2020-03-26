<div class="wrap">
    <h1><?php _e( 'Mail', 'innocode-mail-helpers' ) ?></h1>
    <?php if ( isset( $_GET['updated'] ) ) : ?>
        <?php if ( $_GET['updated'] == 'true' ) : ?>
            <div id="message" class="updated notice is-dismissible">
                <p>
                    <strong><?php _e( 'Test email was sent successfully.', 'innocode-mail-helpers' ) ?></strong>
                </p>
            </div>
        <?php else : ?>
            <div class="notice notice-error">
                <p>
                    <?php _e( 'Test email was not sent. Please contact your server administrator.', 'innocode-mail-helpers' ) ?>
                </p>
            </div>
        <?php endif ?>
    <?php endif ?>
    <form
        method="post"
        action="<?= admin_url( 'tools.php?page=' . Innocode\Mail\Helpers\Plugin::OPTION_GROUP . '_tools' ) ?>"
    >
        <input type="hidden" name="action" value="test">
        <?php wp_nonce_field( Innocode\Mail\Helpers\Plugin::OPTION_GROUP . '-tools' ) ?>
        <?php do_settings_sections(
            Innocode\Mail\Helpers\Plugin::OPTION_GROUP . '_tools'
        ) ?>
        <?php submit_button( __( 'Send', 'innocode-mail-helpers' ) ) ?>
    </form>
</div>