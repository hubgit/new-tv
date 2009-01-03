<?php

require_once 'includes/functions.inc.php';

$result = db_query("SELECT * FROM episodes WHERE MATCH (title, subtitle, synopsis) AGAINST ('%s') LIMIT %d", $_REQUEST['q'], $_REQUEST['n']);

$items = array();
while ($item = mysql_fetch_object($result))
  format_episode($item);


