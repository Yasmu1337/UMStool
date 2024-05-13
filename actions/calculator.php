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
 * Grade calculator
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */

$spec = $_POST['qual_type'];
switch ($spec)
{
	case '4unit':
		$maxums = 400;
		$asums = 200;
		$maths = false;
		break;
	case '6unit':
		$maxums = 600;
		$asums = 300;
		$maths = false;
		break;
	case 'maths':
		$maxums = 600;
		$asums = 300;
		$maths = true;
		break;
	default:
		header('HTTP/1.0 500 Internal Server Error');
		return;
		break;
}

// UMS boundaries
$a = $maxums*0.8;
$b = $maxums*0.7;
$c = $maxums*0.6;
$d = $maxums*0.5;
$e = $maxums*0.4;

$asa = $asums*0.8;
$asb = $asums*0.7;
$asc = $asums*0.6;
$asd = $asums*0.5;
$ase = $asums*0.4;

// ONLY FOR NON-MATHS
$star = $asums*0.9; // AS ums is always the same as A2 ums

$smstar = false;
$fmstar = false;

if ($maxums == 600) { $a2only = $_POST['unit_4'] + $_POST['unit_5'] + $_POST['unit_6']; }
if ($maxums != 600) { $a2only = $_POST['unit_4'] + $_POST['unit_5']; }

if($maxums == 600)
{
	$asums = $_POST['unit_1'] + $_POST['unit_2'] + $_POST['unit_3'];
	$a2ums = $asums + $_POST['unit_4'] + $_POST['unit_5'] + $_POST['unit_6'];
}
else
{
	$asums = $_POST['unit_1'] + $_POST['unit_2'];
	$a2ums = $asums + $_POST['unit_4'] + $_POST['unit_5'];
}

if ($maths === true)
{
	// further maths, highest 3
	$modules = array($_POST['unit_2'], $_POST['unit_3'], $_POST['unit_4'], $_POST['unit_5'], $_POST['unit_6']);
	rsort($modules);
	$applicable = $modules[0] + $modules[1] + $modules[2];
	
	// normal maths - assume c3 and c4 to be units 4 and 5 in either order
	$single = $_POST['unit_4'] + $_POST['unit_5'];
}

// find as grade
$asgrade = 'U';
if($asums >= $ase) { $asgrade = 'E'; }
if($asums >= $asd) { $asgrade = 'D'; }
if($asums >= $asc) { $asgrade = 'C'; }
if($asums >= $asb) { $asgrade = 'B'; }
if($asums >= $asa) { $asgrade = 'A'; }

// find a2 grade
$a2grade = 'U';
if($a2ums >= $e) { $a2grade = 'E'; }
if($a2ums >= $d) { $a2grade = 'D'; }
if($a2ums >= $c) { $a2grade = 'C'; }
if($a2ums >= $b) { $a2grade = 'B'; }
if($a2ums >= $a) { $a2grade = 'A'; }

// handle A* grade
if ($asgrade == 'A' && $maths == false)
{
	// we need an A at AS to get an A* at A2
	if($a2only >= $star) { $a2grade = 'A*'; }
}
elseif ($maths == true && $a2ums >= 480)
{
	if($single >= 180) { $smstar = true; }
	if($applicable >= 270) { $fmstar = true; }
}

?>
<h2>Results</h2>
<p>From the data you have entered, you have achieved a <b>grade <?php echo $a2grade; ?></b> at A2-level and a <b>grade <?php echo $asgrade; ?></b> at AS-level.</p>
<h3>AS results</h3>
<p>You have achieved <b><?php echo $asums; ?> out of <?php echo $maxums / 2; ?></b> UMS points for AS. The table below shows you how many more UMS points you need to achieve the following grades:</p>
<table class="nicetable" cellpadding="0" cellspacing="0">
	<tr><th>Grade</th><th>A</th><th>B</th><th>C</th><th>D</th><th>E</th></tr>
	<tr><td><b>Points needed</b></td><td><?php if($asums >= $asa) { echo 0; } else { echo $asa - $asums; } ?></td><td><?php if($asums >= $asb) { echo 0; } else { echo $asb - $asums; } ?></td><td><?php if($asums >= $asc) { echo 0; } else { echo $asc - $asums; } ?></td><td><?php if($asums >= $asd) { echo 0; } else { echo $asd - $asums; } ?></td><td><?php if($asums >= $ase) { echo 0; } else { echo $ase - $asums; } ?></td></tr>
