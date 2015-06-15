<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class url_group {

    function topic($params){
        return url("group/topic/tpid/$params[tpid]");
    }

}
?>