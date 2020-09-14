# Recommended Modules

Last updated February, 2020

!> Note: Try to avoid "Experimental" modules. An alert will appear on the System Report page that cannot be cleared, and may be interpreted by a client as a problem with the site. They are also generally buggy and unreliable and should be used with extreme caution.

!> Always set up a new site using a Minimal install profile, to ensure only the modules we need are installed and configured.

## Core Modules

These modules are part of core and should already come with Drupal when it is installed. The recommendations below are for what modules to activate. Not every Core module is necessary.

Explanations below each module are not exhaustive to their use to the site.

### Core

1. Automated Cron

    Allows cron to run at the end of server responses.    
    
2. Block

    Blocks are a major component of Drupal functionality.
    
3. Breakpoint

    This module allows you to modify behavior of layout and images based on the breakpoint of the window. This module is required by several other modules, but its core functionality is not generally relied on by our integration.
    
4. CKEditor

    WYSIWYG for all rich-text fields.
    
5. Configuration Manager

    Allows you to export a site's configuration, or import configuration from another site.

6. Content Moderation

    Rudimentary workflow.
    
7. Contextual Links

    Allows for inline Quick Edit functionality as well as other helpful Drupal functionality.
    
8. Custom Block

    Allows creating custom blocks like HTML
    
9. Custom Menu Links

    Allows adding custom links to menus, like external links.
    
10. Database Logging

11. Field

12. Field UI

13. Filter

14. Help

15. Inline Form Errors

16. Internal Dynamic Page Cache

17. Internal Page Cache

    Caches pages for anonymous users. Disable if using Acquia or a host that comes with Varnish caching.

18. Menu UI

    Allows administrators to modify menus in the CMS.
    
19. Node

20. Path

    Facilitates clean URLs across the site (e.g. /news/ or /about-us)
    
21. Settings Tray

22. Shortcut

    Allows backend users to save specific pages in the CMS to a tray of links in the toolbar.
    
23. System

24. Taxonomy

    Basic tagging functionality using terms and vocabularies.
    
25. Text Editor

26. Toolbar

    Top level administration categories and a way to quickly get around in the CMS.
    
27. Update Manager

28. User

29. Views

    Views are like SQL without writing queries. They are a very powerful tool for creating screens of lists of content. Our typical integration method does not rely on Views for things like faceted search pages or indexes of content, but Views is capable of creating those pages.
    
30. Views UI

31. Workflows

    A slightly more robust Workflow for Drupal 8. Requires some configuration to make it useful for site editors.

### Field Types

1. Datetime

2. File

3. Image

4. Link

5. Options

6. Text

### Optional Core Modules

1. Internal Page Cache

    Drupal will store a static copy of pages for anonymous users. Typically not necessary for sites hosted on platforms like Acquia, which offer additional caching using Varnish.
    
2. Media

    Improved interface for referencing Media items, like files or images. Sometimes can be buggy, so proceed with caution. Do not install if using IMCE.
    
3. Media Library

    See Media note.
    
4. Quick Edit

    Can allow the editor to modify fields while previewing the page without leaving it. Does not reliably work with all field types and may require your templates to be formatted in specific ways to allow this functionality.
    
## Other Modules

