<?php
/**
* 微信公众号
*/
class mc_weixin_menu extends ms_base 
{
	private $accObj = null;
	private $menu_create_url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token={ACCESS_TOKEN}';
	private $menu_ger_url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token={ACCESS_TOKEN}';
	
	public function __construct()
	{
		parent::__construct();
		$this->accObj = mc_weixin_account::instant();
		$this->accObj->getAccessToken();
	}

	public function get_menus($to_array = true)
	{
		if(!$this->accObj->access_token) {
			return $this->add_error('未获取微信授权令牌（Access_Token）');
		}
		$url = str_replace('{ACCESS_TOKEN}', $this->accObj->access_token, $this->menu_ger_url);
		$result = http_get($url);
		if(!$result) return false;
		$json = json_decode($result, true);
		if($json['errcode'] > 0) {
			log_write('weixin', $url."\n".$result);
			if($json['errcode'] != '46002' && $json['errcode'] != '46003') {
				return $this->add_error($json['errmsg']."(errcode:{$json['errcode']})");
			} else {
				$json['menu']['button'] = array();
			}
		}
		if($to_array) {
			return $json;
		}
		return $result;
	}

	public function create_menus($post)
	{
		if(!$this->accObj->access_token) {
			return $this->add_error('未获取微信授权令牌（Access_Token）');
		}
		$buttons = $this->parse_post_data($post);
		if(!is_array($buttons) && !$buttons) return false;
		$json_str = urldecode(json_encode($buttons));

		$url = str_replace('{ACCESS_TOKEN}', $this->accObj->access_token, $this->menu_create_url);
		$result = $this->http_post($url, $json_str);

		$json = json_decode($result);
		if($json->errcode != '0') {
			return $this->add_error($json->errmsg.'(errcode:'.$json->errcode.')');
		}
		return true;
	}

	//把表单提交的菜单数据转换成微信规定的提交格式
	private function parse_post_data($post_array)
	{
		$buttons = array();
		if($post_array) foreach ($post_array as $key => $btn) {
			$button = array(
				'name' => urlencode($btn['name']),
			);
			if($btn['root'] == 'true' && $btn['type']=='root') {
				foreach ($btn['sub_button'] as $sbtn) {
					$sub_button = array();
					$sub_button['name'] = urlencode($sbtn['name']);
					$sub_button['type'] = $sbtn['type'];
					if($sbtn['type']=='view') {
						$sub_button['url'] = urlencode($sbtn['url']);
					} elseif($sbtn['type']=='click') {
						$sub_button['key'] = urlencode($sbtn['key']);
					} else {
						return $this->add_error('格式错误，二级菜单类型不正确。');
					}
					$button['sub_button'][] = $sub_button;
				}
			} else {
				$button['type']=$btn['type'];
				if($btn['type']=='view') {
					$button['url'] = urlencode($btn['url']);
				} elseif($btn['type']=='click') {
					$button['key'] = urlencode($btn['key']);
				} else {
					return $this->add_error('格式错误，一级菜单类型不正确。');
				}
			}
			$buttons[] = $button;
		}
		return array('button'=>$buttons);
	}

	private function http_post($url, $data) {
		if(!function_exists('curl_exec')) {
			return $this->add_error('服务器PHP未开启 curl 扩展模块');
		}
        $ch = curl_init ($url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST,  false);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        //错误记录
        $error = curl_error($ch);
        if($error) {
            $message = "微信自定义菜单提交失败\t$error\n$url";
            log_write('weixin', $message);
            return $this->add_error('提交失败。');
        }
        if(DEBUG) log_write('weixin_debug', $url."\n".$result);
        curl_close($ch);
        return $result;
	}

	private function http_get($url) {
		if(!function_exists('curl_exec')) {
			return $this->add_error('服务器PHP未开启 curl 扩展模块');
		}
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result =  curl_exec($ch);
        //错误记录
        $error = curl_error($ch);
        if($error) {
            $message = "微信自定义菜单提交失败\t$error\n$url";
            log_write('weixin', $message);
            return $this->add_error('提交失败。');
        }
        if(DEBUG) log_write('weixin_debug', $url."\n".$result);
        curl_close($ch);
        return $result;
	}
}
/** end **/