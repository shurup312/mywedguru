var app = require('ampersand-app');
var Model = require('./base-model');

module.exports = Model.extend({
    props: {
        id: ['any', true, null],
        path: ['string', false, ''],
        size: ['number', false, 0],
        extension: ['string', false, ''],
        modified: ['string', false, ''],
        hasKnownType: ['boolean', false, false],
        isLinkView: ['boolean', false, false],
        isImageView: ['boolean', false, false],
        content: ['string', false, ''],
        type: ['string', false, ''],
        previewUrl: ['string', false, '']
    },

    knownTypes: ['txt', 'doc', 'rtf', 'log', 'tex', 'msg', 'text', 'wpd', 'wps', 'docx', 'page', 'csv', 'dat', 'tar', 'xml', 'vcf', 'pps', 'key', 'ppt', 'pptx', 'sdf', 'gbr', 'ged', 'mp3', 'm4a', 'waw', 'wma', 'mpa', 'iff', 'aif', 'ra', 'mid', 'm3v', 'e_3gp', 'shf', 'avi', 'asx', 'mp4', 'e_3g2', 'mpg', 'asf', 'vob', 'wmv', 'mov', 'srt', 'm4v', 'flv', 'rm', 'png', 'psd', 'psp', 'jpg', 'tif', 'tiff', 'gif', 'bmp', 'tga', 'thm', 'yuv', 'dds', 'ai', 'eps', 'ps', 'svg', 'pdf', 'pct', 'indd', 'xlr', 'xls', 'xlsx', 'db', 'dbf', 'mdb', 'pdb', 'sql', 'aacd', 'app', 'exe', 'com', 'bat', 'apk', 'jar', 'hsf', 'pif', 'vb', 'cgi', 'css', 'js', 'php', 'xhtml', 'htm', 'html', 'asp', 'cer', 'jsp', 'cfm', 'aspx', 'rss', 'csr', 'less', 'otf', 'ttf', 'font', 'fnt', 'eot', 'woff', 'zip', 'zipx', 'rar', 'targ', 'sitx', 'deb', 'e_7z', 'pkg', 'rpm', 'cbr', 'gz', 'dmg', 'cue', 'bin', 'iso', 'hdf', 'vcd', 'bak', 'tmp', 'ics', 'msi', 'cfg', 'ini', 'prf', 'lnk'],
    imageTypes: ['png', 'jpg', 'tif', 'tiff', 'gif', 'bmp', 'jpeg'],

    initialize: function () {
        this.on('change:extension', function () {
            this.hasKnownType = (this.knownTypes.indexOf(this.extension) !== -1);
            this.isLinkView = this.isLink();
            this.isImageView = this.isImage();
            if (this.isImage()) {
                this.previewUrl = app.previewUrl + '?file=' + this.id;
            }
        });
    },

    link: function () {
        return '/root/' + this.id;
    },

    name: function () {
        return this.id.split('|').pop();
    },

    url: function () {
        if (this.id) {
            if (this.id instanceof Array) {
                var keys = [];
                for (var i = 0; this.id[i]; i++) {
                    keys.push('id[]=' + this.id[i]);
                }
                return this.createUrl('?' + keys.join('&'));
            } else {
                return this.createUrl('?id=' + this.id);
            }
        } else {
            return this.createUrl();
        }
    },

    isLink: function () {
        return this.type == 'lnk'
    },

    isImage: function () {
        return this.imageTypes.indexOf(this.extension) !== -1;
    }
});