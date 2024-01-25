# ianmoffitt.dev

CMS:

JIRA Project:

Epic Ticket:

Stash Repo: 

##Project Info:

### Static Repo Versions

**Pattern Scaffold**: 0.0.5
 
**SASS Boilerplate**: 3.0.9

**SASS Mixins**: 2.0.4

**Handlebar Helpers**: 2.0.7

## NPM Scripts

`npm install`

## Commands

`npm run check` - run linting tests

`npm start` - run server

### Building

`npm run build` - Run a full build on static assets and the pattern library.

## Tasks

### build

`npm run clean:dist && npm run scaffold && npm run check && npm run build:img && npm run build:css && npm run build:js && fractal build`

- Cleans up the dist folder.
- Runs the modernizr and preprocessor tasks. (scaffold) 
- Runs lint checks for SASS and ES6 (check)
- Runs the IMG build tasks. (build:img)
- Runs the CSS build tasks. (build:css)
- Runs the JavaScript build tasks. (build:js)
- Compiles the fractal pattern library.

### build:css

`npm run sass:build && npm run postcss:preprocess && npm run postcss:postprocess`

- Runs Sass build task. (sass:build)
- Runs PostCSS build task. (postcss:build)

### build:img

`node lib/imagemin.js`

- Runs imagemin script on app/img and outputs into dist/img.

### build:js

`npx webpack --config ./_config/webpack.config.js --mode=production`

- Runs webpack configuration on ejs directory, with a production flag.

### check

`eslint --ignore-path _config/.eslintignore -c _config/.eslintrc.json assets/ejs && npm run sass:lint`

- Runs eslint on the ejs directory.
- Runs Sass linting tasks. (sass:lint)

### clean:dist

`node lib/del.js --dist`

- Remove compiled assets from public folder.

### modernizr

`customizr -c ./_config/modernizr-config.json`

- Runs customizr script to produce a custom build of modernizr.js, which it places in `app/js/plugins/`

### postcss:fixsass

`postcss --config _config/ -r assets/scss/**/*.scss --env=scss`

- Runs postcss script using congfiguration (_config/postcss.config.js) on scss file in assets directory. This is using the scss configuration. 
- PostCSS plugins: 
    - [postcss-sorting](https://github.com/hudochenkov/postcss-sorting) - Sorts rules in SCSS.

### postcss:postprocess

`postcss --config _config/ -r public/css/main.css --env=csspost`

- Runs postcss script using congfiguration (_config/postcss.config.js) on css file in public directory. This is using the csspost configuration. 
- PostCSS plugins: 
    - [postcss-pxtorem](https://github.com/cuth/postcss-pxtorem) - Converts px values to rem values.
    - [autoprefixer](https://github.com/postcss/autoprefixer) - Adds vendor prefixes based on browser requirements.
    - [cssnano](https://github.com/cssnano/cssnano) - Minifies CSS.

### postcss:preprocess

`postcss --config _config/ -r public/css/main.css --env=csspre`

- Runs postcss script using congfiguration (_config/postcss.config.js) on css file in app directory. This is using the csspre configuration. 
- PostCSS plugins: 
    - [postcss-pxtorem](https://github.com/cuth/postcss-pxtorem) - Converts px values to rem values.
    - [autoprefixer](https://github.com/postcss/autoprefixer) - Adds vendor prefixes based on browser requirements.
    - [cssnano](https://github.com/cssnano/cssnano) - Minifies CSS.
    
### preprocess

`npm run preprocess:css && npm run preprocess:js && npm run modernizr`

- Runs CSS Preprocessing task.
- Runs JS Preprocessing task.
- Runs modernizr taskk.

### preprocess:css

`npm run sass:index && npm run sass:build && npm run postcss:preprocess`

- Runs Sass Index task.
- Runs Sass Build task.
- Runs Postcss Preprocess task.

### preprocess:js

`npx webpack --config ./_config/webpack.config.js`

- Compiles ES6 JavaScript using Webpack configuration.

### sass:index

`node lib/updateScss.js`

- Recompiles the index scss files for the atomic components in the scss folder.

### sass:build

`node lib/nodesass.js`

- Compiles SCSS using nodesass configuration.

### sass:lint

`npm run postcss:fixsass && stylelint "assets/scss/**/*.scss" --fix --cache --cache-location "./.stylelintcache/" --config "./_config/.stylelintrc.json" --ignore-path "./_config/.stylelintignore"`

- Runs Postcss Fixsass task.
- Lints the SCSS files based on defined stylelint rules.

### scaffold

`npm run modernizr && npm run preprocess`

- Runs modernizr task
- Runs preprocess task

### start

`npm run scaffold && fractal start`

- Runs scaffold task
- Starts a server on port 3000 (or first available port).

### test

`mocha`

- Runs tests on the project files.
