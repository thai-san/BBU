<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**  
 * Type:     modifier<br>
 * Name:     Square Meter to Hectare<br>
 * Purpose:  simple search/replace
 * 
 * @author 	Thai San  Gmail(sanpc168@gmail.com)
 * @param 	input Square Meter
 * @return 	Hectare 
 */
function smarty_modifier_sqrmeter_to_ha($number)
{
    return ($number/10000);
} 

?>