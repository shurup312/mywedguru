var api = require('../core/api');
var app = require('ampersand-app');
var View = require('ampersand-view');

module.exports = View.extend({
    template: require('../templates/views/add-link.html'),
    events: {
        submit: function (event) {
            event.stopPropagation();
            event.preventDefault();

            var form = event.target;

            var link = form.elements['link'].value;
            var name = form.elements['name'].value;
            if (!link || !name) {
                return;
            }

            var path = app.layout.breadcrumbs.path;
            var url = api.createUrl(null, {type: 'link'});

            xhr = new XMLHttpRequest();
            xhr.onload = xhr.onerror = function () {
                if (this.status == 200) {
                    form.reset();
                    app.router.reload();
                } else {
                    console.log("error " + this.status);
                }
            };
            xhr.open('POST', url, true);
            xhr.send(JSON.stringify({path: path, link: link, name: name}));
        }
    }
});