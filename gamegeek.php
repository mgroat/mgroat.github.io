<link rel='stylesheet' type='text/css' href='style.css'>
<form action="gamegeek.php" method="get">
Enter a list of boardgamegeek users, separated by a space
<input type="text" name="users" value="<?php if (isset($_GET['users'])) echo $_GET['users'];?>">
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
		//echo "<pre>";
		//print_r($index);
		//print_r($vals);
		foreach ($index['NAME'] as $key => $item) {
			//echo $item;
			//print_r($vals[$item]['value']);
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
	echo "<table>";
	echo "<tr class='col_header'><td>Game Title</td><td>Image</td><td>Owned by</td></tr>";
	foreach ($all_games as $key => $game) {
		echo "<tr>";
		echo "<td><a href='https://boardgamegeek.com/geeksearch.php?action=search&objecttype=boardgame&q=".$key."'>".$key."</a>".
		"</td><td><img src='".$game['thumb']."'></td><td>".$game['owner']."</td>";
		echo "</tr>";
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