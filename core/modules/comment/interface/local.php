<?php
/**
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2011 Moufersoft
 * @website www.modoer.com
 */
class msm_comment_local extends msm_comment_interface {

	protected $interface_info = array(
		'name' => '模块内置评论',
		'url' => '',
		'intro' => '网站系统内置的评论功能',
	);

	public function display($common_cfg) {

	}

	public function get_info() {
	}

	public function check_setting() {
		$fields = array('check_comment','filter_word','comment_interval','member_seccode','guest_seccode',
			'content_min','content_max');
		$setting = array();
		foreach ($fields as $key) {
			$setting[$key] = $_POST[$key];
		}
		$setting['content_min'] = abs((int)$setting['content_min']);
		$setting['content_max'] = abs((int)$setting['content_max']);
		if($setting['content_min'] >= $setting['content_max']) redirect('评论字数最大值不能小于登录最小值。');
		return $setting;
	}

	public function form_elements() {
        $elements = array();
        $elements[] = 
            array(
            'title' => '开启评论审核',
            'des' => '提交的评论只有经过后台审核才能显示在网站前台。',
            'content' => form_bool('check_comment',(bool)$this->cfg['check_comment']),
        );
        $elements[] = 
            array(
            'title' => '使用关键词过滤',
            'des' => '提交的评论只有经过后台审核才能显示在网站前台。',
            'content' => form_bool('filter_word',(bool)$this->cfg['filter_word']),
        );
        $comment_interval = is_numeric($this->cfg['comment_interval']) ? $this->cfg['comment_interval'] : 10;
        $elements[] = 
            array(
            'title' => '单页评论时间间隔',
            'des' => '提交的评论只有经过后台审核才能显示在网站前台。',
            'content' => form_input('comment_interval', $comment_interval, 'txtbox5'),
        );
        $elements[] = 
            array(
            'title' => '发布评论显示验证码',
            'des' => '发布评论时，必须填写验证码',
            'content' => '<div>会员:'.form_bool('member_seccode', (bool)$this->cfg['member_seccode']).'</div>'.
            			'<div>游客:'.form_bool('guest_seccode', (bool)$this->cfg['guest_seccode']).'</div>',
        );
        $elements[] = 
            array(
            'title' => '评论内容字数限制',
            'des' => '提交的评论只有经过后台审核才能显示在网站前台。',
            'content' => form_input('content_min', $this->cfg['content_min'], 'txtbox4') . ' ~ ' .
            			form_input('content_max', $this->cfg['content_max'], 'txtbox4'),
        );
        return $elements;
	}

}
/** end **/