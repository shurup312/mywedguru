var app = require('ampersand-app');
var dom = require('ampersand-dom');
var View = require('ampersand-view');

module.exports = View.extend({
    template: require('../templates/views/drop-panel.html'),
    defaultText: 'Сюда можно перетащить файлы для загрузки',
    overText: 'Отпустите файлы',

    render: function () {

        var self = this;

        this.renderWithTemplate(this);

        dom.text(this.el, this.defaultText);

        this.el.ondragover = function () {
            dom.text(this, self.overText);
            dom.addClass(this, 'over');
            return false;
        };

        this.el.ondragleave = function () {
            dom.text(this, self.defaultText);
            dom.removeClass(this, 'over');
            return false;
        };

        this.el.ondrop = function (event) {
            event.stopPropagation();
            event.preventDefault();

            dom.text(this, self.defaultText);
            dom.removeClass(this, 'over');
            app.layout.controlPanel.fileUpload(event);
        };

        return this;
    }
});