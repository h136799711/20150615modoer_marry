<?php
function get_category_config($catid) {
    $C = _G('loader')->model('item:category');
    $pid = $C->get_parent_id($catid, 1);
    $cate = _G('loader')->variable('category','item');
    if(!$cate || !$cate[$pid]) return false;
    return $cate[$pid]['config'];
}

function has_dbfunction_nearby() {
    global $_G;
    $q = $_G['db']->query('select database() as db');
    $v = $q->fetch_array();
    $db = $v['db'];

    $q = $_G['db']->query('show function status');
    if(!$q) return FALSE;
    $funcs = array();
    while ($v=$q->fetch_array()) {
        if($db==$v['Db'] && 'ModoerGetDistance'==$v['Name']) return TRUE;
    }
    $q->free_result();
    return FALSE;
}

function add_dbfunction_nearby() {
    global $_G;
    $SQL="CREATE FUNCTION `ModoerGetDistance` (`lng1` DECIMAL(12,8),`lat1` DECIMAL(12,8),`lng2` DECIMAL(12,8),`lat2` DECIMAL(12,8))
    RETURNS INT(10) UNSIGNED NOT DETERMINISTIC NO SQL SQL SECURITY DEFINER
    BEGIN
        declare s DOUBLE;
        declare radLat1 DOUBLE;
        declare radLat2 DOUBLE;
        declare radLng1 DOUBLE;
        declare radLng2 DOUBLE;
        set radLat1=lat1*PI()/180.0;
        set radLat2=lat2*PI()/180.0;
        set radLng1=lng1*PI()/180.0;
        set radLng2=lng2*PI()/180.0;
        set s = ACOS(COS(radLat1)*COS(radLat2)*COS(radLng1-radLng2)+SIN(radLat1)*SIN(radLat2));
        set s = s*6378137;
        set s = round(s* 10000) / 10000;
        return s;
    END";
    $_G['db']->catch = true;
    $_G['db']->exec($SQL);
    if($error = $_G['db']->error()) {
    	redirect($error . '<br />对不起，您的MySQL账号不支持或没有权限创建存储过程，无法使用手机web附近主题功能！');
    }
}

function del_dbfunction_nearby() {
    global $_G;
    $SQL="DROP FUNCTION IF EXISTS `ModoerGetDistance`";
    $_G['db']->exec($SQL);
}