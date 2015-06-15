<?php

if (!defined('IN_OKNJUYGF') || !defined('IN_EWTRYTU')) {
    show_error('File Error.');
    exit(0);
}
!defined('IN_MUDDER') && exit('Access Denied');
class ms_liccode {
    public $key = 1;
    function decode($str) {
        list($code, $key, $kuid, $aid, $len) = explode(',', $str);
        $this->set_key($key);
        $uid = $kuid / $this->key;
        $start = $this->_get_start($uid);
        list($str1, $str2) = $this->_str3_de($code, $start, $len);
        $str2 = $this->_str2_de($str2);
        foreach ($str2 as $k) {
            $str1{$k} = strtoupper($str1{$k});
        }
        $str = $this->_str1_de($str1);
        return array(
            $str,
            $uid,
            $aid,
            $key
        );
    }
    function set_key($key) {
        $this->key = (int)$key;
    }
    function _get_start($uid) {
        return ($uid % 2 == 0) ? 0 : 1;
    }
    function _str1_de($str) {
        $code = base64_decode($str);
        return $this->_chr($code);
    }
    function _str2_de($str) {
        $code = explode(',', ($this->_chr(base64_decode($str))));
        foreach ($code as $k => $v) {
            $code[$k] = $v - $this->key;
        }
        return $code;
    }
    function _str3_de($str, $start, $s1_len) {
        $len = strlen($str);
        $str1 = $str2 = '';
        for ($i = 0; $i < $len; $i++) {
            if ($i % 2 == 0) {
                if ($start) $str2.= $str{$i};
                else $str1.= $str{$i};
            } else {
                if ($start) $str1.= $str{$i};
                else $str2.= $str{$i};
            }
            if (strlen($str1) >= $s1_len) break;
        }
        $str2.= substr($str, ($s1_len + strlen($str2)));
        return array(
            $str1,
            $str2
        );
    }
    function _ord($str) {
        $result = '';
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $result.= $split . (ord($str{$i}) * ($i + $this->key));
            $split = ',';
        }
        return $result;
    }
    function _chr($str) {
        $result = '';
        $i = 0;
        foreach (explode(',', $str) as $val) {
            $result.= chr($val / ($i++ + $this->key));
        }
        return $result;
    }
}
?>
