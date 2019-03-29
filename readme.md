## GDPR Consent

A very simple consent bar.

This plugin has already been approved by Wordpress for use in VIP installations.

### Installing on VIP GO

```shell
git submodule add https://github.com/firstandthird/wp-gdpr-consent.git plugins/wp-gdpr-consent
```

Add this to `client-mu-plugins/plugin-loader.php` or similar:

```php
wpcom_vip_load_plugin( 'wp-gdpr-consent' );
```

### Installing on other Wordpress installations

Download the latest release and copy the contents of the zip file to the plugins directory.

Activate the plugin.

### First use

Go to Settings » GDPR Consent

Click save to set default text.

Banner should show up on site mainpage.

### Wrapping scripts

Some scripts should only be run once consent has been granted. This plugin includes a helper method to queue up scripts.

```javascript
if (window.gdprSafeTrack) {
  window.gdprSafeTrack(function() {
    // place your script here
  });
}
```

### Dev setup

`./run`

Open browser: http://localhost:8080

Install wordpress.

Activate plugin.

### Creating plugin zip

`./run generate-package`
