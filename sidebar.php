<?php require_once 'includes/functions.inc.php'; ?>

<form id="search" action="sidebar.php" method="get">
    <input class="q" type="text" name="q" size="30" value="<?php print filter_var($_REQUEST['q'], FILTER_SANITIZE_SPECIAL_CHARS);?>"/>
    <input class="limit" type="hidden" name="n" value="10"/>
    <input type="submit" value="search"/>
</form>

<?php
if ($_REQUEST['q'])
  $result = db_query("SELECT * FROM episodes WHERE MATCH (title, subtitle, synopsis) AGAINST ('%s') ORDER BY date DESC LIMIT %d", $_REQUEST['q'], array_key_exists('n', $_REQUEST) ? $_REQUEST['n'] : 50);
else
  $result = db_query("SELECT * FROM episodes ORDER BY date DESC LIMIT 50");
  
while ($item = mysql_fetch_object($result))
  format_episode($item);

?>

<script>
$().ready(init);
</script>
