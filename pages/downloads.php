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
$pagetitle = $course->getName().' Downloads';

load_page('header');

?>
<a href="index.php">Index</a> &#8594; <a href="index.php?p=board&amp;key=<?php echo $course->getBoard()->getKey(); ?>"><?php echo $course->getBoard()->getName(); ?></a> &#8594; <a href="index.php?p=course&amp;key=<?php echo $course->getKey(); ?>"><?php echo $course->getName(); ?></a> &#8594;
<h1>Downloads</h1>
<p>Any download links for papers which have been recorded in the database are shown below.</p>
<?php
if(count($papers) == 0)
{
	echo '<div class="no_items">No papers exist for this course</div>';
}
else
{
	?>
<table class="nicetable fullwidth" cellpadding="0" cellspacing="0">
	<tr>
		<th>Session</th><th style="width: 100px">Question paper</th><th style="width: 100px">Mark scheme</th><th style="width: 100px">Report</th>
	</tr>
	<?php
	$totalcount = 0;
	
	foreach($papers as $paper)
	{
		$sessions = Session::getAllByPaper($paper->getID());
		$count = 0;
		foreach($sessions as $session)
		{
			if(!$session->hasQuestionPaper() && !$session->hasMarkScheme() && !$session->hasExaminersReport())
			{
				continue;
			}
			else
			{
				$count++;
			}
		}

		if ($count > 0)
		{
			$totalcount++;
			echo '<tr><td colspan="4"><b>'.$paper->getName().'</b></td></tr>';
		}

		foreach($sessions as $session)
		{
			if(!$session->hasQuestionPaper() && !$session->hasMarkScheme() && !$session->hasExaminersReport())
			{
				continue;
			}
			else
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
				
				if($session->hasQuestionPaper())
				{
					$qp = '<a href="'.$session->getQuestionPaper().'">Download</a>';
				}
				else
				{
					$qp = '<span class="no_items">-</span>';
				}
				
				if($session->hasMarkScheme())
				{
					$ms = '<a href="'.$session->getMarkScheme().'">Download</a>';
				}
				else
				{
					$ms = '<span class="no_items">-</span>';
				}
				
				if($session->hasExaminersReport())
				{
					$er = '<a href="'.$session->getExaminersReport().'">Download</a>';
				}
				else
				{
					$er = '<span class="no_items">-</span>';
				}
				
				echo '<tr><td>'.$month.' '.$session->getYear().'</td><td>'.$qp.'</td><td>'.$ms.'</td><td>'.$er.'</td></tr>';
			}
		}
	}
	if($totalcount == 0)
	{
		echo '<tr><td colspan="4"><span class="no_items">No downloads have been registered for this course</span></td></tr>';
	}
	?>
</table>
<br />
<a href="index.php?p=course&amp;key=<?php echo $course->getKey(); ?>">Return to <?php echo $course->getName(); ?></a>
	<?php
}

load_page('footer');