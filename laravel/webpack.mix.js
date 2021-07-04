const mix = require('laravel-mix');
const path = require('path')
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

mix
  .browserSync('shigyo-match.site:10081')
  .ts('resources/ts/app.ts', 'public/js')
  .sass('resources/sass/app.scss', 'public/css')
  .version()


mix.webpackConfig({
  resolve: {
    extensions: ['.js', '.ts', '.vue', '.json'],
    alias: {
      vue$: 'vue/dist/vue.esm.js',
      '@': path.resolve('resources/ts'),
    },
  },
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        loader: 'ts-loader',
        options: { appendTsSuffixTo: [/\.vue$/] },
        exclude: /node_modules/,
      },
    ],
  },
})
