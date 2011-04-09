[!!] Contributions are welcome in any shape or form! :)

# Issue Reporting

Report all issues in the issues tab of the respective extension/git
repository. Mark each issue according to the type of issue (bug,
security vulnerability, documentation-related, feature request).

# Git

All source is kept under git version control and will be hosted at
github.com. A Luthier organization has been created to host all “official”
Luthier extensions (see submitting a module).
[Vincent Driessen's git branching model](http://nvie.com/posts/a-successful-git-branching-model)
will be used for all development and releases.

# Collaborating

Anyone can fork any of the Luthier repositories and submit pull requests.
All code is subject to requirements compliance as described below prior to merging.

Collaborators will be added to the github organization on a case-by-case basis.

# Submitting a Module

If you’ve developed a Luthier extension from which others might benefit,
let us know so we can include it as an “official” extension! “Official”
extensions will be hosted under the Luthier organization on github.com and
included in the demo app (if applicable/possible). Inclusion as an “official”
extension means that an extension meets the requirements described below.
If any of these requirements are not met, the extension will not be included
until it conforms to the requirements.

To submit an extension for inclusion, raise an issue in the [demo app](https://github.com/luthier/luthier-demo)
as a Feature Request, providing a link to the extension and specifying whether
you will be able to provide support for the extension (don’t worry, it isn’t a
lifelong binding contract).

Instructions for integrating an extension with Luthier are documented in the userguide docs.

## Code/Extension Requirements

## Functionality

If a submitted extension provides similar (but different in some way) functionality
as another extension, a valid comparison of the two extensions must be included in
the extension documentation to assist developers in choosing between the two extensions.

## Modularity

One of Luthier’s goals is to be extremely modular. This means that if functionality may
be used in several extensions--it should be its own extension (e.g. luthier-comments),
or if it could be exchangeable--it should be its own extension (e.g. luthier-markitup).

## Versioning

Each Luthier extension should include a version number. Where to specify this is TBD

 - Define a global onstant? --kind of icky.
 - Static variable of a class? --not all extensions will have classes.
 - Separate version file? --extra work.
 - Only in core module and plugins? --no way to define version dependencies between extensions.
 -  Only in readme? --no programmatic access.

## Coding Standards

All code MUST follow the Kohana coding conventions. All code will be checked using
PHP Codesniffer prior to inclusion or merging.

## Naming Standards

Luthier modules and repositories will be prefixed with “luthier-” (such as luthier-blog,
luthier-comments, etc.). Luthier theme extensions will be prefixed with “luthier-theme-”
(such as luthier-theme-davies). Luthier plugin extensions (if/when any are developed)
will be prefixed with “luthier-plugin-”.

## Media Files

A media route is provided by the luthier-core module. All media files related to an
extension should be placed in the `media/<extension-name>` folder of the extension module.

For example:

 - `MODPATH/luthier-theme-davies/media/luthier-theme-davies/css/template.css`
 - `MODPATH/luthier-markitup/media/luthier-markitup/js/jquery.markitup.js`

Media files can be access through the `luthier/media` route.

    Route::get(‘luthier/media’)->uri(array(‘file’ => ‘luthier-markitup/js/jquery.markitup.js’));

## Testing

The goal is for every Luthier extension to be fully tested. While 100% code coverage
is not always possible (nor is that the goal), code coverage is a valuable analysis
tool to identify code that is not being tested. Following the “one assertion per test”
philosophy is not required. Rather, unit tests should be written in such a way that they
test only one “specification” (so there should only be a few assertions per test).

Unit tests are written using PHPUnit. Future efforts may include creating behavioral
tests. Each Luthier extension with unit tests should provide a phpunit.xml file in the
module root so that unit tests can be run from the command line (i.e. typing ‘phpunit’
from the module root). If a unit test fails, please write up an issue in the appropriate
extension so it can be addressed.

[!!] The unittest module changed a little in 3.1 which makes this impractical. revisit.

## Documentation

The goal for documentation is to provide exhaustive, well-written documentation for "users"
(userguide docs) and developers (the api browser)

 - All classes, methods, and variables MUST be documented appropriately with Kodoc comments.
 - Usage documentation should be provided via the official userguide (if appropriate).
   See [adding userguide documentation](documentation).
 - A readme file must be written using the appropriate [extension readme template](extension-readme)
   or [theme readme template](theme-readme).

