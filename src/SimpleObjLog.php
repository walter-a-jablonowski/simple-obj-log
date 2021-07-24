<?php

namespace WAJ\Lib\Log;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;


/*@

SimpleObjLog

- no type hinting, be conpatible old versions

Code should stay simple

*/
final class SimpleObjLog /*@*/
{

  private $log;
  private $config = [
    'format'      => 'csv',
    'addTime'     => true,
    'useMS'       => true,
    'linksMember' => 'xlinks',          // advanced optional
    // 'linkClass'       => 'MyClass',
    // 'linkClassMember' => 'MyClass',  
    'delim'       => ';'                // csv only
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

  Merges time and type in front

  ARGS:
    obj: just an array

  */
  public function log( $objType, $obj ) /*@*/
  {
    if( $this->config['addTime'] )
    {
      if( $this->config['useMS'] )
        $time = (new \DateTime())->format('Y-m-d H:i:s.u');  // date() has no .u
      else
        $time = (new \DateTime())->format('Y-m-d H:i:s');
    }


    switch( $this->config['format'] )
    {
      case 'csv':

        // print header

        if( ! is_file($this->log) || trim( file_get_contents($this->log)) === '' )
        {
          $s = ( $this->config['addTime'] )  ?  'time|type|object_data'  :  'type|object_data';
          file_put_contents( $this->log, implode($this_config['delim'], explode('|', $s)));
        }

        // add time

        if( $this->config['addTime'] )
          $obj = array_merge( ['@time' => $time, '@type' => $objType], $obj );
        else
          $obj = array_merge( ['@type' => $objType], $obj );

        // log

        file_put_contents( $this->log,
          "\n" . implode( $this->config['delim'], $obj), FILE_APPEND
        );

        break;


      case 'json':

        $s = json_encode( $obj, JSON_PRETTY_PRINT );

        // TASK: like yml

        break;


      case 'csv':

        $s = Yaml::dump( $obj, 2, 2 );  // use multi line, indent

        // TASK: add time ad key, type?
        // TASK: print one level of linked obj in linksMember

        // $this->prepareStructured()

        break;
    }
  }

  
  private function prepareStructured()
  {
  
  }

}

?>
