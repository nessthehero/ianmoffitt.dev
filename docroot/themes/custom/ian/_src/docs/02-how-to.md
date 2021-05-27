---
title: "How to perform common tasks"
---

## Getting Started

`npm install`

Install the dependencies.

`npm start`

Start the server.

## Adding a new pattern

1. Figure out the appropriate collection to include the pattern.    
2. Create a new folder in the appropriate section using the BEM name of your component. (e.g. button, accordion, news-and-events)
3. Create a HBS file. (e.g. button.hbs)
4. Create a config file. (e.g. button.config.json) (optional but recommended)

```json
{
    "title": "Button",
    "status": "ready",
    "context": {
        
    }
}
```

Config files can also be in YAML, but for all our examples we will use JSON.

### Name

You can provide a custom **name** or **handler** entry in the config to adjust the reference handler the pattern uses.

For example:

```json
{
    "title": "Button",
    "name": "super-button",
    "status": "ready",
    "context": {
        
    }
}
```

You would use `\{{> @super-button}}` or `\{{render '@super-button'}}` to render the pattern.

### Status

A pattern can have one of three statuses:

1. **ready** - Pattern is ready for use and feature complete
2. **wip** - Pattern is in progress 
3. **prototype** - Pattern is experimental

It is possible to create custom statuses with additional configuration.

### Preview

You can change what file will render the pattern using the `preview` entry.

For example:

```json
{
    "title": "Button",
    "status": "ready",
    "preview": "@template",
    "context": {
        
    }
}
```

The pattern would now use `_template.hbs` to render instead of `_preview.hbs`. 

The default is `_preview.hbs`.

For more information on configuration, read the documentation: [https://fractal.build/guide/core-concepts/configuration-files.html](https://fractal.build/guide/core-concepts/configuration-files.html)

## Creating a variant of a pattern

You can add variants to the pattern in two ways:

### 1. Config

Add the variants array to the config.

```
{
    "title": "Button",
    "status": "ready",
    "context": {
        
    },
    "variants": [
        {
            "name": "Secondary",
            "context": {

            }
        }
    ]
}    
```

The context of each variant *inherits* the same data as the parent. You only need to apply data necessary for that variant.

### 2. File

If you need alternate markup for a variant, you can create a new HBS file and give it a BEM syntax alternate name.

For example, 
```markdown
components /
    button /
        button.config.json
        button.hbs
        button--secondary.hbs
```

The context for this variant is the default, unless you specify a variant in the config with the same name. In this example, you would need a variant with the name "secondary" to pass specific data to the newly created button--secondary.hbs.

For more information on variants, read the documentation: [https://fractal.build/guide/components/variants.html#creating-variants](https://fractal.build/guide/components/variants.html#creating-variants) 

## Sorting patterns

You can control the order of any pattern by prefixing the file or folder name with a two digit number, prefixed with a zero if it's less than 10.

Example: 
```markdown
components /
    01-buttons /
        buttons.hbs
    accordions /
        accordions.hbs
```

## Hiding patterns

If you do not want a pattern to appear in the UI, prefix the file or folder name with an underscore.

Example:
```markdown
components /
    _private /
        thing.hbs
    button /
        button.hbs
        _secret-button.hbs
```

## Passing Data to a Pattern

In this example we'll have a testing pattern that pulls in an existing button pattern and changes the title of the button.

*example.config.json*
```json
{
    "title": "Example",
    "status": "ready",
    "context": {
        "button": {
            "title": "Test"
        }
    }        
}
```

*example.hbs*
```handlebars
<div class="example">
    {{render '@button' button merge=true}}    
</div>
```

If `merge=true` is omitted, then the context of the button will be replaced by the context you pass in.

## Attach scripts to patterns

By default, _preview.hbs is used to render patterns in the preview and when compiled.

The following code renders scripts into the template:

```handlebars
{{#if _target.context.scripts}}
	{{#each _target.context.scripts}}
		<script type="text/javascript" src="{{ scriptPath this }}"></script>
	{{/each}}
{{else}}
	<script type="text/javascript" src="{{ path '/js/vendor.js' }}"></script>
	<script type="text/javascript" src="{{ path '/js/main.js' }}"></script>
{{/if}}
```

In the pattern's config, you can add an array named "scripts" to the context object to set what scripts load.

```json
{
    "title": "Level Page",
    "context": {
        "scripts": [
            "main",
            "level"                        
        ]   
    }    
}
```

This will add main.js and level.js to the layout when it is rendered.

## Changing the layout for a pattern

You can override the layout used to render a component in the config. By default
