# Pre Launch Checklist

These are some items that may need addressed before the site goes live. They are generic enough that they should apply to every site.

This is not an exhaustive list. Make sure you have all your ducks in a row before handing over the keys to the client.

## 1: Change the default email address to a client email.

There are two places at minimum that are recommended to replace before going live. The first is the site default email, which can be found at `/admin/config/system/site-information`. This is the default email address which will receive update notifications, form submissions, security alerts, etc. Anything that can send an email will default to this email as the "From" address as well. It should be set to a client managed email address.
    
The second place is at `/admin/reports/updates/settings`. You can add one or more email addresses to be notified of any security or module updates for the site.
    
## 2: Turn on caching

Go to `/admin/config/development/performance` and check both boxes under "Bandwith Optimization", and change the maximum age to something other than "&lt;no caching&gt;".
