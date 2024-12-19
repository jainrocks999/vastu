const mix = require('laravel-mix')
const path = require('path')

const directory = path.basename(path.resolve(__dirname))
const source = 'platform/plugins/' + directory
const dist = 'public/vendor/core/plugins/' + directory

mix
    .js(`${source}/resources/js/franchise-product.js`, `${dist}/js`)
    .js(`${source}/resources/js/franchise-setting.js`, `${dist}/js`)
    .js(`${source}/resources/js/store-revenue.js`, `${dist}/js`)
    .js(`${source}/resources/js/store.js`, `${dist}/js`)
    .js(`${source}/resources/js/vendor-dashboard/franchise.js`, `${dist}/js`)
    .js(`${source}/resources/js/vendor-dashboard/franchise-vendor.js`, `${dist}/js`)
    .js(`${source}/resources/js/vendor-dashboard/discount.js`, `${dist}/js`)
    .js(`${source}/resources/js/customer-register.js`, `${dist}/js`)
    .vue()

    .sass(`${source}/resources/sass/vendor-dashboard/franchise.scss`, `${dist}/css`)
    .sass(`${source}/resources/sass/vendor-dashboard/franchise-rtl.scss`, `${dist}/css`)

if (mix.inProduction()) {
    mix.copy(`${dist}/js/franchise.js`, `${source}/public/js`)
        .copy(`${dist}/js/franchise-product.js`, `${source}/public/js`)
        .copy(`${dist}/js/franchise-vendor.js`, `${source}/public/js`)
        .copy(`${dist}/js/franchise-setting.js`, `${source}/public/js`)
        .copy(`${dist}/js/discount.js`, `${source}/public/js`)
        .copy(`${dist}/js/store-revenue.js`, `${source}/public/js`)
        .copy(`${dist}/js/store.js`, `${source}/public/js`)
        .copy(`${dist}/js/customer-register.js`, `${source}/public/js`)
        .copy(`${dist}/css/franchise.css`, `${source}/public/css`)
        .copy(`${dist}/css/franchise-rtl.css`, `${source}/public/css`)
}
