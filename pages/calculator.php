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
 * Calculator page template
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */
global $pagetitle;
$pagetitle = 'Grade calculator';
load_page('header');

?>
<a href="index.php">Index</a> &#8594;
<h1>Grade calculator</h1>
<?php
if(!array_key_exists('target', $_GET) || $_GET['target'] !== 'gce')
{
	echo '<b>Error:</b> No calculator support is implemented for this target yet, or no target specified.';
	load_page('footer');
	return;
}

?>
<p>You can use this page to calculate what grade you will achieve at GCE A-Level using the UMS points calculated using this tool. It will also tell you how close you are to achieving the grade you want.</p>
<p>If you do not want to input details of a paper (e.g. if you want to only look at AS grades, or if you want to find out how many points you need in a final paper to attain the grade you want), place a 0 in any boxes which do not apply.</p>
<form accept-charset="utf-8" action="index.php?ajax=true&amp;action=calculator" onsubmit="runAjax('index.php?ajax=true&amp;action=calculator', 'calculator_form', 'spinner', 'submit_button', 'response_box'); return false;" method="post" id="calculator_form">
<table>
	<tr>
		<td><b><label for="qual_type">Qualification type:</label></b></td>
		<td>
			<select name="qual_type" id="qual_type" onchange="if($('qual_type').getValue() == '4 unit specification (400 UMS max)') { $('unit_3').disable(); $('unit_6').disable(); } else { $('unit_3').enable(); $('unit_6').enable(); }">
				<option value="4unit" selected="selected">4 unit specification (400 UMS max)</option>
				<option value="6unit">6 unit specification (600 UMS max)</option>
				<option value="maths">Mathematics 6 unit specification (600 UMS max)</option>
			</select>
		</td>
	</tr>
	<tr>
		<td><b>AS UMS points:</b></td>
		<td>
			<table>
				<tr>
					<td><b><label for="unit_1">Unit 1</label></b></td><td><b><label for="unit_2">Unit 2</label></b></td><td><b><label for="unit_3">Unit 3</label></b></td>
				</tr>
				<tr>
					<td><input type="text" name="unit_1" id="unit_1" value="0" maxlength="3" style="width: 50px;" /></td><td><input type="text" name="unit_2" id="unit_2" value="0" maxlength="3" style="width: 50px;" /></td><td><input type="text" name="unit_3" id="unit_3" value="0" maxlength="3" disabled="disabled" style="width: 50px;" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><b>A2 UMS points:</b></td>
		<td>
			<table>
				<tr>
					<td><b><label for="unit_4">Unit 4</label></b></td><td><b><label for="unit_5">Unit 5</label></b></td><td><b><label for="unit_6">Unit 6</label></b></td>
				</tr>
				<tr>
					<td><input type="text" name="unit_4" id="unit_4" value="0" maxlength="3" style="width: 50px;" /></td><td><input type="text" name="unit_5" id="unit_5" value="0" maxlength="3" style="width: 50px;" /></td><td><input type="text" name="unit_6" id="unit_6" value="0" maxlength="3" disabled="disabled" style="width: 50px;" /></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td></td>
		<td><input type="submit" id="submit_button" value="Calculate" /><img src="style/spinner.gif" id="spinner" style="display: none;" alt="Please wait, working..." /></td>
	</tr>
</table>
</form>

<div id="response_box" class="response_box" style="display: none;"></div>

<?php

load_page('footer');
