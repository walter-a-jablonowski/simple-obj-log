<?php

use WAJ\Lib\Log\SimpleObjLog;

require 'src/SimpleObjLog.php';


$log = new SimpleObjLog('coin.log');

$log->log('order', [

  'type'     => 'buy',
  'pair'     => 'BTC/USDT',
  'amount'   => '1.0',
  'exchange' => 'Binance',
  'id'       => '8r2u98jf7843ufjht893gj4328032'
]);

var_dump( $log );

?>