# Controllers

While Kalf does not require controllers to follow any conventions, there
are some benefits to following the Kalf controller naming convention. A
Kalf controller is one which resides in `classes/controller/kalf/<directory>`,
and is named `Controller_Kalf_<directory>_<controller>`. Following this
naming/file pattern allows for:

 - Inclusion in the Kalf view’s automatic [navigation](navigation)
 - Use of the primary Kalf route (see below)

# Routing

The following routes are defined in kalf-core’s `init.php` file and are
referred to as the standard Kalf routes. All of the standard Kalf routes
use a variable namespace, which defaults to ‘admin’. This can be changed
by extending `Kalf_Core`. Thus, the entire adminstrative application could
reside under `domain.com/admin` or `domain.com/cp`.

Route name | Matching URL                                                 | Use
---------- | ------------------------------------------------------------ | --------------------
kalf/media | &lt;ns&gt;/media/&lt;file&gt;                                | Media file route
kalf/auth  | &lt;ns&gt;&lt;login\|logout&gt;                              | Authentication route
kalf       | &lt;ns&gt;(/&lt;directory(/&lt;controller(/&lt;action&gt;))) | Primary Kalf route

[!!] It is encouraged that Kalf extension developers support the variable namespace.

## Media Route

The media route returns a file found in `MODPATH/.../media`.

Examples:

    Route::get(‘kalf/media’)->uri(array(‘file’ => ‘kalf/css/theme.css’));
    Route::get(‘kalf/media’)->uri(array(‘file’ => ‘kalf/js/action.js’));

Given URL                           | Returns:
----------------------------------- | ------------------------------------------
&lt;ns&gt;/media/kalf/css/theme.css | The file `MODPATH/.../media/kalf/css/theme.css`
&lt;ns&gt;/media/kalf/js/action.js  | The file `MODPATH/.../media/kalf/js/action.js`

## Auth Route

The auth route provides access to the login and logout page.

Examples:

    Route::get(‘kalf/auth’)->uri(); // action defaults to login
    Route::get(‘kalf/auth’)->uri(array(‘action’ => ‘logout’));

Give URL                     | Routes to:
---------------------------- | ------------------------------------
&lt;ns&gt;/login             | `Controller_Kalf_Auth::login`
&lt;ns&gt;/logout            | `Controller_Kalf_Auth::logout`

## Primary Kalf Route

The primary Kalf route provides aliased access to Kalf controllers, via a
lambda function, to route the request to the correct controller, regardless
of namespace in use. Essentially, it prepends the directory parameter with
`kalf/` so that the routed controller is `Controller_Kalf_<directory>_<controller>::<action>`,
with directory defaulting to be blank (before prepending), controller defaulting to `home`,
and action defaulting to ‘index’.

Examples:

    Route::get(‘kalf’)->uri();
    Route::get(‘kalf’)->uri(array(‘directory’ => ‘blog’));
    Route::get(‘kalf’)->uri(array(‘directory’ => ‘blog’, ‘controller’ => ‘articles’));
    Route::get(‘kalf’)->uri(array(‘directory’ => ‘blog’, ‘controller’ => ‘articles’, ‘action’ => ‘new’));

Given URL                    | Routes to:
---------------------------- | ------------------------------------
&lt;ns&gt;                   | `Controller_Kalf_Home::index`
&lt;ns&gt;/blog              | `Controller_Kalf_Blog_Home::index`
&lt;ns&gt;/blog/articles     | `Controller_Kalf_Blog_Articles::index`
&lt;ns&gt;/blog/articles/new | `Controller_Kalf_Blog_Articles::new`

If a requested controller does not exist for the requested directory,
then an attempt is made with the requested controller treated as the
action parameter, essentially fowarding actions to the home controller.
This allows for cleaner access to home controller actions. When using
reverse routing for a home controller action, the directory parameter should be omitted.

    Route::get(‘kalf’)->uri(array(‘controller’ => ‘users’));
    Route::get(‘kalf’)->uri(array(‘controller’ => ‘users’, ‘action’ => ‘new’));

    // although this would produce the same route
    Route::get(‘kalf’)->uri(array(‘directory’ => ‘users’, ‘controller’ => ‘new’));

Given URL                 | Routes to:
------------------------- | ------------------------------------
&lt;ns&gt;/users          | `Controller_Kalf_Users_Home::index`
&lt;ns&gt;/users/new      | `Controller_Kalf_Users_Home::new`

[!!] Note that the forwarding will not work if an action in the home controller
matches one of the other controllers of the directory.

Given URL                    | Routes to:
---------------------------- | ------------------------------------
&lt;ns&gt;/users/admins      | `Controller_Kalf_Users_Admins::index`
&lt;ns&gt;/users/home/admins | `Controller_Kalf_Users_Home::admins`

