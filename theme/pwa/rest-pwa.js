if (location.protocol === 'https:' && 'serviceWorker' in navigator) {
    navigator.serviceWorker
        .getRegistration(window.location.hostname)
        .then(function (worker) {

            if (typeof worker !== 'object') {
                navigator.serviceWorker
                    .register('/service-worker.js')
                    .then(function (registration) {
                        //do nothing
                    })
                    .catch(function (err) {
                        //do nothing
                    });
            }

        });
}