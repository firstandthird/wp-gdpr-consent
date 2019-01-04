<?php
add_action('admin_menu', 'gdprconsent_add_admin_menu');
add_action('admin_init', 'gdprconsent_settings_init');

function gdprconsent_add_admin_menu() {
	add_options_page('GDPR Consent', 'GDPR Consent', 'manage_options', 'gdpr_consent', 'gdprconsent_options_page');
}


function gdprconsent_settings_init(  ) {
	register_setting('gdprconsent', 'gdprconsent_copy');
	register_setting('gdprconsent', 'gdprconsent_cta');

	add_settings_section(
		'gdprconsent_pluginPage_copy_section',
		__('Copy', 'gdprconsent'),
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
		'consent-cta',
		__('CTA Button', 'gdprconsent'),
		'gdprconsent_field_cta_render',
		'gdprconsent',
		'gdprconsent_pluginPage_copy_section'
	);
}


function gdprconsent_field_consent_copy_render() {
  $default_copy = __('To personalize content, tailor and measure ads and provide a safer experience, we use cookies. By tapping on the site you agree to our use of cookies.', 'gdprconsent');
	$option = get_option('gdprconsent_copy', $default_copy);
	?>
	<textarea cols='40' rows='5' name='gdprconsent_copy'><?php echo esc_textarea($option); ?></textarea>
	<?php
}

function gdprconsent_field_cta_render() {
  $default_cta = __('I Agree', 'gdprconsent');
	$option = get_option('gdprconsent_cta', $default_cta);
	?>
	<input type='text' name='gdprconsent_cta' value='<?php echo esc_attr($option); ?>'>
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
