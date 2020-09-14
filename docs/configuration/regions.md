# Regions

A region in Drupal is a location on the page where blocks and content can be rendered. All CMS data and functionality will be rendered into a region, with few exceptions.

You declare the regions in the info file of the theme.

```yaml
name: BarkleyREI
type: theme
description: 'BarkleyREI Drupal Theme'
core: 8.x
screenshot: screenshot.png

regions:
  header: Header
  page_top: Page Top
  admin: Administration
  page_title: Page Title
  breadcrumbs: Breadcrumbs
  content: Content
  sidebar_menu: Sidebar Menu
  sidebar: Sidebar Components
  page_bottom: Page Bottom
  footer: Footer
```

In the YAML above, the key is the machine name of the region (used for preprocessing), and the value is the clean name referred to in the CMS.

There are default regions provided by drupal if no regions are provided by the theme, but we recommend you always declare the regions. 

## Important notes about regions

1. A region with no content will not render on the page. This means that it's impossible to add preprocessing or provide default markup or fallbacks for a region that has no valid blocks inside it. A good workaround for this, for such regions as the header, footer, and sidebars, is to place relevant menus inside them.

## Initial Setup

After enabling the theme in the CMS, navigate to `/admin/structure/block` to manage the initial blocks in each region.

Here are some recommendations for setting up regions that may or may not apply to your theme:
 
1. Place the main header navigation, audience navigation, secondary navigation, and any other menus that appear in the header into the header region.
 
2. Repeat the same for the footer. 
 
3. If you have already created menus for the sidebar, you can place those as well. We recommend creating at least one for testing purposes, but also recommend using the "High Level" menu strategy outlined on the Menu page of this documentation. [See "High Level" strategy](/configuration/menus?id=performance-issues)
 
4. Disable  or remove the "site branding" block
 
5. Move "tabs", "primary admin actions", and "status messages" into the Administration region.
 
6. If you have a specific region for the page title, you can move that block to that region. Otherwise, it can go in the Content region.
 
7. Place the breadcrumbs block into the breadcrumbs region. It may not be already active, so click the "Place block" button to add it.

