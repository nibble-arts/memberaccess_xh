Memberaccess
============

The CMSimple_XH plugin is designed to grant page access depending on defined group rights. The registration is automatically done by the user adding a defined default group. The profile is locked until a confirmation link is called, which is automatically sent to the given email. After a definable timeout, unconfirmed profiles are automatically removed. When logged in, the user can edit his name and email. The profile data with the password hash is stored in a text file, as well as the group affiliation.

Plugin use
==========

In the plugin call different function can be selected:

{{{memberaccess("function")}}}

login
-----

Shows a login screen with two links for registration and password reset. When logged in, only the name of the user and a logout button is shown.

register
--------

A form for the registration is shown.

forgotten
---------

A form with username and email is shown. When the username and email address is valid, an email with a new password is sent.

profile
-------

Shows the profile data. Except the username, all information can be changed.

pages
-----

under developement

administration
--------------

under developement