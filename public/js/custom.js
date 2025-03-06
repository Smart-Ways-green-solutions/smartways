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
            'autolink',
             'lists' ,
             'link', 'image', 'charmap', 'preview', 'anchor',
            'visualblocks', 'code', 'fullscreen',
            'media', 'table',  'code', 'help', 'wordcount'
        ],
      
        toolbar: 'undo redo | bold italic underline strikethrough styleselect | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment',
        content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif; font-size: 14px; -webkit-font-smoothing: antialiased; }'
    }
    if (localStorage.getItem("tablerTheme") === 'dark') {
        options.skin = 'oxide-dark';
        options.content_css = 'dark';
    }
    tinyMCE.init(options);
})