( function()
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

	const ChangeItem = function()
	{
		CurrentItem++;
		if ( CurrentItem >= Items.length )
		{
			CurrentItem = 0;
		}
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
				console.log( Items[ CurrentItem ].style.opacity - OpacityChange );
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

	for ( i = 0; i < Items.length; i++ )
	{
		Items[ i ].style.opacity = 1;
	}

	SetImagePriority();
	requestAnimationFrame( Update );
}());
