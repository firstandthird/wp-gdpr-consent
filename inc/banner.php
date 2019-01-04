<?php

add_action('wp_footer', 'gdprconsent_banner_output');

function gdprconsent_banner_output() {
  $copy = get_option('gdprconsent_copy');

  if (empty($copy)) {
    return;
  }

  ?>
  <div class="gdprconsent-container" id="GDPRConsentBar">
    <div class="gdprconsent-wrapper">
      <div class="gdprconsent-content">
        <p><?php echo wp_kses_post($copy); ?></p>
      </div>
    </div>
  </div>
  <?php
}
