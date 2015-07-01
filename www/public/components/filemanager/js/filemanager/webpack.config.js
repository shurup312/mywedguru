var webpack = require("webpack");
module.exports = {
    context: __dirname,
    entry: __dirname + "/core/app.js",
    output: {
        path: __dirname + "/../",
        filename: "filemanager.js"
    },
    module: {
        loaders: [
            {
                test: /\.html$/,
                loader: 'mustache?minify'
            }
        ]
    }
    /*plugins: [
        new webpack.optimize.UglifyJsPlugin({minimize: true})
    ]*/
};