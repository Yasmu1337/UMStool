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
 * Examination board
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */

/**
 * Examination board
 * @package UMSTool
 * @subpackage Board
 */
class Board
{
	/**
	 * ID number
	 * @var integer
	 */
	protected $id;
	
	/**
	 * URL friendly key
	 * @var string
	 */
	protected $key;

	/**
	 * User friendly name
	 * @var string
	 */
	protected $name;
	
	/**
	 * Construct a new Board object
	 * 
	 * @param integer $id
	 * @param array $row 
	 */
	public function __construct($id, $row = null)
	{
		global $db;
		
		if (!is_array($row))
		{
			$row = $db->querySingle('SELECT * FROM boards WHERE id = '.$db->escapeString($id), true);
			
			if(!is_array($row) || count($row) == 0)
			{
				throw new Exception('This board does not exist');
			}
		}

		$this->id = $row['id'];
		$this->key = $row['key'];
		$this->name = $row['name'];
	}
	
	/**
	 * Get all Board objects
	 * 
	 * @return array Array containing Board objects
	 */
	public static function getAll()
	{
		global $db;
		$boards = array();
		
		$results = $db->query('SELECT * FROM boards ORDER BY name ASC');
		while ($row = $results->fetchArray())
		{
			$boards[] = new Board($row['id'], $row);
		}
		
		return $boards;
	}
	
	/**
	 * Get a board object based on its key
	 * 
	 * @param string $key board key
	 * @return object Instance of Board object
	 */
	public static function getByKey($key)
	{
		global $db;
		$row = $db->querySingle('SELECT * FROM boards WHERE key = "'.$db->escapeString($key).'"', true);
		if(!is_array($row) || !array_key_exists('id', $row))
		{
			throw new Exception('This board does not exist');
		}

		return new Board($row['id'], $row);
	}
	
	/**
	 * Get the id number
	 * @return integer id
	 */
	public function getID()
	{
		return $this->id;
	}
	
	/**
	 * Get the board's key
	 * @return string key
	 */
	public function getKey()
	{
		return $this->key;
	}
	
	/**
	 * Get the user-readable name
	 * @return string user friendly name
	 */
	public function getName()
	{
		return $this->name;
	}
}