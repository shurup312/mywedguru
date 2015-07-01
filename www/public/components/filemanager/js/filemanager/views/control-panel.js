var api = require('../core/api');
var app = require('ampersand-app');
var dom = require('ampersand-dom');
var View = require('ampersand-view');
var File = require('../models/file');
var fileHelper = require('../helpers/file');
var FileUpload = require('./file-upload');

module.exports = View.extend({
    template: require('../templates/views/control-panel.html'),

    events: {
        'submit [data-hook=create-folder]': 'createFolder',
        'click [data-hook=delete-selected]': 'deleteSelected',
        'click [data-hook=file-upload-trigger]': 'uploadTrigger',
        'change [data-hook=file-upload]': 'fileUpload'
    },

    currentFileNameList: [],

    memory: null,

    setMemory: function (options) {
        this.memory = options;
        var percent = parseInt(options.occupiedMemory * 100/options.availableMemory);
        dom.setAttribute(this.queryByHook('memory-progress'), 'value', percent);
        dom.setAttribute(this.queryByHook('memory-progress'), 'title', percent + '%');
        this.queryByHook('memory-occupied').innerHTML = fileHelper.humanFileSize(options.occupiedMemory);
        this.queryByHook('memory-available').innerHTML = fileHelper.humanFileSize(options.availableMemory);
    },

    clipboard: [],

    clearClipboard: function () {
        this.clipboard = [];
        dom.addAttribute(this.queryByHook('delete-selected'), 'disabled');
    },

    select: function (what) {
        this.clipboard.push(what);
        dom.removeAttribute(this.queryByHook('delete-selected'), 'disabled');
    },

    deselect: function (what) {
        var position = this.clipboard.indexOf(what);
        if (~position) {
            this.clipboard.splice(position, 1);
        }
        if (!this.clipboard.length) {
            dom.addAttribute(this.queryByHook('delete-selected'), 'disabled');
        }
    },

    deleteSelected: function () {
        var model = new File({id: this.clipboard});
        model.destroy({
            success: function (model, response, options) {
                app.router.reload();
            }
        });
    },

    uploadTrigger: function () {
        this.queryByHook('file-upload').click();
    },

    fileUpload: function (event) {
        var files = event.target.files || event.dataTransfer.files;

        var invalidFileIndexes = [];

        // validation memory
        if (this.memory.availableMemory) {
            var occupiedMemory = this.memory.occupiedMemory;
            for (var i = 0, file; file = files[i]; i++) {
                occupiedMemory += file.size;
                if (occupiedMemory > this.memory.availableMemory) {
                    alert('Превышен лимит доступной памяти');
                    return;
                }
            }
        }
        // validation allow types
        if (app.allowMimeTypes) {
            for (var i = 0, file; file = files[i]; i++) {
                if (app.allowMimeTypes.indexOf(file.type) === -1) {
                    alert('Файл ' + file.name + ' имеет недопустимый тип ' + file.type);
                    invalidFileIndexes.push(i);
                }
            }
        }
        // validation deny types
        if (app.denyMimeTypes) {
            for (var i = 0, file; file = files[i]; i++) {
                if (app.denyMimeTypes.indexOf(file.type) !== -1) {
                    alert('Файл ' + file.name + ' имеет недопустимый тип ' + file.type);
                    invalidFileIndexes.push(i);
                }
            }
        }
        // validation same file name
        if (this.currentFileNameList.length) {
            for (var i = 0, file; file = files[i]; i++) {
                if (this.currentFileNameList.indexOf(file.name) !== -1 && !confirm('Файл с именем ' + file.name + ' уже существует. Присвоить имя автоматически?')) {
                    invalidFileIndexes.push(i);
                }
            }
        }

        var length = files.length - invalidFileIndexes.length;
        for (var i = 0, file; file = files[i]; i++) {
            if (invalidFileIndexes.indexOf(i) !== -1) {
                continue;
            }
            var fileType = file.name.split('.').pop().toLowerCase().replace('jpeg', 'jpg');
            var model = new File();
            model.extension = fileType;
            var fileView = new FileUpload({model: model});
            fileView.render();
            app.currentPage.queryByHook('list').appendChild(fileView.el);
            this.sendFile(fileView, file, i, length);
        }
    },

    uploadCounter: 0,

    sendFile: function (fileView, file, i, length) {
        var self = this;
        var form = new FormData();
        file.name = encodeURIComponent(file.name);
        form.append('file', file);
        form.append('path', app.layout.breadcrumbs.path);
        xhr = new XMLHttpRequest();
        xhr.onload = xhr.onerror = function () {
            if (this.status == 200) {
                if (xhr.responseText) {
                    var response = JSON.parse(xhr.responseText);
                }
                if (response && response.error) {
                    alert(response.error);
                    self.uploadCounter = 0;
                    app.router.reload();
                }
                self.uploadCounter++;
                if (self.uploadCounter == length) {
                    self.uploadCounter = 0;
                    app.router.reload();
                }
            } else {
                console.log("error " + this.status);
            }
        };
        xhr.upload.onprogress = function (event) {
            var percent = parseInt(100 * (event.loaded / event.total));
            var progress = fileView.queryByHook('progress');
            progress.style.width = percent + 'px';
            progress.innerHTML = percent + '%';
        };
        xhr.open("POST", api.uploadUrl, true);
        xhr.send(form);
    },

    createFolder: function (e) {
        e.preventDefault();

        var form = e.target;
        var folder = form.elements['folder'].value;
        if (!folder) {
            return;
        }

        if (app.layout.breadcrumbs.path) {
            var path = app.layout.breadcrumbs.path + '|' + folder.replace(/\\/g, '|').replace(/\//g, '|');
        } else {
            var path = folder.replace(/\\/g, '|').replace(/\//g, '|');
        }

        var model = new File();
        model.save({path: path}, {
            success: function (model, response, options) {
                form.reset();
                app.router.reload();
            }
        });
    }
});