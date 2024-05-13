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
 * Object factories
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */

/**
 * Object factories
 * @package UMSTool
 * @subpackage Factory
 */
class Factory
{
    /**
     * Return an exam board object
     * @param integer|string ident ID number or key of board
     * @return mixed
     */
	static function manufactureBoard($ident)
	{
		return self::_manufacture('Board', $ident);
	}
	
    /**
     * Return an course object
     * @param integer|string ident ID number or key of course
     * @return mixed
     */
	static function manufactureCourse($ident)
	{
		return self::_manufacture('Course', $ident);
	}
	
    /**
     * Return an exam paper object
     * @param integer|string ident ID number or key of paper
     * @return mixed
     */
	static function manufacturePaper($ident)
	{
		return self::_manufacture('Paper', $ident);
	}
	
    /**
     * Return a session object for a session
     * @param integer|string ident ID number or key of paper
     * @return mixed
     */
	static function manufactureSession($ident)
	{
		return self::_manufacture('Session', $ident);
	}
	
    /**
     * Return a generic object, used by methods of this class
     * @param integer|string ident ID number or key
     * @return mixed
     */
	private static function _manufacture($item, $ident)
	{
		try
		{
			if(is_numeric($ident))
			{
				try
				{
					// The key may be numeric anyway
					return $item::getByKey($ident);
				}
				catch (Exception $e)
				{
					try
					{
						return new $item($ident);
					}
					catch (Exception $e)
					{
						throw $e;
					}
				}
			}
			else
			{
				return $item::getByKey($ident);
			}
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
}
