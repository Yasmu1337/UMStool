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
 * Course object
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */

/**
 * Course object
 * @package UMSTool
 * @subpackage Course
 */
class Course
{
	/**
	 * ID number
	 * @var integer
	 */
	protected $id;
	
	/**
	 * Board object this belongs to
	 * @var object
	 */
	protected $board;
	
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
	 * Course types
	 */
	const TYPE_GCSE = 1;
	const TYPE_ALEVEL = 2;
	
	/**
	 * Construct a new Course object
	 * 
	 * @param integer $id
	 * @param array $row 
	 */
	public function __construct($id, $row = null)
	{
		global $db;
		
		if (!is_array($row))
		{
			$row = $db->querySingle('SELECT * FROM courses WHERE id = '.$db->escapeString($id), true);
			
			if(!is_array($row) || count($row) == 0)
			{
				throw new Exception('This course does not exist');
			}
		}

		$this->id = $row['id'];
		$this->key = $row['key'];
		$this->name = $row['name'];
		$this->type = $row['type'];
		$this->board = Factory::manufactureBoard($row['board_id']);
	}
	
	/**
	 * Get all Course objects
	 * 
	 * @return array Array containing Course objects
	 */
	public static function getAll()
	{
		global $db;
		$courses = array();
		
		$results = $db->query('SELECT * FROM courses ORDER BY name ASC');
		while ($row = $results->fetchArray())
		{
			$courses[] = new Course($row['id'], $row);
		}
		
		return $courses;
	}
	
	/**
	 * Get a course object based on its type (gcse/alevel)
	 * 
	 * @param string $type course type (1 for gcse, 2 for alevel)
	 * @return array Array of course objects
	 */
	public static function getAllByType($type)
	{
		if($type !== self::TYPE_GCSE && $type !== self::TYPE_ALEVEL)
		{
			throw new Exception('invalid type');
		}
		
		global $db;
		$courses = array();
		
		$results = $db->query('SELECT * FROM courses WHERE type = '.$db->escapeString($type).' ORDER BY name ASC');
		while ($row = $results->fetchArray())
		{
			$courses[] = new Course($row['id'], $row);
		}
		
		return $courses;
	}
	
	/**
	 * Get a course object based on its type (gcse/alevel) and the board
	 * 
	 * @param string $type course type (1 for gcse, 2 for alevel)
	 * @param integer $board board id
	 * @return array Array of course objects
	 */
	public static function getAllByTypeAndBoard($type, $board)
	{
		if($type !== self::TYPE_GCSE && $type !== self::TYPE_ALEVEL)
		{
			throw new Exception('invalid type');
		}
		
		global $db;
		$courses = array();
		
		$results = $db->query('SELECT * FROM courses WHERE type = '.$db->escapeString($type).' AND board_id = '.$db->escapeString($board).' ORDER BY name ASC');
		while ($row = $results->fetchArray())
		{
			$courses[] = new Course($row['id'], $row);
		}
		
		return $courses;
	}
	
	/**
	 * Get a course object based on its key
	 * 
	 * @param string $key course key
	 * @return object Instance of Course object
	 */
	public static function getByKey($key)
	{
		global $db;
		$row = $db->querySingle('SELECT * FROM courses WHERE key = "'.$db->escapeString($key).'"', true);
		if(!is_array($row) || !array_key_exists('id', $row))
		{
			throw new Exception('This course does not exist');
		}

		return new Course($row['id'], $row);
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
	 * Get the board object
	 * @return object board
	 */
	public function getBoard()
	{
		return $this->board; 
	}
	
	/**
	 * Get the course's key
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
	
	/**
	 * Get the type, 1 for GCSE and 2 for A-Level
	 * @return integer type
	 */
	public function getType()
	{
		return $this->type;
	}
}