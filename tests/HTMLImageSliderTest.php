<?php

use PHPUnit\Framework\TestCase;
use WaughJ\HTMLImageSlider\HTMLImageSlider;
use WaughJ\FileLoader\FileLoader;

class HTMLImageSliderTest extends TestCase
{
	public function testGeneral()
	{
		$sizes = [ [ 'w' => '500', 'h' => '334' ], [ 'w' => '1000', 'h' => '667' ], [ 'w' => '2000', 'h' => '1333' ], [ 'w' => '3000', 'h' => '2000' ] ];
		$loader = new FileLoader([ 'directory-url' => 'http://localhost/slider', 'shared-directory' => 'img' ]);
		$slider = new HTMLImageSlider
		(
			[
				[ 'base' => 'water', 'ext' => 'png' ],
				[ 'base' => 'bridge', 'ext' => 'png' ],
				[ 'base' => 'clear', 'ext' => 'png' ]
			],
			$sizes,
			$loader
		);
		$this->assertContains( 'srcset="http://localhost/slider/img/water-500x334.png 500w, http://localhost/slider/img/water-1000x667.png 1000w, http://localhost/slider/img/water-2000x1333.png 2000w, http://localhost/slider/img/water-3000x2000.png 3000w"', $slider->getHTML() );
		$this->assertContains( 'srcset="http://localhost/slider/img/bridge-500x334.png 500w, http://localhost/slider/img/bridge-1000x667.png 1000w, http://localhost/slider/img/bridge-2000x1333.png 2000w, http://localhost/slider/img/bridge-3000x2000.png 3000w"', $slider->getHTML() );
	}
}