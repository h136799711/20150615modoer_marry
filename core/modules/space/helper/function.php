<?php
/**
* @author moufer<moufer@163.com>
* @pageage space
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');

function space_template($filename, $templateid=0, $update = FALSE, $show_error = false)
{
    return template($filename, 'space', $templateid, $update);
}

/** end **/