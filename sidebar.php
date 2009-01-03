<?php require_once 'includes/functions.inc.php'; ?>

<form id="search" action="search.php" method="get">
    <input class="q" type="text" name="q" size="30"/>
    <input class="limit" type="hidden" name="n" value="10"/>
    <input type="submit" value="search"/>
</form>

<?php
$result = db_query("SELECT * FROM episodes WHERE date > %d ORDER BY date DESC LIMIT 10", time() - 60*60*24*7); // 1 week

while ($item = mysql_fetch_object($result))
  format_episode($item);

?>
