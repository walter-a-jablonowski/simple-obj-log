<?php

use WAJ\Lib\Logs\SimpleObjLog;

require 'src/SimpleObjLog.php';


$log = new SimpleObjLog('coins.log');
$log->setConfig(['addLinks' => true]);

$log->log('order', [

  'type'     => 'buy',
  'pair'     => 'BTC/USDT',
  'amount'   => '1.0',
  'exchange' => 'Binance',
  'id'       => '8r2u98jf7843ufjht893gj4328032',
  'xlinks'   => [
    [
      'some'     => 'details',
      'bla'      => 1
    ]
  ]
]);

var_dump( $log );

?>
