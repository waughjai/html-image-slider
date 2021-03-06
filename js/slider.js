( function()
{
	const FirstItem = document.getElementById( 'waj-image-slider-item-1' );
	const Container = document.getElementById( 'waj-image-slider' );
	const ZoomsIn = Container.classList.contains( 'waj-image-slider-zoom' );
	const ShowButtons = Container.classList.contains( 'waj-image-slider-show-buttons' );
	const ItemClass = 'waj-image-slider-item';
	const Items = document.getElementsByClassName( ItemClass );
	const SliderStates =
	{
		Zooming: 0,
		Fading: 1
	};
	const OriginalItemWidth = 100;
	const OriginalItemWidthSpeed = 0.6;
	const ItemWidthDeceleration = 1.007;
	const ItemFadeSpeed = 5000;
	const ChangeDelay = 120;

	let CurrentItem = 0;
	let CurrentSliderState = SliderStates.Zooming;
	let PreviousTimestamp = 0;
	let CurrentItemWidth = OriginalItemWidth;
	let CurrentItemWidthSpeed = OriginalItemWidthSpeed;
	let Timer = 0;

	const SetImagePriority = function()
	{
		let PriorityCountdown = Items.length;
		// Current highest priority, then gradually going down next items.
		for ( let i = CurrentItem; i < Items.length; i++ )
		{
			Items[ i ].style.zIndex = PriorityCountdown;
			PriorityCountdown--;
		}

		// & then priority continues down from start till we're back to current ( going through all items ).
		for ( let j = 0; j < CurrentItem; j++ )
		{
			Items[ j ].style.zIndex = PriorityCountdown;
			PriorityCountdown--;
		}

		// If current is 3 out o' 6:
		// From highest to lowest priority: 3, 4, 5, 0, 1, 2
	};

	// Ensure container keeps proportions with original item height & doesn't grow as current image zooms in.
	const SetContainerHeight = function()
	{
		const FirstImage = new Image();
		FirstImage.onload = function()
		{
			const AspectRatio = this.height / this.width;
			const NewHeight = AspectRatio * Items[ GetNextItem() ].clientWidth;
			Container.style.height = `${ NewHeight }px`;
		}
		FirstImage.src = FirstItem.currentSrc || FirstItem.getAttribute( 'src' );
	};

	const GetNextItem = function()
	{
		NextItem = CurrentItem+1;
		if ( NextItem >= Items.length )
		{
			NextItem = 0;
		}
		return NextItem;
	};

	const ChangeItem = function()
	{
		CurrentItem = GetNextItem();
		SetImagePriority();
	};

	const ForceToCertainSlide = function( SlideNumber )
	{
	};

	const Update = function( Timestamp )
	{
		switch ( CurrentSliderState )
		{
			case ( SliderStates.Zooming ):
			{
				if ( ZoomsIn )
				{
					const Percentage = parseInt( Items[ CurrentItem ].style.width );
					if ( Percentage >= 160 )
					{
						PreviousTimestamp = Timestamp;
						CurrentSliderState = SliderStates.Fading;
						CurrentItemWidth = OriginalItemWidth;
						CurrentItemWidthSpeed = OriginalItemWidthSpeed;
					}
					else
					{
						Items[ CurrentItem ].style.width = `${ CurrentItemWidth }%`;
						CurrentItemWidth += CurrentItemWidthSpeed;
						CurrentItemWidthSpeed /= ItemWidthDeceleration;
					}
				}
				else
				{
					if ( Timer >= ChangeDelay )
					{
						PreviousTimestamp = Timestamp;
						CurrentSliderState = SliderStates.Fading;
						Timer = 0;
					}
					else
					{
						Timer++;
					}
				}
			}
			break;

			case ( SliderStates.Fading ):
			{
				const OpacityChange = ( Timestamp - PreviousTimestamp ) / ItemFadeSpeed;
				Items[ CurrentItem ].style.opacity -= OpacityChange;
				if ( Items[ CurrentItem ].style.opacity <= 0 )
				{
					CurrentSliderState = SliderStates.Zooming;
					Items[ CurrentItem ].style.opacity = 1;
					Items[ CurrentItem ].style.width = `${ OriginalItemWidth }%`;
					ChangeItem();
				}
			}
			break;
		}

		requestAnimationFrame( Update );
	};

	// Ensure opacity is set.
	for ( i = 0; i < Items.length; i++ )
	{
		Items[ i ].style.opacity = 1;
	}

	SetContainerHeight();
	SetImagePriority();
	requestAnimationFrame( Update );
	window.addEventListener( 'resize', SetContainerHeight );

	if ( ShowButtons )
	{
		for ( let i = 0; i < Items.length; i++ )
		{

		}
	}
}());
