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
 * Course list page (for a board) template
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */

if(!array_key_exists('key', $_GET))
{
	load_page('header');
	load_page('notfound');
	return;
}

try
{
	$board = Factory::manufactureBoard($_GET['key']);
	$gcsecourses = Course::getAllByTypeAndBoard(Course::TYPE_GCSE, $board->getID());
	$alevelcourses = Course::getAllByTypeAndBoard(Course::TYPE_ALEVEL, $board->getID());
}
catch (Exception $e)
{
	load_page('header');
	load_page('notfound');
	return;
}

global $pagetitle;
$pagetitle = $board->getName();

load_page('header');

?>
<a href="index.php">Index</a> &#8594; 
<h1><?php echo $board->getName(); ?></h1>
<p>The following courses have been registed in this system for this exam board. If the course you are studying is not listed here, please contact whoever manages this tool. Please select the relevant course, and you can then select what paper you have sat.</p>

<h2>GCE A-Level</h2>
<?php

if(count($alevelcourses) == 0)
{
	echo '<div class="no_items">No courses exist for this level</div>';
}
else
{
	echo '<table class="buttontable" cellspacing="10">';
	$i = 0;
	foreach($alevelcourses as $course)
	{
		if ($i == 0)
		{
			echo '<tr>';
			echo '<td><a href="index.php?p=course&amp;key='.$course->getKey().'">'.$course->getName().'</a></td>';
		}
		elseif ($i == 2)
		{
			$i = 0;
			echo '<td><a href="index.php?p=course&amp;key='.$course->getKey().'">'.$course->getName().'</a></td>';
			echo '</tr>';
		}
		else
		{
			echo '<td><a href="index.php?p=course&amp;key='.$course->getKey().'">'.$course->getName().'</a></td>';
		}
		$i++;
	}
	echo '</table>';
}

?>

<h2>GCSE</h2>
<?php

if(count($gcsecourses) == 0)
{
	echo '<div class="no_items">No courses exist for this level</div>';
}
else
{
	echo '<table class="buttontable" cellspacing="10">';
	$i = 0;
	foreach($gcsecourses as $course)
	{
		if ($i == 0)
		{
			echo '<tr>';
			echo '<td><a href="index.php?p=course&amp;key='.$course->getKey().'">'.$course->getName().'</a></td>';
		}
		elseif ($i == 2)
		{
			$i = 0;
			echo '<td><a href="index.php?p=course&amp;key='.$course->getKey().'">'.$course->getName().'</a></td>';
			echo '</tr>';
		}
		else
		{
			echo '<td><a href="index.php?p=course&amp;key='.$course->getKey().'">'.$course->getName().'</a></td>';
		}
		$i++;
	}
	echo '</table>';
}

load_page('footer');