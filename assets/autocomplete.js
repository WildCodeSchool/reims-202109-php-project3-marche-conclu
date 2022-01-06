function initialize() {
    const options = {
        types: ['(cities)'],
        componentRestrictions: {
            country: 'fr',
        },
    };
    const { google } = window;
    const input = document.getElementById('autocomplete');
    const autocomplete = new google.maps.places.Autocomplete(input, options);
}

initialize();
