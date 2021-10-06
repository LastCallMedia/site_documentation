# Site Documentation (Module for Drupal 9+)

Allows administrators to create documentation that lives within the help
section of the site.

The purpose of this project is to add a place within the site for documentation
that is useful to site editors. This documentation can be added/edited by
developers or site administrators. The hope is that this will help keep
documentation more up to date, as well as make it more accessible to site
editorial teams.

Functionality
------------------

- `/admin/help` page gets a new section added to it called `Site Documentation`
- A new entity type (`site_documentation`) is added. Any entities created will appear at `/admin/help` under the `Site Documentation` section.
- View permissions are added for each `HelpSection`
  - Users needs permission to view each help section on `/admin/help`
  - This was done so technical help documentation can be withheld from less technical users. By default, `hook_help` is available to _all_ users, which is not very useful to 99% of them.
  
