<?php

add_action('wp_footer', 'gdprconsent_banner_output');

function gdprconsent_banner_output() {
  $copy = get_option('gdprconsent_copy');
  $cta = get_option('gdprconsent_cta');

  if (empty($copy) || empty($cta)) {
    return;
  }

  ?>
  <div class="gdprconsent-container" id="GDPRConsentBar">
    <div class="gdprconsent-wrapper">
      <div class="gdprconsent-content">
        <p><?php echo wp_kses_post($copy); ?></p>
      </div>
      <div class="gdprconsent-button">
        <button type="button"><?php echo esc_html($cta); ?></button>
      </div>
    </div>
  </div>
  <?php
}
