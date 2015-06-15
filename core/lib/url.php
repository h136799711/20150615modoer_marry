<?php
!defined('IN_MUDDER') && exit('Access Denied');

/**
 * URL重写模式
 */
define('MF_URL_REWRITE_NORMAL', 	'normal');
define('MF_URL_REWRITE_HTML', 		'html');
define('MF_URL_REWRITE_PATH', 		'pathinfo');
/**
 * 分站URL展示模式
 */
define('MF_URL_CITY_NORMAL', 		0);
define('MF_URL_CITY_SUBDOMAIN',		1);
define('MF_URL_CITY_PATHNAME', 		2);
/**
 * URL替换行为
 */
define('MF_URL_PREG_PARSE', 		'P');
define('MF_URL_PREG_CREATE', 		'C');

/**
* URL管理类
*/
class ms_url {
	/**
	 * url地址是否存在（能指向相应的模块页面）
	 * @var boolean
	 */
	private $url_not_found = false;
	/**
	 * 当前页面URL所使用的url重写模式，一般和下方一致，不一致的话一般是中途修改过url重写模式
	 * 而之前的URL已经被搜索引擎收录，从搜索引擎进入是和当前配置的不一致造成页面无法访问的现象
	 * @var string
	 */
	private $real_rewrite_mod = '';
	/**
	 * 当前来路URL是否隐藏了index.php (并非系统设置，而是当前访问的URL上进行的判断)
	 * @var string
	 */
	private $real_hide_index = true;
	/**
	 * 系统配置设置的url重写模式
	 * @var string
	 */
	private $rewrite_mod = 'normal';
	/**
	 * 系统配置设置的城市分站url模式
	 * @var integer
	 */
	private $city_mod = 0;
	/**
	 * 来路URL重写模式和系统设置的不一致时是否进行跳转
	 * @var boolean
	 */
	private $rewrite_location = false;
	/**
	 * 系统配置设置的是否隐藏index.php
	 * @var integer
	 */
	private $hide_index = 0;
	/**
	 * 中文兼容
	 * @var boolean
	 */
	private $chinses_compatible = false;
	/**
	 * url地址中获取的城市域名前缀（目录名）
	 * @var string
	 */
	private $sldomain = '';
	/**
	 * url地址解析后的url表达式
	 * @var string
	 */
	private $url_exp = '';
	/**
	 * url解析后的数组
	 * @var array
	 */
	private $params = array();
	/**
	 * url参数解析后进行规则配对成键名+键值数组
	 * @var array
	 */
	private $get = array();
	/**
	 * 当前URL是index.php/形式模拟URL重写
	 * @var boolean
	 */
	private $use_sim = false;
	/**
	 * 当前URL是否使用了URL重写
	 * @var boolean
	 */
	private $use_rewrite = false;
	
	/**
	 * 构造函数，加载配置，解析当前页面URL
	 */
	public function __construct() {
		$this->_init_config();
		$this->_pares_url();
	}

	/**
	 * 提供初始url参数，根据索引号取得
	 * @param  int $index 数组下标
	 * @return [type]
	 */
	public function param($index = null) {
		if($index==null) return $this->params;
		return $this->params[$index];
	}

	/**
	 * 判断url重写模式是否和系统设置的一致
	 * @return bool
	 */
	public function rewirte_mod_compare() {
		if(!$this->real_rewrite_mod) return TRUE;
		return $this->rewrite_mod == $this->real_rewrite_mod && $this->hide_index == $this->real_hide_index;
	}

	/**
	 * 获取当前页面解析后的URL表达式
	 * @return string
	 */
	public function get_url_exp() {
		return $this->url_exp;
	}

	/**
	 * 访问页面不存在
	 * @return boolean
	 */
	public function is_404() {
		return $this->url_not_found;
	}

	/**
	 * 访问页面是否使用了rewrite功能,normal不属于rewrite
	 * @return boolean
	 */
	public function is_rewrite() {
		return $this->use_rewrite;
	}

