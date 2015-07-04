var app = require('ampersand-app');
var dom = require('ampersand-dom');
var View = require('ampersand-view');

module.exports = View.extend({
    template: require('../templates/views/file.html'),

    events: {
        'dblclick': function (e) {
            if (this.model.isLink()) {
                var url = this.model.content;
            } else {
                var url = app.cloudUrl + '/' + this.model.id.replace(/\|/g, '/');
            }
            var win = window.open(url, '_blank');
            win.focus();
        },

        'click': function (e) {
            if (dom.hasClass(this.el, 'selected')) {
                app.layout.controlPanel.deselect(this.model.id);
                dom.removeClass(this.el, 'selected');
            } else {
                app.layout.controlPanel.select(this.model.id);
                dom.addClass(this.el, 'selected');
            }
        }
    }
});