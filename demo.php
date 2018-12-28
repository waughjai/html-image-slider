<?php error_reporting( E_ALL ); ?>
<?php ini_set( 'display_errors', 1 ); ?>
<?php require_once( getcwd() . '/vendor/autoload.php' ); ?>
<?php use WaughJ\HTMLImageSlider\HTMLImageSlider; ?>
<?php use WaughJ\FileLoader\FileLoader; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<link href="css/slider.css" rel="stylesheet" />
</head>
<body>
<?php
	$sizes = [ [ 'w' => '500', 'h' => '334' ], [ 'w' => '1000', 'h' => '667' ], [ 'w' => '2000', 'h' => '1333' ], [ 'w' => '3000', 'h' => '2000' ] ];
	$loader = new FileLoader([ 'directory-url' => 'http://localhost/slider', 'shared-directory' => 'demo-img' ]);
	echo new HTMLImageSlider
	(
		[
			[ 'base' => 'water', 'ext' => 'png' ],
			[ 'base' => 'bridge', 'ext' => 'png' ],
			[ 'base' => 'clear', 'ext' => 'png' ]
		],
		$sizes,
		$loader
	);
?>
<script src="js/slider.js"></script>
</body>
</html>
