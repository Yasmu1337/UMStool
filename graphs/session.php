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
 * Raw to UMS graph for a session
 * @author Philip Kent <kentphilip@gmail.com>
 * @package UMSTool
 */

if(!array_key_exists('id', $_GET) || !is_numeric($_GET['id']))
{
	header('HTTP/1.0 404 Not Found');
	return;
}

try {
	$session = Factory::manufactureSession($_GET['id']);
}
catch (Exception $e)
{
	header('HTTP/1.0 404 Not Found');
}

require("../lib/pchart/class/pDraw.class.php");
require("../lib/pchart/class/pImage.class.php");
require("../lib/pchart/class/pData.class.php");
require("../lib/pchart/class/pScatter.class.php");
require("../lib/pchart/class/pCache.class.php");

switch($session->getMonth())
{
	case Session::SESSION_AUTUMN:
		$monthname = 'Autumn';
		break;
	case Session::SESSION_WINTER:
		$monthname = 'Winter';
		break;
	case Session::SESSION_SPRING:
		$monthname = 'Spring';
		break;
	case Session::SESSION_SUMMER:
		$monthname = 'Summer';
		break;
}

/* Create the pData object */
$myData = new pData();  
$paper = $session->getPaper();

if ($session->getPaper()->getCourse()->getType() == Course::TYPE_ALEVEL)
{
	if ($session->getGradeStar() == '')
	{
		$cap = $session->getCap();

		$raw = array(0, $session->getGradeN(), $session->getGradeE(), $session->getGradeD(), $session->getGradeC(), $session->getGradeB(), $session->getGradeA(), $cap, $paper->getMaxRaw());
		$ums = array(0, $paper->getMaxUms()*0.3, $paper->getMaxUms()*0.4, $paper->getMaxUms()*0.5, $paper->getMaxUms()*0.6, $paper->getMaxUms()*0.7, $paper->getMaxUms()*0.8, $paper->getMaxUms(), $paper->getMaxUms());
	}
	else
	{
		$cap = $session->getCap();
		
		$raw = array(0, $session->getGradeN(), $session->getGradeE(), $session->getGradeD(), $session->getGradeC(), $session->getGradeB(), $session->getGradeA(), $session->getGradeStar(), $cap, $paper->getMaxRaw());
		$ums = array(0, $paper->getMaxUms()*0.3, $paper->getMaxUms()*0.4, $paper->getMaxUms()*0.5, $paper->getMaxUms()*0.6, $paper->getMaxUms()*0.7, $paper->getMaxUms()*0.8, $paper->getMaxUms()*0.9, $paper->getMaxUms(), $paper->getMaxUms());
	}
}
else
{
	$cap = $session->getCap();
	
	$raw = array(0, $session->getGradeG(), $session->getGradeF(), $session->getGradeE(), $session->getGradeD(), $session->getGradeC(), $session->getGradeB(), $session->getGradeA(), $session->getGradeStar(), $cap, $paper->getMaxRaw());
	$ums = array(0, $paper->getMaxUms()*0.2, $paper->getMaxUms()*0.3, $paper->getMaxUms()*0.4, $paper->getMaxUms()*0.5, $paper->getMaxUms()*0.6, $paper->getMaxUms()*0.7, $paper->getMaxUms()*0.8, $paper->getMaxUms()*0.9, $paper->getMaxUms(), $paper->getMaxUms());
}
	
// Add data to graph
$myData->addPoints($raw, "RAW");
$myData->addPoints($ums, "UMS");

// RAW axis - set name, position and apply markers
$myData->setAxisName(0, "RAW");
$myData->setAxisXY(0, AXIS_X);
$myData->setAxisPosition(0, AXIS_POSITION_BOTTOM);
$myData->setSerieShape("RAW", SERIE_SHAPE_FILLEDTRIANGLE);

// UMS axis - set name and position
$myData->setSerieOnAxis("UMS", 1);
$myData->setAxisName(1, "UMS");
$myData->setAxisXY(1, AXIS_Y);
$myData->setAxisPosition(1, AXIS_POSITION_LEFT);

// Bind data
$myData->setScatterSerie("RAW", "UMS", 0);
$myData->setScatterSerieDescription(0, "Conversion line");
$myData->setScatterSerieTicks(1, 1);
$myData->setScatterSerieColor(0, array("R" => 205, "G" => 105, "B" => 0));

// Cache
$myCache = new pCache(array('CacheFolder' => UMSTOOL_CACHE));
$ChartHash = $myCache->getHash($myData);

if(!array_key_exists('size', $_GET) || $_GET['size'] != 'large')
{
	$size = 'small';
}
else
{
	$size = 'large';
}

if($myCache->isInCache($ChartHash.$size))
{
	 $myCache->strokeFromCache($ChartHash.$size,"cache.png");
	 return;
}

/*
 * If no size set, or if size is not large, show normal graph
 */