1. [Acquia Connector](https://www.drupal.org/project/acquia_connector)

    Recommended if the client is hosted on Acquia. It connects their site to their dashboard for reports and statistics.
    
2. [Acquia Search](https://docs.acquia.com/acquia-search/modules/)

    Powerful search functionality. Requires some additional modules and configuration both in the CMS and through Acquia to implement. Do not use if client is not hosted on Acquia. TODO: LINK TO SECTION ABOUT ACQUIA SEARCH
    
3. [Admin Toolbar](https://www.drupal.org/project/admin_toolbar)

    Extends the Core toolbar to offer deeper dropdowns to CMS functionality, plus the ability to run cron or clear caches from anywhere in the site.
    
4. Admin Toolbar Extra Tools

    Part of Admin Toolbar.
    
5. [Chaos Tool Suite](https://www.drupal.org/project/ctools)

    This module provides a number of tools and utilities for other modules. Nothing generally needs configured for it by itself.
    
6. [CKEditor Anchor Link](https://www.drupal.org/project/anchor_link)

    Improves the link dialog for CKEditor, allowing the user to reference internal nodes and also external links.
    
7. [Field Group](https://www.drupal.org/project/field_group)

    Allows you to group fields together under tabs or accordions, improving the experience for an editor when they enter content.
    
8. [Maxlength](https://www.drupal.org/project/maxlength)

    Offers the ability to set a maxlength on text fields without changing the length of the field in the system. You can also set different maxlengths per content type to the same field.
    
9. [Date Recur](https://www.drupal.org/project/date_recur)

    A date field that has an option to provide an RRULE which can programmatically generate a range of dates. Useful if you need (or desire) to have a singular Event node produce repeating occurrences in the site.
    
10. Datetime Range

    Adds end date functionality to normal date fields. Included with Core.
    
11. [Entity Reference Revisions](https://www.drupal.org/project/entity_reference_revisions)

    Used to create node reference fields, as well as required by Paragraphs. Very useful for creating component based websites.
    
12. [Entity Embed](https://www.drupal.org/project/entity_embed)

    Allows an editor to embed another node directly in a WYSIWYG area.
    
13. [IMCE File Manager](https://www.drupal.org/project/imce)

    Asset manager UI for image and file upload fields, and allows content editors to manage and reuse existing uploads.
    
    (If the Media modules are used, ICME should not be used in tandem. Use one or the other.)

14. [Embed](https://www.drupal.org/project/embed)

    Required for Entity Embed and other modules that allow inserting content into the WYSIWYG.

15. [Link Attributes](https://www.drupal.org/project/link_attributes)

    Allows options to set additional attributes on Link fields, such as target, aria-label, title, etc.

16. [Link Field Autocomplete Filter](https://www.drupal.org/project/link_field_autocomplete_filter)

    Allows options to limit node reference fields (in links) to only certain content types.

17. [Pathauto](https://www.drupal.org/project/pathauto)

    Automatically generates aliases for nodes based on patterns set in configuration.

18. [Quick Node Clone](https://www.drupal.org/project/quick_node_clone)

    Provides an option to quickly clone a node and all its content.

19. [Redirect](https://www.drupal.org/project/redirect)

    Provides the ability to create manual redirects and maintain a canonical URL for all content, redirecting all other requests to that path.

20. Redirect 404

    Logs all 404 requests and offers a UI to quickly create new redirects for them. Included with Redirect module.

21. [Token](https://www.drupal.org/project/token)

    Provides additional tokens not supported by core (most notably fields), as well as a UI for browsing tokens.

22. [Twig Tweak](https://www.drupal.org/project/twig_tweak)

    Provides a Twig extension with some useful functions and filters that can improve development experience.

23. [Paragraphs](https://www.drupal.org/project/paragraphs)

    Powerful module used to create flexible, repeatable components. Powers the majority of all page-level functionality on the site.

24. Paragraph Type Permissions

    Allows you to set permissions for each paragraph type. Included with Paragraphs.

25. [Search API](https://www.drupal.org/project/search_api)

    Provides a framework to other modules to search entities in Drupal. Necessary for Acquia Site Search.

26. [Solr Search](https://www.drupal.org/project/search_api_solr)

    Provides a Solr backend for the Search API module. Necessary for Acquia Site Search.

27. [Metatag](https://www.drupal.org/project/metatag)

    Allows you to automatically provide structured metadata, aka "meta tags", about a website.

28. [Honeypot](https://www.drupal.org/project/honeypot)

    Anti-spam measures for the Webform module.

29. [Webform](https://www.drupal.org/project/webform)

    Very powerful form builder.
