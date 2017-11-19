<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
<title>Untitled Document</title>

<!--external css link is imported  -->

<link rel="stylesheet" type="text/css" href="style.css"/>
</head>
<body>
<div id="header" style="font-size:25px;">
<center>rankwatch_metadata</center>
<div class="header-left">
</div>
</div>
<div id="wrapper">
<div class="container">
<?php

/* curl initialization*/

function file_get_contents_curl($url){
	$ch=curl_init();
	curl_setopt($ch,CURLOPT_HEADER,0);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);
	$data=curl_exec($ch);
	curl_close($ch);
return $data;}

/*check submit is set or not*/

if(isset($_POST['submit'])){
	$weburl=$_POST['url'];
	
/*fetch data from server*/

$html=file_get_contents_curl($weburl);

/*parsing begins here
create dom document object*/

$doc=new DOMDocument();
@$doc->loadHTML($html);

/*set title date in nodes variavle*/

$nodes= $doc->getElementsByTagName('title');
$title=$nodes->item(0)->nodeValue;
$metas=$doc->getElementsByTagName('meta');
for($i=0;$i<$metas->length;$i++){
	$meta=$metas->item($i);
	if($meta->getAttribute('name')=='description')
		$description=$meta->getAttribute('content');
	if($meta->getAttribute('name')=='keywords')
		$keywords=$meta->getAttribute('content');
}

/*get and display data what you need*/

echo "Title: $title".'<br/><br/>';
echo "Description: $description".'<br/><br/>';
echo "Keywords: $keywords".'<br/><br/>';
$regex='|<a.*?href="(.*?)"|';
preg_match_all($regex,$html,$parts);
$links=$parts[1];

/*display all internal and external links*/

foreach($links as $link){
	echo "Internal and External Links".":".$link."<br>";
}
echo '<br/>';

/*display IP address of given website*/

echo "IP address of your Website:",gethostbyname($weburl).'<br/><br/>';

/*display load time for the website*/

$start=0;
$time=microtime();
$time=explode(' ',$time);
$time=$time[1]+$time[0];
$finish=$time;
$total_time=round(($finish-$start),4);
echo 'Load Time:Page generated in '.$total_time.' seconds.';
echo '<br/><br/>';

/*curl initialization*/

$ch=curl_init($weburl);
curl_setopt($ch,CURLOPT_HEADER,true);
curl_setopt($ch,CURLOPT_NOBODY,true);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_TIMEOUT,10);
$output=curl_exec($ch);
$httpcode=curl_getinfo($ch,CURLINFO_HTTP_CODE);
curl_close($ch);
echo 'HTTP code: '.$httpcode;
}
?>


</div>
</div>
</body>
</html>