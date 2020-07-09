<?php
/*
 Plugin Name: Plugin container
 Version: 0.0.1
 Description: Test the concept of plugin containers.
 Author: Szabolcs
 Author URI: https://github.com/szabolcsnagy
 Text Domain: plgn-container
 */

// Define the namespace that we wish to use to get the SettingsPage and Container classes.
use Plgn\Container;
use Plgn\SettingsPage;
use Plgn\Logger;
use Plgn\LogLevel;

// this function is native to PHP and it vill register our loader.
spl_autoload_register( 'plgn_autoloader' );

// The loader is called every time a class is referenced that is not loaded yet.
function plgn_autoloader( $class_name ) {
  // error_log("CLASSNAME TO LOAD: $class_name");
  // we only care about the classes in this plugin so check if the classname contains
  // the name of the plugin Plgn;
  // We adpot the following naming convention:
  // - the classname it the path in the src folder with underscores
  //   example: Plgn/View.php contains Plgn_View class
  if ( false !== strpos( $class_name, 'Plgn' ) ) {
    $classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR;
    // generate the path to php file from the classname
    $class_file = str_replace( '\\', DIRECTORY_SEPARATOR, $class_name ) . '.php';
    if(WP_DEBUG === true) {
      error_log("AUTOLOADING:$classes_dir$class_file ");
    }
    // this is where our class is loaded.
    require_once $classes_dir . $class_file;
  }
}


// The Container

add_action('plugins_loaded','plgn_init');
function plgn_init(){
  $container = new Container();
  // All the setup is in one place
  $container['version'] = '0.0.1';
  $container['path'] = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR;
  $container['url'] = plugin_dir_url( __FILE__ );
  $container['settings_page_properties'] = array( 
    'parent_slug' => 'options-general.php',
    'page_title' =>  'Plgn Container',
    'menu_title' =>  'Plgn Container',
    'capability' => 'manage_options',
    'menu_slug' => 'plgn-container-settings',
    'option_group' => 'plgn_option_group',
    'option_name' => 'plgn_option_name'
  );
  // When the container looks at the string 'plgn_settings_service' it will find 
  // the function plgn_settings_service in the global namespace and will deem it callable
  $container['settings_page'] = 'plgn_settings_service'; // new SettingsPage( $container['settings_page_properties'] );
  
  // setup service with anonymous function
  $container['logger'] = function($container){
    // A service MUST return a object that will be written back to the container.
    // It must be an object because that is not a callable and when it is accessed 
    // (see container getter) it won't be executed again.
    static $logger;
    if (null !== $logger) {
      return $logger;
    }
    $logger = new Logger('PLGN LOG: ',LogLevel::ERROR);

    return $logger;
  };

  // call run on everything in the container.
  $container->run();
}


// Services

function plgn_settings_service($container) {
  // This service will generate the settings page only when needed

  // It creates only one instance of this service
  // If a request references this service more than once the same object is returned
  static $object;
  if (null !== $object) {
    return $object;
  } 

  $object = new SettingsPage($container['settings_page_properties'],$container['logger']);
  return $object;
}