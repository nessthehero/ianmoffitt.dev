---
title: Glossary
---

# Global Terms

## Fractal

A tool that helps build and document web component libraries and integrate them into a larger project. Fractal is a dependency of this project that generates the user interface you are viewing this documentation on.

## Pattern

A "thing" in a component or pattern library. Example: a button, a carousel, a template, a color palette, icons.

## Component

A complicated pattern built from smaller patterns. 

## Collection

A logical group of patterns. Example: Atoms, Molecules, Organisms.

## Atomic Design

A design system that breaks down interfaces into smaller and smaller logical pieces. 

## Atom
The smallest type of pattern. Can't be logically reduced beyond this point. It is typically comprised of a single tag. Also can include abstract items such as color palettes, fonts, icons.

Example: Button, Image, Link, Copy, Form Field.

## Molecule
A pattern made from one or more atoms. Can be reused individually as components or grouped together into organisms to form more complicated components or page fixtures.

Example: Accordion Item, Carousel Slide, Image with Caption, Complex Form Field.

## Organism
A pattern made from one or more molecules. Typically complex enough to stand on their own and be distinguished on a page or template.
 
 Example: Header, Footer, Form, Accordion, Carousel.

## Template

A generic version of a full website page, that can be customized based on its appearance, and the types of components available.

Example: Level Page, Aggregate Page.

## Page

A fleshed out template that has real content and images on it.

Example: Home Page, Faculty Aggregate, About Us.

# Technical Terms

## Variant

An alternate version of a pattern.

## View Template
  
The file that the component or pattern's markup lives in. Typically uses handlebars (.hbs).
  
## Context Data
  
Data passed to a view template to render a pattern. Typically in the form of a JSON (.json) file.

## Handler

An identifier that refers to a specific pattern/variant in Fractal. Typically prefixed with a '@' symbol.

Example: '@button', '@button--disabled'
