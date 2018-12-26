( function()
{
	const ItemClass = 'waj-image-slider-item';
	const Items = document.getElementsByClassName( ItemClass );
	let CurrentItem = 0;
	let PreviousTimestamp = 0;

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
		if ( Timestamp - PreviousTimestamp >= 1200 )
		{
			Items[ CurrentItem ].style.opacity = 1000 / ( Timestamp - PreviousTimestamp - 1200 );
			if ( Items[ CurrentItem ].style.opacity <= 0 )
			{
				PreviousTimestamp = Timestamp;
				Items[ CurrentItem ].style.opacity = 1;
				ChangeItem();
			}
		}
		requestAnimationFrame( Update );
	};

	SetImagePriority();
	requestAnimationFrame( Update );
}());
