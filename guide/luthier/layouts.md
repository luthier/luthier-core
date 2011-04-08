# Luthier Layouts

All Luthier views are Kostache view classes using mustache templates. Luthier
provides an abstract layout view which is to be used as the base for all
Luthier views. This layout view provides the basic methods for generating the
main navigation (by auto-detecting Luthier controllers) and other common
template variables.

The Luthier layout view supports a two-tiered layout where a second mustache
layout may be used (as a partial) to further specify how a view should be
rendered (ie, a sub-layout). The commonly used sub-layouts are `narrow`,
`wide`, and `full`. Examples of these are best seen with the luthier-blog
extension and the davies theme. It is the discretion of the Luthier theme
to implement sub layout or to support even more sub layouts (see
[developing Luthier themes](developing-themes)).

Example application views using the different layouts:

~~~
/**
* View that uses the wide Luthier sub-layout to insert content
* into the current Luthier theme, using most of the horizontal space.
*/
class View_Luthier_Posts_List extends Luthier_Layout {
        protected $_sub_layout = “wide”;
        public function posts() { }
}

/**
* View that uses the full Luthier sub-layout to insert content
* into the current Luthier theme, using all of the horizontal space.
*/
class View_Luthier_Posts_Edit extends Luthier_Layout {
        protected $_sub_layout = “full”;
        public function post() { }
}

/**
* View that uses the narrow Luthier sub-layout to insert content
* into the current Luthier theme, using some of the horizontal space.
*/
class View_Luthier_Comments_Edit extends Luthier_Layout {
        protected $_sub_layout = “narrow”;
        public function comment() { }
}
~~~

Luthier-core & Kostache views:

~~~
/** For transparent extension */
abstract class Luthier_Layout extends Luthier_Layout_Core { }

/**
* Adds common methods used in the layout template.
* Sets the layout template to the Luthier layout mustache template.
* Prior to rendering, adds the layout type as a partial (“layout”).
*/
abstract class Luthier_Layout_Core extends Kostache_Layout {
        protected $_sub_layout = “full”;        // narrow, wide, or full
        protected $_layout = “luthier/layout”;
        public function main_navigation() { }
        public function render() {
                $this->_partials[‘layout’] = ‘luthier/layout/’.$this->_sub_layout;
                return parent::render();
        }
}

/**
* Prior to rendering, adds the current template as a partial (“current”),
* and sets the layout template as the rendered view template.
*/
abstract class Kostache_Layout {
        protected $_layout = “layout”;
}
~~~

Where the corresponding mustache templates could be (for example):

~~~
// luthier/users/list.mustache:

<ul>
    // ...
</ul>

// luthier/layout.mustache:

<html>
<head>...</head>
<body>
    {\{> layout}\}
</body>
</html>

// luthier/layout/wide.mustache:

<p class=”wide”>
    {\{> content}\}
</p>

// luthier/layout/narrow.mustache:

<p class=”narrow”>
    {\{> content}\}
</p>
~~~

