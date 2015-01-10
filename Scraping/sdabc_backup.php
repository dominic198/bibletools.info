<?php
ini_set('memory_limit','1024M');
ini_set('max_execution_time', 300);
include("./dom/simple_html_dom.php");

$servername = "127.0.0.1";
$username = "root";
$password = "root";
$database = "dev_su";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

$html = file_get_html('http://localhost/TEST/vol7_select.html');

// Find all images 
foreach($html->find('section.verse') as $verse) {
	$book = $verse->getAttribute("data-book");
	$chapter = $verse->getAttribute("data-chapter");
	$verse_number = $verse->getAttribute("data-verse");
	$content = mysqli_real_escape_string($conn, $verse->innertext);
	
	$sql = "INSERT INTO sdabc (book, chapter, verse, content)
		VALUES ('$book', $chapter, '$verse_number', '$content')";
	$result = mysqli_query($conn, $sql);
	//die($result);

}

/*
//print_r($html);
//die($verses);
/*foreach( $verses as $verse ){

	die($verse);
	$sections = $verse->find("div.resourcetext");
	$book = $verse->getAttribute("data-book");
	$chapter = $verse->getAttribute("data-chapter");
	$verse = $verse->getAttribute("data-verse");
		
	/*foreach( $sections as $section ){
		die($section."TEST".$section->innertext);
	}
}

$sql = "SELECT * FROM sdabc WHERE content NOT LIKE CONCAT('%', verse, '%')";
	$result = mysqli_query($conn, $sql);
	$array = mysqli_fetch_all($result,MYSQLI_ASSOC);

	foreach($array as $item){
		$verse = substr($item['content'],23);
		$verse = explode(".", $verse);
		$verse = $verse[0];
		$correction = "UPDATE sdabc SET verse=".$verse." WHERE id=".$item['id'];
		mysqli_query($conn, $correction);
	}
*/
mysqli_close($conn);
?>