<?php
//print_r($_ENV); exit();
//if ($_ENV['_'] != '/usr/bin/php') exit(); // command line only

require dirname(__FILE__) . '/main.php';

foreach (range(1,0) as $day){
  $date = date('Y/m/d', time() - (60*60*24*$day)); // yesterday's and today's listings
  //$date = date('Y/m/d');

  $channels = array(
    "http://www.bbc.co.uk/bbcone/programmes/schedules/london/$date.json",
    "http://www.bbc.co.uk/bbctwo/programmes/schedules/england/$date.json",
    "http://www.bbc.co.uk/bbcthree/programmes/schedules/$date.json",
    "http://www.bbc.co.uk/bbcfour/programmes/schedules/$date.json",
  );

  foreach ($channels as $channel){
    debug($channel);
    $data = $api->get_data($channel, NULL, 'json');
    
    foreach ($data->schedule->day->broadcasts as $broadcast){
      if ($broadcast->is_repeat) continue;
        
      $episode = $broadcast->programme;
      if (!$episode->media) continue;
      
      $series = new stdClass;
      if ($episode->programme)
        $series = $episode->programme;
          
      $db->query(
      "INSERT IGNORE INTO episodes 
      (`date`, episode, series, position, series_position, series_total, title, subtitle, synopsis) 
      VALUES 
      (%d, '%s', '%s', %d, %d, %d, '%s', '%s', '%s')", 
      strtotime($broadcast->start), $episode->pid, $series->pid, $episode->position, $series->position, $series->expected_child_count, $episode->display_titles->title, $episode->display_titles->subtitle, $episode->short_synopsis
      );
    }
  }
}

print "updated\n";
