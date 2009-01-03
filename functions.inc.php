<?php

function db_query(){
  $params = func_get_args();
  $query = array_shift($params);

  foreach ($params as $key => $value)
    if (!is_int($value))
      $params[$key] = mysql_real_escape_string($value);
  
  $sql = vsprintf($query, $params);
  debug($sql);
  $result = mysql_query($sql);
  if (mysql_errno())
    debug(mysql_error());
  return $result;
}

function debug($item){
  $debug = 0;
  if ($debug){
    print_r($item);
    print "<br/>\n";
  }
}
