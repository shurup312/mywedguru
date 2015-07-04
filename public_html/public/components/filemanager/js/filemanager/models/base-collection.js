var api = require('../core/api');
var Collection = require('ampersand-rest-collection');

module.exports = Collection.extend({
    createUrl: function (path) {
        return api.createUrl(path);
    }
});