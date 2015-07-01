var app = require('ampersand-app');
var View = require('ampersand-view');
var Folder = require('../views/folder');
var File = require('../views/file');

module.exports = View.extend({
    template: require('../templates/pages/index.html'),
    pageTitle: '/',

    props: {
        folders: 'any',
        files: 'any'
    },

    initialize: function () {
        var self = this;
        app.loadMemory(function (response) {
            app.layout.controlPanel.setMemory(response);
        });
        // unique file names in folder
        app.layout.controlPanel.currentFileNameList = [];
        this.files.each(function (model) {
            model.extension = model.id.split('.').pop();
            app.layout.controlPanel.currentFileNameList.push(model.name());
        });
    },

    render: function () {
        this.renderWithTemplate();

        app.layout.controlPanel.clearClipboard();

        if (this.folders.path) {
            app.layout.breadcrumbs.setItems(this.folders.path);
        } else {
            app.layout.breadcrumbs.setItems('');
        }

        this.renderCollection(this.folders, Folder, this.queryByHook('list'));
        this.renderCollection(this.files, File, this.queryByHook('list'));

        return this;
    }
});
