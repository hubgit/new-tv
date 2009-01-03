<?php

require_once 'includes/db.inc.php';

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

function format_episode($item){
    static $old_date;
    
    $date = date('Y-m-d', $item->date);
    if ($old_date != $date):
      $old_date = $date;
      
    ?>
    <h3 class="date"><?php print $date; ?></h3> 
    <?php endif; ?>
    <li class="episode">
     <div class="meta">
       <a class="player" type="application/x-shockwave-flash" href="http://www.bbc.co.uk/emp/9player.swf?config=http://www.bbc.co.uk/emp/iplayer/config.xml&amp;config_settings_skin=silver&amp;config_settings_suppressRelatedLinks=true&amp;config_plugin_autoResumePlugin_recentlyPlayed=false&amp;playlist=http://www.bbc.co.uk/iplayer/playlist/<?php print $item->episode; ?>"><img align="left" src="http://www.bbc.co.uk/iplayer/images/episode/<?php print $item->episode; ?>_150_84.jpg"></a> <!-- also 640x360 -->
       
       <a href="<?php print 'http://www.bbc.co.uk/programmes/' . $item->series; ?>"><?php print $item->title; ?></a>: 
       <?php if ($item->subtitle): ?>  
         <a href="<?php print 'http://www.bbc.co.uk/programmes/' . $item->episode; ?>"><?php print $item->subtitle; ?></a>
       <?php endif; ?>
       <br>
       <span class="synopsis"><?php print $item->synopsis; ?><span>
     </div>
    </li>
   <?php
}
