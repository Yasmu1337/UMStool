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

if(!array_key_exists('key', $_GET) || !array_key_exists('year', $_GET) || !array_key_exists('month', $_GET))
{
	load_page('notfound');
	return;
}

try
{
	$paper = Factory::manufacturePaper($_GET['key']);
	$session = Session::getByPaperAndSession($paper->getID(), $_GET['year'], $_GET['month']);
}
catch (Exception $e)
{
	load_page('notfound');
	return;
}

global $pagetitle;
switch($session->getMonth())
{
	case Session::SESSION_AUTUMN:
		$monthname = 'Autumn';
		break;
	case Session::SESSION_WINTER:
		$monthname = 'Winter';
		break;
	case Session::SESSION_SPRING:
		$monthname = 'Spring';
		break;
	case Session::SESSION_SUMMER:
		$monthname = 'Summer';
		break;
}
$pagetitle = $paper->getName().': '.$monthname.' '.$session->getYear();

load_page('header');

?>
<a href="index.php">Index</a> &#8594; <a href="index.php?p=board&amp;key=<?php echo $paper->getCourse()->getBoard()->getKey(); ?>"><?php echo $paper->getCourse()->getBoard()->getName(); ?></a> &#8594; <a href="index.php?p=course&amp;key=<?php echo $paper->getCourse()->getKey(); ?>"><?php echo $paper->getCourse()->getName(); ?></a> &#8594; <b><?php echo $paper->getName(); ?></b> &#8594;
<h1><?php echo $monthname.' '.$session->getYear(); ?></h1>
<ul>
	<?php if($session->hasQuestionPaper()): ?><li><a href="<?php echo $session->getQuestionPaper(); ?>">Download paper</a></li><?php endif; ?>
	<?php if($session->hasMarkScheme()): ?><li><a href="<?php echo $session->getMarkScheme(); ?>">Download mark scheme</a></li><?php endif; ?>
	<?php if($session->hasExaminersReport()): ?><li><a href="<?php echo $session->getExaminersReport(); ?>">Download examiner's report</a></li><?php endif; ?>
</ul>
<h2>UMS Conversion graph</h2>
<img src="index.php?graph=true&amp;draw=session&amp;id=<?php echo $session->getID(); ?>" alt="" />
<p><a href="index.php?graph=true&amp;draw=session&amp;id=<?php echo $session->getID(); ?>&amp;size=large" target="_blank">View a larger version of this graph</a></p>
<h2>UMS Conversion table</h2>
<b>Maximum RAW mark:</b> <?php echo $paper->getMaxRaw(); ?> :: <b>Maximum UMS points:</b> <?php echo $paper->getMaxUms(); ?>
<table class="nicetable fullwidth" cellpadding="0" cellspacing="0">
	<tr><th>Grade</th><th style="width: 55px">(cap)</th><?php if ($session->getGradeStar() != ''): ?><th style="width: 55px">A*</th><?php endif; ?><th style="width: 55px">A</th><th style="width: 55px">B</th><th style="width: 55px">C</th><th style="width: 55px">D</th><th style="width: 55px">E</th><?php if($paper->getCourse()->getType() == Course::TYPE_GCSE): ?><th style="width: 55px">F</th><th style="width: 55px">G</th><?php else: ?><th style="width: 55px">N</th><?php endif; ?><th style="width: 55px">U</th></tr>
	<tr class="centered"><td><b>UMS points</b></td><td><?php echo $paper->getMaxUms(); ?></td><?php if ($session->getGradeStar() != ''): ?><td><?php echo ($paper->getMaxUms()*0.9); ?></td><?php endif; ?><td><?php echo ($paper->getMaxUms()*0.8); ?></td><td><?php echo ($paper->getMaxUms()*0.7); ?></td><td><?php echo ($paper->getMaxUms()*0.6); ?></td><td><?php echo ($paper->getMaxUms()*0.5); ?></td><td><?php echo ($paper->getMaxUms()*0.4); ?></td><?php if($paper->getCourse()->getType() == Course::TYPE_GCSE): ?><td><?php echo ($paper->getMaxUms()*0.3); ?></td><td><?php echo ($paper->getMaxUms()*0.2); ?></td><?php else: ?><td><?php echo ($paper->getMaxUms()*0.3); ?></td><?php endif; ?><td>0</td></tr>
	<tr class="centered"><td><b>RAW marks</b></td><td><?php echo $session->getCap(); ?></td><?php if ($session->getGradeStar() != ''): ?><td><?php echo $session->getGradeStar(); ?></td><?php endif; ?><td><?php echo $session->getGradeA(); ?></td><td><?php echo $session->getGradeB(); ?></td><td><?php echo $session->getGradeC(); ?></td><td><?php echo $session->getGradeD(); ?></td><td><?php echo $session->getGradeE(); ?></td><?php if($paper->getCourse()->getType() == Course::TYPE_GCSE): ?><td><?php echo $session->getGradeF(); ?></td><td><?php echo $session->getGradeG(); ?></td><?php else: ?><td><?php echo $session->getGradeN(); ?></td><?php endif; ?><td>0</td></tr>
