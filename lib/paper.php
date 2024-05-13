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
 * Paper object
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */

/**
 * Paper object
 * @package UMSTool
 * @subpackage Paper
 */
class Paper
{
	/**
	 * ID number
	 * @var integer
	 */
	protected $id;
	
	/**
	 * Course object this belongs to
	 * @var object
	 */
	protected $course;
	
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
	 * Paper max_ums
	 * @var integer
	 */
	protected $max_ums;
	
	/**
	 * Paper max_raw
	 * @var integer
	 */
	protected $max_raw;
	
	/**
	 * Construct a new Paper object
	 * 
	 * @param integer $id
	 * @param array $row 
	 */
	public function __construct($id, $row = null)
	{
		global $db;
		
		if (!is_array($row))
		{
			$row = $db->querySingle('SELECT * FROM papers WHERE id = '.$db->escapeString($id), true);
			
			if(!is_array($row) || count($row) == 0)
			{
				throw new Exception('This paper does not exist');
			}
		}

		$this->id = $row['id'];
		$this->key = $row['key'];
		$this->name = $row['name'];
		$this->max_ums = $row['max_ums'];
		$this->max_raw = $row['max_raw'];
		$this->course = Factory::manufactureCourse($row['course_id']);
	}
	
	/**
	 * Get all Paper objects
	 * 
	 * @return array Array containing Paper objects
	 */
	public static function getAll()
	{
		global $db;
		$papers = array();
		
		$results = $db->query('SELECT * FROM papers ORDER BY name ASC');
		while ($row = $results->fetchArray())
		{
			$papers[] = new Paper($row['id'], $row);
		}
		
		return $papers;
	}
	
	/**
	 * Get a paper object based on its key
	 * 
	 * @param string $key paper key
	 * @return object Instance of Paper object
	 */
	public static function getByKey($key)
	{
		global $db;
		$row = $db->querySingle('SELECT * FROM papers WHERE key = "'.$db->escapeString($key).'"', true);
		if(!is_array($row) || !array_key_exists('id', $row))
		{
			throw new Exception('This paper does not exist');
		}

		return new Paper($row['id'], $row);
	}
	
	/**
	 * Get all papers for a particular course
	 * 
	 * @param id $course course id
	 * @return array Array of Paper objects
	 */
	public static function getAllByCourse($course)
	{
		global $db;
		$papers = array();
		
		$results = $db->query('SELECT * FROM papers WHERE course_id = '.$db->escapeString($course).' ORDER BY name ASC');
		while ($row = $results->fetchArray())
		{
			$papers[] = new Paper($row['id'], $row);
		}
		
		return $papers;
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
	 * Get the course object
	 * @return object course
	 */
	public function getCourse()
	{
		return $this->course;
	}
	
	/**
	 * Get the paper's key
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
	 * Get the maximum UMS points
	 * @return integer max_ums
	 */
	public function getMaxUms()
	{
		return $this->max_ums;
	}
	
	/**
	 * Get the maximum raw marks
	 * @return integer max_raw
	 */
	public function getMaxRaw()
	{
		return $this->max_raw;
	}
}