function autocomplete_c(value) {
	console.log(value);
	$.post(
		'SearchCountry/search',
		{value: value},
		function(data) {
			console.log(data);
		}
	);
}