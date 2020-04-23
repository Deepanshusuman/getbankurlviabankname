<?php
libxml_use_internal_errors(true);
$bank = "Bank of America";
 $ban = str_replace(' ','+',$bank);
$html = file_get_contents("https://google.com/search?q=".$ban);
$htmlC = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $html);
$htmlCo = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $htmlC);
$htmlCon = preg_replace('/<header\b[^>]*>(.*?)<\/header>/is', "", $htmlCo);
$htmlCont = preg_replace('/<head\b[^>]*>(.*?)<\/head>/is', "", $htmlCon);
$htmlContent = preg_replace('/<head\b[^>]*>(.*?)<\/head>/is', "", $htmlCont);
function removeStyleTags($html="") {
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);   
    $doc->loadHTML($html);
    $doc->encoding = 'UTF-8';
    $path = new DOMXPath($doc);
    $nodes = $path->query("//*[@style]");
    foreach ($nodes as $node) {
      $node->removeAttribute('style');
    }
    $html_new = $doc->saveHTML();
    return $html_new;
}
function get_string($string, $start, $end){
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
removeStyleTags($htmlContent);
$output = preg_replace('/(<[^>]+) style=".*?"/i', '$1', removeStyleTags($htmlContent));
$html1 = preg_replace('/\sstyle=("|\').*?("|\')/i', '', $output);
$data =  strstr($html1, 'People also search for', true);
$data1 =  strstr($data, 'All results');
$spaceString = str_replace( '<', ' <',$data1 );
$doubleSpace = strip_tags( $spaceString );
$singleSpace = str_replace( '  ', ' ', $doubleSpace );
$clear = html_entity_decode($singleSpace);
$clear = urldecode($clear);
$clear = trim($clear);
$details =  get_string($clear,"Wikipedia","Subsidiaries");
preg_match_all('/https?\:\/\/[^\",]+/i', $clear, $match);
preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $clear, $match1);

//echo "<b>Details: </b>".$details."<br>";
echo "<b>URL: </b>";
foreach ($match1[0] as $key => $value) {
 echo  $value.PHP_EOL;
}
    
?>
