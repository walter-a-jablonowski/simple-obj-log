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
    'typeField'    => 'type',
    // 'linkClass' => 'MyClass'
    'linkedField'  => 'linkedGraph'

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
    type:
    obj:
    linkedGraph:  if given graph is passed seperately ins of inside obj (useful if it is seperate vars)

  TASKS: see Readme and mngm
  
  */
  public function log( $type, $obj, $linkedGraph = [] ) /*@*/
  {
    $rec = [];

    $timeField   = $this->config['timeField'];
    $typeField   = $this->config['typeField'];
    $linkedField = $this->config['linkedField'];


    // Add time

    if( $this->config['addTime'] )
    {
      if( $this->config['useMS'] )
        $rec[$timeField] = (new \DateTime())->format('Y-m-d H:i:s.u');  // date() has no .u
      else
        $rec[$timeField] = (new \DateTime())->format('Y-m-d H:i:s');
    }


    // Add type

    $rec[$typeField] = $type;


    // Fields

    $rec = array_merge($rec, $obj);


    // Add linked graph if given seperately

    if( $linkedGraph )
      $rec[$linkedField] = $linkedGraph;


    switch( $this->config['format'] )
    {
      case 'csv':

        $delim = $this->config['delim'];
        $csv = $rec;


        // Encode linked graph

        if( $csv[$linkedField] )
          $csv[$linkedField] = json_encode( $csv[$linkedField] );


        // Implode

        $csv = implode( $delim, $csv);


        // Fill csv fields
        // TASK: CAN be improved
        
        $fill = $this->config['fillCSV'] - 1;
        
        $times = 0;
        if( $fill > 0 && substr_count($rec, $delim) < $fill)
          $times = $fill - substr_count($rec, $delim);
        
        $csv .= str_repeat( $delim, $times);


        // Print header if empty

        if( ! is_file($this->log) || trim( file_get_contents($this->log)) === '' )
        {
          $header = '';
          $header .= ( $this->config['addTime'] )  ?  "time$delim"  :  $header;
          $header .= "type$delim";
          $header .= implode( $delim, array_keys($rec)) . $delim;
          $header .= $linkedField;
          $header .= str_repeat( $delim, $times);
          
          file_put_contents( $this->log, $header);
        }


        // Log

        file_put_contents( $this->log, "\n" . $csv, FILE_APPEND );

        break;


      case 'json':

        $s = json_encode( $obj, JSON_PRETTY_PRINT );

        // TASK: like yml

        break;


      case 'yml':

        $s = Yaml::dump( $obj, 2, 2 );  // use multi line, indent

        // TASK: add time ad key, type?
        // TASK: print linkedField

        // $this->prepareStructured()

        break;
    }
  }

  
  private function prepareStructured()
  {
  
  }

}

?>
