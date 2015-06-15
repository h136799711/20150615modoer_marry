<?php
/**
* 附近主题指令支持
*/
class weixin_cmd_nearby_subject extends mc_weixin_cmd
{
	//获取主题数量
	private $search_num = 8;
    private $usermsg;

	public static function get_name()
	{
		return '附近商户';
	}

	public static function get_mark()
	{
		return 'nearby';
	}

	public static function intro() 
	{
		return "发送：\n".self::get_mark()."\n查看附近商户。";
	}

    public static function match($msgObj)
    {
        $content = strtolower(trim($msgObj->Content));
		return $content == self::get_mark();
    }

	public function execute($msgObj, mc_weixin_session $session)
	{
		if($msgObj->Location_Y && $msgObj->Location_X) {

            //回复附近主题
		    $lng = $msgObj->Location_Y;
            $lat = $msgObj->Location_X;

		    $result = $this->search($lng, $lat);
		    if(is_array($result)) {
            	$reply_obj = mc_weixin_reply::factory('news');
            	foreach ($result as $key => $value) {
            		$reply_obj->add_article($value);
            	}
		    } else {
				$reply_obj = mc_weixin_reply::factory('text');
            	$reply_obj->set_content($result);
		    }
            //指令对完完结，删除记录
            $session->action = mc_weixin_session::ACTION_RESET;

		} else {

        	$reply_obj = mc_weixin_reply::factory('text');
            $content = "请发送您的位置信息。\n\n点击底部回复框右侧“+”图标，再点击“位置”图标，发送即可。";
            $content .= "\n\n跳出本次指令对话，请输入： @exit";

            //回复用户发送位置信息
            $reply_obj->set_content($content);

            //保存本次指令对话
            $session->action = mc_weixin_session::ACTION_SAVE;
  
        }

        $reply_obj->set_user($msgObj->FromUserName, $msgObj->ToUserName);
        //回复消息
        $reply_obj->send();
	}

	private function search($lng, $lat) {

		if('baidu' == strtolower(S('mapflag'))) {
			_G('loader')->helper('baidumap');
			$bd_lnglat = BaiduMap::gps2baidu($lat, $lng);
			if(!$bd_lnglat) {
				return '对不起，定位坐标转换出错。';
			}
			$lat = $bd_lnglat['lat'];
			$lng = $bd_lnglat['lng'];
		}

		$r = _G('db')->from('dbpre_subject')
			->select('sid,city_id,name,subname,map_lng,map_lat,thumb,avgprice,reviews,favorites')
			->select_param('ModoerGetDistance(%s,%s,map_lng,map_lat) AS distance', array($lng, $lat))
			->where('status', 1)
			->where_not_equal('map_lng', 0)
			->order_by('distance')
			->limit(0, $this->search_num)
			->get();
		if(!$r) return '在您的附近没有找到商户。';
		_G('loader')->helper('location','item');
		//$content = "搜索到附近商户：";
        $city_id = 0;
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
				'Description'	=> $this->showDistanceUnit($v['distance']),
				'PicUrl'		=> get_imageurl($v['thumb']?$v['thumb']:'static/images/noimg.gif', true),
				'Url'			=> U("item/mobile/do/detail/id/$v[sid]",true),
			);
			$result[] = $data;
		}
		if($r->num_rows() >= $this->search_num) {
			$result[] = array(
				'Title' => '查看更多...',
				'Url'	=> U("item/mobile/do/nearby/city_id/$city_id", true),
			);
			//$content .= "\n".'<a href="'.U("item/mobile/do/nearby/city_id/$city_id", true).'">查看更多</a>';
		}
		$r->free_result();
		//return $content;
		return $result;
	}

    //获取一个友好的距离单位
	function showDistanceUnit($distance) {
		$distance = (int) $distance;
		if($distance < 1000) {
			for ($i = 1000; $i >= 0 ; $i = $i - 100) {
				if($i<=$distance) return '少于' . ($i+100) . '米';
			}
		} elseif($distance < 5000) {
			for ($i = 5000; $i > 0 ; $i = $i - 500) { 
				if($i<$distance) return '少于' . ($i+500)/1000 . '公里';
			}
		} else {
			return '相聚' . ($distance/1000) . '公里';
		}
	}
	
}
/** end **/