<?php

$genres = array(
 'drama' => 'Drama',
 'entertainmentandcomedy' => 'Comedy',
 'factual' => 'Factual',
 'news' => 'News',
 'music' => 'Music',
);

$formats = array(
 'animation' => 'Animation',
 'discussionandtalk' => 'Discussion',
 'documentaries' => 'Documentaries',
 'films' => 'Films',
 'gamesandquizzes' => 'Games',
 'magazinesandreviews' => 'Magazines',
 'performancesandevents' => 'Performances',
);

function fetch_data(){
  if (array_key_exists('genre', $_GET))
    $json = file_get_contents("http://www.bbc.co.uk/tv/programmes/genres/{$_GET['genre']}/schedules/yesterday.json");
  else if (array_key_exists('format', $_GET))
    $json = file_get_contents("http://www.bbc.co.uk/tv/programmes/formats/{$_GET['format']}/schedules/yesterday.json");
  else
    $json = file_get_contents("http://www.bbc.co.uk/tv/programmes/genres/entertainmentandcomedy/schedules.json");
    
  return json_decode($json);
}
