# Pathauto

This module allows Drupal to automatically generate url aliases for content on creation, based on tokens. These tokens can be anything from node values, to menu positioning, to global site data.

## Standard Pattern

The standard pattern for an alias is the menu position. This means we will look at where a node is on a menu, and using the Menu Link Name of the node as well as the Path of its parent, we generate a new alias.

### Token

`[node:menu-link:parent:url:path]/[node:title]`

The reason we use the path of the parent is so that if the parent node has a custom alias, we will take that into consideration when generating the path of all its children.

Aliases often need customized to match URLs of the older site, or to reduce very long URL names to something easier to type.
