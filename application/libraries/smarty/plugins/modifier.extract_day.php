<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**  
 * Type:     modifier<br>
 * Name:     Extract day
 * Purpose:  Extract day from date
 * 
 * @author 	Thai San  Gmail(sanpc168@gmail.com)
 * @param 	Date String eg.2013-12-25 YYYY-mm-dd
 * @return 	Day 
 */
function smarty_modifier_extract_day($date_string)
{
	$day = explode("-",$date_string);
	return $day[2];
} 
?>