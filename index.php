<!DOCTYPE html>
<?php 
require dirname(__FILE__) . '/main.php'; 

$iplayer = 'http://www.bbc.co.uk/iplayer/episode/';
  $agents = array('iphone', 'ipod');
  foreach ($agents as $agent)
    if (preg_match("/\b$agent\b/i", $_SERVER['HTTP_USER_AGENT']))
      $iplayer = 'http://www.bbc.co.uk/mobile/iplayer/episode/';
  
  $page = $_REQUEST['page'] ? (int) $_REQUEST['page'] : 1;
  
  $n = 50;
  $start = ($page - 1) * $n;
  
  if ($q = $_REQUEST['q'])
    $result = $db->query("SELECT * FROM episodes WHERE MATCH (title, subtitle, synopsis) AGAINST ('%s') ORDER BY date DESC LIMIT %d, %d", $q, $start, $n);
  else
    $result = $db->query("SELECT * FROM episodes ORDER BY date DESC LIMIT %d, %d", $start, $n);
    
  $old_date = 0;
  $ignores = array('news', 'weather', 'racing');
  
?>
<html>
<head>
  <title>New TV</title>
  <link rel=stylesheet href=style.css>
  <script src=/jquery.js></script>
  <script>
    $().ready(function(){
      $(".meta").click(function(){
        window.location.href = $(this).find("a").attr("href");
      });
    });
  </script>
</head>

<body>
    <nav>
      <?php if ($page > 1): ?><a href="./?page=<?php print $page - 1; ?>">Back</a><?php endif; ?>
      <a href="./">New TV</a>
    </nav>

    <form id="search">
      <input name="q" size="30" value="<?php $api->output($q, 'attr'); ?>"> <input type="submit" value="search">
    </form>    

    <table id="programmes">    
<?php while ($item = mysql_fetch_object($result)): ?>
<?php foreach ($ignores as $ignore) if (preg_match("/\b$ignore\b/i", $item->title)) continue(2); ?>
<?php $date = date('Y-m-d', $item->date); ?>
<?php $link = $iplayer . $item->episode; ?>
     
<?php if ($old_date != $date): $old_date = $date; ?><tr colspan="2"><td class="date"><?php print $date; ?></td></tr><?php endif; ?>
      
      <tr class="episode <?php if ($item->position == 1) print 'new-series'; else if ($item->position == 0) print 'single'; ?>">
          <td class="thumbnail">
            <a href="<?php $api->output($link, 'attr'); ?>">
              <img class="episode-image" src="http://www.bbc.co.uk/iplayer/images/episode/<?php $api->output($item->episode, 'attr'); ?>_150_84.jpg"/> <!-- also 640x360 -->
            </a>
          </div> 
          
          <td class="meta">
              <div class="title">
                <a href="<?php $api->output($link, 'attr'); ?>"><?php $api->output($item->title); ?></a>
              </div>
              
              <div class="synopsis"><?php $api->output($item->synopsis); ?><div>

<?php if ($item->series && $item->position): ?>
              <div class="series">
              Episode <span class="position"><?php $api->output($item->position); ?><span><?php if ($item->series_total): ?>/<span class="series-total"><?php $api->output($item->series_total); ?></span><?php endif; ?><?php if ($item->series_position): ?>, Series <span class="series-position"><?php $api->output($item->series_position); ?></span><?php endif; ?>
              </div>  
<?php endif; ?>
          </td>
      </tr>
<?php endwhile; ?>     
    </table>
    
    <nav>
      <a id="more" rel="external" href="./?page=<?php print $page + 1; ?>">More</a>
    </nav>
  </div>
</body>
</html>
