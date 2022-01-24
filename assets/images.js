window.onload = () => {
    // gestion des boutons "supprimer"
    const links = document.querySelectorAll('[data-delete');

    // on boucle sur links
    links.forEach((link) => {
        // on écoute le clic
        link.addEventListener('click', function image(event) {
            // on empêche la navigation
            event.preventDefault();

            // on demande confirmation
            if (window.confirm('Voulez-vous supprimer cette image ?')) {
                //eslint-disable-line
                // on envoie une requête Ajax vers le href du lien  avec la méthode DELETE
                fetch(this.getAttribute('href'), {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ _token: this.dataset.token }),
                }).then(
                    // on récupère la réponse en JSON
                    (response) => response.json(),
                ).then((data) => {
                    if (data.success) this.parentElement.remove();
                    else alert(data.error);
                }).catch(() => alert(event));
            }
        });
    });
};
