
document.addEventListener('DOMContentLoaded', function() {
    let deleteItem = document.getElementById('delete-item');
    if (deleteItem) {
        document.addEventListener('click', function(event) {
            if (event.target && event.target.id === 'delete-item') {
                let href = event.target.getAttribute('data-href');
                let deleteHref = document.getElementById('delete-href');
                if (deleteHref) {
                    deleteHref.setAttribute('href', href);
                }
            }
        });
    }
});