	/**
	 * 获取二级域名前缀
	 * @return string
	 */
	public function get_sldomain() {
		return $this->sldomain;
	}

	/**
	 * 获取当前系统URL重写模式
	 * @return string
	 */
	public function get_rewrite_mod() {
		return $this->rewrite_mod;
	}

	/**
	 * 获取当前系统城市分站URL形式
	 * @return int
	 */
	public function get_city_mod() {
		return $this->city_mod;
	}

	/**
	 * url表达式生成url地址, rewrite_mod类型
	 * @param  string  $url_exp     URL表达式
	 * @param  boolean $full_url    完整路径
	 * @param  string  $rewrite_mod URL重写模型类型
	 * @return string
	 */
	public function create($url_exp, $full_url = false, $rewrite_mod = null) {
		static $cache = array();
		if($cache_info = $cache[$url_exp]) {
			list($url_address,$p2,$p3) = $cache_info;
			if($p2===$full_url && $p3===$rewrite_mod) return $url_address;
		}
		$url_exp = trim($url_exp);
		if(strtolower(substr($url_exp,0,4)) == 'http') {
			//非url表达式，为完整url地址
			return $url_exp;
		}
		//url表达式初步解析, 然后交给url改写类生成,最后返回url address
		$url_address = $this->_create($url_exp, $full_url, $rewrite_mod);
		$cache[$url_exp] = array($url_address, $full_url, $rewrite_mod);
		return $url_address;
	}

	/**
	 * 判断当前模块行为生成的URL是否为全局URL（没有分站二级域名，分站目录形式）
	 * @param  string $module 模块标识
	 * @param  string $act    模块内行为
	 * @return bool
	 */
	public function global_url($module, $act) {
		return $this->_domain_without($module, $act);
	}

	/**
	 * url相关配置初始化
	 * @return [type] [description]
	 */
	private function _init_config() {
		//URL重写模式
		$this->rewrite_mod = _G('cfg','rewrite') ? _G('cfg', 'rewrite_mod') : MF_URL_REWRITE_NORMAL;
		//分站URL显示模式
		$this->city_mod = _G('cfg', 'city_sldomain');
		//入口文件模拟Rewrite
		$this->hide_index = _G('cfg', 'rewrite_hide_index') ? true : false;
		//来路不一致跳转性
		$this->rewrite_location = _G('cfg', 'rewrite_location') ? true : false;
		$this->chinses_compatible = _G('cfg','rewritecompatible') ? true : false;
		if(!$this->rewrite_mod) $this->rewrite_mod = 'normal';
		//伪静态形式URL重写不能使用目录形式分站
		if(in_array($this->rewrite_mod, array(MF_URL_REWRITE_HTML, MF_URL_REWRITE_NORMAL)) 
			&& MF_URL_CITY_PATHNAME == $this->city_mod) {
				$this->city_mod = MF_URL_CITY_NORMAL;
		}
		//尚未判断当前来路是否隐藏index前，保持和系统设置一致
		$this->real_hide_index = $this->hide_index;
	}

