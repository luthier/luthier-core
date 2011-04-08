# Kalf Layouts

All Kalf views are Kostache view classes using mustache templates. Kalf
provides an abstract layout view which is to be used as the base for all
Kalf views. This layout view provides the basic methods for generating the
main navigation (by auto-detecting Kalf controllers) and other common
template variables.

The Kalf layout view supports a two-tiered layout where a second mustache
layout may be used (as a partial) to further specify how a view should be
rendered (ie, a sub-layout). The commonly used sub-layouts are `narrow`,
`wide`, and `full`. Examples of these are best seen with the kalf-blog
extension and the davies theme. It is the discretion of the Kalf theme
to implement sub layout or to support even more sub layouts (see
[developing Kalf themes](developing-themes)).

Example application views using the different layouts:

~~~
/**
* View that uses the wide Kalf sub-layout to insert content
* into the current Kalf theme, using most of the horizontal space.
*/
class View_Kalf_Posts_List extends Kalf_Layout {
        protected $_sub_layout = “wide”;
        public function posts() { }
}

/**
* View that uses the full Kalf sub-layout to insert content
* into the current Kalf theme, using all of the horizontal space.
*/
class View_Kalf_Posts_Edit extends Kalf_Layout {
        protected $_sub_layout = “full”;
        public function post() { }
}

/**
* View that uses the narrow Kalf sub-layout to insert content
* into the current Kalf theme, using some of the horizontal space.
*/
class View_Kalf_Comments_Edit extends Kalf_Layout {
        protected $_sub_layout = “narrow”;
        public function comment() { }
}
~~~

Kalf-core & Kostache views:

~~~
/** For transparent extension */
abstract class Kalf_Layout extends Kalf_Layout_Core { }

/**
* Adds common methods used in the layout template.
* Sets the layout template to the Kalf layout mustache template.
* Prior to rendering, adds the layout type as a partial (“layout”).
*/
abstract class Kalf_Layout_Core extends Kostache_Layout {
        protected $_sub_layout = “full”;        // narrow, wide, or full
        protected $_layout = “kalf/layout”;
        public function main_navigation() { }
        public function render() {
                $this->_partials[‘layout’] = ‘kalf/layout/’.$this->_sub_layout;
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
//kalf/users/list.mustache:

<ul>
    // ...
</ul>

// kalf/layout.mustache:

<html>
<head>...</head>
<body>
    {\{> layout}\}
</body>
</html>

// kalf/layout/wide.mustache:

<p class=”wide”>
    {\{> content}\}
</p>

// kalf/layout/narrow.mustache:

<p class=”narrow”>
    {\{> content}\}
</p>
~~~

