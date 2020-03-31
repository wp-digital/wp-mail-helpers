<?php

namespace Innocode\Mail\Helpers;

/**
 * Class Admin
 * @package Innocode\Mail\Helpers
 */
final class Admin
{
    /**
     * @var array
     */
    private $_settings = [
        'new_from_address'      => [
            'sanitize_callback' => 'sanitize_email',
        ],
        'from_name'             => [
            'sanitize_callback' => 'sanitize_text_field',
        ],
    ];
    /**
     * @var array
     */
    private $_pages = [];
    /**
     * @var array
     */
    private $_sections = [];
    /**
     * @var array
     */
    private $_fields = [];

    /**
     * Admin constructor.
     * @param callable $option
     * @param callable $view
     */
    public function __construct( callable $option, callable $view )
    {
        foreach ( [ 'management', 'options' ] as $page ) {
            $this->_pages[ $page ] = [
                'callback' => function () use ( $view, $page ) {
                    $view( "$page-page" );
                },
            ];
        }

        $this->_sections['headers'] = [
            'title'    => __( 'Headers', 'innocode-mail-helpers' ),
            'callback' => function () {
                printf(
                    '<p>%s</p>',
                    __( 'Here it\'s possible to override some additional headers of mail.', 'innocode-mail-helpers' )
                );
            },
            'page'     => Plugin::OPTION_GROUP,
        ];
        $this->_sections['test'] = [
            'title'    => __( 'Test Email', 'innocode-mail-helpers' ),
            'callback' => function () {
                printf(
                    '<p>%s</p>',
                    __(
                        'Send a test email to selected address and check mail functionality, headers etc.',
                        'innocode-mail-helpers'
                    )
                );
            },
            'page'     => Plugin::OPTION_GROUP . '_tools',
        ];

        $this->_fields['new_from_address'] = [
            'title'    => __( 'From Email', 'innocode-mail-helpers' ),
            'callback' => function () use ( $option ) {
                /**
                 * @var Option $new_from_address
                 * @var Option $from_address
                 */
                $new_from_address = $option( 'new_from_address' );
                $from_address = $option( 'from_address' );
                $value = $from_address->get();

                printf(
                    '<input type="email" id="%s" name="%s" value="%s" placeholder="%s" class="regular-text">
<p class="description">%s</p>',
                    Plugin::OPTION_GROUP . '-new_from_address',
                    esc_attr( $new_from_address->get_name() ),
                    esc_attr( $value ),
                    esc_attr( $from_address->get_default() ),
                    __(
                        'The email address which emails are sent from. <strong>It\'s recommended that the domain portion matches your sending domain.</strong>',
                        'innocode-mail-helpers'
                    )
                );

                if ( defined( 'MAILGUN_DOMAIN' ) ) {
                    printf(
                        '<p class="description">%s</p>',
                        sprintf(
                            __(
                                'Most likely %s is used as mailer and <strong>%s</strong> is your sending domain.',
                                'innocode-mail-helpers'
                            ),
                            '<a href="https://www.mailgun.com/" target="_blank" rel="noreferrer noopener">Mailgun</a>',
                            MAILGUN_DOMAIN
                        )
                    );
                }

                $new_value = $new_from_address->get();

                if ( $new_value && $new_value != $value ) {
                    printf(
                        '<div class="updated inline">
    <p>%s <a href="%s">%s</a></p>
</div>',
                        sprintf(
                            __( 'There is a pending change to %s.', 'innocode-mail-helpers' ),
                            '<code>' . esc_html( $new_value ) . '</code>'
                        ),
                        esc_url(
                            wp_nonce_url(
                                $this->get_options_url( "dismiss={$new_from_address->get_name()}" ),
                                'dismiss-' . get_current_blog_id() . "-{$new_from_address->get_name()}"
                            )
                        ),
                        __( 'Cancel', 'innocode-mail-helpers' )
                    );
                }
            },
            'page'     => Plugin::OPTION_GROUP,
            'section'  => 'headers',
        ];
        $this->_fields['from_name'] = [
            'title'    => __( 'From Name', 'innocode-mail-helpers' ),
            'callback' => function () use ( $option ) {
                /**
                 * @var Option $from_name
                 */
                $from_name = $option( 'from_name' );

                printf(
                    '<input type="text" id="%s" name="%s" value="%s" placeholder="%s" class="regular-text">
<p class="description">%s</p>',
                    Plugin::OPTION_GROUP . '-from_name',
                    esc_attr( $from_name->get_name() ),
                    esc_attr( $from_name->get() ),
                    esc_attr( $from_name->get_default() ),
                    __( 'The name which emails are sent from.', 'innocode-mail-helpers' )
                );
            },
            'page'     => Plugin::OPTION_GROUP,
            'section'  => 'headers',
        ];
        $this->_fields['test_email_to'] = [
            'title'    => __( 'Email Address', 'innocode-mail-helpers' ),
            'callback' => function () {
                $user_email = wp_get_current_user()->user_email;

                printf(
                    '<input type="email" id="%s" name="%s" value="%s" placeholder="%s" required class="regular-text">
<p class="description">%s</p>',
                    Plugin::OPTION_GROUP . '-test_email_to',
                    'to',
                    esc_attr( $user_email ),
                    esc_attr( $user_email ),
                    __( 'The email address where test email will be sent.', 'innocode-mail-helpers' )
                );
            },
            'page'     => Plugin::OPTION_GROUP . '_tools',
            'section'  => 'test',
        ];
    }

    /**
     * @return array
     */
    public function get_settings()
    {
        return $this->_settings;
    }