	/**
	 * url表达式初步解析
	 * @param  string $url_exp     URL表达式
	 * @param  bool $full_url    是否显示完整路径
	 * @param  string $rewrite_mod URL模式类型
	 * @return string
	 */
	private function _create($url_exp, $full_url,$rewrite_mod) {
		static $domains = array();
		static $city = null;
		static $domain_without = array();
		$domain = $city_id =null;
		$url_exp = trim(trim($url_exp), '/');
		$info = explode("/", $url_exp);
		//url表达式包含指定的城市ID
		if(preg_match("/^city:([0-9]+)$/i", $info[0], $matches)) {
			$city_id = (int) $matches[1];
			$info = explode('/', substr($url_exp, strlen($matches[0])+1));
			if($city_id > 0) {
				$domain = $domains[$city_id];
				if(!$domain) {
					$domain = display('modoer:area',"aid/$city_id/keyname/domain");
					$domains[$city_id] = $domain;
				}
			} else {
				$domain = '{GLOBAL}';
			}
		}
		if(empty($info)) {
			$url_exp = "index";
			$module = "modoer";
			$act = "";
		} else {
			strtolower($info[0]) == 'modoer' && $info[0] = 'index';
			$module = $info[0];
			$act = $info[1];
			if(count($info) > 2) {
				//排除是否有空值现象，如果是空值，则从表达式中删除对应的参数（键和值）
				$newinfo = array(0 => $module, 1=> $act);
				for ($i=2; $i < count($info); $i = $i + 2) { 
					if($info[$i+1] == '') continue;
					$newinfo[$i] = $info[$i];
					$newinfo[$i+1] = $info[$i+1];
				}
				$url_exp = implode('/', $newinfo);
			} else {
				$url_exp = implode('/', $info);
			}
		}
		//首页
		if(in_array(strtolower($url_exp), array('index', 'index/index'))) {
			$url_exp = '';
		}
		if(!$city) $city = _G('city');
		if($domain == '{GLOBAL}') {
			$domain = '';
		} elseif (!$domain && $city) {
			//判断模块或行为是否需要城市二级域名或者城市目录
			$domain = $city['domain'];
		}
		//当前模块行为产生的URL是否是全局URL（不包含二级域名），如果是全局URL，则传入domain参数
		$dw_key = "{$module}_{$act}";
		if(!isset($domain_without[$dw_key])) {
			$domain_without[$dw_key] = $this->_domain_without($module, $act);
		}

		if(/*!$city_id && */$domain && $url_exp && $domain_without[$dw_key]) {
			$domain = '';
			// 使用二级域名做分站，则必须要完整路径
			if($this->city_mod == MF_URL_CITY_SUBDOMAIN) {
				$full_url = TRUE;
			}
		}
		//判断URL重写模式
		if($rewrite_mod == null || $rewrite_mod == $this->rewrite_mod) {
			$this->url->set_hide_index($this->hide_index); //是否显示入口文件
			$this->url->set_city_mod($this->city_mod);
			$address = $this->url->create($url_exp, $domain);
		} else {
			$url = $this->_factory($rewrite_mod);
			$url->set_hide_index($this->hide_index); //是否显示入口文件
			$url->set_city_mod($this->city_mod); //分站URL显示模式
			$address = $url->create($url_exp, $domain); //开始生成
		}

		//要求完整路径时
		if($full_url && 'http://' != strtolower(substr($address, 0, 7))) {
			//$address = _G('cfg','siteurl') . ltrim($address,'/');
			if(_G('sldomain')) {
				//二级域名被模块拦截成功，则说明当前域名不是网站常规域名
				if($this->city_mod == MF_URL_CITY_SUBDOMAIN && _G('city','domain')) {
					$fldomain = get_fl_domain(_G('cfg','siteurl'));
					$address = 'http://'._G('city','domain').'.'.$fldomain.'/'.ltrim($address,'/');
				} else {
					$address = _G('cfg','siteurl') . ltrim($address,'/');
				}
			} else {
				if($domain_without[$dw_key]) {
					$address = _G('cfg','siteurl') . ltrim($address,'/');
				} else {
					$address = 'http://'.get_current_domain().'/'.ltrim($address,'/');
				}
				
			}
		}
		return $address;
	}

