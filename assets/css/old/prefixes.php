<?php
//---------------------------
//------VARIABLES
//---------------------------
$green = "#83E147";
#9dc06d = "#9dc06d";
$fadedGreen = "#A0C768";
$paperColor = "#FCF59B";
$titleColor = "#888";
$fontColor = "#414141";

$greenGrad = "
	background-color: #ACD277;
	background-image: -webkit-linear-gradient(#ACD277, #9dc06d );
	background-image: -moz-linear-gradient(#ACD277, #9dc06d );
	background-image: -o-linear-gradient(#ACD277, #9dc06d );
	background-image: -ms-linear-gradient(#ACD277, #9dc06d );
	background-image: linear-gradient(#ACD277, #9dc06d );";
$redGrad = "
	background-image: -webkit-linear-gradient(#9b2b2b, #7d2323);
	background-image: -moz-linear-gradient(#9b2b2b, #7d2323);
	background-image: -o-linear-gradient(#9b2b2b, #7d2323);
	background-image: -ms-linear-gradient(#9b2b2b, #7d2323);
	background-image: linear-gradient(#9b2b2b, #7d2323);";
$greyGrad = "
	background-image: -webkit-linear-gradient(#fff, #DDDCDA);
	background-image: -moz-linear-gradient(#fff, #DDDCDA);
	background-image: -o-linear-gradient(#fff, #DDDCDA);
	background-image: -ms-linear-gradient(#fff, #DDDCDA);
	background-image: linear-gradient(#fff, #DDDCDA);;
	background-color: #EEEDEB;";
$blackGrad = "
	background-image: -webkit-linear-gradient(#444, #333);
	background-image: -moz-linear-gradient(#444, #333);
	background-image: -o-linear-gradient(#444, #333);
	background-image: -ms-linear-gradient(#444, #333);
	background-image: linear-gradient(#444, #333);;
	background-color: #444;";
$boxSizing = "
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	-ms-box-sizing: border-box;
	box-sizing: border-box;";
	
function boxShadow($string){
	$css = "
	box-shadow: ".$string.";
	-webkit-box-shadow: ".$string.";
	-moz-box-shadow: ".$string.";
	-o-box-shadow: ".$string.";
	-ms-box-shadow: ".$string.";
	";
	return $css;
}
function borderRadius($radius){
	$css = "
	border-radius: ".$radius.";
	-webkit-border-radius: ".$radius.";
	-moz-border-radius: ".$radius.";
	-o-border-radius: ".$radius.";
	-ms-border-radius: ".$radius.";
	";
	return $css;
}
function rotate($deg){
	$css = "
	-webkit-transform: rotate(".$deg."deg);
	-moz-transform: rotate(".$deg."deg);
	-o-transform: rotate(".$deg."deg);
	-ms-transform: rotate(".$deg."deg);
	";
	return $css;
}
function transition($value){
	$css = "
	transition: ".$value.";
	-moz-transition: ".$value."; /* Firefox 4 */
	-webkit-transition: ".$value."; /* Safari and Chrome */
	-o-transition: ".$value."; /* Opera */
	";
	return $css;
}
?>