<?php
add_action('admin_menu', 'gdprconsent_add_admin_menu');
add_action('admin_init', 'gdprconsent_settings_init');

function gdprconsent_add_admin_menu() {
	add_options_page('GDPR Consent', 'GDPR Consent', 'manage_options', 'gdpr_consent', 'gdprconsent_options_page');
}


function gdprconsent_settings_init(  ) {
	register_setting('gdprconsent', 'gdprconsent_copy');
	register_setting('gdprconsent', 'gdprconsent_button');
	register_setting('gdprconsent', 'gdprconsent_privacy_link_text');
	register_setting('gdprconsent', 'gdprconsent_privacy_link');

	add_settings_section(
		'gdprconsent_pluginPage_copy_section',
		__('Copy', 'gdprconsent'),
		'__return_empty_string',
		'gdprconsent'
	);

  add_settings_section(
		'gdprconsent_pluginPage_privacy_section',
		__('Privacy Policy', 'gdprconsent'),
		'__return_empty_string',
		'gdprconsent'
	);

	add_settings_field(
		'consent-copy',
		__('Consent', 'gdprconsent'),
		'gdprconsent_field_consent_copy_render',
		'gdprconsent',
		'gdprconsent_pluginPage_copy_section'
	);

  add_settings_field(
		'consent-button',
		__('Button', 'gdprconsent'),
		'gdprconsent_field_consent_button_render',
		'gdprconsent',
		'gdprconsent_pluginPage_copy_section'
	);

  add_settings_field(
		'consent-privacy-text',
		__('Link Text', 'gdprconsent'),
		'gdprconsent_field_consent_privacy_link_text_render',
		'gdprconsent',
		'gdprconsent_pluginPage_privacy_section'
	);

  add_settings_field(
		'consent-privacy-link',
		__('Link', 'gdprconsent'),
		'gdprconsent_field_consent_privacy_link_render',
		'gdprconsent',
		'gdprconsent_pluginPage_privacy_section'
	);
}


function gdprconsent_field_consent_copy_render() {
  $default_copy = __('To personalize content, tailor and measure ads and provide a safer experience, we use cookies. By clicking accept you agree to our [privacy].', 'gdprconsent');
	$option = get_option('gdprconsent_copy', $default_copy);
	?>
	<textarea cols='40' rows='5' name='gdprconsent_copy' class='large-text'><?php echo esc_textarea($option); ?></textarea>
	<?php
}

function gdprconsent_field_consent_button_render() {
  $default_button = __('I Agree', 'gdprconsent');
	$option = get_option('gdprconsent_button', $default_button);
	?>
	<input type='text' name='gdprconsent_button' value='<?php echo esc_attr($option); ?>' class='regular-text' />
	<?php
}

function gdprconsent_field_consent_privacy_link_text_render() {
  $default = __('privacy policy', 'gdprconsent');
	$option = get_option('gdprconsent_privacy_link_text', $default);
	?>
	<input type='text' name='gdprconsent_privacy_link_text' value='<?php echo esc_attr($option); ?>' class='regular-text' />
	<?php
}

function gdprconsent_field_consent_privacy_link_render() {
  $default = __('/privacy', 'gdprconsent');
	$option = get_option('gdprconsent_privacy_link', $default);
	?>
	<input type='text' name='gdprconsent_privacy_link' value='<?php echo esc_attr($option); ?>' class='regular-text' />
	<?php
}

function gdprconsent_options_page() {
	?>
	<form action='options.php' method='post'>
		<h1><?php esc_html_e('GDPR Consent', 'gdprconsent'); ?></h1>

		<?php
		settings_fields('gdprconsent');
		do_settings_sections('gdprconsent');
		submit_button();
		?>

	</form>
	<?php
}
