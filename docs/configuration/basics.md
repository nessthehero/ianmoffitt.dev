# Basic Configuration

These are some simple configuration recommendations when first setting up a Drupal site.

Many of these configurations are automatically taken care of by the BarkleyREI install profile included in the [Drupal 8 Boilerplate](https://stash.barkleylabs.com/projects/BREID/repos/drupal-8-boilerplate/browse).  

## Activate recommended Core modules

!> This is handled by the BarkleyREI Install Profile

Activate all the recommended Core modules in this guide. That list is located [here](/overview/modules).

Guide on Drupal.org on enabling modules: [Installing Drupal 8 Modules](https://www.drupal.org/docs/8/extending-drupal-8/installing-drupal-8-modules#s-step-2-enable-the-module)

## Activate/install necessary or desired modules

Activate all the modules you may need for site functionality. There is a list of recommended additional modules [here](/overview/modules?id=other-modules).

If a module is not part of the site, you will need to install it.

You can learn how to install modules [here](/overview/installing-modules).

## Activate the theme

Despite making no modifications to the base theme in the boilerplate, it's still appropriate to activate it in the CMS.

Navigate to `/admin/appearance` and scroll down to the list of uninstalled themes. The one provided by the boilerplate is named `BarkleyREI`. You can click the link to "Install and set as default".

After activating, you can uninstall the "Stark" theme, but do not uninstall "Seven", as it is used in the admin interface.

## Create Administrator role

!> This is handled by the BarkleyREI Install Profile

You will need to create a role for the Administrator on the site. By default, the first user is a special "super user" that can do anything on the site, regardless of permissions.

To create a new role, go to `/admin/people/roles` and choose "Add role". Use "Administrator" as the name of the role.

Next, go to `/admin/config/people/accounts`, and select the new role you created under the "Administrator role" section.

On this page, you can make sure only administrators can register accounts (unless the client requires otherwise).

## Create an account for yourself.

Do not use the Super User account for integration. Once most of the basic configuration has been completed, create an account for yourself as an administrator and use that account for everything. 

The reason for this is that many integration issues are a result of poor permissions, and the super user by default has all permissions and is capable of all tasks. If something is not configured properly, you will be unable to tell as the super user. It also provides a level of accountability, as your user account will be associated with any nodes you create. If the super user credentials are shared between multiple parties (such as internal development and the client's IT team), it may be unclear who has used the account to create nodes in the CMS.
