Memberaccess
============

The CMSimple_XH plugin is designed to grant page access depending on defined group rights. The registration is automatically done by the user adding a defined default group. The profile is locked until a confirmation link is called, which is automatically sent to the given email. After a definable timeout, unconfirmed profiles are automatically removed. When logged in, the user can edit his name and email. The profile data with the password hash is stored in a text file, as well as the group affiliation.

The user and group data is stored in the content area of CMSimple, with a secure hash for the password. A backup of the CMSimple content directory stores the member data automatically.

Access for other plugins
========================

Using a plugin call on a page without a function name makes the member login data available for other plugins. The ma\Access class can be implemented getting access to all user login information.

Examples
--------

ma\Access::user() returns the user object of logged, of false

Plugin use
==========

In the plugin call different function can be selected:

{{{memberaccess("function")}}}

Functions
=========
login
-----

Shows a login screen with two links for registration and password reset. When logged in, the name of the user, a link to the profile page and a logout button is shown.

register
--------

Renders a form for the registration.

forgotten
---------

Renders a page with a form for username and email. When the username and email address is valid, an email with a new password is sent to this address.

profile
-------

Shows the profile data. Except the username, all information can be changed.

pages
-----

Shows a list of the resticted pages the user is granted access, sorted by page name, with a direct link to the page.

administration
--------------

The administration can be used on the frontend using the admin group on special pages. Both the member data (except user name) and the groups can be edited. Each access action is recorded in a log file.

ToDos
-----

* Check the auto-remove function of unconfirmed registrations
* Adding and removing of groups an the administration page
