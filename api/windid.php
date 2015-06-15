<?php
@define('DS', DIRECTORY_SEPARATOR);
@define('IN_MUDDER', TRUE);
@define('MUDDER_ROOT', substr(dirname(__FILE__),0,-3));

$_G = array();
require_once MUDDER_ROOT . './data/config.php';
require_once MUDDER_ROOT . './core/lib/mysql.php';
require_once MUDDER_ROOT . './core/lib/database.php';
$_G['cfg'] = include MUDDER_ROOT.'./data/cachefiles/modoer_config.php';


require_once MUDDER_ROOT . './windid_client/src/windid/WindidApi.php';//引入windid接口类
require_once MUDDER_ROOT . './windid_client/src/windid/service/base/WindidUtility.php'; //引入windid工具库

$_windidkey = getInput('windidkey', 'get');
$_time = (int)getInput('time', 'get');
$_clentid = (int)getInput('clientid', 'get');
$time = Pw::getTime();
$timestamp = $time;

if (WindidUtility::appKey(WINDID_CLIENT_ID, $_time, WINDID_CLIENT_KEY) != $_windidkey) showMessage('fail1'); //对密钥进行验证
if ($time - $_time > 120) showMessage('timeout'); //检查通知是否超时

$operation = (int)getInput('operation', 'get');

list($method, $args) = getMethod($operation);
if (!$method) showMessage('fail2');

$notify = new notify();  //定义一个通知处理类 在这时定义为下一步所示的notify
if(!method_exists($notify, $method)) showMessage('success');//不指定的方法，默认返回成功状态
$result = call_user_func_array(array($notify,$method), getInput($args,'request'));
if ($result == true) showMessage('success');
showMessage('fail3');

class notify {

    private $db = null;
    private $dbpre = '';

    public function __construct() {
        global $_G;

        $_G['dns']['pconnect'] = 0;
        $this->dbpre = $_G['dns']['dbpre'];
        $this->db = new ms_database($_G['dns']);
        $this->db->connect();
    }
        
    public function test($uid) {
        return $uid ? true : false;
    }

    public function addUser($uid) {
        $api = WindidApi::api('user');
        if($user = $api->getUser($uid)) {
            //vp($user);exit;
            //客户端系统处理添加新用户
            $set = array();
            $set['username'] = $user['username'];
            $set['password'] = md5($user['password']);
            $set['email'] = $user['email'];
            $post['logintime'] = $post['regdate'] = $user['regdate'];
            $post['regip'] = $post['loginip'] = $user['regip'];
            $post['logincount'] = 1;
            $post['groupid'] = 10;
            $this->db->from('dbpre_members');
            $this->db->set($set);
            $this->db->where('uid',$uid);
            $this->db->insert();
        }
        return true;
    }

    public function editUser($uid) {
        $api = WindidApi::api('user');
        $user = $api->getUser($uid);
        if($user) {
            //客户端系统处理修改用户信息
            $set = array();
            $set['username'] = $user['username'];
            $set['password'] = $user['password'];
            $set['email'] = $user['email'];
            $this->db->from('dbpre_members');
            $this->db->set($set);
            $this->db->where('uid',$uid);
            $this->db->update();
        }
        return true;
    }

    public function deleteUser($uid) {
        $this->db->exec("DELETE FROM {$this->dbpre}members WHERE uid IN ($uid)");
        $this->db->exec("DELETE FROM {$this->dbpre}spaces WHERE uid IN ($uid)");
        return true;
    }

    public function synLogin($uid) {
		$uid = intval($uid);

        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');

        $cookietime = 31536000;
        $q = $this->db->query("SELECT uid,username FROM {$this->dbpre}members WHERE uid='$uid'");
        if($q) {
            $member = $q->fetch_array();
            $q->free_result();
        }
        if($member) {
            mo_setcookie('uid', $uid, $cookietime);
            mo_setcookie('hash', mo_formhash($uid, $member['username'], ''), $cookietime);
        } else {
            $api = WindidApi::api('user');
            $user = $api->getUser($uid);
            mo_setcookie('username', $user['username'], $cookietime);
            mo_setcookie('activationauth', mo_authcode_ex($uid . "\t" . $user['username']), $cookietime);
        }
        return true;
    }

    public function synLogout($uid) {
		//note 同步登出 API 接口
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');

        mo_setcookie('uid', '', -86400 * 365);
        mo_setcookie('hash', '', -86400 * 365);
        mo_setcookie('username', '', -86400 * 365);
        mo_setcookie('activationauth', '', -86400 * 365);
        return true;
    }
}

function getInput($key, $method = 'get') {
    if (is_array($key)) {
            $result = array();
            foreach ($key as $_k=>$_v) {
                $result[$_k] = getInput($_v, $method);
            }
            return $result;
    }
    switch ($method) {
        case 'get':
          return isset($_GET[$key]) ? $_GET[$key] : null;
        case 'post':
          return isset($_POST[$key]) ? $_POST[$key] : null; 
        case 'request':
          return isset($_REQUEST[$key]) ? $_REQUEST[$key] : null;   
       default:
            return null;
    }
}
/**
windid只接收两种返回状态‘success’ 和'fail'，并在windid后台的“消息队列”里显示
*/
function showMessage($message = 'success') {
    echo $message;
    exit();
}
/**
根据操作代表获取操作方法，获取参数
*/
function getMethod($operation) {
    $config = include MUDDER_ROOT.'./windid_client/src/windid/service/base/WindidNotifyConf.php';  //在这个文件中，定义了通知的接口类型，接收参数
    $method = isset($config[$operation]['method']) ? $config[$operation]['method'] : '';
    $args = isset($config[$operation]['args']) ? $config[$operation]['args'] : array();
    return array($method, $args);
}

