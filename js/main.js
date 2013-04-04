$(function() {
	$('#search-query').autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: $('#form-search').attr('action'),
				dataType: 'jsonp',
				data: {
					q: request.term
				},
				success: function( data ) {
					response( $.map( data, function( event ) {
						var location = event.LocationCity;
						if(event.LocationStateProvince)
						{
							if(location) location += ', ';
							location += event.LocationStateProvince;
						}
						if(event.LocationCountry)
						{
							if(location) location += ', ';
							location += event.LocationCountry;
						}
						console.log({
							label: event.Name,
							location: location,
							uri: event.BlogUri
						})
						return {
							label: event.Name,
							location: location,
							uri: event.BlogUri
						};
					}));
				}
			});
		},
		minLength: 2,
		position: { my : "right top", at: "right+27 bottom" },
		focus: function(event, ui) {
			$(this).val('');
		},
		select: function( event, ui )
		{
			$(this).val('');
			document.location = ui.item.uri;
			return false;
		},
	}) .data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		return $('<li/>')
				.append($('<a/>', {href: item.uri, text: item.label})
						.append($('<div/>', {class: 'location', text: item.location})))
				.appendTo(ul);
		};
});