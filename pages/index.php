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
 * Index page template
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */

$boards = Board::getAll();
global $pagetitle;
$pagetitle = 'Index';
load_page('header');

?>
<h1>Welcome to <?php echo UMSTOOL_NAME; ?></h1>
<p><i><b>Please note that this tool is not endorsed by any organization involved in the examination systems in which this tool is compatible with. Information on this tool should be taken as advisory and may be innacurate, this should not be used to make life-changing decisions. <u>The authors will not be held responsible for the outcome of any decisions made on the basis of this tool.</u></b></i></p>

<p>To begin, choose the exam board for the course you are studying. This will bring up a list of courses for this board which have been registered into the system, from which you can choose the paper you have sat and thus find out your score.</p>

<?php

if(count($boards) == 0)
{
	echo '<div class="no_items">No boards exist</div>';
}
else
{
	echo '<table class="buttontable" cellspacing="10">';
	$i = 0;
	foreach($boards as $board)
	{
		if ($i == 0)
		{
			echo '<tr>';
			echo '<td><a href="index.php?p=board&amp;key='.$board->getKey().'">'.$board->getName().'</a></td>';
		}
		elseif ($i == 2)
		{
			$i = 0;
			echo '<td><a href="index.php?p=board&amp;key='.$board->getKey().'">'.$board->getName().'</a></td>';
			echo '</tr>';
		}
		else
		{
			echo '<td><a href="index.php?p=board&amp;key='.$board->getKey().'">'.$board->getName().'</a></td>';
		}
		$i++;
	}
	echo '</table>';
}

?>
<h2>Grade calculators</h2>
<p>You can also use <?php echo UMSTOOL_NAME; ?> to calculate what grade you would attain in a full examination, based on the UMS points you have gained. Please choose the calculator which is appropriate to what you are studying.</p>
<table class="buttontable" cellspacing="10">
	<tr><td><a href="index.php?p=calculator&amp;target=gce">A Level</a></td></tr>
</table>
<?php

load_page('footer');