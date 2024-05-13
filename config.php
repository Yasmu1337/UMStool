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
 * Configuration file
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */

/**
 * Path to database file, relative to lib/database.php
 */
define('UMSTOOL_DB', '../db/umstool.db');
define('UMSTOOL_NAME', 'UMS Tools');
define('UMSTOOL_CACHE', __DIR__.'/lib/pchart/cache');