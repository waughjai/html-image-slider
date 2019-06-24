<?php

use PHPUnit\Framework\TestCase;
use WaughJ\HTMLImageResponsive\HTMLImageResponsive;
use WaughJ\HTMLImageSlider\HTMLImageSlider;
use WaughJ\HTMLPicture\HTMLPicture;
use WaughJ\File\File;
use WaughJ\FileLoader\FileLoader;

class HTMLImageSliderTest extends TestCase
{
	public function testNormal()
	{
		$sizes = [ [ 'w' => '500', 'h' => '334' ], [ 'w' => '1000', 'h' => '667' ], [ 'w' => '2000', 'h' => '1333' ], [ 'w' => '3000', 'h' => '2000' ] ];
		$loader = new FileLoader([ 'directory-url' => 'http://localhost/slider', 'shared-directory' => 'img' ]);
		$image_data =
		[
			new File( 'water', 'png' ),
			new File( 'bridge', 'png' ),
			new File( 'clear', 'png' )
		];

		$images = [];
		foreach ( $image_data as $image_item )
		{
			$image = new HTMLImageResponsive
			(
				$image_item->getBaseFilename(),
				$image_item->getExtension(),
				$sizes,
				$loader
			);
			$images[] = $image;
		}

		$slider = new HTMLImageSlider( $images );
		$this->assertStringContainsString( ' srcset="http://localhost/slider/img/water-500x334.png 500w, http://localhost/slider/img/water-1000x667.png 1000w, http://localhost/slider/img/water-2000x1333.png 2000w, http://localhost/slider/img/water-3000x2000.png 3000w"', $slider->getHTML() );
		$this->assertStringContainsString( ' srcset="http://localhost/slider/img/bridge-500x334.png 500w, http://localhost/slider/img/bridge-1000x667.png 1000w, http://localhost/slider/img/bridge-2000x1333.png 2000w, http://localhost/slider/img/bridge-3000x2000.png 3000w"', $slider->getHTML() );
		$this->assertStringContainsString( ' class="waj-image-slider-item"', $slider->getHTML() );
		$this->assertStringContainsString( ' id="waj-image-slider-item-3"', $slider->getHTML() );
	}

	public function testSimple()
	{
		$sizes = [ [ 'w' => '500', 'h' => '334' ], [ 'w' => '1000', 'h' => '667' ], [ 'w' => '2000', 'h' => '1333' ], [ 'w' => '3000', 'h' => '2000' ] ];
		$loader = new FileLoader([ 'directory-url' => 'http://localhost/slider', 'shared-directory' => 'img' ]);
		$slider = HTMLImageSlider::generateSimple
		(
			[
				new File( 'water', 'png' ),
				new File( 'bridge', 'png' ),
				new File( 'clear', 'png' )
			],
			$sizes,
			$loader
		);
		$this->assertStringContainsString( ' srcset="http://localhost/slider/img/water-500x334.png 500w, http://localhost/slider/img/water-1000x667.png 1000w, http://localhost/slider/img/water-2000x1333.png 2000w, http://localhost/slider/img/water-3000x2000.png 3000w"', $slider->getHTML() );
		$this->assertStringContainsString( ' srcset="http://localhost/slider/img/bridge-500x334.png 500w, http://localhost/slider/img/bridge-1000x667.png 1000w, http://localhost/slider/img/bridge-2000x1333.png 2000w, http://localhost/slider/img/bridge-3000x2000.png 3000w"', $slider->getHTML() );
		$this->assertStringContainsString( ' class="waj-image-slider-item"', $slider->getHTML() );
		$this->assertStringContainsString( ' id="waj-image-slider-item-3"', $slider->getHTML() );
	}

	public function testZoom()
	{
		$sizes = [ [ 'w' => '500', 'h' => '334' ], [ 'w' => '1000', 'h' => '667' ], [ 'w' => '2000', 'h' => '1333' ], [ 'w' => '3000', 'h' => '2000' ] ];
		$loader = new FileLoader([ 'directory-url' => 'http://localhost/slider', 'shared-directory' => 'img' ]);
		$slider = HTMLImageSlider::generateSimple
		(
			[
				new File( 'water', 'png' ),
				new File( 'bridge', 'png' ),
				new File( 'clear', 'png' )
			],
			$sizes,
			$loader
		);
		$this->assertStringContainsString( ' class="waj-image-slider"', $slider->getHTML() );
		$slider = HTMLImageSlider::generateSimple
		(
			[
				new File( 'water', 'png' ),
				new File( 'bridge', 'png' ),
				new File( 'clear', 'png' )
			],
			$sizes,
			$loader,
			true
		);
		$this->assertStringContainsString( ' class="waj-image-slider waj-image-slider-zoom"', $slider->getHTML() );
	}

	public function testPicture()
	{
		$sizes = [ [ 'w' => '500', 'h' => '334' ], [ 'w' => '1000', 'h' => '667' ], [ 'w' => '2000', 'h' => '1333' ], [ 'w' => '3000', 'h' => '2000' ] ];
		$loader = new FileLoader([ 'directory-url' => 'http://localhost/slider', 'shared-directory' => 'img' ]);
		$image_data =
		[
			new File( 'water', 'png' ),
			new File( 'bridge', 'png' ),
			new File( 'clear', 'png' )
		];

		$images = [];
		foreach ( $image_data as $image_item )
		{
			$image = HTMLPicture::generate
			(
				$image_item->getBaseFilename(),
				$image_item->getExtension(),
				$sizes,
				[ 'loader' => $loader ]
			);
			$images[] = $image;
		}

		$slider = new HTMLImageSlider( $images, [ 'zoom' => false ], [ 'class' => 'something', 'width' => 200 ] );
		$this->assertStringContainsString( ' class="waj-image-slider-item"', $slider->getHTML() );
		$this->assertStringContainsString( ' id="waj-image-slider-item-3"', $slider->getHTML() );
		$this->assertStringContainsString( ' class="waj-image-slider something"', $slider->getHTML() );
		$this->assertStringContainsString( ' width="200"', $slider->getHTML() );
	}
}