/**************** Modoer库函数 ********************/
//简单加密
function mo_authcode_ex($string, $operation = 'E') {
    global $_G;
    $key = $_G['cfg']['authkey'];
    $key = md5($key);
    $key_length = strlen($key);
    $string = $operation == 'D'?base64_decode($string):substr(md5($string.$key),0,8) . $string;
    $string_length = strlen($string);
    $rndkey = $box = array();
    $result = '';
    for($i=0; $i<=255; $i++) {
        $rndkey[$i]=ord($key[$i%$key_length]);
        $box[$i]=$i;
    }
    for($j=$i=0; $i<256; $i++) {
        $j=($j+$box[$i]+$rndkey[$i]) % 256;
        $tmp=$box[$i];
        $box[$i]=$box[$j];
        $box[$j]=$tmp;
    }
    for($a=$j=$i=0;$i<$string_length;$i++) {
        $a=($a+1)%256;
        $j=($j+$box[$a])%256;
        $tmp=$box[$a];
        $box[$a]=$box[$j];
        $box[$j]=$tmp;
        $result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
    }
    if($operation == 'D') {
        if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)) {
            return substr($result,8);
        } else {
            return'';
        }
    } else {
        return str_replace('=','',base64_encode($result));
    }
}

function mo_setcookie($var, $value, $life = 0) {
	global $timestamp, $_G, $_SERVER;
	$life = $life ? ($timestamp + $life) : 0;
	$secure = $_SERVER['SERVER_PORT'] == '443' ? 1 : 0;
	$var = $_G['cookiepre'].$var;
	return setcookie($var, $value, $life, $_G['cookiepath'], $_G['cookiedomain'], $secure);
}

//生成表单序列
function mo_formhash($p1, $p2, $p3) {
    global $_G;
    $authkey = $_G['cfg']['authkey'];
    return substr(md5($authkey . $p1 . $p2 . $p3), 8, 8);
}

function mo_arrayeval($array, $level = 0) {

	if(!is_array($array)) {
		return "'".$array."'";
	}

	if(is_array($array) && function_exists('var_export')) {
		return var_export($array, true);
	}

	$space = '';
	for($i = 0; $i <= $level; $i++) {
		$space .= "\t";
	}
	$evaluate = "array (\n\r";
	$comma = $space;
	if(is_array($array)) {
		foreach($array as $key => $val) {
			$key = is_string($key) ? '\''.mo_addcslashes($key).'\'' : $key;
			$val = !is_array($val) && (!preg_match("/^\-?[0-9]\d*$/", $val) || strlen($val) > 12) ? '\''.mo_addcslashes($val, '\'\\').'\'' : $val;
			if(is_array($val)) {
				$evaluate .= "$comma$key => ".mo_addcslashes($val, $level + 1);
			} else {
				$evaluate .= "$comma$key => $val";
			}
			$comma = ",\n\r$space";
		}
	}
	$evaluate .= "\n\r$space)";
	return $evaluate;
}

function mo_addcslashes($string) {
    return $string ? addcslashes($string, '\'\\') : '';
}

function mo_debug($name, $array) {
    global $timestamp;
    $logfile = MUDDER_ROOT.'./api/api_'.$name.'.log';
    if(@$fp = fopen($logfile, 'a')) {
        @fwrite($fp, date('Y-m-d H:i:s', $timestamp)."\r\n");
        @fwrite($fp, (is_array($array) ? mo_arrayeval($array,TRUE) : $array) . "\r\n");
        @fwrite($fp, '--------------------------------------------'."\r\n");
        @fclose($fp);
    }
}

//写入log
function log_write($file, $log) {
    $yearmonth = gmdate('Ym', time());
    $logdir = MUDDER_ROOT.'data/logs'.DS;
    $logfile = $logdir.$yearmonth.'_'.$file.'.php';
    if(@filesize($logfile) > 2048000) {
        $dir = opendir($logdir);
        $length = strlen($file);
        $maxid = $id = 0;
        while($entry = readdir($dir)) {
            if(strposex($entry, $yearmonth.'_'.$file)) {
                $id = intval(substr($entry, $length + 8, -4));
                $id > $maxid && $maxid = $id;
            }
        }
        closedir($dir);
        $logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($maxid + 1).'.php';
        @rename($logfile, $logfilebak);
    }
    if($fp = @fopen($logfile, 'a')) {
        @flock($fp, 2);
        $log = is_array($log) ? $log : array($log);
        foreach($log as $tmp) {
            fwrite($fp, "<?PHP exit;?>\t".str_replace(array('<?', '?>'), '', $tmp)."\n");
        }
        fclose($fp);
    }
}
/* end */