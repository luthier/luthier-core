# What is Kalf?

Kalf (unofficially for Kohana Admin Layer Framework) is a collection of modules
for adding an administrative layer to a Kohana application. Since, by itself,
Kalf does not actually do anything and requires additional code to be developed
in order for an admin area to work, Kalf is more like a framework than a
stand-alone module. In this sense, it is a framework on top of the Kohana framework.

Kalf is extremely modular and broken up into several Kohana modules. Each module
(except for the kalf-core module) is referred to as a “Kalf extension”. These are
not user/plug-and-play extensions, but rather extensions that developers must piece
together to create an administration layer (in the same way that Kohana modules are
used). Note that it would be possible for a plugin extension to be developed that
would allow user-enabled plugins--but such functionality is not provided at this
point in time.

The [kalf-core](https://github.com/kalf/kalf-core) module is the base of Kalf,
providing core libraries and views used by all Kalf extensions. Modules like
[kalf-blog](https://github.com/kalf/kalf-blog) and [kalf-comments](https://github.com/kalf/kalf-comments)
provide complete functionality (such as blog post management and comments management).
Other modules provide libraries used by other Kalf extensions, such as
[kalf-stats](https://github.com/kalf/kalf-stats) and [kalf-markitup](https://github.com/kalf/kalf-markitup).

Kalf uses Kostache for all views. This allows for easy overriding of templates to
provide different themes. Without a theme extension enabled, Kalf will render as
plain HTML. The [kalf-theme-davies](https://github.com/kalf/kalf-theme-davies)
theme uses Colonel-Rosa’s admin-template theme. Hopefully more themes will be
developed to allow more theming options.

# What are its dependencies?

Each Kalf extension can have any number of interdependencies with other Kalf
extensions and external dependencies with other Kohana modules. These dependencies
will be listed in the documentation for each extension.

External dependencies of the [kalf-core](https://github.com/kalf/kalf-core) module are:

 - Kohana 3.1
 - Kostache
 - A2?

Kalf uses Wouter’s [A2](https://github.com/Wouterrr/A2) module as a common interface
for authorization. It can be used with Wouter’s [A1](https://github.com/Wouterrr/A1)
module or the [official auth](https://github.com/kohana/auth) module for authentication
(instructions for getting this set up is outside of the scope of the Kalf documentation).
