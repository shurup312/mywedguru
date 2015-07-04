var View = require('ampersand-view');

module.exports = View.extend({
    template: require('../templates/views/breadcrumbs.html'),

    baseUrl: '/',

    props: {
        items: 'array'
    },

    initialize: function (options) {
        if (options && options.baseUrl) {
            this.baseUrl = options.baseUrl;
        }
        this.on('change:items', function () {
            this.render();
        });
    },

    setItems: function (path) {
        this.path = path;
        var crumbs = path.split('|'), accumulate = '', items = [];
        for (var i = 0; i < crumbs.length; i++) {
            accumulate += crumbs[i] + '|';
            items.push({
                title: crumbs[i],
                link: this.baseUrl + '/' + encodeURIComponent(accumulate.slice(0, -1)),
                isCurrent: i == crumbs.length - 1
            });
        }
        this.items = items;
    }
});