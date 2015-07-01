module.exports = {
    baseUrl: '/filemanager/api',
    uploadUrl: '/filemanager/upload',
    createUrl: function (path, params) {
        if (path) {
            path = (path.charAt(0) === '/') ? path.slice(1) : path;
        }
        var queryString = '';
        if (params) {
            queryString = '?' + this.createQueryString(params);
        }
        return (path ? this.baseUrl + '/' + path : this.baseUrl) + queryString;
    },
    createQueryString: function (obj, prefix) {
        var str = [];
        for(var p in obj) {
            if (obj.hasOwnProperty(p)) {
                var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
                str.push(typeof v == "object" ?
                    serialize(v, k) :
                encodeURIComponent(k) + "=" + encodeURIComponent(v));
            }
        }
        return str.join("&");
    }
};