	/**
	 * 解析当前打开的页面URL
	 * @return [type] [description]
	 */
	private function _pares_url() {
		$mod = $wurl = null;
		if(isset($_GET['Rewrite'])) {
			//HTML伪静态
			$mod = MF_URL_REWRITE_HTML;
			$wurl = $_GET['Rewrite'];
		} elseif($_GET['Pathinfo']) {
			//Pathinfo目录形式
			$mod = MF_URL_REWRITE_PATH;
			$wurl = $_GET['Pathinfo'];
		} elseif(strpos(request_uri(), '/index.php/') !== false) {
			//!$this->hide_index
			//通过入口文件引导的模拟rewrite功能
			list($mod, $wurl) = $this->_parse_index_url();
			//当前来路并没有隐藏index.php
			$this->real_hide_index = false;
		}

		if($mod) {

			if($this->rewrite_mod == $mod || ($this->rewrite_mod != $mod && $this->rewrite_location)) {
				$url = $this->_factory($mod);
				$this->url_exp = $url->pares($wurl);
				$this->url_not_found = $url->not_found;
				if(!$this->url_not_found) {
					$this->_pares_urlexp(); //解析url表达式成为可用url参数数组
					$this->_import_get(); //导入参数到$_GET
					if($url->sldomain) $this->sldomain = $url->sldomain; //url中指定的域名
				}
				if($this->rewrite_mod == $mod) $this->url = $url;
				$this->real_rewrite_mod = $mod;
			} else {
				//来路URL重写模式和设置的不一致
				$this->url_not_found = false;
			}
			if(MF_URL_REWRITE_NORMAL != $mod) $this->use_rewrite = true;
		}
		if(!$this->url) {
			$this->url = $this->_factory($this->rewrite_mod);
		}
		//设置URL改写是否需要隐藏入口文件(index.php，针对服务器不支持rewrite的)
		$this->url->set_hide_index($this->hide_index);
		//取得二级域名
		if(!$url->sldomain) $this->url->get_sldomain();
		if($url->sldomain) {
			//通过城市别名（二级域名前缀城市目录）获取城市ID。生成完整的URL表达式
			$city = get_city_for_doman($url->sldomain);
			$city_id = (int) $city['aid'];
			if($this->url_exp) $this->url_exp = "city:$city_id/" . $this->url_exp;
		}
	}

	/**
	 * URL重写使用了入口文件做引导的
	 * @return array [description]
	 */
	private function _parse_index_url() {
		$result = array('', '');
        // 获取请求的URI
        foreach (array('REQUEST_URI', 'HTTP_X_REWRITE_URL') as $var) {
            if ($uri = $_SERVER[$var]) {
                $uri = trim($uri);
                break;
            }
        }
        if(!$uri) return $result;
        // 去除多个/的情况
        $uri = preg_replace('%/{2,}%i', '/', $uri);
        $i = strpos(strtolower($uri), URLROOT . '/index.php/');
        if ($i !== false) {
            $uri = substr($uri, $i + strlen(URLROOT . '/index.php/'));
        } else {
        	return $result;
        }
        //使用系统设置的URL重写模式
        $this->use_sim = true; //模拟URL重写
        $result[0] = $this->rewrite_mod;
        $result[1] = $uri;
        return $result;
        /*
        //通过是否有“/”，来判断是否是PATHINFO模式
        if(strpos($uri, '/') === false) {
        	//通过文件后缀判断模拟rewrite模式
        	$ext = substr(strtolower($uri), -5);
        	if('.html' == $ext) {
        		//模拟HTML伪静态
        		$result[0] = MF_URL_REWRITE_HTML;
        		$result[1] = $uri;
        	} else {
        		//不是系统的URL，系统页面不存在
        		$this->url_not_found = true;
        	}
        } else {
        	//初步判断PATHINFO模式，进入相关类后再进行判断
        	$result[0] = MF_URL_REWRITE_PATH;
        	$result[1] = $uri;
        }
        return $result; //返回array(模式,URL)
        */
	}

	/**
	 * 判断一些模块和模块行为不需要城市目录或者二级域名
	 * @param  string $module 模块标识
	 * @param  string $act    模块内行为
	 * @return bool         
	 */
	private function _domain_without($module, $act) {
		static $citypath_without = null;
		if($citypath_without==null) {
			$cw_arr = array();
			$cfg_cw = _G('cfg', 'citypath_without');
			is_string($cfg_cw) && $cw_arr = explode("\r\n", $cfg_cw);
		    foreach(_G('modules') as $md) {
		    	//所有模块的会员管理行为
		        $cw_arr[] = "$md[flag]/member";
		    }
    		$cw_arr[] = 'member/*';
		    $cw_arr[] = 'index/*';
		    foreach ($cw_arr as $key => $match) {
		    	list($m, $a) = explode('/', $match);
		    	$citypath_without[$m][] = $a;
		    }
		    if(!$citypath_without) $citypath_without = false;
		}
		if($citypath_without[$module]) foreach($citypath_without[$module] as $a) {
			if($a == '*' || $a == $act) return true;
			//数字
			if($a == '{num}' && is_numeric($act)) return true;
		}
		if($citypath_without['*']) foreach($citypath_without['*'] as $a) {
			if($act == $a) return true;
		}
		return false;
	}

