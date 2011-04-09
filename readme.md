# What is Luthier?

The Luthier content management framework is a collection of modules
for adding an administrative layer to a Kohana application. Since, by itself,
Luthier does not actually do anything and requires additional code to be developed
in order for an admin area to work, Luthier is more like a framework than a
stand-alone module. In this sense, it is a framework on top of the Kohana framework.

Luthier is extremely modular and broken up into several Kohana modules. Each module
(except for the luthier-core module) is referred to as a “Luthier extension”. These are
not user/plug-and-play extensions, but rather extensions that developers must piece
together to create an administration layer (in the same way that Kohana modules are
used). Note that it would be possible for a plugin extension to be developed that
would allow user-enabled plugins--but such functionality is not provided at this
point in time.

The [luthier-core](https://github.com/luthier/luthier-core) module is the base of Luthier,
providing core libraries and views used by all Luthier extensions. Modules like
[luthier-blog](https://github.com/luthier/luthier-blog) and
[luthier-comments](https://github.com/luthier/luthier-comments)
provide complete functionality (such as blog post management and comments management).
Other modules provide libraries used by other Luthier extensions, such as
[luthier-stats](https://github.com/luthier/luthier-stats) and [luthier-markitup](https://github.com/luthier/luthier-markitup).

Luthier uses Kostache for all views. This allows for easy overriding of templates to
provide different themes. Without a theme extension enabled, Luthier will render as
plain HTML. The [luthier-theme-davies](https://github.com/luthier/luthier-theme-davies)
theme uses Colonel-Rosa’s admin-template theme. Hopefully more themes will be
developed to allow more theming options.

# What are its dependencies?

Each Luthier extension can have any number of interdependencies with other Luthier
extensions and external dependencies with other Kohana modules. These dependencies
will be listed in the documentation for each extension.

External dependencies of the [luthier-core](https://github.com/luthier/luthier-core) module are:

 - Kohana 3.1
 - Kostache

# How do I use it?

To being using Luthier, see the userguide or demo
[application](http://github.com/luthier/luthier-demo).

# How can I contribute?

See the developer's guide in the included userguide.
