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
 * Main web interface, handles routing
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */

// Initially, test to see if config file exists and database exists
if(!file_exists('../config.php'))
{
	die('Configuration file not found');
}

require('../config.php');

if(!defined('UMSTOOL_DB'))
{
	die('Database path not set');
}

if(!file_exists(UMSTOOL_DB))
{
	die('Database file does not exist, see installation instructions');
}

if(!extension_loaded('gd') && !extension_loaded('gd2'))
{
	die('Required extension gd is not loaded');
}

if(!extension_loaded('sqlite3'))
{
	die('Required extension sqlite3 is not loaded');
}

if(!is_writable(UMSTOOL_CACHE))
{
	die('Cache directory '.UMSTOOL_CACHE.' is not writable by the webserver');
}

require('../lib/factory.php');
require('../lib/functions.php');
require('../lib/board.php');
require('../lib/course.php');
require('../lib/paper.php');
require('../lib/session.php');

$db = new SQLite3(UMSTOOL_DB);
$_GLOBALS['db'] = $db;

if(array_key_exists('ajax', $_GET))
{
	if(!array_key_exists('action', $_GET))
	{
		header('HTTP/1.0 404 Not Found');
	}
	else
	{
		$page = $_GET['action'];
	}

	try
	{
		load_action($page);
	}
	catch (Exception $e)
	{
		header('HTTP/1.0 404 Not Found');
	}
}
elseif(array_key_exists('graph', $_GET))
{
	if(!array_key_exists('draw', $_GET))
	{
		header('HTTP/1.0 404 Not Found');
	}
	else
	{
		$page = $_GET['draw'];
	}

	try
	{
		load_graph($page);
	}
	catch (Exception $e)
	{
		header('HTTP/1.0 404 Not Found');
	}
}
else
{
	if(!array_key_exists('p', $_GET))
	{
		$page = 'index';
	}
	else
	{
		$page = $_GET['p'];
	}

	try
	{
		load_page($page);
	}
	catch (Exception $e)
	{
		load_page('notfound');
	}
}

$db->close();