	/**
	 * 实例化url重写类
	 * @param  [type] $rewrite_mod 重写模式类型
	 * @return ms_url_base
	 */
	private function _factory($rewrite_mod) {
		$classname = 'ms_url_' . $rewrite_mod;
		return new $classname();
	}

	/**
	 * 把url表达式解析成程序可用有序数组
	 * @return array
	 */
	private function _pares_urlexp() {
		if(!$this->url_exp);
		$this->url_exp = trim($this->url_exp, '/');
		$this->params = explode('/', $this->url_exp); //参数数组
		//根据规则生成URL正式参数数组
		$this->get['m'] = $this->params[0]; //模块名
		if(isset($this->params[1])) {
			$this->get['act'] = $this->params[1];  //行为
		}
		if(count($this->params) > 2) {
			//参数配对
			for ($i = 2; $i < count($this->params) ; $i=$i + 2) {
				$key = $this->params[$i];
				$value = $this->params[$i+1];
				//使用系模拟URL重写，需要手动 rawurldecode
				if($this->use_sim) {
					$key = rawurldecode($key);
					$value = rawurldecode($value);
				}
				//中文兼容需要再次 rawurldecode
				if($this->chinses_compatible && $this->real_rewrite_mod != MF_URL_REWRITE_NORMAL) {
					$key = rawurldecode($key);
					$value = rawurldecode($value);
				}
				$this->get[$key] = str_replace('_f_','-',$value);
			}
		}
	}

	/**
	 * url参数数字导入到$_GET中，供系统在GET种获取使用
	 */
	private function _import_get() {
		if(!$this->get) return;
		foreach ($this->get as $key => $value) {
			$_GET[$key] = $value;
		}
	}
}

/**
 * URL重写抽象基类
 */
abstract class ms_url_base {
	/**
	 * 二级域名分站或者分站目录名（城市）
	 * @var string
	 */
	public $sldomain = '';
	/**
	 * 是否不存在的url
	 * @var boolean
	 */
	public $not_found = false;
	/**
	 * 分站URL形式
	 * @var string
	 */
	public $city_mod = MF_URL_CITY_PATHNAME;
	/**
	 * url自定义替换类
	 * @var [type]
	 */
	protected $pregurl = null;
	/**
	 * 是否隐藏唯一入口PHP文件
	 * @var boolean
	 */
	protected $hide_index = true;
	/**
	 * 唯一入口index的网页文件名
	 * @var string
	 */
	protected $index_name = 'index.php';
	/**
	 * 中文参数兼容
	 * @var boolean
	 */
	private $chinses_compatible = false;

	public function __construct() {
		$this->chinses_compatible = _G('cfg','rewritecompatible') ? true : false;
		$this->_pregurl_factory();
	}

	/**
	 * 设置当前分站URL模式
	 * @param string $mod 分站URL模式
	 */
	public function set_city_mod($mod) {
		$this->city_mod = $mod;
	}

	/**
	 * 设置hide_index
	 * @param bool $boolean 是否隐藏index
	 */
	public function set_hide_index($boolean) {
		$this->hide_index = (bool) $boolean;
	}

	/**
	 * URL参数编码,$rawurlencode中文兼容。留空表示按配置，false表示不使用，true表示强制使用
	 * @param  string $str 准备被编码的URL参数
	 * @return string
	 */
	public function urlencode($str) {
		if(is_numeric($str)) return $str;
		$str = rawurlencode($str);
		if( $this->chinses_compatible ) {
			$str = rawurlencode($str);
		}
		return $str;
	}

