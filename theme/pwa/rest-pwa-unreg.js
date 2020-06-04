if (location.protocol === 'https:' && 'serviceWorker' in navigator) {
    navigator.serviceWorker
        .getRegistration(window.location.hostname)
        .then(function (worker) {
            if(typeof worker === 'object'){
                worker.unregister().then(function (boolean) {
                });
            }
        });
}