</table>
<?php if ($session->getPaper()->getCourse()->getType() == Course::TYPE_ALEVEL): ?>
<br />
<i><?php if ($session->getGradeStar() != ''): ?>You can not be awarded a grade A* on an A2 paper, it is only recorded here for conversion purposes. <?php endif; ?>Grade N is not an awardable grade and is included here for conversion purposes only.</i>
<?php endif; ?>
<h2>Tools</h2>
<table cellpadding="5" cellspacing="0" class="fullwidth">
	<tr>
		<td style="width: 50%; vertical-align: top;" class="noheaderpadding">
			<h3>Convert from RAW to UMS points</h3>
			<form accept-charset="utf-8" action="index.php?ajax=true&amp;action=convert" onsubmit="runAjax('index.php?ajax=true&amp;action=convert', 'conversion_form_1', 'spinner_1', 'submit_button_1', 'response_box_1'); return false;" method="post" id="conversion_form_1">
			<input type="hidden" name="session" value="<?php echo $session->getID(); ?>" />
			<table>
				<tr>
					<td><b><label for="raw">RAW marks:</label></b></td>
					<td>
						<input name="raw" id="raw" value="0" maxlength="3" style="width: 50px;" onchange="if($('raw').getValue() == '') { $('submit_button_1').disable(); } else { $('submit_button_1').enable(); }" />
					</td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" id="submit_button_1" value="Convert" /><img src="style/spinner.gif" id="spinner_1" style="display: none;" alt="Please wait, working..." /></td>
				</tr>
			</table>
			</form>
			<div id="response_box_1" class="response_box" style="display: none;"></div>
		</td>
		<td style="width: 50%; vertical-align: top;" class="noheaderpadding">
			<h3>Convert from UMS to RAW marks</h3>
			<form accept-charset="utf-8" action="index.php?ajax=true&amp;action=convert&amp;mode=2" onsubmit="runAjax('index.php?ajax=true&amp;action=convert&amp;mode=2', 'conversion_form_2', 'spinner_2', 'submit_button_2', 'response_box_2'); return false;" method="post" id="conversion_form_2">
			<input type="hidden" name="session" value="<?php echo $session->getID(); ?>" />
			<table>
				<tr>
					<td><b><label for="ums">UMS points:</label></b></td>
					<td>
						<input name="ums" id="ums" value="0" maxlength="3" style="width: 50px;" onchange="if($('raw').getValue() == '') { $('submit_button_1').disable(); } else { $('submit_button_2').enable(); }" />
					</td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" id="submit_button_2" value="Convert" /><img src="style/spinner.gif" id="spinner_2" style="display: none;" alt="Please wait, working..." /></td>
				</tr>
			</table>
			</form>
			<div id="response_box_2" class="response_box" style="display: none;"></div>
		</td>
	</tr>
</table>
<?php

load_page('footer');