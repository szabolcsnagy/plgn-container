<?php
namespace Plgn;

abstract class WpSettingsPage {
  protected $settings_page_properties;
  protected $logger;

  public function __construct($settings,$logger) {
    $this->settings_page_properties = $settings;
    $this->logger = $logger;
  }

  public function run() {
    // When a callable is inside a namespace you need to create an
    // array where the first item is the namespace and the second one is
    // the callable
    add_action('admin_menu',array($this,'add_menu_and_page'));
    add_action('admin_init',array($this,'register_settings'));
  }

  public function add_menu_and_page() {
    // https://developer.wordpress.org/reference/functions/add_submenu_page/
    add_submenu_page(
      $this->settings_page_properties['parent_slug'],
      $this->settings_page_properties['page_title'],
      $this->settings_page_properties['menu_title'],
      $this->settings_page_properties['capability'],
      $this->settings_page_properties['menu_slug'],
      array($this,'render_settings_page')
    );
    $this->logger->info("Settings page menu added.");
    $this->logger->warn("Settings page menu added.");
    $this->logger->error("Settings page menu added.");
  }

  public function register_settings() {
    // https://developer.wordpress.org/reference/functions/register_setting/
    register_setting(
      $this->settings_page_properties['option_group'],
      $this->settings_page_properties['option_name']
    );
  }

  public function get_settings_data() {
    return get_option($this->settings_page_properties['option_name'],
      // or return the default
      $this->get_default_settings_data()
    );
  }

  // Need to be implemented in subclasses
  public function get_default_settings_data() {
    $defaults = array();
    
    return defaults;
  }

  // Need to be implemented in subclasses
  public function render_settings_page() {
    
  }
}