const Encore = require('@symfony/webpack-encore');

Encore
// directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.ts)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    // general for each page
    .addEntry('app', './assets/ts/entrypoints/app.ts')
    // panel screen
    .addEntry('panel', './assets/ts/entrypoints/panel.ts')
    // CKEDITOR
    .addEntry('ckeditor', './assets/ts/entrypoints/ckeditor.js').
    addEntry('users', './assets/ts/entrypoints/users.ts')
    //.addEntry('page1', './assets/ts/page1.ts')
    //.addEntry('page2', './assets/ts/page2.ts')

    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.ts
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */.
    cleanupOutputBeforeBuild().
    enableBuildNotifications().
    enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // enables Sass/SCSS support
    .enableSassLoader()
    // enable post CSS loader
    .enablePostCssLoader(options => {
      options.config = {
        // the directory where the postcss.config.ts file is stored
        path: './postcss.config.js',
      };
    })

    // uncomment if you use TypeScript
    .enableTypeScriptLoader()

// uncomment if you're having problems with a jQuery plugin
//.autoProvidejQuery()

// uncomment if you use API Platform Admin (composer req api-admin)
//.enableReactPreset()
//.addEntry('admin', './assets/ts/admin.ts')
;

module.exports = Encore.getWebpackConfig();
