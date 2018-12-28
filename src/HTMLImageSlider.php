<?php

declare( strict_types = 1 );
namespace WaughJ\HTMLImageSlider
{
	use WaughJ\File\File;
	use WaughJ\FileLoader\FileLoader;
	use WaughJ\HTMLImageResponsive\HTMLImageResponsive;

	class HTMLImageSlider
	{
		public function __construct( array $images )
		{
			$this->images = [];
			$i = 0;
			foreach ( $images as $image )
			{
				$this->images[] = $image->addToClass( 'waj-image-slider-item' )->setAttribute( 'id', "waj-image-slider-item-{$i}" );
				$i++;
			}
		}

		public static function generateSimple( array $image_data, array $sizes, FileLoader $loader = null ) : HTMLImageSlider
		{
			$images = [];
			foreach ( $image_data as $image )
			{
				if ( is_a( $image, File::class ) )
				{
					$images[] = new HTMLImageResponsive
					(
						$image->getBaseFilename(),
						$image->getExtension(),
						$sizes,
						$loader
					);
				}
			}
			return new HTMLImageSlider( $images );
		}

		public function __toString()
		{
			return $this->getHTML();
		}

		public function getHTML() : string
		{
			$content = '<div id="waj-image-slider" class="waj-image-slider">';
			foreach ( $this->images as $image )
			{
				$content .= $image->getHTML();
			}
			$content .= '</div>';
			return $content;
		}

		private $images;
	}
}
