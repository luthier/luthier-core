<?php defined('SYSPATH') or die('No direct script access.');

foreach (Kohana::config('luthier.userguide') as $extension)
{
	if ($extension['enabled'])
	{
		$link = Route::url('docs/guide',
			array('module'=>'luthier', 'page'=>'extension/'.$extension['file'])
		);

		echo '<p>'.PHP_EOL;
		echo '<strong><a href="'.$link.'">'.$extension['name'].'</a></strong> - ';
		echo $extension['description'].PHP_EOL;
		echo '</p>'.PHP_EOL;
	}
}
