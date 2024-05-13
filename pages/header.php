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
 * Overall header
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */

global $pagetitle;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" type="text/css" href="style/style.css" media="screen,print" />
		<script src="js/prototype.js" type="text/javascript"></script>
		<script src="js/scriptaculous.js" type="text/javascript"></script>
		<script src="js/ajax.js" type="text/javascript"></script>
		<link href='http://fonts.googleapis.com/css?family=Ubuntu:light,lightitalic,regular,italic,500,500italic,bold,bolditalic' rel='stylesheet' type='text/css' />
		<title><?php echo $pagetitle; ?> - <?php echo UMSTOOL_NAME; ?></title>
	</head>
	<body>
		<div id="header">
			<img src="style/logo.png" alt="" /> <?php echo UMSTOOL_NAME; ?>
			<div id="header_links">
				<?php
					$boards = Board::getAll();
					foreach ($boards as $board)
					{
						echo '<a href="index.php?p=board&amp;key='.$board->getKey().'">'.$board->getName().'</a> :: ';
					}
				?>
				<b>Grade calculators:</b> <a href="index.php?p=calculator&amp;target=gce">A-Level</a>
			</div>
		</div>
		<div id="content">