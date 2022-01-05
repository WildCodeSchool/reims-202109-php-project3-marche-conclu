import AutoCompleteJS from '@tarekraafat/autocomplete.js';

const config = new AutoCompleteJS({
    selector: '#autoComplete',
    threshold: 2,
    maxResults: 1,
    placeHolder: 'Strasbourg, Reims..',
    data: {
        src: ['Strasbourg', 'Metz', 'Reims', 'Nancy', 'Troyes'],
        cache: true,
    },
    resultsList: {
        maxResults: 1,
        element: (list, data) => {
            if (!data.results.length) {
                // Create 'No Results' message element
                const message = document.createElement('div');
                // Add class to the created element
                message.setAttribute('class', 'no_result');
                // Add message text content
                message.innerHTML = `<span>Aucun résultat trouvé pour '${data.query}'</span>`;
                // Append message element to the results list
                list.prepend(message);
            }
        },
        noResults: true,
    },
    resultItem: {
        highlight: true,
    },
    events: {
        input: {
            selection: (event) => {
                const selection = event.detail.selection.value;
                AutoCompleteJS.input.value = selection;
            },
        },
    },
});

export default config;
