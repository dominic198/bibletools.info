<?php

include("./dom/simple_html_dom.php");
    
$html = file_get_html("https://en.m.wikipedia.org/wiki/Big_Ben");
$content = $html->find("#content", 0);

echo $content->innertext;

?>