var Collection = require('./base-collection');
var Model = require('./file');

module.exports = Collection.extend({
    model: Model,

    initialize: function (options) {
        this.path = options.path;
        this.filter = options.filter;
    },

    url: function () {
        if (this.path) {
            return this.createUrl('?path=' + this.path + '&filter=' + this.filter);
        } else {
            return this.createUrl('?filter=' + this.filter);
        }
    }
});