<?php

/*
 *	UMS Tool - Uniform mark conversion tools
 *	Copyright (C) 2011  Philip Kent
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * 404 page template
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */

global $pagetitle;
$pagetitle = 'Error';

load_page('header');

?>
<a href="index.php">Index</a> &#8594;
<h1>Page not found</h1>
<p>The page you have requested does not exist. Please check that you spelt the address correctly, if you have it may be that you are trying to access details which no longer exist on this server.</p>
<a href="index.php">Return to the <?php echo UMSTOOL_NAME; ?> index</a>

<?php
load_page('footer');