if ($size == 'small')
{
	// Aethetics - draw image area
	$myPicture = new pImage(700, 350, $myData);
	$myPicture->setGraphArea(50, 50, 680, 300);

	// Set up font
	$myPicture->setFontProperties(array("R" => 255,"G" => 255,"B" => 255, "FontName" => "../lib/pchart/fonts/Ubuntu-Bold.ttf", "FontSize" => 10));

	// Draw background colour, hashes and gradient
	$Settings = array("R" => 255, "G" => 255, "B" => 255, "Dash" => 1, "DashR" => 230, "DashG" => 230, "DashB" => 230);
	$myPicture->drawFilledRectangle(0, 0, 700, 350, $Settings); 
	$myPicture->drawGradientArea(0, 0, 700, 350, DIRECTION_VERTICAL, array("StartR" => 0, "StartG" => 0, "StartB" => 0, "EndR" => 50, "EndG" => 50, "EndB" => 50, "Alpha" => 80));

	// Draw border
	$myPicture->drawRectangle(0, 0, 699, 349, array("R" => 0, "G" => 0, "B" => 0)); 
}
else
{
	// Aethetics - draw image area
	$myPicture = new pImage(2000, 2000, $myData);
	$myPicture->setGraphArea(50, 50, 1980, 1950);

	// Set up font
	$myPicture->setFontProperties(array("R" => 255,"G" => 255,"B" => 255, "FontName" => "../lib/pchart/fonts/Ubuntu-Bold.ttf", "FontSize" => 10));

	// Draw background colour, hashes and gradient
	$Settings = array("R" => 255, "G" => 255, "B" => 255, "Dash" => 1, "DashR" => 230, "DashG" => 230, "DashB" => 230);
	$myPicture->drawFilledRectangle(0, 0, 2000, 2000, $Settings); 
	$myPicture->drawGradientArea(0, 0, 3000, 3000, DIRECTION_VERTICAL, array("StartR" => 0, "StartG" => 0, "StartB" => 0, "EndR" => 50, "EndG" => 50, "EndB" => 50, "Alpha" => 80));

	// Draw border
	$myPicture->drawRectangle(0, 0, 1999, 1999, array("R" => 0, "G" => 0, "B" => 0)); 
}

// Add shadow to text
$myPicture->setShadow(TRUE, array("X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" =>0 , "Alpha" => 10));

// Add title
$myPicture->drawText(20, 25, $paper->getName().': '.$monthname.' '.$session->getYear());

// Set up font
$myPicture->setFontProperties(array("R" => 255,"G" => 255,"B" => 255, "FontName" => "../lib/pchart/fonts/Ubuntu.ttf", "FontSize" => 10));

if ($size == 'small')
{
	$myPicture->drawText(10, 340, 'Generated by UMS Tools - http://umstools.sf.net', array("R" => 40, "G" => 40, "B" => 40));
}
else
{
	$myPicture->drawText(10, 1990, 'Generated by UMS Tools - http://umstools.sf.net', array("R" => 40, "G" => 40, "B" => 40));
}

$myScatter = new pScatter($myPicture, $myData);

// Set scale parameters and draw scale
if ($size == 'small')
{
	$AxisBoundaries = array(0 => array("Min" => 0, "Max" => $paper->getMaxRaw(), "Rows" => $paper->getMaxRaw()/5, "RowHeight"=>5), 1 => array("Min" => 0, "Max" => $paper->getMaxUms(), "Rows" => $paper->getMaxUms()/10, "RowHeight" => 10));
	$ScaleSettings = array("AxisR" => 255, "AxisG" => 255, "AxisB" => 255, "AxisR" => 255, "AxisG" => 255, "AxisB" => 255, "TickR"=>255,"TickG" => 255, "TickB" => 255, "Mode" => SCALE_MODE_MANUAL, "ManualScale" => $AxisBoundaries, "DrawXLines" => TRUE, "DrawYLines" => ALL, "GridTicks" => 1);
}
else
{
	$AxisBoundaries = array(0 => array("Min" => 0, "Max" => $paper->getMaxRaw(), "Rows" => $paper->getMaxRaw()/2, "RowHeight"=>2), 1 => array("Min" => 0, "Max" => $paper->getMaxUms(), "Rows" => $paper->getMaxUms()/2, "RowHeight" => 2));
	$ScaleSettings = array("DrawSubTicks" => TRUE, "InnerSubTickWidth" => 3, "SubTickR" => 205, "SubTickG" => 105, "SubTickB" => 0, "AxisR" => 255, "AxisG" => 255, "AxisB" => 255, "AxisR" => 255, "AxisG" => 255, "AxisB" => 255, "TickR"=>255,"TickG" => 255, "TickB" => 255, "Mode" => SCALE_MODE_MANUAL, "ManualScale" => $AxisBoundaries, "DrawXLines" => TRUE, "DrawYLines" => ALL, "GridTicks" => 1);
}

$myScatter->drawScatterScale($ScaleSettings);

// Draw legend
if ($size == 'small')
{
	$myScatter->drawScatterLegend(585, 15, array("Mode" => LEGEND_HORIZONTAL, "Style" => LEGEND_NOBORDER, "FontR" => 255, "FontG" => 255, "FontB" => 255));
}
else
{
	$myScatter->drawScatterLegend(1885, 15, array("Mode" => LEGEND_HORIZONTAL, "Style" => LEGEND_NOBORDER, "FontR" => 255, "FontG" => 255, "FontB" => 255));
}

// Draw charts and render
$myScatter->drawScatterLineChart();
$myScatter->drawScatterPlotChart();

// Save in cache
$myCache->writeToCache($ChartHash.$size, $myPicture);

$myPicture->stroke();