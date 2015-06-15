<?php
/**
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2011 Moufersoft
 * @website www.modoer.com
 */
class msm_comment_duoshuo extends msm_comment_interface {

	protected $interface_info = array(
		'name' => '多说',
		'url' => 'http://duoshuo.com/',
		'intro' => '多说网正在改变网站与用户之间，用户与用户之间的互动方式。这个专门基于社交网络的评论系统，能够轻松的帮网站主搭建自己互动性极强的社区，让留言的用户都有“家”的感觉。',
	);

	public function display($comment_cfg) {
		echo "<div class=\"ds-thread\" data-thread-key=\"{$comment_cfg['idtype']}_{$comment_cfg['id']}\" 
			data-title=\"{$comment_cfg['title']}\" ></div>";
		echo str_replace('<div class="ds-thread"></div>','',$this->cfg['duoshuo_code']);
		//同步评论数量
		$this->_update_comments($comment_cfg);
	}

	public function get_info() {
	}

	public function check_setting() {
		$setting['duoshuo_code'] = trim($_POST['duoshuo_code']);
		$setting['duoshuo_short_name'] = _T($_POST['duoshuo_short_name']);
		$setting['duoshuo_key'] = _T($_POST['duoshuo_key']);
		if(!$setting['duoshuo_short_name']) redirect('对不起，您未填写多说二级域名前缀。');
		if(!$setting['duoshuo_key']) redirect('对不起，您未填写多说密钥。');
		if(!$setting['duoshuo_code']) redirect('对不起，您未填写多说通用代码。');
		return $setting;
	}

	public function form_elements() {
        $elements = array();
        $elements[] = 
            array(
            'title' => '二级域名前缀',
            'des' => '您在多说注册登录网站时填写的二级域名，例如二级域名为modoer.duoshuo.com，则二级域名前缀应该为modoer',
            'content' => form_input('duoshuo_short_name', $this->cfg['duoshuo_short_name'],'txtbox2'),
        );
        $elements[] = 
            array(
            'title' => '密钥',
            'des' => '您在多说注册登录网站时，多说系统自动生成的密钥',
            'content' => form_input('duoshuo_key', $this->cfg['duoshuo_key'],'txtbox2'),
        ); 
        $elements[] = 
            array(
            'title' => '多说通用代码',
            'des' => '注册多说账号，并登录网站，进入多说网站管理后，即可在“工具区”看到统计代码。复制到这里即可。',
            'content' => form_textarea('duoshuo_code', $this->cfg['duoshuo_code']),
        );
        return $elements;
	}

	public function _update_comments($comment_cfg) {
		$short_name = $this->cfg['duoshuo_short_name'];
		$threads = $comment_cfg['idtype'] . '_' . $comment_cfg['id'];
		if(!$short_name || !$threads) return;
		$url = "http://api.duoshuo.com/threads/counts.json?short_name=$short_name&threads=$threads";
		$result = file_get_contents($url);
		$msg = json_decode($result);
		if($msg->code == '0' && $msg->response) {
			$obj = $msg->response->$threads;
			if($obj->comments != $comment_cfg['comments']) {
				$CM = _G('loader')->model(':comment');
				$CM->update_comments($comment_cfg['idtype'], $comment_cfg['id'], $obj->comments, 0);
			}
		}
	}

}
/** end **/