    /**
     * @return array
     */
    public function get_pages()
    {
        return $this->_pages;
    }

    /**
     * @return array
     */
    public function get_sections()
    {
        return $this->_sections;
    }

    /**
     * @return array
     */
    public function get_fields()
    {
        return $this->_fields;
    }

    /**
     * @param string $path
     * @return string
     */
    public function get_options_url( $path = '' )
    {
        return admin_url(
            'options-general.php?page=' . Plugin::OPTION_GROUP . (
                $path ? "&$path" : ''
            )
        );
    }

    /**
     * @param string $path
     * @return string
     */
    public function get_tools_url( $path = '' )
    {
        return admin_url(
            'tools.php?page=' . Plugin::OPTION_GROUP . '_tools' . (
                $path ? "&$path" : ''
            )
        );
    }

    public function register_settings()
    {
        foreach ( $this->get_settings() as $setting => $args ) {
            register_setting(
                Plugin::OPTION_GROUP,
                Plugin::OPTION_GROUP . "_$setting",
                $args
            );
        }
    }

    public function add_pages()
    {
        foreach ( $this->get_pages() as $name => $page ) {
            $function = "add_{$name}_page";
            $function(
                __( 'Mail', 'innocode-mail-helpers' ),
                __( 'Mail', 'innocode-mail-helpers' ),
                'manage_options',
                Plugin::OPTION_GROUP . (
                    $name == 'management' ? '_tools' : ''
                ),
                $page['callback']
            );
        }
    }

    public function add_sections()
    {
        foreach ( $this->get_sections() as $id => $section ) {
            add_settings_section(
                $id,
                $section['title'],
                $section['callback'],
                $section['page']
            );
        }
    }

    public function add_fields()
    {
        foreach ( $this->get_fields() as $name => $field ) {
            $id = Plugin::OPTION_GROUP . "-$name";

            add_settings_field(
                $id,
                $field['title'],
                $field['callback'],
                $field['page'],
                $field['section'],
                [ 'label_for' => $id ]
            );
        }
    }

    public function check_actions_capability()
    {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die(
                '<h1>' . __( 'You need a higher level of permission.' ) . '</h1>' .
                '<p>' . __( 'Sorry, you are not allowed to manage options for this site.' ) . '</p>',
                403
            );
        }
    }

    /**
     * @param Option $from_address
     * @param Option $new_from_address
     */
    public function check_from_email_domain( Option $from_address, Option $new_from_address )
    {
        if ( ! defined( 'MAILGUN_DOMAIN' ) ) {
            return;
        }

        $from_address_value = $from_address->get();

        if ( ! $from_address_value || ! is_email( $from_address_value ) ) {
            return;
        }

        list( , $domain ) = explode( '@', $from_address_value, 2 );

        if ( $domain != MAILGUN_DOMAIN ) {
            add_settings_error(
                $new_from_address->get_name(),
                'from_email_domain_different_with_sender',
                sprintf(
                    __(
                        'The currently set From Email address has different domain with the most likely used sending domain: %s.',
                        'innocode-mail-helpers'
                    ),
                    MAILGUN_DOMAIN
                ),
                'warning'
            );
        }
    }

    /**
     * @param Option $from_address
     * @param Option $new_from_address
     * @param Option $hash
     */
    public function new_from_address_action( Option $from_address, Option $new_from_address, Option $hash )
    {
        $updated = 'false';
        $hash_value = $hash->get();

        if (
            is_array( $hash_value ) &&
            hash_equals( $hash_value['hash'], $_GET[ $hash->get_name() ] ) &&
            ! empty( $hash_value['newemail'] )
        ) {
            $from_address->update( $hash_value['newemail'] );
            $hash->delete();
            $new_from_address->delete();
            $updated = 'true';
        }

        wp_redirect( $this->get_options_url( "updated=$updated" ) );
        exit;
    }

    /**
     * @param Option $new_from_address
     * @param Option $hash
     */
    public function dismiss_new_from_address_action( Option $new_from_address, Option $hash )
    {
        check_admin_referer(
            'dismiss-' . get_current_blog_id() . "-{$new_from_address->get_name()}"
        );

        $hash->delete();
        $new_from_address->delete();

        wp_redirect( $this->get_options_url( 'updated=true' ) );
        exit;
    }

    public function send_test_email_action()
    {
        check_admin_referer( Plugin::OPTION_GROUP . '-tools' );

        $sent = 'false';
        $to = isset( $_POST['to'] ) ? $_POST['to'] : '';

        if ( ! is_email( $_POST['to'] ) ) {
            $to = wp_get_current_user()->user_email;
        }

        /* translators: Do not translate EMAIL, SITENAME, SITEURL: those are placeholders. */
        $email_text = __(
            'Howdy,

You recently sent a test email.

You can safely ignore and delete this email.

This email has been sent to ###EMAIL###

Regards,
All at ###SITENAME###
###SITEURL###',
            'innocode-mail-helpers'
        );

        $content = apply_filters(
            'innocode_mail_helpers_test_email_content',
            $email_text
        );
        $content = str_replace( '###EMAIL###', $to, $content );
        $content = str_replace(
            '###SITENAME###',
            wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES ),
            $content
        );
        $content = str_replace( '###SITEURL###', home_url(), $content );

        if ( wp_mail(
            $to,
            sprintf(
                /* translators: Test mail notification email subject. %s: Site title. */
                __( '[%s] Test Email' ),
                wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES )
            ),
            $content
        ) ) {
            $sent = 'true';
        }

        wp_redirect( $this->get_tools_url( "updated=$sent" ) );
        exit;
    }
}
