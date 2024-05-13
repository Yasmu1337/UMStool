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
 * Paper list page (for a course) template
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */

if(!array_key_exists('key', $_GET))
{
	load_page('notfound');
	return;
}

try
{
	$course = Factory::manufactureCourse($_GET['key']);
	$papers = Paper::getAllByCourse($course->getID());
}
catch (Exception $e)
{
	load_page('notfound');
	return;
}

global $pagetitle;
$pagetitle = $course->getName();

load_page('header');

?>
<a href="index.php">Index</a> &#8594; <a href="index.php?p=board&amp;key=<?php echo $course->getBoard()->getKey(); ?>"><?php echo $course->getBoard()->getName(); ?></a> &#8594;
<h1><?php echo $course->getName(); ?></h1>
<p>Please select the paper you have sat to view the RAW to UMS conversion data and tools. Download links for past papers and examiners reports can be viewed by clicking the link below, but will also appear on each paper's page (if download links have been supplied).</p>
<ul>
	<li><a href="index.php?p=downloads&amp;key=<?php echo $course->getKey(); ?>">Download papers, mark schemes and reports</a></li>
</ul>
<?php
if(count($papers) == 0)
{
	echo '<div class="no_items">No papers exist for this course</div>';
}
else
{
	echo '<table class="colwrap"><tr><td class="left" style="padding-right: 5px;">';
	$changeat = ceil(count($papers) / 2);
	$i = 0;
	foreach($papers as $paper)
	{
		if ($i == $changeat)
		{
			$col = 1;
			echo '</td><td class="right">';
		}
		$i++;
		echo '<h2>'.$paper->getName().'</h2>';
		$sessions = Session::getAllByPaper($paper->getID());
		if(count($sessions) == 0)
		{
			echo '<div class="no_items">No sessions exist for this paper</div>';
		}
		else
		{
			echo '<ul>';
			foreach($sessions as $session)
			{
				switch($session->getMonth())
				{
					case 1;
						$month = 'Winter';
						break;
					case 2:
						$month = 'Spring';
						break;
					case 3:
						$month = 'Summer';
						break;
					case 4:
						$month = 'Autumn';
						break;
				}
				
				echo '<li><a href="index.php?p=paper&amp;key='.$paper->getKey().'&amp;year='.$session->getYear().'&amp;month='.$session->getMonth().'">'.$month.' '.$session->getYear().'</a></li>';
			}
			echo '</ul>';
		}
	}
	echo '</td></tr></table>';
}

load_page('footer');