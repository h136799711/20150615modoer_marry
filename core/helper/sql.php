<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/

function sql_get_create_table($sql, $dbcharset) {
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
		(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
}

function sql_run_query($sql) {
	global $_G;

	$sql = str_replace("\r", "\n", str_replace(' modoer_', ' '.$_G['dns']['dbpre'], $sql));

    //pares mysql function
    $funcs = array();
    $start_split = 'DELIMITER ;;;';
    $end_split = 'END;;;';
    $k = 0;
    do {
        $i = strpos($sql, $start_split, $k);
        if($i !== false) {
            $j = strpos($sql, $end_split, $i);
            if($j !== false) {
                $funcs[] = substr($sql, $i, $j - $i + strlen($end_split));
            }
            $k += $i + strlen($start_split);
        }
        
    } while ( $i !== false );

    if($funcs) foreach ($funcs as $k => $value) {
        $sql = str_replace($value, '', $sql);
        $value = str_replace($start_split, '', $value);
        $value = str_replace($end_split, 'END', $value);
        $funcs[$k] = $value;
    }

	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= $query[0] == '#' ? '' : $query;
		}
		$num++;
	}
	unset($sql);

    if($funcs) foreach ($funcs as $value) {
        $ret[] = $value;
    }

	foreach($ret as $query) {
		$query = trim($query);
		if($query) {
			if(substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1", $query);
				//$create_text .= 'create'.$name.' ... <font color="#0000EE">succeed</font><br />';
				$_G['db']->exec(sql_get_create_table($query, $_G['dns']['dbcharset']));
				//$tablenum++;
			} else {
				$_G['db']->exec($query);
			}
		}
	}
}

function sql_rename_table($oldtablename,$newtablename,$add_dbpre=true) {
    global $_G;
    if($add_dbpre) {
        $oldtablename = $_G['dns']['dbpre'] . $oldtablename;
        $newtablename = $_G['dns']['dbpre'] . $newtablename;        
    }
    $sql = "RENAME TABLE  `$oldtablename` TO  `$newtablename`";
    $_G['db']->exec($sql);
}

function sql_create_table($tablename, $sql) {
    global $_G;
    $dbcharset = $_G['dns']['dbcharset'];
    $tablename = $_G['dns']['dbpre'] . $tablename;
    $sql = "CREATE TABLE IF NOT EXISTS " . $tablename . " ($sql) ";
    if ($_G['db']->version() > '4.1') {
        $sql .= "ENGINE=MyISAM" . ($dbcharset ? " DEFAULT CHARSET=$dbcharset" : '');
    } else {
        $sql .= "TYPE=MyISAM";
    }
    $_G['db']->exec($sql);
}

function sql_exists_table($tablename, $add_dbpre=tru) {
    global $_G;

    if($add_dbpre) {
        $tablename = $_G['dns']['dbpre'] . $tablename;       
    }

    $row = $_G['db']->query("SHOW TABLES FROM `" . $_G['dns']['dbname'] . "` LIKE '" . $tablename . "'");
    if(!$row) return FALSE;
    $result = $row->fetch_array();
    $row->free_result();

    return !empty($result);
}

function sql_exists_field($tablename, $feild) {
    global $_G;
    $tablename = $_G['dns']['dbpre'] . $tablename;
    $row = $_G['db']->query("SHOW COLUMNS FROM $tablename LIKE '$feild'");
    if(!$row) return FALSE;
    $rt = $row->fetch_array();
    $row->free_result();

    return $rt['Field'] == $feild;
}

function sql_delete_table($tablename, $add_dbpre=true) {
    global $_G;
    if($add_dbpre) $tablename = $_G['dns']['dbpre'].$tablename;
    return $_G['db']->exec("DROP TABLE IF EXISTS $tablename");
}

function sql_drop_field($tablename,$field) {
    global $_G;
    if(!sql_exists_field($tablename,$field)) return;
    $tablename = $_G['dns']['dbpre'] . $tablename;
    $sql = "ALTER TABLE `$tablename` DROP `$field`";
    $_G['db']->exec($sql);
}

//"table","delflag","add","delflag tinyint(1) NOT NULL DEFAULT '0' AFTER new"
function sql_alter_field($tablename, $field, $act, $sql) {
    global $_G;
    $tablename = $_G['dns']['dbpre'] . $tablename;
    $row = $_G['db']->query("SHOW COLUMNS FROM $tablename LIKE '$field'");
    if($row) {
        $rt = $row->fetch_array();
        $row->free_result();
    } else {
        $rt = array();
    }

    $lowersql = strtolower("ALTER TABLE $tablename $act $sql");
    if ((strpos($lowersql,' add ') !== false && $rt['Field']!=$field) || 
        ((strpos($lowersql,' drop ') !== false || 
        strpos($lowersql,' change ') !== false) && $rt['Field'] == $field)) {
        $_G['db']->exec($lowersql);
    }
}

//"shops", "ADD", "ownerid", "ownerid (ownerid,status,addtime)"
//"shops", "DROP", "cate", "cate"
function sql_alter_index($tablename, $alter, $iname, $iparam) {
    global $_G;
    $rt = sql_exists_table_index($tablename,$iname);
    $alter = strtoupper($alter);
    $tablename = $_G['dns']['dbpre'] . $tablename;

    if(($rt && $alter == 'DROP') || (!$rt && $alter == 'ADD')) {
        $sql = "ALTER TABLE $tablename $alter INDEX $iparam";
        $_G['db']->exec($sql);
    }
}

function sql_alter_pk($tablename, $alter, $iparam='') {
    global $_G;
    $rt = sql_exists_table_index($tablename, 'PRIMARY');
    $alter = strtoupper($alter);
    $tablename = $_G['dns']['dbpre'] . $tablename;
    $sql = '';
    if($alter == 'ADD' && $iparam && !$rt)
        $sql = "ALTER TABLE $tablename ADD PRIMARY KEY $iparam";
    elseif($alter == 'DROP' && !$rt)
        $sql = "ALTER TABLE $tablename DROP PRIMARY KEY";
    if(!$sql) return;
    $_G['db']->exec($sql);
}

function sql_exists_table_index($tablename,$indexname) {
    global $_G;
    $tablename = $_G['dns']['dbpre'] . $tablename;
    $rt = FALSE;
    $query = $_G['db']->query("SHOW INDEX FROM $tablename");
    if($query) {
        while($row = $query->fetch_array()) {
            if($row['Key_name'] == $indexname) {
                $rt = TRUE;
                break;
            }
        }
        $query->free_result();
    }
    return $rt;
}
?>