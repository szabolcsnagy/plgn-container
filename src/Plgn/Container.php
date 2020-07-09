<?php
/**
 * The container holds together the plugin wide constants 
 * It implements the ArrayAccess interface so it can be used as an array to add 
 * new components
 * An other advantage is to use do all new/init at one spot instead of scattering it all over the code 
 */

namespace Plgn;
// once you start using namespaces all classes 
// outside of your namespace needs to be referred
// to with namepace\classname
class Container implements \ArrayAccess {
  // This will store the container content 
  protected $contents;

  public function __construct(){
    $this->contents = array();
  }

  public function offsetSet($offset,$value){
    $this->contents[$offset] = $value;
  }

  public function offsetExists($offset) {
    return array_key_exists($offset,$this->contents);
  }

  public function offsetUnset($offset) {
    if($this->offsetExists($offset)) {
      unset($this->contents[$offset]);
    }
  }

  // This is crutial to understand that it will call any callable upon get
  public function offsetGet($offset) {
    // first check if the requested content is callable
    if(is_callable($this->contents[$offset])) {
      if(WP_DEBUG===true) {
        error_log("CALLABLE $offset");
      }
      // if so, then call it with all this container so it 
      // can access everything in this pluging
      return call_user_func($this->contents[$offset],$this);
    }

    // otherwise check if it is exists or return null
    return $this->offsetExists($offset)? $this->contents[$offset] : null;
  }

  // This might not be a good idea as the order of 
  // calling run on the container content is not guaranteed
  public function run() {
    // goes through the content calls anything callable and rewrite its value 
    // into the container
    foreach($this->contents as $key => $content){
      if(is_callable($content)) {
        // This will call the callable because in offsetGet
        // we implemented call_user_func
        $content = $this[$key]; // calls the function and stores its return value???
      }

      if(is_object($content)) {
        // get a reflection class to see if we have a run method
        $reflection = new \ReflectionClass($content);
        if($reflection->hasMethod('run')) {
          $content->run();
        }
      }
    }
  }

}
