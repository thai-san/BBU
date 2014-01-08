<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**  
 * Type:     modifier<br>
 * Name:     Extract Month
 * Purpose:  Extract month from date
 * 
 * @author 	Thai San  Gmail(sanpc168@gmail.com)
 * @param 	Date String eg.2013-12-25 YYYY-mm-dd
 * @return 	Month 
 */
function smarty_modifier_extract_month($date_string)
{
	$month = explode("-",$date_string);
	return $month[1];
} 
?>