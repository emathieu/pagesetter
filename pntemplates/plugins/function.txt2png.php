<?php
function smarty_function_txt2png($args, & $smarty) {

	$smarty->caching = 0;
	if (!isset ($args['string']))
		return "Missing 'string' argument in Smarty plugin 'txt2png'";
		
	$string = $args['string'];
    $size = isset($args['size']) ? intval($args['size']) : 12;

	// Create the image
	$imw = 400;
	$imh = $size*2;
	$im = imagecreatetruecolor($imw, $imh);

	// Create some colors
	$bgcolor = imagecolorallocate($im, 255, 255, 255);
	$fgcolor = imagecolorallocate($im, 0, 0, 0);
	imagefilledrectangle($im, 0, 0, $imw-1, $imh-1, $bgcolor);

	// Replace path by your own font path
	$font = 'C:/Windows/Fonts/arial.ttf';

	// Add the text
	$margin = 10;
	$box = imagettftext($im, $size, 0, $margin, $size, $fgcolor, $font, $string);
	$maxwidth = $box[2] - $box[0];
	$y = $box[1] - $box[5];
	$im2 = imagecreate($maxwidth + 20, $y);
    imagecopyresized(
        $im2, $im, $margin, 0, $box[6], $box[7], $maxwidth,
        $y, $maxwidth, $y
    );
	
	// start buffering
	ob_start();
	imagepng($im2);
	$contents = ob_get_contents();
	ob_end_clean();

	$png = "<img src='data:image/png;base64," . base64_encode($contents) . "' />";

	imagedestroy($im);
	imagedestroy($im2);
	return $png;
}
?>
