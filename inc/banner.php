<?php

add_action('wp_footer', 'gdprconsent_banner_output');

function gdprconsent_banner_output() {
  $copy = get_option('gdprconsent_copy');
  $button = get_option('gdprconsent_button');
  $privacyLinkText = get_option('gdprconsent_privacy_link_text');
  $privacyLink = get_option('gdprconsent_privacy_link');

  if (empty($copy)) {
    return;
  }

  $linkHtml = '';

  if (!empty($privacyLinkText) && !empty($privacyLink)) {
    $linkHtml = '<a href="' . $privacyLink . '">' . $privacyLinkText . '</a>';
  }

  $copy = str_replace('[privacy]', $linkHtml, $copy);

  ?>
  <div class="gdprconsent-container" id="GDPRConsentBar">
    <div class="gdprconsent-wrapper">
      <div class="gdprconsent-content">
        <p><?php echo wp_kses_post($copy); ?></p>
        <?php if (!empty($button)) { ?>
        <div class="gdprconsent-button">
          <button type="button" data-gdpr-accept><?php echo esc_html($button); ?></button>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <?php
}
