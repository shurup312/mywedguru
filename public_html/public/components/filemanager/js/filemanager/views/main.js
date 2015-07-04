var app = require('ampersand-app');
var View = require('ampersand-view');
var ViewSwitcher = require('ampersand-view-switcher');
var Breadcrumbs = require('./breadcrumbs');
var ControlPanel = require('./control-panel');
var DropPanel = require('./drop-panel');
var AddLink = require('./add-link');

module.exports = View.extend({
    template: require('../templates/views/main.html'),

    events: {
        'click a[href]': 'handleLinkClick'
    },

    initialize: function () {
        app.layout = this;
        this.listenTo(app.router, 'page', this.handleNewPage);
        this.controlPanel = new ControlPanel();
        this.breadcrumbs = new Breadcrumbs({baseUrl: '/root'});
        this.dropPanel = new DropPanel();
        this.addLink = new AddLink();
    },

    render: function () {

        this.renderWithTemplate(this);

        this.breadcrumbs.render();
        this.queryByHook('breadcrumbs').appendChild(this.breadcrumbs.el);

        this.controlPanel.render();
        this.queryByHook('control-panel').appendChild(this.controlPanel.el);

        this.dropPanel.render();
        this.queryByHook('drop-panel').appendChild(this.dropPanel.el);

        this.addLink.render();
        this.queryByHook('add-link').appendChild(this.addLink.el);

        this.pageSwitcher = new ViewSwitcher(this.queryByHook('page-container'), {
            show: function (newView, oldView) {
                document.title = newView.pageTitle || 'Welcome';
                document.scrollTop = 0;
                app.currentPage = newView;
            }
        });

        return this;
    },

    handleNewPage: function (view) {
        this.pageSwitcher.set(view);
    },

    handleLinkClick: function (e) {
        var aTag = e.delegateTarget;
        var local = aTag.host === window.location.host;
        if (local && !e.ctrlKey && !e.shiftKey && !e.altKey && !e.metaKey && !e.defaultPrevented) {
            e.preventDefault();
            app.navigate(aTag.pathname);
        }
    }
});