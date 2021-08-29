<?php

namespace WAJ\Lib\Logs;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;


/*@

SimpleObjLog

DEV-COMMENTS:
  - no type hinting, be conpatible old versions
  - Code should stay simple

*/
final class SimpleObjLog /*@*/
{

  private $log;
  private $config = [

    // general config

    'format'       => 'csv',
    'delim'        => ';',      // csv only
    'fillCSV'      => 0,        // csv only
    // 'addId'     => false,
    // 'idField'   => 'id',
    // 'cache'     => 'simple_obj_log_cache/',
    'addTime'      => true,
    'timeField'    => 'time',
    'useMS'        => true,
    'addType'      => true,
    'typeField'    => 'type',
    // 'linkClass' => 'MyClass'
    'addLinks'     => false,
    'linksField'   => 'xlinks'

  ];


  /*@

  */
  public function __construct( $log, $clear = false ) /*@*/
  {
    $this->log = $log;

    if( $clear )
      $this->clear();
  }


  /*@

  */
  public function setConfig( $config ) /*@*/
  {
    // TASK: valid keys...

    if( array_key_exists('format', $config) && ! in_array($config, ['csv', 'json', 'yml']) )
      throw new \Exception('Illegal format');

    $this->config = array_merge_recursive( $this->config, $config );
  }

  
  /*@

  */
  public function clear() /*@*/
  {
    if( file_exists($this->log) )
      unlink( $this->log );
  }


  /*@

  Log

  - merges time and type in front

  ARGS:
    objType:
    obj:  

  TASKS: see Readme and mngm
  
  */
  public function log( $objType, $obj ) /*@*/
  {
    // if logging php obj frist of all make array
    $o = $obj;
    $obj = [];

    // Add time

    if( $this->config['addTime'] )
    {
      $timeField = $this->config['timeField'];

      if( $this->config['useMS'] )
        $obj[$timeField] = (new \DateTime())->format('Y-m-d H:i:s.u');  // date() has no .u
      else
        $obj[$timeField] = (new \DateTime())->format('Y-m-d H:i:s');
    }

    // Add type

    if( $this->config['addType'] )
    {
      $typeField = $this->config['typeField'];
      $obj[$typeField] = $objType;
    }

    // Get links (see below)

    $linkedObj = [];
    $linksField = $this->config['linksField'];

    if( $this->config['addLinks'] )
    {
      $linkedObj = $o[$linksField];
    }

    if( isset($o[$linksField]) )
      unset($o[$linksField]);

    // Merge data

    $obj = array_merge( $obj, $o );


    switch( $this->config['format'] )
    {
      case 'csv':

        // Record

        $rec = implode( $this->config['delim'], $obj);
        $delim = $this->config['delim'];

        // Add links

        if( $linkedObj )
          $obj[$linksField] = json_encode( $linkedObj );

        // Fill csv fields
        
        $fill = $this->config['fillCSV'] - 1;
        
        $times = 0;
        if( $fill > 0 && substr_count($rec, $delim) < $fill)
          $times = $fill - substr_count($rec, $delim);
        
        $rec .= str_repeat( $delim, $times);

        // Print header if empty

        if( ! is_file($this->log) || trim( file_get_contents($this->log)) === '' )
        {
          $header = '';
          $header .= ( $this->config['addTime'] )  ?  'time|'  :  $header;
          $header .= ( $this->config['addType'] )  ?  'type|'  :  $header;
          $header .= 'object_data';
          $header .= str_repeat( $delim, $times);
          
          file_put_contents( $this->log, implode($delim, explode('|', $header)));
        }

        // Log

        file_put_contents( $this->log, "\n" . $rec, FILE_APPEND );

        break;


      case 'json':

        $s = json_encode( $obj, JSON_PRETTY_PRINT );

        // TASK: like yml

        break;


      case 'yml':

        $s = Yaml::dump( $obj, 2, 2 );  // use multi line, indent

        // TASK: add time ad key, type?
        // TASK: print linksField

        // $this->prepareStructured()

        break;
    }
  }

  
  private function prepareStructured()
  {
  
  }

}

?>
