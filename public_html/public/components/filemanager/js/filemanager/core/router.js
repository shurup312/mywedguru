var Router = require('ampersand-router');
var IndexPage = require('../pages/index');
var FileCollection = require('../models/file-collection');

module.exports = Router.extend({
    routes: {
        '': 'index',
        'root/:path': 'index',
        '(*path)': 'catchAll'
    },

    index: function (path) {
        var self = this;
        var folders = new FileCollection({
            path: path,
            filter: 'folders'
        });
        var files = new FileCollection({
            path: path,
            filter: 'files'
        });
        folders.fetch({
            success: function () {
                files.fetch({
                    success: function () {
                        self.trigger('page', new IndexPage({
                            folders: folders,
                            files: files
                        }));
                    }
                });
            }
        });
    },

    catchAll: function () {
        this.redirectTo('');
    }
});