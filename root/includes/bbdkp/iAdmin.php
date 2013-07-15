<?php

namespace bbdkp;
/**
 * @ignore
 */
if (! defined('IN_PHPBB'))
{
	exit();
}

/**
 * Admin
 * 
 * Admin Interface
 * 
 * @package bbDKP
 * @link http://www.bbdkp.com
 * @author Sajaki@gmail.com
 * @copyright 2013 bbdkp
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @version 1.3.0
 */
 interface iAdmin 
{
	function gen_group_key($part1, $part2, $part3);
	function curl($url, $return_Server_Response_Header = false, $loud= false); 
	function switch_order($sort_order, $arg = URI_ORDER);
	function create_bar($width, $show_text = '', $color = '#AA0033');
	function generate_pagination2($base_url, $num_items, $per_page, $start_item, $add_prevnext_text = true, $tpl_prefix = '');
	function log_insert($values);
	
}

?>