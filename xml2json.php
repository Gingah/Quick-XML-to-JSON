<?php 
require_once('simplepie.inc');
$feed = new SimplePie();

$path = 'file';
$path = $path.'.xml';
$filename = $path.'.js';
$feed->set_feed_url($path);
$feed->init();
$feed->handle_content_type();
echo "Wrote:<br />";
$return = 'var data = {' . "\n";
$return .= '"info" : [{' . "\n";
$return .= '"title": "'.$feed->get_title().'",' . "\n";
$return .= '"description": "'.$feed->get_description().'"' . "\n";
$return .= '}],' . "\n";
$amount = $feed->get_item_quantity();
$return .= '"playlist" : [' . "\n";
foreach ($feed->get_items() as $item) {
	$return .= '{' . "\n";
	$return .= '"title": "'.$item->get_title().'",' . "\n";
	$return .= '"description": "'.$item->get_description().'",' . "\n";
	$return .= '"href": "'.$item->get_permalink().'",' . "\n";
	if ($enclosure = $item->get_enclosure()) {
		$return .= '"url": "'.$enclosure->get_link().'",' . "\n";
		$return .= '"thumbnail": "'.str_replace('.png', '_thumbnail.png', $enclosure->get_thumbnail()).'",' . "\n";
		$return .= '"time": "'.$enclosure->get_duration(true).'"' . "\n";
	}
	$return .= '}, ';
}
$return = rtrim($return, ", ");
$return .= ']' . "\n";
$return .= '}' . "\n";
echo "<pre>$return</pre>";
echo '<br />to: ';
echo $filename;
$filehandle = fopen($filename, 'w') or die("could not write to file");
fwrite($filehandle, $return);
fclose($filehandle);
?>