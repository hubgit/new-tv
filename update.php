<?php
//print_r($_ENV); exit();
//if ($_ENV['_'] != '/usr/bin/php') exit(); // command line only

require dirname(__FILE__) . '/main.php';
Config::set('DEBUG', 'OFF');

foreach (range(1,0) as $day){
  $date = date('Y/m/d', time() - (60*60*24*$day)); // yesterday's and today's listings
  //$date = date('Y/m/d');

  $schedules = array(
    'bbcone',
    'bbctwo',
    'bbcthree',
    'bbcfour',
	  'cbeebies',
  );

  foreach ($schedules as $channel){
    debug($channel);
    $api->cache = FALSE;
    $url = "https://bbc-programmes-json.now.sh/$channel/$date";
    debug($url);
    $data = $api->get_data($url, NULL, 'json');

    foreach ($data as $broadcast){
      if ($broadcast['repeat']) continue;

      // if (!$broadcast['media']) continue;

      $db->query(
      "INSERT IGNORE INTO episodes
      (`date`, episode, series, image, position, series_position, series_total, title, subtitle, synopsis)
      VALUES
      (%d, '%s', '%s', '%s', %d, %d, %d, '%s', '%s', '%s')",
      strtotime($broadcast['start']),
      $broadcast['episode']['pid'],
      $broadcast['series']['pid'],
      $broadcast['episode']['image'],
      $broadcast['episode']['position'],
      $broadcast['season']['position'],
      $broadcast['episode']['total'],
      $broadcast['title'],
      $broadcast['subtitle'],
      $broadcast['episode']['description']
      );
    }
  }
}

print "updated\n";

