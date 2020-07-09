<?php

namespace Plgn;

class SettingsPage extends WpSettingsPage {
 
  public function __construct($settings,$logger) {
    parent::__construct($settings,$logger);
  }

  public function get_default_settings_data() {
    $defaults = array();
    $defaults['textbox'] = '';
    return $defaults;
  }

  public function render_settings_page() {
    $option_name = $this->settings_page_properties['option_name'];
    $option_group = $this->settings_page_properties['option_group'];
    $settings_data = $this->get_settings_data();
    ?>
    <div class="wrap">
      <h2> Plgn Container</h2>
      <p>This plugin is using the settings API</p>
      <form method="post" action="options.php">
        <?php
          // https://codex.wordpress.org/Settings_API
          settings_fields($option_group);
        ?>
        <table class="form-table">
          <tr>
            <th><label for="textbox">Textbox:</label></th>
            <td>
              <input type="text" id="textbox"
                name="<?php echo esc_attr($option_name."[textbox]"); ?>"
                value="<?php echo esc_attr($settings_data['textbox']); ?>"
                />
            </td>
          </tr>
        </table>
        <input type="submit" name="submit" id="submit" class="button button-primary" 
          value="Save Options"
        />
      </form>
    </div>
    <?php
  }
}