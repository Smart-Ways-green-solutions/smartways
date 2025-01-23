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

document.addEventListener("DOMContentLoaded", function () {
    let options = {
        selector: '.tinymce',
        height: 300,
        menubar: false,
        statusbar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table paste code help wordcount'
        ],
        toolbar: 'undo redo | formatselect | ' +
            'bold italic backcolor | alignleft aligncenter ' +
            'alignright alignjustify | bullist numlist outdent indent | ' +
            'removeformat',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; -webkit-font-smoothing: antialiased; }'
    }
    if (localStorage.getItem("tablerTheme") === 'dark') {
        options.skin = 'oxide-dark';
        options.content_css = 'dark';
    }
    tinyMCE.init(options);
})