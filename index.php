<?php
require 'db.inc.php';
require 'functions.inc.php';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title>New TV</title>
<link rel="stylesheet" href="style.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.2.6/jquery.min.js"></script>
<script type="text/javascript" src="script.js"></script>
</head>

<body>

<ul id="sidebar">
  <?php
  $old_date = '';
  $result = db_query("SELECT * FROM episodes WHERE date > %d ORDER BY date DESC LIMIT 100", time() - 60*60*24*7); // 1 week
  while ($item = mysql_fetch_object($result)):
  $date = getdate($item->date);
  $date = date('Y-m-d', $item->date);
  if ($old_date != $date):
    $old_date = $date;
  ?>
  <h3 class="date"><?php print $date; ?></h3> 
  <?php endif; ?>
     <li>
       <div class="meta">
         <a class="player" type="application/x-shockwave-flash" href="http://www.bbc.co.uk/emp/9player.swf?config=http://www.bbc.co.uk/emp/iplayer/config.xml&amp;config_settings_skin=silver&amp;config_settings_suppressRelatedLinks=true&amp;config_plugin_autoResumePlugin_recentlyPlayed=false&amp;playlist=http://www.bbc.co.uk/iplayer/playlist/<?php print $item->episode; ?>"><img align="left" src="http://www.bbc.co.uk/iplayer/images/episode/<?php print $item->episode; ?>_150_84.jpg"></a> <!-- also 640x360 -->
         
         <a href="<?php print 'http://www.bbc.co.uk/programmes/' . $item->series; ?>"><?php print $item->title; ?></a>: 
         <?php if ($item->subtitle): ?>  
           <a href="<?php print 'http://www.bbc.co.uk/programmes/' . $item->episode; ?>"><?php print $item->subtitle; ?></a>
         <?php endif; ?>
         <br>
         <a class="synopsis"><?php print $item->synopsis; ?></a><br>
         <!--<object class="download" type="application/x-shockwave-flash" data="http://www.bbc.co.uk/iplayer/dm/iplayer_download_badge.swf?playlist=http://www.bbc.co.uk/iplayer/playlist/b00gqgtw&amp;flashVersionRequired=10.0.15&amp;electraVersionRequired=0.6.1463&amp;pid=b00gqgtw&amp;playItem=b00gqfsv&amp;debugging=on&amp;labelText=To%20Computer"></object>-->
       </div>
     </li>
  <?php endwhile; ?>
</ul>

<object id="player" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="default" quality="high" bgcolor="#000000">You need to install Flash Player.</object>

</body>