	/**
	 * 针对url表达式生成相应的url地址
	 * @param  [type] $url_exp [description]
	 * @param  string $domain  [description]
	 * @return [type]          [description]
	 */
	public function create($url_exp, $domain='') {}

	/**
	 * 传入的url地址进行解析成url表达式
	 * @param  [type] $url [description]
	 * @return [type]      [description]
	 */
	public function pares($url) {}

	/**
	 * 获取当前url地址里使用的二级域名或城市目录
	 * @return [type] [description]
	 */
	public function get_sldomain() {
		//系统设置的网址
		$url = str_replace(
			array('http://', 'https://', '\\'),
			array('', '', '/'),
			strtolower(_G('cfg','siteurl')));
		if($i = strpos($url,'/')) {
			$domain = substr($url, 0, $i);
		}
		$domain = trim($domain, '/');
		//当前访问的网址和系统设置的网址就行对比
		if($domain == get_current_domain()) {
			//相同则没有使用其他的二级域名访问modoer
			$this->sldomain = '';
		} elseif(get_fl_domain($domain)==get_fl_domain()) {
			//如果判断一级域名相同，二级域名不同，则表明使用了二级域名访问modoer
			$this->sldomain = strtolower(get_sl_domain());
		}
	}

	//获取一个用于自定义URL规则文件改写的类
	private function _pregurl_factory() {
		$classname = get_class($this);
		$rewrite_mod = str_replace('ms_url_', '', $classname);
		if($rewrite_mod != MF_URL_REWRITE_NORMAL) {
			$classname = 'ms_pregurl_' . $rewrite_mod;
			$this->pregurl = new $classname();
		}
	}
}

/**
 * 原始动态URL处理类
 */
class ms_url_normal extends ms_url_base {
	/**
	 * 使用唯一入口,原始url采用入口(index.php?......)
	 * @var boolean
	 */
	private $single_index = false;	//

	public function __construct() {
		parent::__construct();
		//单一入口
		$this->single_index = _G('cfg', 'single_index') ? true : false;
	}

	public function create($url_exp, $domain='') {
		$url = '';
		if($url_exp) {
			$exp = explode('/', $url_exp);
			if(count($exp)>0) {
				$url = $this->single_index ? ($this->index_name.'?m=' . $exp[0]) : ($exp[0].'.php');
				if(isset($exp[1])) $url .=  ($this->single_index?'&':'?') . 'act=' . $exp[1];
				if(count($exp) > 2) for ($i=2; $i < count($exp); $i=$i+2) {
					$url .= '&' . rawurlencode($exp[$i]) .'=' . rawurlencode($exp[$i+1]);
				}
			}
		}

		if(!$domain) return URLROOT . '/' . $url;
		if($this->city_mod == MF_URL_CITY_SUBDOMAIN) {
			return 'http://' . $domain . '.'. get_fl_domain() . '/' . $url;
		} else {
			return URLROOT . '/' . $url;
		}
	}

	public function pares($url) {
		return false;
	}
}

/**
 * html伪静态URL类
 */
class ms_url_html extends ms_url_base {
	/**
	 * html伪静态url后缀
	 * @var string
	 */
	private $html_ext = '.html';

	public function create($url_exp, $domain='') {
		$url_exp = str_replace('-', '_f_', $url_exp);
		$exp = explode('/', $url_exp);
		$url = '';
		foreach ($exp as $key => $value) {
			if(is_string($value)) $value = $this->urlencode($value);
			$url .= '-' . $value;
		}
		$url = ltrim($url, '-');
		if($url) $url .= $this->html_ext;
		//URL自定义替换
		if($this->pregurl && $url) {
			$url = $this->pregurl->preg($url, MF_URL_PREG_CREATE);
		}
		//入口文件(index.php/)
		$index_name = !$this->hide_index ? ($this->index_name . ($url ? '/' : '')) : '';
		//返回URL
		if(!$domain) {
			return URLROOT . '/' . $index_name . $url;
		} else if($this->city_mod == MF_URL_CITY_SUBDOMAIN) {
			return 'http://' . $domain . '.'. get_fl_domain() . URLROOT . '/' . $index_name . $url;
		} else {
			return URLROOT . '/' . $index_name . $url;
		}
	}

