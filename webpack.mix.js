const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/app.js", "public/js")
    .sass("resources/sass/app.scss", "public/css")
    .sass("resources/sass/home/home.scss", "public/css/home/home.css")
    .sass("resources/sass/home/about-us.scss", "public/css/home/about-us.css")
    .sass("resources/sass/shared/heading.scss", "public/css/shared/heading.css")
    .sass(
        "resources/sass/products/view.scss",
        "public/css/products/view.css"
    )
    .sass(
        "resources/sass/products/items/overview.scss",
        "public/css/products/items/overview.css"
    )
    .sass(
        "resources/sass/products/items/edit.scss",
        "public/css/products/items/edit.css"
    )
    .combine(
        [
            "node_modules/jquery/dist/jquery.js",
            "resources/js/products/items/edit.js",
        ],
        "public/js/products/items/edit.js"
    )
    .sourceMaps();
