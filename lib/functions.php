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
 * Miscellaneous functions
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */

/**
 * Load a page template
 * @param string $page Name of page
 */
function load_page($page)
{
	if(preg_match('/[^a-zA-Z0-9 ]/x', $page))
	{
		throw new Exception('invalid page name');
	}
	
	if(!file_exists('../pages/'.$page.'.php'))
	{
		throw new Exception('page does not exist');
	}
	
	require('../pages/'.$page.'.php');
}

/**
 * Load an ajax action
 * @param string $action Name of action
 */
function load_action($action)
{
	if(preg_match('/[^a-zA-Z0-9 ]/x', $action))
	{
		throw new Exception('invalid action name');
	}
	
	if(!file_exists('../actions/'.$action.'.php'))
	{
		throw new Exception('action does not exist');
	}
	
	require('../actions/'.$action.'.php');
}

/**
 * Load a pChart graph
 * @param string $graph Graph to draw
 */
function load_graph($graph)
{
	if(preg_match('/[^a-zA-Z0-9 ]/x', $graph))
	{
		throw new Exception('invalid graph name');
	}
	
	if(!file_exists('../graphs/'.$graph.'.php'))
	{
		throw new Exception('graph does not exist');
	}
	
	require('../graphs/'.$graph.'.php');
}

/**
 * Return version number
 * @return string
 */
function get_version()
{
	// FIXME: The last part should be the SVN revision, which will need setting prior to tagging
	return '1.0.x';
}