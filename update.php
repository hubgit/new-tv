<?php
//print_r($_ENV); exit();
if ($_ENV['_'] != '/usr/bin/php') exit(); // command line only

require 'db.inc.php';
require 'functions.inc.php';

foreach (range(1,0) as $day){
  $date = date('Y/m/d', time() - (60*60*24*$day));
  //$date = date('Y/m/d');

  $channels = array(
    "http://www.bbc.co.uk/bbcone/programmes/schedules/london/$date.json",
    "http://www.bbc.co.uk/bbctwo/programmes/schedules/england/$date.json",
    "http://www.bbc.co.uk/bbcthree/programmes/schedules/$date.json",
    "http://www.bbc.co.uk/bbcfour/programmes/schedules/$date.json",
  );

  foreach ($channels as $channel){
    debug($channel);
    $json = file_get_contents($channel);
    $data = json_decode($json);
    
    foreach ($data->schedule->day->broadcasts as $broadcast){
      if ($broadcast->is_repeat) continue;
        
      $episode = $broadcast->programme;
      if (!$episode->media) continue;
      
      if ($episode->programme) {
        $series = $episode->programme;
        if ($series->programme)
          $brand = $series->programme;
      }
          
      db_query(
      "INSERT IGNORE INTO episodes 
      (date, episode, series, position, title, subtitle, synopsis) 
      VALUES 
      (%d, '%s', '%s', %d, '%s', '%s', '%s')", 
      strtotime($broadcast->start), $episode->pid, $series->pid, $episode->position, $episode->display_titles->title, $episode->display_titles->subtitle, $episode->short_synopsis
      );
    }
  }
}
