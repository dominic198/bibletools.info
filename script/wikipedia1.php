<?php

include("./dom/simple_html_dom.php");

$html = file_get_html("https://en.m.wikipedia.org/wiki/Big_Ben");

$html->find('div.header', 0)->outertext = '';
$html->find('.top-bar', 0)->outertext = '';

echo $html;

?>