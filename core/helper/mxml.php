<?php
/**
 * xml与array互转类
 * 请使用ms_mxml，此文件兼容旧版
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2009 Moufersoft
 * @website www.modoer.com
 */
!defined('IN_MUDDER') && exit('Access Denied');

class mxml 
{
    function from_array($arr, $level = 1)
    {
    		return ms_mxml::from_array($arr, $level);
    }

    function to_array($xmlfile, $is_file = true)
    {
    		return ms_mxml::to_array($xmlfile, $is_file);
    	}
}

/** end **/