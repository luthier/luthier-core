# Creating Extension Documentation

All Kalf extensions should include userguide documentation, if remotely
applicable. Unless an extension’s documentation is large enough to require it’s
own section in the userguide, it should be included as part of the Kalf userguide
and be limited to one page. This will be dealt with on a case-by-case basis. All
documentation should follow the userguide guidelines for formatting and content.

To include pages with the Kalf userguide documentation, add a `kalf_userguide.php`
config file with the following contents:

    return array(
        ‘extensions’ => array(
            ‘extension-name’ => array(
                ‘text’ => ‘Short description of extension’,
                ‘file’ => ‘<file>’,
            ),
        ),
    );

Where `extension-name` is what will be displayed in the menu, along with `text`.
The file should be the markdown formatted documentation file under the
`guide/kalf/<extension>` folder. For example, if kalf-blog had a userguide page
under `MODPATH/kalf-blog/guide/kalf/extension/blog.md`, the config file would be:

    return array(
        ‘extensions’ => array(
            ‘blog’ => array(
                ‘text’ => ‘Blogging extension for article and comment management’,
                ‘file’ => ‘blog’,
            ),
        ),
    );

All source should be well commented, containing Kodoc comments for all classes,
variables, and methods. Each class within an extension should belong to the
`Kalf/<extension-name>` package (eg, Kalf/Blog, Kalf/Stats).
