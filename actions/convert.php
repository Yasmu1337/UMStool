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
 * RAW to UMS conversion
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */

if(!array_key_exists('session', $_POST))
{
	header('HTTP/1.0 404 Not Found');
	die();
}

try {
	$session = Factory::manufactureSession($_POST['session']);
}
catch (Exception $e)
{
	header('HTTP/1.0 404 Not Found');
	die();
}

/*
 * Check conversion mode, 2 is UMS to RAW, anything else is currently RAW to UMS
 */
if(array_key_exists('mode', $_GET) && $_GET['mode'] == '2')
{
	/*
	 * UMS to RAW
	 */
	
	if(!array_key_exists('ums', $_POST))
	{
		header('HTTP/1.0 404 Not Found');
		die();
	}
	
	$paper = $session->getPaper();
	$maxums = $paper->getMaxUms();
	$maxraw = $paper->getMaxRaw();
	$ourums = $_POST['ums'];

	$ourums = intval($ourums);

	if ($paper->getCourse()->getType() == Course::TYPE_ALEVEL)
	{
		/*
		 * A Level
		 */
		
		// Find what boundary our UMS mark is in
		$grade = 'U';
		if($ourums >= ($paper->getMaxUms()*0.3))
		{
			$grade = 'N';
		}
		if($ourums >= ($paper->getMaxUms()*0.4))
		{
			$grade = 'E';
		}
		if($ourums >= ($paper->getMaxUms()*0.5))
		{
			$grade = 'D';
		}
		if($ourums >=($paper->getMaxUms()*0.6))
		{
			$grade = 'C';
		}
		if($ourums >= ($paper->getMaxUms()*0.7))
		{
			$grade = 'B';
		}
		if($ourums >= ($paper->getMaxUms()*0.8))
		{
			$grade = 'A';
		}
		if($ourums >= ($paper->getMaxUms()*0.9) && $session->getGradeStar() != '')
		{
			$grade = 'A*';
		}

		/*
		 * Generate the RAW cap
		 */
		$cap = $session->getCap();

		/*
		 * If we have full UMS, then our raw is between max and the cap. Otherwise calculate
		 */
		if($ourums >= $paper->getMaxUms())
		{
			$rawmarks = $paper->getMaxRaw().' to '.$cap;
			$debug = array();
		}
		else
		{
			/*
			 * This code will work out the raw per ums point for the range between the grade
			 * we are at and the one above it (i.e. the gradient of the line between two points
			 * on the graph.
			 * 
			 * With this, we can take the number of ums points we are into the grade we have achieved
			 * and multiply this by the marks per ums point, this finds the number of raw marks we
			 * are into this grade, this can be added to the minimum raw for this grade to find the total
			 * raw we have.
			 * 
			 * This is rounded to 1 d.p. as often a UMS does not translate neatly to RAW.
			 */

			switch($grade)
			{
				case 'U':
					$rangepoints = $paper->getMaxUms()*0.3;
					$rangeraw = $session->getGradeN();
					$pointsperraw = $rangeraw / $rangepoints;
					$pointsinboundary = $ourums;
					$rawmarks = round($pointsperraw * $pointsinboundary, 1);
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
				case 'N':
					$rangepoints = $paper->getMaxUms()*0.4 - $paper->getMaxUms()*0.3;
					$rangeraw = $session->getGradeE() - $session->getGradeN();
					$pointsperraw = $rangeraw / $rangepoints;
					$pointsinboundary = $ourums - $paper->getMaxUms()*0.3;
					$rawmarks = round($pointsperraw * $pointsinboundary, 1) + $session->getGradeN();
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
				case 'E':
					$rangepoints = $paper->getMaxUms()*0.5 - $paper->getMaxUms()*0.4;
					$rangeraw = $session->getGradeD() - $session->getGradeE();
					$pointsperraw = $rangeraw / $rangepoints;
					$pointsinboundary = $ourums - $paper->getMaxUms()*0.4;
					$rawmarks = round($pointsperraw * $pointsinboundary, 1) + $session->getGradeE();
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
				case 'D':
					$rangepoints = $paper->getMaxUms()*0.6 - $paper->getMaxUms()*0.5;
					$rangeraw = $session->getGradeC() - $session->getGradeD();
					$pointsperraw = $rangeraw / $rangepoints;
					$pointsinboundary = $ourums - $paper->getMaxUms()*0.5;
					$rawmarks = round($pointsperraw * $pointsinboundary, 1) + $session->getGradeD();
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
				case 'C':
					$rangepoints = $paper->getMaxUms()*0.7 - $paper->getMaxUms()*0.6;
					$rangeraw = $session->getGradeB() - $session->getGradeC();
					$pointsperraw = $rangeraw / $rangepoints;
					$pointsinboundary = $ourums - $paper->getMaxUms()*0.6;
					$rawmarks = round($pointsperraw * $pointsinboundary, 1) + $session->getGradeC();
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
				case 'B':
					$rangepoints = $paper->getMaxUms()*0.8 - $paper->getMaxUms()*0.7;
					$rangeraw = $session->getGradeA() - $session->getGradeB();
					$pointsperraw = $rangeraw / $rangepoints;
					$pointsinboundary = $ourums - $paper->getMaxUms()*0.7;
					$rawmarks = round($pointsperraw * $pointsinboundary, 1) + $session->getGradeB();
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
				case 'A':
					/*
					 * If we have no A* (i.e. this is an AS paper), then the next 'grade' is the cap
					 * otherwise it is the A* grade
					 */
					if($session->getGradeStar() == '')
					{
						$rangepoints = $paper->getMaxUms() - $paper->getMaxUms()*0.8;
						$rangeraw = $cap - $session->getGradeA();
						$pointsperraw = $rangeraw / $rangepoints;
						$pointsinboundary = $ourums - $paper->getMaxUms()*0.8;
						$rawmarks = round($pointsperraw * $pointsinboundary, 1) + $session->getGradeA();
					}
					else
					{
						$rangepoints = $paper->getMaxUms()*0.9 - $paper->getMaxUms()*0.8;
						$rangeraw = $session->getGradeStar() - $session->getGradeA();
						$pointsperraw = $rangeraw / $rangepoints;
						$pointsinboundary = $ourums - $paper->getMaxUms()*0.8;
						$rawmarks = round($pointsperraw * $pointsinboundary, 1) + $session->getGradeA();
					}
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
				case 'A*':
					/*
					 * Next grade is the cap
					 */
					$rangepoints = $paper->getMaxUms() - $paper->getMaxUms()*0.9;
					$rangeraw = $cap - $session->getGradeStar();
					$pointsperraw = $rangeraw / $rangepoints;
					$pointsinboundary = $ourums - $paper->getMaxUms()*0.9;
					$rawmarks = round($pointsperraw * $pointsinboundary, 1) + $session->getGradeStar();
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
			}
		}
	}
	else
	{
		/*
		 * GCSE
		 */
		
		// Find what boundary our UMS mark is in
		$grade = 'U';
		if($ourums >= ($paper->getMaxUms()*0.3))
		{
			$grade = 'N';
		}
		if($ourums >= ($paper->getMaxUms()*0.4))
		{
			$grade = 'E';
		}
		if($ourums >= ($paper->getMaxUms()*0.5))
		{
			$grade = 'B';
		}
		if($ourums >=($paper->getMaxUms()*0.6))
		{
			$grade = 'C';
		}
		if($ourums >= ($paper->getMaxUms()*0.7))
		{
			$grade = 'B';
		}
		if($ourums >= ($paper->getMaxUms()*0.8))
		{
			$grade = 'A';
		}
		if($ourums >= ($paper->getMaxUms()*0.9) && $session->getGradeStar() != '')
		{
			$grade = 'A*';
		}

		/*
		 * Generate the RAW cap
		 */
		$cap = $session->getCap();
		
		/*
		 * If we have full UMS, then our raw is between max and the cap. Otherwise calculate
		 */
		if($ourums >= $paper->getMaxUms())
		{
			$rawmarks = $paper->getMaxRaw().' to '.$cap;
			$debug = array();
		}
		else
		{
			/*
			 * This code will work out the raw per ums point for the range between the grade
			 * we are at and the one above it (i.e. the gradient of the line between two points
			 * on the graph.
			 * 
			 * With this, we can take the number of ums points we are into the grade we have achieved
			 * and multiply this by the marks per ums point, this finds the number of raw marks we
			 * are into this grade, this can be added to the minimum raw for this grade to find the total
			 * raw we have.
			 * 
			 * This is rounded to 1 d.p. as often a UMS does not translate neatly to RAW.
			 */

			switch($grade)
			{
				case 'U':
					$rangepoints = $paper->getMaxUms()*0.2;
					$rangeraw = $session->getGradeG();
					$pointsperraw = $rangeraw / $rangepoints;
					$pointsinboundary = $ourums;
					$rawmarks = round($pointsperraw * $pointsinboundary, 1);
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
				case 'G':
					$rangepoints = $paper->getMaxUms()*0.4 - $paper->getMaxUms()*0.2;
					$rangeraw = $session->getGradeE() - $session->getGradeN();
					$pointsperraw = $rangeraw / $rangepoints;
					$pointsinboundary = $ourums - $paper->getMaxUms()*0.3;
					$rawmarks = round($pointsperraw * $pointsinboundary, 1) + $session->getGradeN();
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
				case 'F':
					$rangepoints = $paper->getMaxUms()*0.4 - $paper->getMaxUms()*0.3;
					$rangeraw = $session->getGradeE() - $session->getGradeN();
					$pointsperraw = $rangeraw / $rangepoints;
					$pointsinboundary = $ourums - $paper->getMaxUms()*0.3;
					$rawmarks = round($pointsperraw * $pointsinboundary, 1) + $session->getGradeN();
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
				case 'E':
					$rangepoints = $paper->getMaxUms()*0.5 - $paper->getMaxUms()*0.4;
					$rangeraw = $session->getGradeD() - $session->getGradeE();
					$pointsperraw = $rangeraw / $rangepoints;
					$pointsinboundary = $ourums - $paper->getMaxUms()*0.4;
					$rawmarks = round($pointsperraw * $pointsinboundary, 1) + $session->getGradeE();
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
				case 'D':
					$rangepoints = $paper->getMaxUms()*0.6 - $paper->getMaxUms()*0.5;
					$rangeraw = $session->getGradeC() - $session->getGradeD();
					$pointsperraw = $rangeraw / $rangepoints;
					$pointsinboundary = $ourums - $paper->getMaxUms()*0.5;
					$rawmarks = round($pointsperraw * $pointsinboundary, 1) + $session->getGradeD();
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
				case 'C':
					$rangepoints = $paper->getMaxUms()*0.7 - $paper->getMaxUms()*0.6;
					$rangeraw = $session->getGradeB() - $session->getGradeC();
					$pointsperraw = $rangeraw / $rangepoints;
					$pointsinboundary = $ourums - $paper->getMaxUms()*0.6;
					$rawmarks = round($pointsperraw * $pointsinboundary, 1) + $session->getGradeC();
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
				case 'B':
					$rangepoints = $paper->getMaxUms()*0.8 - $paper->getMaxUms()*0.7;
					$rangeraw = $session->getGradeA() - $session->getGradeB();
					$pointsperraw = $rangeraw / $rangepoints;
					$pointsinboundary = $ourums - $paper->getMaxUms()*0.7;
					$rawmarks = round($pointsperraw * $pointsinboundary, 1) + $session->getGradeB();
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
				case 'A':
					$rangepoints = $paper->getMaxUms()*0.9 - $paper->getMaxUms()*0.8;
					$rangeraw = $session->getGradeStar() - $session->getGradeA();
					$pointsperraw = $rangeraw / $rangepoints;
					$pointsinboundary = $ourums - $paper->getMaxUms()*0.8;
					$rawmarks = round($pointsperraw * $pointsinboundary, 1) + $session->getGradeA();
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
				case 'A*':
					/*
					 * Next grade is the cap
					 */
					$rangepoints = $paper->getMaxUms() - $paper->getMaxUms()*0.9;
					$rangeraw = $cap - $session->getGradeStar();
					$pointsperraw = $rangerraw / $rangepoints;
					$pointsinboundary = $ourums - $paper->getMaxUms()*0.9;
					$rawmarks = round($pointsperraw * $pointsinboundary, 1) + $session->getGradeStar();
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $rawmarks));
					break;
			}
		}
	}
	
	?>
	<h4>Results</h4>
	<ul>
		<li><b>Number of UMS points:</b> <?php echo $_POST['ums']; ?></li>
		<li><b>Number of RAW marks needed:</b> <?php echo $rawmarks; ?></li>
		<li><b>Grade:</b> <?php echo $grade; ?><?php if(($grade == 'A*' || $grade == 'N') && $paper->getCourse()->getType() == Course::TYPE_ALEVEL): ?><br /><i>This grade can not be awarded, and is only shown for conversion purposes.</i><?php endif; ?></li>
	</ul>
	<?php //var_dump($debug); ?>
	<a href="javascript:void(0)" onClick="Effect.Fade('response_box_2', { duration: 0.5 });">Close</a>
	<?php
}
else
{
	/*
	 * RAW to UMS
	 */
	
	if(!array_key_exists('raw', $_POST))
	{
		header('HTTP/1.0 404 Not Found');
		die();
	}
	
	$paper = $session->getPaper();
	$maxums = $paper->getMaxUms();
	$maxraw = $paper->getMaxRaw();
	$ourraw = $_POST['raw'];

	$ourraw = intval($ourraw);

	if ($paper->getCourse()->getType() == Course::TYPE_ALEVEL)
	{
		/*
		 * A Level
		 */
		
		// Find what boundary our raw mark is in
		$grade = 'U';
		if($ourraw >= $session->getGradeN())
		{
			$grade = 'N';
		}
		if($ourraw >= $session->getGradeE())
		{
			$grade = 'E';
		}
		if($ourraw >= $session->getGradeD())
		{
			$grade = 'D';
		}
		if($ourraw >= $session->getGradeC())
		{
			$grade = 'C';
		}
		if($ourraw >= $session->getGradeB())
		{
			$grade = 'B';
		}
		if($ourraw >= $session->getGradeA())
		{
			$grade = 'A';
		}
		if($ourraw >= $session->getGradeStar() && $session->getGradeStar() != '')
		{
			$grade = 'A*';
		}

		/*
		 * Generate the RAW cap
		 */
		$cap = $session->getCap();

		/*
		 * If we are above the cap, then we have full UMS, otherwise calculate separations
		 */
		if($ourraw >= $cap)
		{
			$pointsattained = $paper->getMaxUms();
			$debug = array();
		}
		else
		{
			/*
			 * This code will work out the points per raw mark for the range between the grade
			 * we are at and the one above it (i.e. the gradient of the line between two points
			 * on the graph.
			 * 
			 * With this, we can take the number of raw marks we are into the grade we have achieved
			 * and multiply this by the points per raw mark, this finds the number of ums points we
			 * are into this grade, this can be added to the minimum ums for this grade to find the total
			 * ums we have.
			 * 
			 * This is rounded to the nearest number, as this is what TSR says.
			 */

			switch($grade)
			{
				case 'U':
					$rangepoints = $paper->getMaxUms()*0.3;
					$rangeraw = $session->getGradeN();
					$pointsperraw = $rangepoints / $rangeraw;
					$pointsinboundary = $ourraw;
					$pointsattained = round($pointsperraw * $pointsinboundary);
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
				case 'N':
					$rangepoints = $paper->getMaxUms()*0.4 - $paper->getMaxUms()*0.3;
					$rangeraw = $session->getGradeE() - $session->getGradeN();
					$pointsperraw = $rangepoints / $rangeraw;
					$pointsinboundary = $ourraw - $session->getGradeN();
					$pointsattained = round($pointsperraw * $pointsinboundary) + $paper->getMaxUms()*0.3;
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
				case 'E':
					$rangepoints = $paper->getMaxUms()*0.5 - $paper->getMaxUms()*0.4;
					$rangeraw = $session->getGradeD() - $session->getGradeE();
					$pointsperraw = $rangepoints / $rangeraw;
					$pointsinboundary = $ourraw - $session->getGradeE();
					$pointsattained = round($pointsperraw * $pointsinboundary) + $paper->getMaxUms()*0.4;
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
				case 'D':
					$rangepoints = $paper->getMaxUms()*0.6 - $paper->getMaxUms()*0.5;
					$rangeraw = $session->getGradeC() - $session->getGradeD();
					$pointsperraw = $rangepoints / $rangeraw;
					$pointsinboundary = $ourraw - $session->getGradeD();
					$pointsattained = round($pointsperraw * $pointsinboundary) + $paper->getMaxUms()*0.5;
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
				case 'C':
					$rangepoints = $paper->getMaxUms()*0.7 - $paper->getMaxUms()*0.6;
					$rangeraw = $session->getGradeB() - $session->getGradeC();
					$pointsperraw = $rangepoints / $rangeraw;
					$pointsinboundary = $ourraw - $session->getGradeC();
					$pointsattained = round($pointsperraw * $pointsinboundary) + $paper->getMaxUms()*0.6;
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
				case 'B':
					$rangepoints = $paper->getMaxUms()*0.8 - $paper->getMaxUms()*0.7;
					$rangeraw = $session->getGradeA() - $session->getGradeB();
					$pointsperraw = $rangepoints / $rangeraw;
					$pointsinboundary = $ourraw - $session->getGradeB();
					$pointsattained = round($pointsperraw * $pointsinboundary) + $paper->getMaxUms()*0.7;
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
				case 'A':
					/*
					 * If we have no A* (i.e. this is an AS paper), then the next 'grade' is the cap
					 * otherwise it is the A* grade
					 */
					if($session->getGradeStar() == '')
					{
						$rangepoints = $paper->getMaxUms() - $paper->getMaxUms()*0.8;
						$rangeraw = $cap - $session->getGradeA();
						$pointsperraw = $rangepoints / $rangeraw;
						$pointsinboundary = $ourraw - $session->getGradeA();
						$pointsattained = round($pointsperraw * $pointsinboundary) + $paper->getMaxUms()*0.8;
					}
					else
					{
						$rangepoints = $paper->getMaxUms()*0.9 - $paper->getMaxUms()*0.8;
						$rangeraw = $session->getGradeStar() - $session->getGradeA();
						$pointsperraw = $rangepoints / $rangeraw;
						$pointsinboundary = $ourraw - $session->getGradeA();
						$pointsattained = round($pointsperraw * $pointsinboundary) + $paper->getMaxUms()*0.8;
					}
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
				case 'A*':
					/*
					 * Next grade is the cap
					 */
					$rangepoints = $paper->getMaxUms() - $paper->getMaxUms()*0.9;
					$rangeraw = $cap - $session->getGradeStar();
					$pointsperraw = $rangepoints / $rangeraw;
					$pointsinboundary = $ourraw - $session->getGradeStar();
					$pointsattained = round($pointsperraw * $pointsinboundary) + $paper->getMaxUms()*0.9;
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
			}
		}
	}
	else
	{
		/*
		 * GCSE
		 */
		
		// Find what boundary our raw mark is in
		$grade = 'U';
		if($ourraw >= $session->getGradeG())
		{
			$grade = 'G';
		}
		if($ourraw >= $session->getGradeF())
		{
			$grade = 'F';
		}
		if($ourraw >= $session->getGradeE())
		{
			$grade = 'E';
		}
		if($ourraw >= $session->getGradeD())
		{
			$grade = 'B';
		}
		if($ourraw >= $session->getGradeC())
		{
			$grade = 'C';
		}
		if($ourraw >= $session->getGradeB())
		{
			$grade = 'B';
		}
		if($ourraw >= $session->getGradeA())
		{
			$grade = 'A';
		}
		if($ourraw >= $session->getGradeStar())
		{
			$grade = 'A*';
		}

		/*
		 * Generate the RAW cap
		 */
		$cap = $session->getCap();
			
		/*
		 * If we are above the cap, then we have full UMS, otherwise calculate separations
		 */
		if($ourraw >= $cap)
		{
			$pointsattained = $paper->getMaxUms();
			$debug = array();
		}
		else
		{
			/*
			 * This code will work out the points per raw mark for the range between the grade
			 * we are at and the one above it (i.e. the gradient of the line between two points
			 * on the graph.
			 * 
			 * With this, we can take the number of raw marks we are into the grade we have achieved
			 * and multiply this by the points per raw mark, this finds the number of ums points we
			 * are into this grade, this can be added to the minimum ums for this grade to find the total
			 * ums we have.
			 * 
			 * This is rounded to the nearest number, as this is what TSR says.
			 */

			switch($grade)
			{
				case 'U':
					$rangepoints = $paper->getMaxUms()*0.2;
					$rangeraw = $session->getGradeG();
					$pointsperraw = $rangepoints / $rangeraw;
					$pointsinboundary = $ourraw;
					$pointsattained = round($pointsperraw * $pointsinboundary);
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
				case 'G':
					$rangepoints = $paper->getMaxUms()*0.3 - $paper->getMaxUms()*0.2;
					$rangeraw = $session->getGradeE() - $session->getGradeN();
					$pointsperraw = $rangepoints / $rangeraw;
					$pointsinboundary = $ourraw - $session->getGradeN();
					$pointsattained = round($pointsperraw * $pointsinboundary) + $paper->getMaxUms()*0.3;
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
				case 'F':
					$rangepoints = $paper->getMaxUms()*0.4 - $paper->getMaxUms()*0.3;
					$rangeraw = $session->getGradeE() - $session->getGradeN();
					$pointsperraw = $rangepoints / $rangeraw;
					$pointsinboundary = $ourraw - $session->getGradeN();
					$pointsattained = round($pointsperraw * $pointsinboundary) + $paper->getMaxUms()*0.3;
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
				case 'E':
					$rangepoints = $paper->getMaxUms()*0.5 - $paper->getMaxUms()*0.4;
					$rangeraw = $session->getGradeD() - $session->getGradeE();
					$pointsperraw = $rangepoints / $rangeraw;
					$pointsinboundary = $ourraw - $session->getGradeE();
					$pointsattained = round($pointsperraw * $pointsinboundary) + $paper->getMaxUms()*0.4;
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
				case 'D':
					$rangepoints = $paper->getMaxUms()*0.6 - $paper->getMaxUms()*0.5;
					$rangeraw = $session->getGradeC() - $session->getGradeD();
					$pointsperraw = $rangepoints / $rangeraw;
					$pointsinboundary = $ourraw - $session->getGradeD();
					$pointsattained = round($pointsperraw * $pointsinboundary) + $paper->getMaxUms()*0.5;
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
				case 'C':
					$rangepoints = $paper->getMaxUms()*0.7 - $paper->getMaxUms()*0.6;
					$rangeraw = $session->getGradeB() - $session->getGradeC();
					$pointsperraw = $rangepoints / $rangeraw;
					$pointsinboundary = $ourraw - $session->getGradeC();
					$pointsattained = round($pointsperraw * $pointsinboundary) + $paper->getMaxUms()*0.6;
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
				case 'B':
					$rangepoints = $paper->getMaxUms()*0.8 - $paper->getMaxUms()*0.7;
					$rangeraw = $session->getGradeA() - $session->getGradeB();
					$pointsperraw = $rangepoints / $rangeraw;
					$pointsinboundary = $ourraw - $session->getGradeB();
					$pointsattained = round($pointsperraw * $pointsinboundary) + $paper->getMaxUms()*0.7;
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
				case 'A':
					$rangepoints = $paper->getMaxUms()*0.9 - $paper->getMaxUms()*0.8;
					$rangeraw = $session->getGradeStar() - $session->getGradeA();
					$pointsperraw = $rangepoints / $rangeraw;
					$pointsinboundary = $ourraw - $session->getGradeA();
					$pointsattained = round($pointsperraw * $pointsinboundary) + $paper->getMaxUms()*0.8;
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
				case 'A*':
					/*
					 * Next grade is the cap
					 */
					$rangepoints = $paper->getMaxUms() - $paper->getMaxUms()*0.9;
					$rangeraw = $cap - $session->getGradeStar();
					$pointsperraw = $rangepoints / $rangeraw;
					$pointsinboundary = $ourraw - $session->getGradeStar();
					$pointsattained = round($pointsperraw * $pointsinboundary) + $paper->getMaxUms()*0.9;
					$debug = (array('points range' => $rangepoints, 'raw range' => $rangeraw, 'ums per raw' => $pointsperraw, 'final val' => $pointsattained));
					break;
			}
		}
	}

	?>
	<h4>Results</h4>
	<ul>
		<li><b>Number of RAW marks:</b> <?php echo $_POST['raw']; ?></li>
		<li><b>Number of UMS points:</b> <?php echo $pointsattained; ?></li>
		<li><b>Grade:</b> <?php echo $grade; ?><?php if(($grade == 'A*' || $grade == 'N') && $paper->getCourse()->getType() == Course::TYPE_ALEVEL): ?><br /><i>This grade can not be awarded, and is only shown for conversion purposes.</i><?php endif; ?></li>
	</ul>
	<?php //var_dump($debug); ?>
	<a href="javascript:void(0)" onClick="Effect.Fade('response_box_1', { duration: 0.5 });">Close</a>
	<?php
}
