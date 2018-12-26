<?php

declare( strict_types = 1 );
namespace WaughJ\HTMLImageSlider
{
	use WaughJ\FileLoader\FileLoader;
	use WaughJ\HTMLImageResponsive\HTMLImageResponsive;

	class HTMLImageSlider
	{
		public function __construct( array $images, array $sizes, FileLoader $loader = null )
		{
			$this->images = [];
			$i = 1;
			foreach ( $images as $image )
			{
				$this->images[] = new HTMLImageResponsive
				(
					$image[ 'base' ],
					$image[ 'ext' ],
					$sizes,
					$loader,
					[ 'class' => 'waj-image-slider-item', 'id' => "waj-image-slider-item-{$i}" ]
				);
				$i++;
			}
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
