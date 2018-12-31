<?php

declare( strict_types = 1 );
namespace WaughJ\HTMLImageSlider
{
	use WaughJ\File\File;
	use WaughJ\FileLoader\FileLoader;
	use WaughJ\HTMLImageResponsive\HTMLImageResponsive;

	class HTMLImageSlider
	{
		public function __construct( array $images, bool $zoom = false )
		{
			$this->images = [];
			$i = 1;
			foreach ( $images as $image )
			{
				$this->images[] = $image->addToClass( 'waj-image-slider-item' )->setAttribute( 'id', "waj-image-slider-item-{$i}" );
				$i++;
			}
			$this->zoom = $zoom;
		}

		public static function generateSimple( array $image_data, array $sizes, FileLoader $loader = null, bool $zoom = false ) : HTMLImageSlider
		{
			$images = [];
			foreach ( $image_data as $image_item )
			{
				if ( is_a( $image_item, File::class ) )
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
			}
			return new HTMLImageSlider( $images, $zoom );
		}

		public function __toString()
		{
			return $this->getHTML();
		}

		public function getHTML() : string
		{
			$content = "<div id=\"waj-image-slider\"{$this->getClassAttribute()}>";
			foreach ( $this->images as $image )
			{
				$content .= $image->getHTML();
			}
			$content .= '</div>';
			return $content;
		}

		private function getClassAttribute() : string
		{
			return " class=\"waj-image-slider{$this->getZoomClass()}\"";
		}

		private function getZoomClass() : string
		{
			return ( ( $this->zoom ) ? ' waj-image-slider-zoom' : '' );
		}

		private $images;
	}
}