</table>
<br />
<h3>Overall A-Level results</h3>
<p>You have achieved <b><?php echo $a2ums; ?> out of <?php echo $maxums; ?></b> UMS points for the full A-Level. The table below shows you how many more UMS points you need to achieve the following grades:</p>
<table class="nicetable" cellpadding="0" cellspacing="0">
	<tr><th>Grade</th><th>A</th><th>B</th><th>C</th><th>D</th><th>E</th></tr>
	<tr><td><b>Points needed</b></td><td><?php if($a2ums >= $a) { echo 0; } else { echo $a - $a2ums; } ?></td><td><?php if($a2ums >= $b) { echo 0; } else { echo $b - $a2ums; } ?></td><td><?php if($a2ums >= $c) { echo 0; } else { echo $c - $a2ums; } ?></td><td><?php if($a2ums >= $d) { echo 0; } else { echo $d - $a2ums; } ?></td><td><?php if($a2ums >= $e) { echo 0; } else { echo $e - $a2ums; } ?></td></tr>
</table>
<br />
<h3>Grade A*</h3>
<?php if($maths == false && $a2grade == 'A*'): ?>
<p>You have achieved a grade A*, no further action is needed. However, for reference, to achieve an A* you require:</p>
<ul>
	<li>A grade A overall</li>
	<li><?php echo (($maxums/2)*0.9); ?> UMS points on A2 modules (you have <b><?php echo $a2only; ?></b> points)</li>
</ul>
<?php elseif($maths == false): ?>
<p>Unfortunately you did not attain a grade A*. This is because:</p>
<ul>
	<?php if($a2grade != 'A'): ?>
	<li>You did not attain a grade A at A-level</li>
	<?php endif; ?>
	<?php if($a2only < $star): ?>
	<li>You did not achieve the required number of UMS points at A2 level to achieve an A*. <b>You are missing <?php echo($star - $a2only); ?> points on A2 modules</b></li>
	<?php endif; ?>
</ul>	
<?php elseif($maths == true): ?>
<p>The rules for achieving an A* at Mathematics depends on whether you are working towards an A-Level in Mathematics or Further Mathematics (including Additional).</p>
<h4>Mathematics</h4>
<p>To achieve an A* at Mathematics, you need to achieve a grade A overall, and achieve 180 UMS in modules C3 and C4. <b>Assuming modules C3 and C4 have been entered into Units 4 and 5,</b> we can see that:</p>
<ul>
	<?php if($a2grade != 'A'): ?>
	<li>You have not attained a grade A at A-level</li>
	<?php else: ?>
	<li>You have attained a grade A at A-level</li>
	<?php endif; ?>
	<?php if($single < 180): ?>
	<li>You have not achieved the required number of UMS points on modules C3 and C4 to achieve an A*. <b>You are missing <?php echo 180 - $single; ?> points.</b></li>
	<?php else: ?>
	<li>You have achieved enough points over modules C3 and C4 to achieve an A*. (You have <b><?php echo $single; ?></b> points)</li>
	<?php endif; ?>
</ul>
<p>Therefore, <b><u>if this is a mathematics qualification</u>, you have <?php if($smstar == false): ?>not <?php endif; ?>achieved a grade A*</b> at normal mathematics.</p>
<h4>Further Mathematics and Further Mathematics (Additional)</h4>
<p>To achieve an A* at Further Mathematics, you need to achieve a grade A overall, and achieve 270 UMS in your three best modules <b>except FP1, M1, S1 and D1</b>. As FP1 is not used in this calculation, <b>your value for Unit 1 has been ignored</b>. From your data, we can see that:</p>
<ul>
	<?php if($a2grade != 'A'): ?>
	<li>You have not attained a grade A at A-level</li>
	<?php else: ?>
	<li>You have attained a grade A at A-level</li>
	<?php endif; ?>
	<?php if($applicable < 270): ?>
	<li>You have not achieved the required number of UMS points on your three best modules to achieve an A*. <b>You are missing <?php echo 270 - $applicable; ?> points.</b></li>
	<?php else: ?>
	<li>You have achieved enough points over your best three modules to achieve an A*. (You have <b><?php echo $applicable; ?></b> points over your best three)</li>
	<?php endif; ?>
</ul>
<i>Please note that the top three module calculation will be inaccurate if one of your best performing modules are M1, S1 or D1, as these are not allowed in this calculation. If this is the case, you should manually calculate the total of the points on your best three A2 units, and see if this is greater or equal to 270 points.</i>
<p>Therefore, <b><u>if this is a Further Mathematics qualification</u>, you have <?php if($fmstar == false): ?>not <?php endif; ?>achieved a grade A*</b> at Further Mathematics or Further Mathematics (Additional).</p>
<?php endif; ?>
<a href="javascript:void(0)" onClick="Effect.Fade('response_box', { duration: 0.5 });">Close</a>