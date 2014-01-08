<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**  
 * Type:     modifier<br>
 * Name:     Extract Year
 * Purpose:  Extract Year from Date
 * 
 * @author 	Thai San  Gmail(sanpc168@gmail.com)
 * @param 	Date String eg.2013-12-25 YYYY-mm-dd
 * @return 	Year 
 */
function smarty_modifier_extract_year($date_string)
{
	$year = explode("-",$date_string);
	return $year[0];
} 

?>