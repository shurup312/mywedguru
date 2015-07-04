var app = require('ampersand-app');
var dom = require('ampersand-dom');
var View = require('ampersand-view');

module.exports = View.extend({
    template: require('../templates/views/folder.html'),

    events: {
        'dblclick': function (e) {
            app.navigate(this.model.link());
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