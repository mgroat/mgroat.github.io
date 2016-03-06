<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<style>
  .gameRow {
    background: #c0c0c0;
    background: #8db8c6;
  }
  body {
    background: #add8e6;
  }

</style>
<div class="container">

<form action="gamegeek.php" method="get">
<div class="row lead">
    Enter a list of <a href="http://www.boardgamegeek.com">boardgamegeek</a> users, separated by a space<br>
  You will be given a list of your combined games library
</div>
<div class="row lead">
  <input type="text" name="users" value="<?php if (isset($_GET['users'])) echo $_GET['users'];?>">
  <button type="submit" class="btn btn-success"><i class="fa fa-paper-plane"></i>Search BGG</button>
</div>
</form>
<p>

    
<?php
	if(isset($_GET['users'])) $users = explode(" ",$_GET['users']);
	else $users = [];
	$all_games = [];
	foreach ($users as $user) {
		$bgg_data = file_get_contents("https://www.boardgamegeek.com/xmlapi2/collection?own=1&username=".$user);
		$p = xml_parser_create();
		xml_parse_into_struct($p,$bgg_data,$vals,$index);
		xml_parser_free($p);
    if (!array_key_exists("NAME",$index)) { //Sometimes BGG tells us to try again instead of giving data
      sleep(3); //We may be able to get away with shortening this
      $bgg_data = file_get_contents("https://www.boardgamegeek.com/xmlapi2/collection?own=1&username=".$user);
      $p = xml_parser_create();
      xml_parse_into_struct($p,$bgg_data,$vals,$index);
      xml_parser_free($p);
    }
    if (!array_key_exists("NAME",$index)) { //If the username was invalid
      echo "<h2><font color='red'>Can't find user $user</font></h2>";
      $index["NAME"] = [];
    }
    
		foreach ($index['NAME'] as $key => $item) {
			if(!isset($all_games[$vals[$item]['value']])) {
				$all_games[$vals[$item]['value']]['thumb'] = $vals[$index['THUMBNAIL'][$key]]['value'];
				$all_games[$vals[$item]['value']]['owner'] = $user;
			}
			else {
				$all_games[$vals[$item]['value']]['owner'] .=  " ".$user;
			}
		}
	}
	
 
	//Sort games alphabetically
	ksort($all_games);
  
  //Show column headers
	echo "<div class='row lead gameRow'>".
  "<div class='col-xs-4'><b>Title</b></div>".
  "<div class='col-xs-4'><b><center>Image</center></b></div>".
  "<div class='col-xs-4'><b>Owner</b></div>".
  "</div>";
  
  //Show all games
	foreach ($all_games as $key => $game) {
		echo "<div class='row lead gameRow'>\n";
		echo "<div class='col-xs-4'><a href='https://boardgamegeek.com/geeksearch.php?action=search&objecttype=boardgame&q=".$key."'>".$key."</a>"."</div>\n".
    "<div class='col-xs-4'><center><img src='".$game['thumb']."' class='img-responsive img-rounded'></center></div>\n".
    "<div class='col-xs-4'>".$game['owner']."</div>\n";
		echo "</div>\n\n";
	}
	
	?>
    
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-73410253-1', 'auto');
  ga('send', 'pageview');

</script>


</div>