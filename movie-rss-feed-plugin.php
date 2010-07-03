<?php
/*
Plugin Name: Movie Rss Feed
Plugin URI: http://www.guyro.com/movie-news-rss-plugin
Description: Adds a customizeable widget which displays the latest Movie news from The Movie Blog. 
Version: 1.0
Author: Guy Roman
Author URI: http://www.guyro.com
License: GPL3
*/

function movienews()
{
  $options = get_option("widget_movienews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Movie News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Feed 
  $rss = simplexml_load_file( 
  'http://www.movies.com/rss-feeds/movie-buzz-rss'); 
  ?> 
  
  <ul> 
  
  <?php 
  // max number of news slots, with 0 (zero) all display
  $max_news = $options['news'];
  // maximum length to which a title may be reduced if necessary,
  $max_length = $options['chars'];
  
  // RSS Elements 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Title
    $title = $i->title;
    // Length of title
    $length = strlen($title);
    // if the title is longer than the previously defined maximum length,
    // it'll he shortened and "..." added, or it'll output normaly
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_movienews($args)
{
  extract($args);
  
  $options = get_option("widget_movienews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Movie News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  movienews();
  echo $after_widget;
}

function movienews_control()
{
  $options = get_option("widget_movienews");
  if (!is_array($options)){
    $options = array(
      'title' => 'Movie News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['movienews-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['movienews-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['movienews-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['movienews-CharCount']);
    update_option("widget_movienews", $options);
  }
?> 
  <p>
    <label for="movienews-WidgetTitle">Widget Title: </label>
    <input type="text" id="movienews-WidgetTitle" name="movienews-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="movienews-NewsCount">Max. News: </label>
    <input type="text" id="movienews-NewsCount" name="movienews-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="movienews-CharCount">Max. Characters: </label>
    <input type="text" id="movienews-CharCount" name="movienews-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="movienews-Submit"  name="movienews-Submit" value="1" />
  </p>
  
<?php
}

function movienews_init()
{
  register_sidebar_widget(__('Movie News'), 'widget_movienews');    
  register_widget_control('Movie News', 'movienews_control', 300, 200);
}
add_action("plugins_loaded", "movienews_init");
?>