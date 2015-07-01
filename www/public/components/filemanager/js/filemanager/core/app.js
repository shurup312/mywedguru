var app = require('ampersand-app');
var domReady = require('domready');
var Router = require('./router');
var Layout = require('../views/main');

module.exports = app.extend({
    settingsUrl: '/filemanager/settings',
    memoryUrl: '/filemanager/memory',

    root: null,
    cloudUrl: null,
    previewUrl: '/filemanager/preview',
    allowMimeTypes: null,
    denyMimeTypes: null,

    router: new Router(),

    init: function () {
        var self = this;
        domReady(function () {
            self.loadSettings(function (response) {
                self.extend(response);
                var layout = new Layout();
                layout.render();
                document.getElementById('module-filemanager').appendChild(layout.el);
                self.router.history.start({pushState: false, root: self.root});
            });
        });
    },

    loadSettings: function (callback) {
        xhr = new XMLHttpRequest();
        xhr.onload = xhr.onerror = function () {
            if (this.status == 200) {
                var response = JSON.parse(xhr.responseText);
                callback(response);
            } else {
                console.log("error " + this.status);
            }
        };
        xhr.open("POST", this.settingsUrl, true);
        xhr.send();
    },

    loadMemory: function (callback) {
        xhr = new XMLHttpRequest();
        xhr.onload = xhr.onerror = function () {
            if (this.status == 200) {
                var response = JSON.parse(xhr.responseText);
                callback(response);
            } else {
                console.log("error " + this.status);
            }
        };
        xhr.open("POST", this.memoryUrl, true);
        xhr.send();
    },

    navigate: function (page) {
        var url = (page.charAt(0) === '/') ? page.slice(1) : page;
        this.router.history.navigate(url, {trigger: true});
    }
});

module.exports.init();