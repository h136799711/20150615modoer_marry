<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

if($_GET['rid']) {
    location(url("review/member/ac/edit/rid/$_GET[rid]"));//member-ac-review_add
} else {
    location(url("review/member/ac/add/type"));
}
?>