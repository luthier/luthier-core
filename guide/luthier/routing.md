# Controllers

While Luthier does not require controllers to follow any conventions, there
are some benefits to following the Luthier controller naming convention. A
Luthier controller is one which resides in `classes/controller/luthier/<directory>`,
and is named `Controller_Luthier_<directory>_<controller>`. Following this
naming/file pattern allows for:

 - Inclusion in the Luthier view’s automatic [navigation](navigation)
 - Use of the primary Luthier route (see below)

# Routing

The following routes are defined in luthier-core’s `init.php` file and are
referred to as the standard Luthier routes. All of the standard Luthier routes
use a variable namespace, which defaults to ‘admin’. This can be changed
by extending `Luthier_Core`. Thus, the entire adminstrative application could
reside under `domain.com/admin` or `domain.com/cp`.

Route name    | Matching URL                                                 | Use
------------- | ------------------------------------------------------------ | --------------------
luthier/media | &lt;ns&gt;/media/&lt;file&gt;                                | Media file route
luthier/auth  | &lt;ns&gt;&lt;login\|logout&gt;                              | Authentication route
luthier       | &lt;ns&gt;(/&lt;directory(/&lt;controller(/&lt;action&gt;))) | Primary Luthier route

[!!] It is encouraged that Luthier extension developers support the variable namespace.

## Media Route

The media route returns a file found in `MODPATH/.../media`.

Examples:

    Route::get(‘luthier/media’)->uri(array(‘file’ => ‘luthier/css/theme.css’));
    Route::get(‘luthier/media’)->uri(array(‘file’ => ‘luthier/js/action.js’));

Given URL                              | Returns:
-------------------------------------- | --------------------------------------------------
&lt;ns&gt;/media/luthier/css/theme.css | The file `MODPATH/.../media/luthier/css/theme.css`
&lt;ns&gt;/media/luthier/js/action.js  | The file `MODPATH/.../media/luthier/js/action.js`

## Auth Route

The auth route provides access to the login and logout page.

Examples:

    Route::get(‘luthier/auth’)->uri(); // action defaults to login
    Route::get(‘luthier/auth’)->uri(array(‘action’ => ‘logout’));

Give URL                     | Routes to:
---------------------------- | ------------------------------------
&lt;ns&gt;/login             | `Controller_Luthier_Auth::login`
&lt;ns&gt;/logout            | `Controller_Luthier_Auth::logout`

## Primary Luthier Route

The primary Luthier route provides aliased access to Luthier controllers, via a
lambda function, to route the request to the correct controller, regardless
of namespace in use. Essentially, it prepends the directory parameter with
`luthier/` so that the routed controller is `Controller_Luthier_<directory>_<controller>::<action>`,
with directory defaulting to be blank (before prepending), controller defaulting to `home`,
and action defaulting to ‘index’.

Examples:

    Route::get(‘luthier’)->uri();
    Route::get(‘luthier’)->uri(array(‘directory’ => ‘blog’));
    Route::get(‘luthier’)->uri(array(‘directory’ => ‘blog’, ‘controller’ => ‘articles’));
    Route::get(‘luthier’)->uri(array(‘directory’ => ‘blog’, ‘controller’ => ‘articles’, ‘action’ => ‘new’));

Given URL                    | Routes to:
---------------------------- | ------------------------------------
&lt;ns&gt;                   | `Controller_Luthier_Home::index`
&lt;ns&gt;/blog              | `Controller_Luthier_Blog_Home::index`
&lt;ns&gt;/blog/articles     | `Controller_Luthier_Blog_Articles::index`
&lt;ns&gt;/blog/articles/new | `Controller_Luthier_Blog_Articles::new`

If a requested controller does not exist for the requested directory,
then an attempt is made with the requested controller treated as the
action parameter, essentially fowarding actions to the home controller.
This allows for cleaner access to home controller actions. When using
reverse routing for a home controller action, the directory parameter should be omitted.

    Route::get(‘luthier’)->uri(array(‘controller’ => ‘users’));
    Route::get(‘luthier’)->uri(array(‘controller’ => ‘users’, ‘action’ => ‘new’));

    // although this would produce the same route
    Route::get(‘luthier’)->uri(array(‘directory’ => ‘users’, ‘controller’ => ‘new’));

Given URL                 | Routes to:
------------------------- | ------------------------------------
&lt;ns&gt;/users          | `Controller_Luthier_Users_Home::index`
&lt;ns&gt;/users/new      | `Controller_Luthier_Users_Home::new`

[!!] Note that the forwarding will not work if an action in the home controller
matches one of the other controllers of the directory.

Given URL                    | Routes to:
---------------------------- | ------------------------------------
&lt;ns&gt;/users/admins      | `Controller_Luthier_Users_Admins::index`
&lt;ns&gt;/users/home/admins | `Controller_Luthier_Users_Home::admins`

