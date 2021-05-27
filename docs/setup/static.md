# Static Coding

This pertains to setup of the static site generator within a Drupal 8 project.

## Source Location

Provided you set up the directory using the boilerplate, your static code should go inside the custom theme.

Here's an example of what that might look like:

`/docroot/themes/THEMENAME/_src/`

During initialization of the generator, you may be asked for a deploy directory. You should use the following directory:

`../../../../web`

This will output the static representation of the site to a folder named `web` in the repository root. This is important so that the static site can be served via a Jenkins job or some other kind of simple hosting. It's important to note how deep we are in the repo to explain why we need to go back so far. We should be in `/docroot/themes/THEMENAME/_src/` for our static project. If you have another level of heirarchy under themes, for example you placed your theme under a folder named `custom`, you would need to add another level to compensate.

Some configuration adjustments will be needed in addition to this. See them below.

## Configuration Adjustments

Some compensations need made to account for copying necessary files to the Drupal theme directories. Below is a guide for two versions of the static generator.

### For Generator 3.5.0+

Open up `brei-config.json` in the static project root.

Add a key named `drupal` and set the value to `"../_files"`. You should see something similar to this:

```json
{
	"generatorVersion": "3.5.1",
	"deploy": "../../../../web",
	"drupal": "../_files",
	"name": "example",
	"version": "0.0.1",
	"debug": "false"
}
```

This will output compiled files into the `_files` directory in your theme, where they can easily be served by Drupal 8. In a later chapter we will discuss setting up libraries to serve the static assets.

Next, open `Gruntfile.js` and add a new key to the options object. It should look similar to this:

```json
var options = {
	config: {
		src: 'grunt-config/*.js'
	},
	yeoman: {
		app: 'app',
		dist: 'dist',
		deploy: brei.deploy,
		drupal: brei.drupal
	}
};
```

You will also need to add some new sub tasks to the deploy task.

```javascript
grunt.registerTask('deploy', [
    'clean:deploy',
    'copy:deploy',
    'clean:drupal',
    'copy:drupal',
    'copy:icons'
]);
```

In `grunt-config/clean.js`, you will need to add some globs to the existing configuration.

```json
drupal: {
    options: {
        force: true
    },
    src: [yeoman.drupal]
},
```

In `grunt-config/copy.js`, you will need to add some globs to the existing configuration.

#### SVG Icons

!> If you are including SVG icons from Icomoon, the following section applies.

```json
icons: {
    files: [{
        expand: true,
        dot: true,
        cwd: yeoman.app + '/sass/icons',
        dest: yeoman.drupal,
        src: ['./*.json']
    }]
},
```

In our boilerplate theme, there is some PHP code that scrapes the `selection.json` and creates variables for each one so they can be easily output into the page. The above glob ensures that the `selection.json` file is copied into your `_files/` directory. It should live in the root of that directory.

#### Drupal Theme Files

!> This applies to any site using this setup.

```json
drupal: {
    files: [{
        expand: true,
        dot: true,
        cwd: yeoman.dist,
        dest: yeoman.drupal,
        src: [
            './js/**/*',
            './css/**/*',
            './img/**/*'
        ]
    }]
},
```

The reason we create another copy glob for the Drupal assets is so that none of the .html files or other unwanted or unnecessary files get copied into the theme.

### For Generator 4.0.0+

Open up `_config/_brei.json` in the static project root.

Add a key named `drupal` and set the value to `"../_files"`. You should see something similar to this:

```json
{
  "generatorVersion": "4.0.0",
  "app": "app",
  "dist": "dist",
  "deploy": "../../../../web",
  "drupal": "../_files",
  "brei-project-scaffold": "1.0.6",
  "brei-sass-boilerplate": "3.0.2",
  "brei-sass-mixins": "2.0.2",
  "brei-assemble-structure": "2.0.2",
  "brei-assemble-helpers": "2.0.2"
}
```

In `_config/copy.js`, you will need to add a new glob or two under the `deploy` section of the exports.

```json
'deploy': [
    {
        'cwd': dist,
        'dot': true,
        'src': [
            '**'
        ],
        'dest': deploy
    },
    {
        'cwd': app + '/scss/icons',
        'dot': true,
        'src': [
            './*.json',
        ],
        'dest': drupal
    },
    {
        'cwd': dist,
        'dot': true,
        'src': [
            './js/**/*',
            './css/**/*',
            './img/**/*',
        ],
        'dest': drupal
    }
]
```

The glob for the icons directory is not necessary if you are not using icons from Icomoon in your project.