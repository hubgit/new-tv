<?php

require dirname(__FILE__) . '/main.php';

$page = isset($_REQUEST['page']) ? (int) $_REQUEST['page'] : 1;

$n = 50;
$start = ($page - 1) * $n;

if ($q = $_REQUEST['q'])
  $result = $db->query("SELECT * FROM episodes WHERE MATCH (title, subtitle, synopsis) AGAINST ('%s') ORDER BY date DESC LIMIT %d, %d", $q, $start, $n);
else if (array_key_exists('new-series', $_REQUEST))
  $result = $db->query("SELECT * FROM episodes WHERE position = 1 ORDER BY date DESC LIMIT %d, %d", $start, $n);
else
  $result = $db->query("SELECT * FROM episodes ORDER BY date DESC LIMIT %d, %d", $start, $n);

$items = array();

while ($item = mysql_fetch_object($result)) {
  $items[] = $item;
}

header('Content-Type: application/json');
//print json_encode($items, JSON_PRETTY_PRINT);
print json_encode($items);