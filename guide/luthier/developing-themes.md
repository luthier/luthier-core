# Developing Themes for Luthier

Since Luthier uses Kostache and mustache templates, it is easy to implement various
themes for Luthier by providing replacement template files. Themes are not required
to support sub-layouts (ie, if it does not make sense to have sub-layouts), but
if sub-layouts are supported, themes should support at least the standard set of
sub-layouts (wide, narrow, full, as well as any additional sub-layouts added by
the theme). See [understanding the layout views](layouts) for more information
on this process.

To develop a Luthier theme...

1. Create a theme with HTML, CSS, and any necessary JavaScript or images (obviously).
    - Store all media files (css, JavaScript, images, etc.) under
       `MODPATH/luthier-theme-<name-of-theme>/media/<name-of-theme>`.
    - All media files will be accessible through Luthier’s media controller
        - `Route::get(‘luthier/media’)->uri(array(‘file’ => ‘<name-of-theme>/css/file’));`
        - `<... href=”admin/media/<name-of-theme>/css/file”>`
1. Create a base layout mustache template that contains all common HTML elements,
   replacing `MODPATH/luthier-theme-<name-of-theme>/templates/luthier/layout.mustache`.
1. Create any necessary sub-layout mustache templates.
    1. Create sub-layout mustache templates under
       `MODPATH/luthier-theme-<name-of-theme>/templates/luthier/layout/<type>.mustache`,
       including the `content` partial.
    1. Include the `layout` partial in the layout template.
    1. If no sub-layouts are being supported, include the `content` partial in the layout template.
1. [Optional] Replace the `Luthier_Layout` class, extending `Luthier_Layout_Core` and
   implementing any additional methods necessary for the base layout view.
1. [Optional] Replace any of the `templates/luthier/<form/list/confirm>.mustache`
   templates as needed to integrate the common views into the theme.
1. [Optional] Replace any of the `View_Luthier_<form/list/confirm/etc>` classes,
   extending their appropriate core class and implementing any additional methods
   necessary to support integration into the theme.
1. [Required] Create a readme file according to the [theme readme template](theme-readme).
1. Create a feature request in the [demo app](https://github.com/luthier/luthier-demo)
   and submit the theme extension for inclusion.

