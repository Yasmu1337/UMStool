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
 * Session object
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */

/**
 * Session object
 * @package UMSTool
 * @subpackage Session
 */
class Session
{
	/**
	 * ID number
	 * @var integer
	 */
	protected $id;
	
	/**
	 * Paper object this belongs to
	 * @var object
	 */
	protected $paper;
	
	/**
	 * Year of sitting
	 * @var integer
	 */
	protected $year;
	
	/**
	 * Month period of sitting (1: winter, 2: spring, 3: summer, 4: winter)
	 * @var integer
	 */
	protected $month;
	
	/**
	 * Raw marks for grade A*
	 * @var integer
	 */
	protected $raw_star;
	
	/**
	 * Raw marks for grade A
	 * @var integer
	 */
	protected $raw_a;
		
	/**
	 * Raw marks for grade B
	 * @var integer
	 */
	protected $raw_b;
		
	/**
	 * Raw marks for grade C
	 * @var integer
	 */
	protected $raw_c;
		
	/**
	 * Raw marks for grade D
	 * @var integer
	 */
	protected $raw_d;
		
	/**
	 * Raw marks for grade E
	 * @var integer
	 */
	protected $raw_e;	
	/**
	 * Raw marks for grade F
	 * @var integer
	 */
	protected $raw_f;	
	/**
	 * Raw marks for grade G
	 * @var integer
	 */
	protected $raw_g;
		
	/**
	 * Raw marks for grade N
	 * @var integer
	 */
	protected $raw_n;
	
	/**
	 * URL for the question paper download
	 * @var string
	 */
	protected $qp_link;
	
	/**
	 * URL for the mark scheme download
	 * @var string
	 */
	protected $ms_link;
	
	/**
	 * URL for the examiner's report download
	 * @var string
	 */
	protected $er_link;

	const SESSION_WINTER = 1;
	const SESSION_SPRING = 2;
	const SESSION_SUMMER = 3;
	const SESSION_AUTUMN = 4;
	
	/**
	 * Construct a new Session object
	 * 
	 * @param integer $id
	 * @param array $row 
	 */
	public function __construct($id, $row = null)
	{
		global $db;
		
		if (!is_array($row))
		{
			$row = $db->querySingle('SELECT * FROM sessions WHERE id = '.$db->escapeString($id), true);
			
			if(!is_array($row) || count($row) == 0)
			{
				throw new Exception('This session does not exist');
			}
		}

		$this->id = $row['id'];
		$this->year = $row['year'];
		$this->month = $row['month'];
		$this->paper = Factory::manufacturePaper($row['paper_id']);
		
		$this->raw_star = $row['raw_star'];
		$this->raw_a = $row['raw_a'];
		$this->raw_b = $row['raw_b'];
		$this->raw_c = $row['raw_c'];
		$this->raw_d = $row['raw_d'];
		$this->raw_e = $row['raw_e'];
		$this->raw_f = $row['raw_f'];
		$this->raw_g = $row['raw_g'];
		$this->raw_n = $row['raw_n'];
		
		$this->qp_link = $row['qp_link'];
		$this->ms_link = $row['ms_link'];
		$this->er_link = $row['er_link'];
	}
	
	/**
	 * Get all Session objects
	 * 
	 * @return array Array containing Session objects
	 */
	public static function getAll()
	{
		global $db;
		$sessions = array();
		
		$results = $db->query('SELECT * FROM sessions ORDER BY year ASC, month ASC');
		while ($row = $results->fetchArray())
		{
			$sessions[] = new Session($row['id'], $row);
		}
		
		return $sessions;
	}
	
	/**
	 * Get a session object based on its key
	 * 
	 * @param string $key session key
	 * @return object Instance of Session object
	 */
	public static function getByPaperAndSession($paper, $year, $month)
	{
		if(!is_numeric($paper) || !is_numeric($month) || !is_numeric($year))
		{
			throw new Exception('Invalid session ID');
		}
		global $db;
		$row = $db->querySingle('SELECT * FROM sessions WHERE paper_id = '.$db->escapeString($paper).' AND month = '.$db->escapeString($month).' AND year = '.$db->escapeString($year), true);
		if(!is_array($row) || !array_key_exists('id', $row))
		{
			throw new Exception('This session does not exist');
		}

		return new Session($row['id'], $row);
	}
	