	public function pares($url) {
		if(!$url) return false;
		$url = trim(str_replace('//', '/', $url),'/');

		//URL自定义替换
		if($this->pregurl && $url) {
			$url = $this->pregurl->preg($url, MF_URL_PREG_PARSE);
		}

		$url_exp = trim($url);
		if(substr($url_exp, -5) == $this->html_ext) {
			$url_exp = substr($url_exp, 0, -5);
		}
		$params = explode('-', $url_exp);
		$params[0] = strtolower($params[0]);
		if($params[0] == 'index' || check_module($params[0])) {
			return str_replace('-', '/', $url_exp);
		}
		$this->not_found = true;
		return false;
	}
}

/**
 * pathinfo目录形式URL类
 */
class ms_url_pathinfo extends ms_url_base {
	//目录层级
	private $dir_level = 1;
	//目录后缀
	private $ext = '';

	public function __construct() {
		parent::__construct();
		$this->ext = strtolower(trim(_G('cfg','rewrite_mod_pathinfo_ext')));
	}

	/**
	 * 生成目录形式URL
	 * @param  string $url_exp URL表达式
	 * @param  string $domain  分站名称
	 * @return string
	 */
	public function create($url_exp, $domain='') {
		$url_exp = str_replace('-', '_f_', $url_exp);
		$exp = explode('/', $url_exp);
		$url = '';
		foreach ($exp as $key => $value) {
			if(is_string($value)) $value = $this->urlencode($value);
			$url .= ($key > $this->dir_level ? '-' : '/') . $value;
		}
		$url = trim($url, '/');
		//URL自定义替换
		if($this->pregurl && $url) {
			$url = $this->pregurl->preg($url, MF_URL_PREG_CREATE);
		}
		//加后缀
		if($this->ext && $url && substr($url, -1) != '/') {
			$url .= '.' . $this->ext;
		}
		//入口文件(index.php，如果url为空，则结尾不加/)
		$index_name = !$this->hide_index ? ($this->index_name . ($url ? '/' : '')) : '';
		//返回生成网址
		if(!$domain) {
			return URLROOT . '/' . $index_name . $url;
		} else if ($this->city_mod == MF_URL_CITY_PATHNAME) {
			return URLROOT . '/' . $index_name . $domain . '/' . $url;
		} elseif ($this->city_mod == MF_URL_CITY_SUBDOMAIN) {
			return 'http://' . $domain . '.'. get_fl_domain() . URLROOT . '/' . $index_name . $url;
		} else {
			return URLROOT . '/' . $index_name . $url;
		}
	}

	/**
	 * 解析URL网址
	 * @param  string $url 准备解析的目录形式的网址
	 * @return string      解析后系统认知的URL表达式
	 */
	public function pares($url) {
		if(!$url) return false;
		$ext = strtolower(pathinfo($url, PATHINFO_EXTENSION));
		if(strlen($this->ext)>0 && $ext == strtolower($this->ext)) {
			$url = substr($url, 0, -(strlen($ext) + 1));
		}
		$url = trim(str_replace('//', '/', $url), '/');
		list($p1, $p2) = explode('/', $url);
		//判断url首个参数是否是分站目录名
		if($p1 != 'index' && !check_module($p1) && get_city_for_doman($p1)) {
			//确定是目录名
			$this->sldomain = $p1;
			//从url地址中删除目录名
			$url = ltrim(substr($url,strlen($p1)), '/');
		}
		//自定义过则URL替换系统默认的URL地址
		if($this->pregurl && $url) {
			$url = $this->pregurl->preg($url, MF_URL_PREG_PARSE);
		}
		$url_exp = str_replace('-', '/', $url);
		
		list($module, ) = explode('/', $url_exp);
		if(!$module || $module == 'index' || check_module($module)) {
			return $url_exp;
		}
		$this->not_found = true;
		return false;
	}
}

