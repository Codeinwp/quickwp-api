import './bootstrap';

document.addEventListener('DOMContentLoaded', function() {
    const startButton = document.querySelector('a#start');
    const emailModal = document.getElementById('email-modal');

    if (startButton && emailModal) {
        startButton.addEventListener('click', function() {
            emailModal.classList.remove('hidden');
            emailModal.setAttribute('aria-modal', 'true');
        });
    }

    const subscribeButton = document.querySelector('a#subscribe');
    const emailField = document.querySelector('input#email');

    if (subscribeButton && emailField) {
        subscribeButton.addEventListener('click', function() {
            subscribeButton.disabled = true;
            emailField.disabled = true;

            const email = emailField.value;
            if (email && email.includes('@')) {
                const url = 'https://api.themeisle.com/tracking/subscribe';

                fetch( url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        Accept: 'application/json, */*;q=0.1',
                        'Cache-Control': 'no-cache'
                    },
                    body: JSON.stringify({
                        slug: 'quickwp',
                        email
                    })
                }).then( r => {
                    window.open( '/start', '_self' );
                })?.catch( () => {
                    window.open( '/start', '_self' );
                });
            }
        });
    }
});