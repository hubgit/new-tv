<?php include 'setup.inc.php'; ?>
<html>
<head>
<title>New TV</title>
<link rel="stylesheet" href="style.css">
<script src="http://jqueryjs.googlecode.com/files/jquery-1.2.6.min.js"></script>
<script src="script.js"></script>
</head>

<body>

<ul id="genres" class="type">
 <?php $root = $_SERVER['SCRIPT_NAME'] . '?genre='; foreach ($genres as $id => $title): ?>
    <li>
      <a href="<?php print $root . $id; ?>"><?php print $title; ?></a>
    </li>
 <?php endforeach; ?>
</ul>

<ul id="formats" class="type">
 <?php $root = $_SERVER['SCRIPT_NAME'] . '?format='; foreach ($formats as $id => $title): ?>
   <li>
      <a href="<?php print $root . $id; ?>"><?php print $title; ?></a>
   </li>
 <?php endforeach; ?>
</ul>

<ul id="sidebar">
  <?php
  $data = fetch_data();

  $seen = array();
  foreach ($data->broadcasts as $broadcast):
    //if ($broadcast->is_repeat) continue;
      
    $episode = $broadcast->programme;
    if (!$episode->media) continue;

    
    if ($seen[$episode->pid]) continue;
    else $seen[$episode->pid] = 1;
    
    $series = $episode->programme;
    $brand = $series->programme;
    ?>
     <li>
       <!-- 640x360 -->
       <div class="meta">
         <a class="player" style="float:left" href="http://www.bbc.co.uk/emp/9player.swf?config=http://www.bbc.co.uk/emp/iplayer/config.xml&config_settings_skin=silver&config_settings_suppressRelatedLinks=true&config_plugin_autoResumePlugin_recentlyPlayed=false&playlist=http://www.bbc.co.uk/iplayer/playlist/<?php print $episode->pid; ?>"><img align="left" src="http://www.bbc.co.uk/iplayer/images/episode/<?php print $episode->pid; ?>_150_84.jpg"></a>
         
         <?php if ($episode->programme->programme): ?>
           <a href="<?php print 'http://www.bbc.co.uk/programmes/' . $brand->pid; ?>" class="brand"><?php print $brand->title; ?></a>: 
           <a href="<?php print 'http://www.bbc.co.uk/programmes/' . $series->pid; ?>" class="series"><?php printf('s%02d', $series->position); ?></a>
           <a href="<?php print 'http://www.bbc.co.uk/programmes/' . $episode->pid; ?>" class="episode"><?php printf('e%02d', $episode->position); ?></a>
         <?php else: ?>
           <a href="<?php print 'http://www.bbc.co.uk/programmes/' . $episode->pid; ?>"><?php print $episode->display_titles->title; ?><?php if ($episode->display_titles->subtitle) print ': ' . $episode->display_titles->subtitle; ?></a>
         <?php endif; ?>
         <br>
         <a class="synopsis" href="<?php print 'http://www.bbc.co.uk/programmes/' . $episode->pid; ?>"><?php print $episode->short_synopsis; ?></a>
       </div>
     </li>
  <?php endforeach; ?>
</ul>

<object id="player" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="default" quality="high" bgcolor="#000"></object>

</body>