/**
 * 自定义URL替换类
 */
abstract class ms_pregurl {
	/**
	 * 规则列表
	 * @var array
	 */
	protected $preg = array();
	/**
	 * 加载配置文件
	 * @param string $rewrite_mod URL重写模式
	 */
	public function __construct($rewrite_mod) {
		$this->load_config($rewrite_mod);
	}

	/**
	 * 载入规则文件，并解析规则文件
	 * @param  string $rewrite_mod URL重写模式
	 */
	protected function load_config($rewrite_mod) {
		$filename = MUDDER_ROOT . 'data' . DS . 'rewrite_' . $rewrite_mod . '.inc';
		is_file($filename) and $this->pares_config(@file_get_contents($filename));
	}

	/**
	 * 解析规则文件
	 * @param  string $content 准备解析的规则内容
	 */
	private function pares_config($content) {
        $content = explode("\n", preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "\n", $content));
        foreach($content as $v) {
            if(trim($v)=='') continue;
            $v = trim(preg_replace("/\s+/",' ', str_replace("\t", " ", $v)));
            if($v{0}=='#') continue;
            list($preg, $replace, $params) = explode(' ', $v);
            $this->preg[$preg] = array(
            	'key' => md5($preg),
            	'replace'=>$replace,
            );
            if($params) {
            	$this->preg[$preg]['params'] = $this->_pares_config_params($params);
            } else {
            	$this->preg[$preg]['params'] = array();
            }
        }
	}

	/**
	 * 解析单条规则内的替换规则参数
	 * @param  string $params 参数聚合，需要解析后才能被类所使用
	 * @return array         解析后的规则参数，数组形式
	 */
	private function _pares_config_params($params) {
		$result = array();
		if(!$params) return $result;
		if(substr($params, 0, 1)=='[' && substr($params, -1)==']') {
			$params = substr(strtoupper($params), 1, -1);
		}
		return explode(',', $params);
	}

	/**
	 * 检测替换url
	 * @param  string $url       准备替换的URL地址
	 * @param  string $preg_type URL表达式行为（生成URL时，解析URL时）
	 * @return string            替换后的url地址
	 */
	public function preg($url, $preg_type) {
		//频繁调用，处理为静态变量
		static $preg_objects = array();
        if(!$this->preg) return $url;
        foreach($this->preg as $preg => $match) {
        	$cls = $preg_objects[$match['key']];
        	if(!$cls) {
        		$cls = $this->preg_object($match);
        		$preg_objects[$match['key']] = $cls;
        	}
        	if(!$cls->params[$preg_type]) continue;
        	//B标记表示匹配就跳出
        	if($cls->params['B'] && preg_match("/^$preg$/", $url)) {
        		return $url;
        	}
        	//！！！此处正则需要优化（按模块分组获取正则，避免全部正则放在一起）
        	$newurl = preg_replace("/^$preg$/", $match['replace'], $url);
        	if($newurl != $url) {
        		//L表示终止后面的正则表达
                if($cls->params['L']) return $newurl;
                $url = $newurl;
        	}
        }
        return $url;
	}

	private function preg_object($match) {
		$class = new stdClass();
		if(is_array($match['params'])) foreach ($match['params'] as $value) {
			$class->params[$value] = TRUE;
		}
		return $class;
	}
}

/**
* pathinfo模式下自定义URL
*/
class ms_pregurl_pathinfo extends ms_pregurl {
	public function __construct() {
		parent::__construct(MF_URL_REWRITE_PATH);
	}
}

/**
* pathinfo模式下自定义URL
*/
class ms_pregurl_html extends ms_pregurl {
	public function __construct() {
		parent::__construct(MF_URL_REWRITE_HTML);
	}
}

/* end */