<?php

namespace WAJ\Lib\Log;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;


/*@

SimpleObjLog

- no type hinting, be conpatible old versions

Code should stay simple

*/
final class SimpleObjLog  /*@*/
{

  private $log;
  private $config = [
    'useMS'       => true,
    'format'      => 'csv',
    'linksMember' => 'xlinks',
    // 'linkClass'       => 'MyClass',  // advanced optional
    // 'linkClassMamber' => 'MyClass',  
    'delim'       => ';'                // csv only
  ];


  /*@

  */
  public function __construct( $log, $clear = false )  /*@*/
  {
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

  merges time and type in front

  ARGS:
    obj: just an array

  */
  public function log( $objType, $obj )  /*@*/
  {
    if( $this->config['useMS'] )
      $time = (new \DateTime())->format('Y-m-d H:i:s.u');  // date() has no .u
    else
      $time = (new \DateTime())->format('Y-m-d H:i:s');


    if( $this->config['format'] === 'csv' )
    {
      // print header no file or empty
      if( ! is_file($this->log) || trim( file_get_contents($this->log)) === '' )
        file_put_contents( $this->log, implode($this_config['delim'], explode('|', 'time|type|object_data')));

      $a = array_merge( ['@time' => $time, '@type' => $objType], $obj );

      // TASK: dont print linked obj in csv

      $s = implode( $this->config['delim'], $a);
    }
    elseif( $this->config['format'] === 'json' )
    {
      $s = json_encode( $obj, JSON_PRETTY_PRINT );

      // TASK: like yml
    }
    elseif( $this->config['format'] === 'yml' )
    {
      $s = Yaml::dump( $obj, 2, 2 );  // use multi line, indent

      // TASK: add time ad key, type?
      // TASK: print one level of linked obj in linksMember

      // $this->prepareStructured()
    }

    file_put_contents( $this->log, "\n" . $s, FILE_APPEND );
  }

  
  private function prepareStructured()
  {
  
  }

}

?>
