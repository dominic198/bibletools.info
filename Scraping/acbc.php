<?php
ini_set('memory_limit','1024M');
ini_set('max_execution_time', 800);
include("./dom/simple_html_dom.php");

$servername = "127.0.0.1";
$username = "root";
$password = "root";
$database = "dev_su";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);


$book = 0;
while( $book < 66 ){
	$html = file_get_html('http://www.studylight.org/commentaries/acc/view.cgi?bk='.$book);
	$book_num = $book +1;
	foreach($html->find('.content_main td a') as $section) {
		$url = $section->getAttribute("href");
		$chapter = explode("ch=", $url);
		$chapter = $chapter[1];
		$chapter_html = file_get_html('http://www.studylight.org/commentaries/acc/view.cgi?bk='.$book.'&ch='.$chapter);
		
		foreach($chapter_html->find('div.general > div') as $verse) {
			$spans = $verse->find('span.large');
			if($spans){
				$verse_num = $spans[0]->getAttribute("name");
				if($verse_num == "intro")
					$verse_num = 0;
				
				foreach ($verse->find('span.large') as $node){
			        $node->outertext = '';
			    }
				
				$verse_content = $verse->innertext;
				if (substr($verse_content, 0, strlen("<br />")) == "<br />") {
				    $verse_content = substr($verse_content, strlen("<br />"));
				}
				$verse_content = mysqli_real_escape_string($conn, $verse_content);
				
				$sql = "INSERT INTO mhcc (book, chapter, verse, content) VALUES ('$book_num', $chapter, '$verse_num', '$verse_content')";
				$result = mysqli_query($conn, $sql) or die(mysqli_error($conn));
				echo $result;
			}
		}
	}
	$book++;
}
mysqli_close($conn);
?>