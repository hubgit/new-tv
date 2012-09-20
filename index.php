<!DOCTYPE html>
<?
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
  <link rel=stylesheet href=style.css?1>
  <link rel=apple-touch-icon href=icon.png>
  <meta name=viewport content=width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0;>
  <meta name=apple-mobile-web-app-capable content=yes>
  <meta name=apple-mobile-web-app-status-bar-style content=black-translucent>
</head>

<body>
  <nav>
<? if ($page > 1): ?>
    <a id=back href="./?page=<? h($page - 1); ?>">Back</a>
<? endif; ?>
    <a href=./><h1>New TV</h1></a>
  </nav>

  <div class="intro">TV episodes broadcast for the first time on BBC iPlayer</div>

  <form id=search>
    <input name=q value="<? h($q); ?>" placeholder="Search">
    <!--<input type=submit value=search>-->
  </form>

  <ol id=programmes>
<? while ($item = mysql_fetch_object($result)): ?>
<? foreach ($ignores as $ignore) if (preg_match("/\b$ignore\b/i", $item->title)) continue(2); ?>
<? $date = date('j F Y', $item->date); ?>
<? $link = $iplayer . $item->episode; ?>

<? if ($old_date != $date): $old_date = $date; ?>
    <li>
      <h2 class=date><? h($date); ?></h2>
    </li>
<? endif; ?>

    <li class="episode<? if ($item->position == 1) print ' new-series'; else if ($item->position == 0) print ' single'; ?>">
      <a class=thumbnail href="<? h($link); ?>">
        <img width=150 height=84 class=episode-image src="http://www.bbc.co.uk/iplayer/images/episode/<? h($item->episode); ?>_150_84.jpg"/> <!-- also 640x360 -->
      </a>

      <div class=meta>
        <div class=title>
          <a href="<? h($link); ?>"><? h($item->title); ?></a>
        </div>
        <div class=synopsis><? h($item->synopsis); ?></div>
<? if ($item->series && $item->position): ?>
        <div class=series>Episode <span class=position><? h($item->position); ?><span><? if ($item->series_total): ?>/<span class=series-total><? h($item->series_total); ?></span><? endif; ?><? if ($item->series_position): ?>, Series <span class=series-position><? h($item->series_position); ?></span><? endif; ?></div>
<? endif; ?>
      </div>
    </li>
<? endwhile; ?>
  </ol>

  <nav>
    <a id=more rel=next href="./?q=<? h($q); ?>&page=<? h($page + 1); ?>">More</a>
  </nav>

  <script src=/jquery.js></script>
  <script src=/js/jquery/jquery.inview/jquery.inview.min.js></script>
  <script>
    function loadMore(e, isInView, visiblePartX, visiblePartY){
      if (!isInView) return false;
      $("#more").unbind("inview").html("Loading&hellip;");
      $.ajax({
        url: $(this).attr("href"),
        datatype: "html",
        success: function(data){
          var episodes = $("li.episode", data);
		if (!episodes.length) {
			$("#more").hide();
			return;
		}
          episodes.appendTo("#programmes");
          $("#more").attr("href", $("#more", data).attr("href"));
          $("#more").bind("inview", loadMore).html("More");
        },
      });
    }
    
    $().ready(function(){
      $(".episode").click(function(){
        window.location.href = $(this).find("a").attr("href");
      });
      
      $("#more").bind("inview", loadMore);
    });
  </script>
</body>
</html>

