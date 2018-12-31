( function()
{
	const FirstItem = document.getElementById( 'waj-image-slider-item-0' );
	const FirstImage = new Image();
	FirstImage.onload = function()
	{
		const ItemClass = 'waj-image-slider-item';
		const Items = document.getElementsByClassName( ItemClass );
		const SliderStates =
		{
			Zooming: 0,
			Fading: 1
		};
		const OriginalItemWidth = 100;
		const OriginalItemWidthSpeed = 0.5;
		const ItemWidthDeceleration = 1.008;
		const ItemFadeSpeed = 5000;
		const AspectRatio = this.height / this.width;

		let CurrentItem = 0;
		let CurrentSliderState = SliderStates.Zooming;
		let PreviousTimestamp = 0;
		let CurrentItemWidth = OriginalItemWidth;
		let CurrentItemWidthSpeed = OriginalItemWidthSpeed;

		const SetImagePriority = function()
		{
			let PriorityCountdown = Items.length;
			for ( let i = CurrentItem; i < Items.length; i++ )
			{
				Items[ i ].style.zIndex = PriorityCountdown;
				PriorityCountdown--;
			}
			for ( let j = 0; j < CurrentItem; j++ )
			{
				Items[ j ].style.zIndex = PriorityCountdown;
				PriorityCountdown--;
			}
		};

		// Ensure container keeps proportions with original item height & doesn't grow as current image zooms in.
		const SetContainerHeight = function()
		{
			const NewHeight = AspectRatio * Items[ GetNextItem() ].clientWidth;
			document.getElementById( 'waj-image-slider' ).style.height = `${ NewHeight }px`;
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

		const Update = function( Timestamp )
		{
			switch ( CurrentSliderState )
			{
				case ( SliderStates.Zooming ):
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
	}
	FirstImage.src = FirstItem.getAttribute( 'src' );
}());
