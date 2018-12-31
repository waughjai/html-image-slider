<?php

declare( strict_types = 1 );
namespace WaughJ\HTMLImageSlider
{
	use WaughJ\File\File;
	use WaughJ\FileLoader\FileLoader;
	use WaughJ\HTMLImageResponsive\HTMLImageResponsive;
	use WaughJ\HTMLImage\HTMLImage;
	use WaughJ\HTMLPicture\HTMLPicture;

	class HTMLImageSlider
	{
		public function __construct( array $images, bool $zoom = false )
		{
			$this->images = [];
			$i = 1;
			foreach ( $images as $image )
			{
				if ( is_a( $image, HTMLImage::class ) || is_subclass_of( $image, HTMLImage::class ) )
				{
					$this->images[] = $image->addToClass( 'waj-image-slider-item' )->setAttribute( 'id', "waj-image-slider-item-{$i}" );
				}
				else if ( is_a( $image, HTMLPicture::class ) || is_subclass_of( $image, HTMLPicture::class ) )
				{
					$this->images[] = $image->changeFallbackImage( $image->getFallbackImage()->addToClass( 'waj-image-slider-item' )->setAttribute( 'id', "waj-image-slider-item-{$i}" ) );
				}
				else
				{
					throw new \Exception( get_class($image) . " is an invalid image type for HTMLImageSlider class." );
				}
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
