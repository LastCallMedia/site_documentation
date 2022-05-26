# User Manual (Module for Drupal 9+)

Allows administrators to create manual that lives within the help
section of the site.

The purpose of this project is to add a place within the site for manual
that is useful to site editors. This manual can be added/edited by
developers or site administrators. The hope is that this will help keep
manual more up to date, as well as make it more accessible to site
editorial teams.

Functionality
------------------

- `/admin/help` page gets a new section added to it called `User Manual`
- A new entity type (`user_manual`) is added. Any entities created will appear at `/admin/help` under the `User Manual` section.
- View permissions are added for each `HelpSection`
  - Users needs permission to view each help section on `/admin/help`
  - This was done so technical help manual can be withheld from less technical users. By default, `hook_help` is available to _all_ users, which is not very useful to 99% of them.

