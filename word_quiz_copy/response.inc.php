<?php
/*
* created by: 	gdbzdnql @20161102
* name: 		response.inc.php
* function:		response to all the ajaxrequest from the frame page
* current_dev:	functioning independently as a webpage
*/

#include all necessary files
include("db.php");

#predefine all the needed functions
function sanitize($a){
	$san = preg_replace("/[^A-Za-z0-9.,:_; ]/", "", $a);
	return $san;
}

#establish the connections
$conn = new mysqli($lh, $un, $pw, $db);

#decide which page issued the ger request
if ($_GET['page'] == 'main'){
	$act = sanitize($_GET['act']);
	if ($act == 'add'){
		$word = sanitize($_GET['word']);
		$def = sanitize($_GET['def']);
		$query = "INSERT INTO gre(word, definition, star_marked) VALUES('$word', '$def', 'no');";
		$res = $conn -> query($query);
		if ($res){
			echo "successfully added";
		}
	}
	if ($act == 'show'){
		$toRet = "";
			if (!is_null($_GET['filt'])){
				$filt = sanitize($_GET['filt']);
				if ($filt == 'Learned'){
					$query = "SELECT * FROM gre WHERE star_marked='no' ORDER BY word;";
				}
				if ($filt == 'Unlearned'){
					$query = "SELECT * FROM gre WHERE star_marked='yes' ORDER BY word;";	
				}
				if ($filt == 'Total'){
					$query = "SELECT * FROM gre ORDER BY word;";
				}
			}
			else{
				$query = "SELECT * FROM gre ORDER BY word;";
			}
			$res = $conn -> query($query);
			$count = 0;
			if ($res){
				while(list($word, $definiton, $star_marked) = mysqli_fetch_array($res, MYSQLI_NUM)){
					$count++;
					$toRet .= "Word:<br/> <div class='content' id='word_$count'> $word</div><br/>
					Definition:<br/> <div class='content' id='def_$count'>$definiton</div><br/>";
					if ($star_marked=='no'){
						$toRet .= "Status:<br/><div class='content' id='star_$count'>learned.</div><br/>";
					}
					else{
						$toRet .= "Status:<br/> <div class='content' id='star_$count'>not learned.</div><br />";
					}
					$toRet .= "<button class='content' type='button' id='mark_$count' onclick='mark($count, &#39;$word&#39;)'>mark</button> ";
					$toRet .= "<button class='content' tyoe='button' id='change_$count' onclick='change($count, &#39;$word&#39;)'>change</button> ";
					$toRet .= "<button class='content' type='button' id='del_$count' onclick='del($count, &#39;$word&#39;)'>del</button>";
					$toRet .= 'SPLIT';
				}
				echo $toRet;
			}
			else{
				echo "error occurred during databse connection";
			}
	}
	if ($act == 'mark'){
		$word = sanitize($_GET['word']);
		$query = "SELECT star_marked FROM gre WHERE word = '$word';";
		$res = $conn -> query($query);
		if ($res){
			$s_m = mysqli_fetch_array($res, MYSQLI_NUM);
			if ($s_m[0] == 'no'){
				$query = "UPDATE gre SET star_marked='yes' WHERE word='$word';";
			}
			else{
				$query = "UPDATE gre SET star_marked='no' WHERE word='$word';";
			}
		}		
		$conn -> query($query);
		echo "$word mark changed!";
	}
	if ($act == 'del'){
		$word = sanitize($_GET['word']);
		$query = "DELETE FROM gre WHERE word='$word';";
		$conn -> query($query);
	}
	if ($act == 'add'){
		$word = sanitize($_GET['word']);
		$def = sanitize($_GET['def']);
		$query = "SELECT word FROM gre WHERE word = '$word';";
		$res = $conn -> query($query);
		if ($_GET['star']){
			$star = sanitize('star');
		}
		else{
			$star = 'no';
		}
		if (!$res){
			$query = "INSERT INTO gre(word, definition, star_marked) VALUES('$word', '$def', '$star');";
		}
	}
	if ($act == 'init'){
		$query = "SELECT star_marked FROM gre ORDER BY word;";
		$res = $conn -> query($query);
		if ($res){
			$toRet = "";
			while(list($star) = mysqli_fetch_array($res, MYSQLI_NUM)){
				if ($star == 'no'){
					$toRet .= "1SPLIT";
				}
				else{
					$toRet .= "0SPLIT";
				}
			}
			echo $toRet;
		}
	}
}

if ($_GET['page'] == 'quiz'){

}


?>
