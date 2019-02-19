<?php

declare( strict_types = 1 );
namespace WaughJ\HTMLImageSlider
{
	use WaughJ\File\File;
	use WaughJ\FileLoader\FileLoader;
	use WaughJ\HTMLImageResponsive\HTMLImageResponsive;
	use WaughJ\HTMLImage\HTMLImage;
	use WaughJ\HTMLPicture\HTMLPicture;
	use WaughJ\HTMLAttributeList\HTMLAttributeList;
	use WaughJ\VerifiedArgumentsSameType\VerifiedArgumentsSameType;
	use function WaughJ\TestHashItem\TestHashItemString;
	use function WaughJ\TestHashItem\TestHashItemIsTrue;

	class HTMLImageSlider
	{
		public function __construct( array $images, array $options = [], array $container_attributes = [] )
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
			$this->options = new VerifiedArgumentsSameType( $options, self::DEFAULT_OPTIONS );
			$this->extra_classes = TestHashItemString( $container_attributes, 'class', null );
			unset( $container_attributes[ 'class' ] );
			$this->container_attributes = new HTMLAttributeList( $container_attributes );
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
			$content = "<div id=\"waj-image-slider\"{$this->getClassAttribute()}{$this->container_attributes->getAttributesText()}>";
			foreach ( $this->images as $image )
			{
				$content .= $image->getHTML();
			}
			$content .= '</div>';
			return $content;
		}

		private function getClassAttribute() : string
		{
			$classes = implode( ' ', $this->getClasses() );
			return " class=\"{$classes}\"";
		}

		private function getClasses() : array
		{
			return array_merge( [ 'waj-image-slider' ], $this->getOptionClasses(), $this->getExtraClasses() );
		}

		private function getOptionClasses() : array
		{
			$extra_options = [];
			foreach ( self::OPTION_CLASSES as $option => $option_class )
			{
				if ( $this->options->get( $option ) )
				{
					$extra_options[] = $option_class;
				}
			}
			return $extra_options;
		}

		private function getExtraClasses() : array
		{
			return ( $this->extra_classes === null ) ? [] : [ $this->extra_classes ];
		}

		const DEFAULT_OPTIONS =
		[
			'zoom' => false,
			'show-buttons' => false
		];

		const OPTION_CLASSES =
		[
			'zoom' => 'waj-image-slider-zoom',
			'show-buttons' => 'waj-image-slider-show-buttons'
		];

		private $images;
		private $container_attributes;
		private $extra_classes;
		private $options;
	}
}
