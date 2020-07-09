<?php
namespace Plgn;

class LogLevel{
  const INFO=0;
  const WARN=1;
  const ERROR=2; 
  const LABELS = array(
    self::INFO => 'INFO',
    self::WARN => 'WARN',
    self::ERROR => 'ERROR'
  );
}

class Logger{
  private $prefix;
  private $logLevel;
  public function __construct($prefix,$logLevel){
    $this->prefix = $prefix;
    $this->logLevel = $logLevel ? $logLevel : LogLevel::INFO;
    error_log('CURRENT LOGLEVEL'.$this->logLevel);
  }

  protected function log($message,$logLevel) {
    if (WP_DEBUG===true) {
      error_log(LogLevel::LABELS[$logLevel].' '.$this->prefix.$message);
    }
  }

  public function info($message) {
    if ($this->logLevel <= LogLevel::INFO) {
      $this->log($message,LogLevel::INFO);
    }
  }

  public function warn($message) {
    if ($this->logLevel <= LogLevel::WARN) {
      $this->log($message,LogLevel::WARN);
    }
  }

  public function error($message) {
    if ($this->logLevel <= LogLevel::ERROR) {
      $this->log($message,LogLevel::ERROR);
    }
  }

}