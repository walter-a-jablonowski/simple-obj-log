<?php

namespace WAJ\Lib\Log;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;


/*@

SimpleObjLog

- no type hinting, be conpatible old versions

*/
final class SimpleObjLog  /*@*/
{

  private $log;
  private $config = [
    'useMS'  => true,
    'format' => 'csv',
    'delim'  => ';'
  ];

  /*@

  */
  public function __construct( $log, $clear = true )  /*@*/
  {
    // TASK: is valid file path

    $this->log = $log;

    if( $clear )
      $this->clear();
  }

  /*@

  */
  public function setConfig( $config )  /*@*/
  {
    if( array_key_exists('format', $config) && ! in_array($config, ['csv', 'json', 'yml']) )
      throw new \Exception('illegal format');

    // TASK: ...

    $this->config = array_merge_recursive( $this->config, $config );
  }
  
  /*@

  */
  public function clear()  /*@*/
  {
    if( file_exists($this->log) )
      unlink( $this->log );
  }

  /*@

  */
  public function log( $objType, $obj )  /*@*/
  {
    if( $this->config['useMS'] )
      $time = (new \DateTime())->format('Y-m-d H:i:s.u');  // date() has no .u
    else
      $time = (new \DateTime())->format('Y-m-d H:i:s');

    $a = array_merge( ['time' => $time, 'type' => $objType], $obj );

    if( $this->config['format'] === 'csv' )
      $s = implode( $this->config['delim'], $a);
    elseif( $this->config['format'] === 'json' )
      $s = json_encode( $a, JSON_PRETTY_PRINT );
    elseif( $this->config['format'] === 'yml' )
      $s = Yaml::dump( $a, 100, 2 );  // 100 = never use inline

    file_put_contents( $this->log, "\n" . $s, FILE_APPEND );
  }

}

?>