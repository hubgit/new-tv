<?php
//print_r($_ENV); exit();
//if ($_ENV['_'] != '/usr/bin/php') exit(); // command line only

require dirname(__FILE__) . '/main.php';
Config::set('DEBUG', 'OFF');

foreach (range(1,0) as $day){
  $date = date('Y/m/d', time() - (60*60*24*$day)); // yesterday's and today's listings
  //$date = date('Y/m/d');

  $schedules = array(
    'bbcone' => 'london',
    'bbctwo' => 'england',
    'bbcthree' => NULL,
    'bbcfour' => NULL,
	'cbeebies' => NULL,
  );

  foreach ($schedules as $channel => $region){
    debug($channel);
    $api->cache = FALSE;
    $url = "http://www.bbc.co.uk/$channel/programmes/schedules/$region/$date.json";
    debug($url);
    $data = $api->get_data($url, NULL, 'json');

    foreach ($data['schedule']['day']['broadcasts'] as $broadcast){
      if ($broadcast['is_repeat']) continue;

      $episode = $broadcast['programme'];
      if (!$episode['media']) continue;

      $series = array();
      if ($episode['programme'])
        $series = $episode['programme'];

      $image = $episode['image'];

      $db->query(
      "INSERT IGNORE INTO episodes
      (`date`, episode, series, image, position, series_position, series_total, title, subtitle, synopsis)
      VALUES
      (%d, '%s', '%s', '%s', %d, %d, %d, '%s', '%s', '%s')",
      strtotime($broadcast['start']),
      $episode['pid'],
      $series['pid'],
      $image['pid'],
      $episode['position'],
      $series['position'],
      $series['expected_child_count'],
      $episode['display_titles']['title'],
      $episode['display_titles']['subtitle'],
      $episode['short_synopsis']
      );
    }
  }
}

print "updated\n";

