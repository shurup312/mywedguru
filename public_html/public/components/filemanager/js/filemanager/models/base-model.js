var api = require('../core/api');
var Model = require('ampersand-model');

module.exports = Model.extend({
    createUrl: function (path) {
        return api.createUrl(path);
    },

    url: function () {
        return this.createUrl();
    }
});