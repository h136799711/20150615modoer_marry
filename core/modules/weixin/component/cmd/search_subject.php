<?php
/**
* 搜索主题指令支持
*/
class weixin_cmd_search_subject extends mc_weixin_cmd
{
	//获取主题数量
	private $search_num = 5;

	public static function get_name()
	{
		return '搜索商户';
	}

	public static function get_mark()
	{
		return 'search';
	}

	public static function intro() 
	{
		return "发送：\n".self::get_mark()."\n搜索商户。";
	}

    public static function match($msgObj)
    {
    	$mark = self::get_mark();
        $content = strtolower(trim($msgObj->Content));
		if($content == $mark) return true;
		$pre = substr($content, 0, strlen($mark) + 1);
		return $pre == $mark.' ';
    }

	public function execute($msgObj, mc_weixin_session $session)
	{
        $keyword = $this->pares_keyword($msgObj->Content);

        if(_G('charset') != 'utf-8') {
            $keyword = charset_convert($keyword, 'utf-8', _G('charset'));
        }

        $exit_msg = "\n\n跳出本次指令对话，请输入： @exit";

        if($msgObj->MsgType != 'text' || !$keyword) {

            $content = "请发送搜索关键字，例如：小肥羊" . $exit_msg;

            //回复用户发送位置信息
            $reply_obj = mc_weixin_reply::factory('text');
            $reply_obj->set_content($content);

            //保存本次指令对话
            $session->action = mc_weixin_session::ACTION_SAVE;

        } else {

            $result = $this->search($keyword);
            if($result) {
            	$reply_obj = mc_weixin_reply::factory('news');
            	foreach ($result as $key => $value) {
            		$reply_obj->add_article($value);
            	}
            	//结束指定对话
            	$session->action = mc_weixin_session::ACTION_RESET; 

            } else {
            	$reply_obj = mc_weixin_reply::factory('text');
            	$result = "没有搜索到关于“{$keyword}”的商户信息，请重新发送搜索关键字。" . $exit_msg;
            	$reply_obj->set_content($result);
            	//记住搜索对话进程，保持回话
            	$session->action = mc_weixin_session::ACTION_SAVE;
            }
            
        }

        $reply_obj->set_user($msgObj->FromUserName, $msgObj->ToUserName);
		//回复消息
		$reply_obj->send();
	}

	private function search($keyword)
	{
		$db = _G('db');
		$q 	= _T($keyword);
		$r 	= $db->from('dbpre_subject')
				->select('sid,city_id,aid,pid,name,subname,description,thumb')
				->where('status', 1)
				->where_concat_like('name,subname', "%{$q}%")
				->order_by('finer', 'DESC')
				->limit(0, $this->search_num)
				->get();
		if(!$r) return false;
		//$content = "搜索到关于“{$q}”的信息：";
        $city_id = '';
        $result = array();
		while ($v = $r->fetch_array()) {
            !$city_id && $city_id = $v['city_id'];
            /*
			$content .= "\n[".display('modoer:area',"aid/$v[city_id]")."]".
				"<a href=\"".U("item/mobile/do/detail/id/$v[sid]",true)."\">" 
				. $v['name'] . ($v['subname']?"({$v['subname']})":'') . "</a>";
			*/
			$data = array(
				'Title'			=> "[".display('modoer:area',"aid/$v[city_id]")."]".$v['name'].($v['subname']?"({$v['subname']})":''),
				'Description'	=> $v['description'],
				'PicUrl'		=> get_imageurl($v['thumb']?$v['thumb']:'static/images/noimg.gif', true),
				'Url'			=> U("item/mobile/do/detail/id/$v[sid]",true),
			);
			$result[] = $data;
		}
		
		if($r->num_rows() >= $this->search_num) {
			$result[] = array(
				'Title' => '搜索更多...',
				'Url'	=> U("item/mobile/do/search/keyword/$keyword/city_id/$city_id", true),
			);
			//$content .= "\n".'<a href="'.U("item/mobile/do/search/keyword/$keyword/city_id/$city_id", true).'">查看更多</a>';
		}
		
		$r->free_result();
		//return $content;
		return $result;
	}

	private function pares_keyword($content)
	{
		$mark = self::get_mark();
		if(strtolower($content)==$mark) return '';
        $substr = strtolower(substr($content, 0, strlen($mark) + 1));
		if($substr == $mark.' ') {
        	return trim(substr($content, strlen($mark) + 1));
        }
        return $content;
	}
	
}
/** end **/