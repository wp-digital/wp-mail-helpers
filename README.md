# Mail Helpers

### Description

Overrides mail From headers, adds check of mail functionality.

### Install

- Preferable way is to use [Composer](https://getcomposer.org/):

    ````
    composer require innocode-digital/wp-mail-helpers
    ````

    By default it will be installed as [Must Use Plugin](https://codex.wordpress.org/Must_Use_Plugins).
    But it's possible to control with `extra.installer-paths` in `composer.json`.

- Alternate way is to clone this repo to `wp-content/mu-plugins/` or `wp-content/plugins/`:

    ````
    cd wp-content/plugins/
    git clone git@github.com:innocode-digital/wp-mail-helpers.git
    cd wp-mail-helpers/
    composer install
    ````

If plugin was installed as regular plugin then activate **Mail Helpers** from Plugins page 
or [WP-CLI](https://make.wordpress.org/cli/handbook/): `wp plugin activate wp-mail-helpers`.

### Usage

#### Constants

If it's needed to override **From** mail headers in all emails then next constant should be
added (usually to `wp-config.php`):

````
define( 'MAIL_FROM_ADDRESS', '' );
define( 'MAIL_FROM_NAME', '' );
````

##### Notes

It's not required to set both constants, in most cases you only need to set `MAIL_FROM_ADDRESS`.

Plugin sets hook with pretty big priority `9999` but another plugins and themes could set 
bigger value and in this case you should change priority according to your needs, e.g.:

```
if ( function_exists( 'innocode_mail_helpers' ) ) {
    remove_filter( 'wp_mail_from', [ innocode_mail_helpers(), 'mail_from' ], 9999 );
    add_filter( 'wp_mail_from', [ innocode_mail_helpers(), 'mail_from' ], 10001 );
}
```

#### Settings

There is a possibility to set **From** mail headers in WordPress administration panel:
**Settings** > **Mail**. **From Email** should be verified in a similar way like WordPress
verifies **Administration Email Address**. These settings have bigger priority than constant.

#### Tools

Plugin adds a tool for testing mail functionality. You can send a test email in WordPress
administration panel from **Tools** > **Mail** page.