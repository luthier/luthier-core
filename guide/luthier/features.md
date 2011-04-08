# What does Luthier (luthier-core) provide?

The luthier-core module provides the necessary building blocks for other
Luthier extensions to create a unified administration area.

## Layout Views

Luthier provides an abstract layout view class (Kostache view model) to
allow easy integration into Luthier themes. To use it, simply extend the
view and create a custom view class with your own mustache template
(see [understanding the layout views](layouts)).

    /* Uses template/my/view.mustache */
    class View_My_View extends Luthier_Layout {
        protected $_layout_type = “wide”;
    }

The layout view will automatically build the site navigation by listing
all Luthier extension controllers (see [navigation](navigation)).
The layout view provides methods to add items to a section navigation
(which may be displayed as a side menu or secondary navigation list
depending on the theme in use).

    /* Add “create new post” to “Blog Mgmt” section nav */
    $this->_add_section_nav(“Blog Mgmt”, “admin/blog/new”, “create new post”);

## Common Views

Luthier provides several commonly-used view classes to promote the DRY
principle. These views include, but are not limited to, a table view,
a form view, and a confirmation view. These views may be extended or
used as-is (setting instance variables appropriately).

## Basic HTML Templates

Luthier provides a set of basic HTML templates used by the layout and common
views. These are typically overwritten by theme extensions and exist only
to facilitate HTML rendering in the absence of a theme.

## Error pages

Luthier provides a set of common error pages (view classes) for common HTTP errors (404, 403, etc.).

## User Messages

Luthier provides a set of utilities to set and retrieve flash messages that
can be used to display error/success/info messages to the user.

## Base Controller

Luthier provides an optional base controller which assists in differentiating
between an external request (the initial request) and an internal request (HMVC or ajax).

 - A `redirect()` method is added to the controller to specify a redirection URL.
   The redirect will occur only if the request is an external request.
 - A `message()` method is added to the controller to specify a user message
   to be set in flash session if the request is an external request, or to be
   set as the response if the request is an internal request.