	/**
	 * Get all sessions for a particular paper
	 * 
	 * @param id $course course id
	 * @return array Array of Session objects
	 */
	public static function getAllByPaper($paper)
	{
		global $db;
		$sessions = array();
		
		$results = $db->query('SELECT * FROM sessions WHERE paper_id = '.$db->escapeString($paper).' ORDER BY year ASC, month ASC');
		while ($row = $results->fetchArray())
		{
			$sessions[] = new Session($row['id'], $row);
		}
		
		return $sessions;
	}
	
	/**
	 * This method is intentionally not implemented
	 * @param mixed $key
	 */
	public static function getByKey($key)
	{
		throw new Exception('Unimplemented');
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
	 * Get the paper object
	 * @return object paper
	 */
	public function getPaper()
	{
		return $this->paper;
	}

	/**
	 * Get the year of sitting
	 * @return integer year
	 */
	public function getYear()
	{
		return $this->year;
	}
	
	/**
	 * Get the month period of sitting
	 * @return integer month peried
	 */
	public function getMonth()
	{
		return $this->month;
	}
	
	/**
	 * Get raw marks needed for A* grade
	 * @return integer raw marks
	 */
	public function getGradeStar()
	{
		return $this->raw_star;
	}
		
	/**
	 * Get raw marks needed for A grade
	 * @return integer raw marks
	 */
	public function getGradeA()
	{
		return $this->raw_a;
	}
		
	/**
	 * Get raw marks needed for B grade
	 * @return integer raw marks
	 */
	public function getGradeB()
	{
		return $this->raw_b;
	}
		
	/**
	 * Get raw marks needed for C grade
	 * @return integer raw marks
	 */
	public function getGradeC()
	{
		return $this->raw_c;
	}
		
	/**
	 * Get raw marks needed for D grade
	 * @return integer raw marks
	 */
	public function getGradeD()
	{
		return $this->raw_d;
	}
		
	/**
	 * Get raw marks needed for E grade
	 * @return integer raw marks
	 */
	public function getGradeE()
	{
		return $this->raw_e;
	}
		
	/**
	 * Get raw marks needed for F grade
	 * @return integer raw marks
	 */
	public function getGradeF()
	{
		return $this->raw_f;
	}
		
	/**
	 * Get raw marks needed for G grade
	 * @return integer raw marks
	 */
	public function getGradeG()
	{
		return $this->raw_g;
	}
		
	/**
	 * Get raw marks needed for N grade
	 * @return integer raw marks
	 */
	public function getGradeN()
	{
		return $this->raw_n;
	}
	
	/**
	 * Check to see if a question paper link is set
	 * @return boolean
	 */
	public function hasQuestionPaper()
	{
		if($this->qp_link == '') { return false; } else { return true; }
	}
	
	/**
	 * Check to see if a mark scheme link is set
	 * @return boolean
	 */
	public function hasMarkScheme()
	{
		if($this->ms_link == '') { return false; } else { return true; }
	}
	
	/**
	 * Check to see if an examiner's report link is set
	 * @return boolean
	 */
	public function hasExaminersReport()
	{
		if($this->er_link == '') { return false; } else { return true; }
	}
	
	/**
	 * Get link for question paper
	 * @return string
	 */
	public function getQuestionPaper()
	{
		return $this->qp_link;
	}
	
	/**
	 * Get link for mark scheme
	 * @return string
	 */
	public function getMarkScheme()
	{
		return $this->ms_link;
	}
	
	/**
	 * Get link for examiner's report
	 * @return string
	 */
	public function getExaminersReport()
	{
		return $this->er_link;
	}
	
	/**
	 * Get the minimum number of RAW marks needed for full UMS
	 * @return integer
	 */
	public function getCap()
	{
		if ($this->getPaper()->getCourse()->getType() == Course::TYPE_ALEVEL)
		{
			if ($this->getGradeStar() == '')
			{
				$sep = $this->getGradeA() - $this->getGradeB();
				$cap = $this->getGradeA() + 2*$sep;
				if ($cap > $this->getPaper()->getMaxRaw())
				{
					// is this valid? if its too big do 1*sep
					$cap = $this->getGradeA() + $sep;
				}
			}
			else
			{
				$sep = $this->getGradeStar() - $this->getGradeA();
				$cap = $this->getGradeStar() + $sep;
			}
		}
		else
		{
			$sep = $this->getGradeStar() - $this->getGradeA();
			$cap = $this->getGradeStar() + $sep;
			if ($cap > $this->getPaper()->getMaxRaw())
			{
				$cap = $this->getPaper()->getMaxRaw();
			}
		}
		return $cap;
	}
}