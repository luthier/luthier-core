# Navigation

By default, the Kalf layout view will automatically build the view’s
navigation by scanning the Cascading File System for Kalf controllers
([see routing](routing)). Each detected directory results in one item
in the main navigation, linking to the first (alphabetically) detected
controller in each directory. If a `home` controller exists in a directory,
then that controller is used for the link. This allows for a “landing page”
to be used for a group of controllers.

This was done this way since it is common to have several controllers
that are related to one another and provide similar functionality (eg,
blogging controllers include articles and comments controllers) that would
be arranged in a hierarchical manner. This also means that, as a compromise,
a controller must be in a directory/group even if it is the only controller
within that group.

For example, given the following hierarchy of controllers:

    classes/
        controller/
            kalf/
                blog/
                    articles.php
                    home.php
                    comments.php
                gallery/
                    photos.php
                statistics/
                    pageviews.php
                    subscriptions.php
                users/
                    admins.php
                    groups.php
                    home.php

The main navigation will be:

    <ul>
        <li><a href=”admin”>Home</a></li>
        <li><a href=”admin/blog”>Blog</a></li>
        <li><a href=”admin/gallery/photos”>Gallery</a></li>
        <li><a href=”admin/statistics/pageviews”>Statistics</a></li>
        <li><a href=”admin/users”>Users</a></li>
        <li><a href=”admin/logout”>Logout</a></li>
    </ul>

If automatic detection and building of the main navigation is not desired
(custom ordering/grouping, etc.), then the `Kalf_Layout` class can be replaced
(extending `Kalf_Layout_Core`) or extended, overriding the `main_navigation()` method.

Additionally, a section navigation is built containing links to controllers within
the current directory.  Depending on the theme, the section navigation may be displayed
as a sub-menu, a side-bar menu, or not displayed at all. The section navigation will
not be displayed if there is only one controller in the current directory. The home controller
is not included in the section navigation (rationale is that the link to the home controller
is already part of the main navigation).

Given the same controller hierarchy as above, the section navigation displayed when
on any of the `users` pages will be:

    <h2>Users</h2>
    <ul>
        <li><a href=”admin/users/admins”>Admins</a></li>
        <li><a href=”admin/users/groups”>Groups</a></li>
    </ul>

While on the `gallery/photos` page, no section navigation will be displayed.

The Kalf layout view contains convenience methods for individual views (extending
the layout class) to add items to the section navigation and to add additional headings.
Note that additional section navigation items apply only to the current view, thus to
modify the section navigation for all pages/views of a particular group of controllers,
a common view class would need to be made and then extended by all views used by the group.

For example, any of the `users` view classes (extending the layout class) could call the following methods:

    $this->_add_section_nav(‘Moderators’, ‘admin/users/mods’);
    $this->_add_section_nav(‘Create New User’, ‘admin/users/new’, ‘Quick Links’);
    $this->_add_section_nav(‘Edit My Info’, ‘admin/users/profile’, ‘Quick Links’);

And the section navigation displayed would be:

    <h2>Users</h2>
    <ul>
        <li><a href=”admin/users/admins”>Admins</a></li>
        <li><a href=”admin/users/groups”>Groups</a></li>
        <li><a href=”admin/users/mods”>Moderators</a></li>
    </ul>
    <h2>Quick Links</h2>
    <ul>
        <li><a href=”admin/users/new”>Create New User</a></li>
        <li><a href=”admin/users/profile”>Edit My Info</a></li>
    </ul>

Just as with the main navigation, if automatic detection and building of the section
navigation is not desired, the `Kalf_Layout` class should be replaced or extended,
overriding the `section_navigation()` method.

