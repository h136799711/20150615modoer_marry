<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/

!defined('IN_MUDDER') && exit('Access Denied');

abstract class msm_seosetting extends ms_base {

    protected $keypre = 'seo_';
    protected $global_setting = array(
        'title'         => '页面标题(Title)',
        'keywords'      => '页面关键字(Keywords)',
        'description'   => '页面描述(Description)',
    );
    
    /*
     *当前模块标示名
     */
    protected $module_flag = '';
    /*
    结构布局
      $pages['page_name'] = array(
          'des' => '文件说明',
          'tags' = array('tag_name'=>'des'),
          'setting' = array('keyname'=>'des'),
      );
    */
    protected $pages = array();

    /*
    结构布局
    $tags['aaa'] = 'des';
    */
    protected $global_tags = array(
        'site_name'         => '网站名称',
        'site_keywords'     => '网站全局keywords',
        'site_description'  => '网站全局description',
        'city_name'         => '正在访问的城市名称',
        'module_name'       => '正在访问的模块名称',
    );

    /*
     * 某个模块的seo设置信息
     */
     protected $settings = array();
    
    public function __construct($module) {
        parent::__construct();
        $this->module_flag = $module;
        if(!check_module($this->module_flag)) {
            show_error('seo设置：模块【'.$this->module_flag.'】不存在。');
        }
        $this->settings = $this->load_all_setting($module);
        $this->_init();
    }

    //输出HTML页面
    public function display() {
        $content = '<table class="maintable" border="0" cellspacing="0" cellpadding="0">';
        foreach($this->pages as $page => $arr) {
            $content .="<tr><td class=\"altbg1\" valign=\"top\" width=\"40%;\">\n";
            $content .="<strong>{$arr['des']}:</strong>\n";
            $content .="<p>可用标签(鼠标放在标签上可见含义)：\n";
            $tags = $this->global_tags;
            if($arr['tags']) $tags = array_merge($tags, $arr['tags']);
            foreach($tags as $tag=>$tag_name) {
                $content .= "<span title=\"$tag_name\" class=\"font_1\">{".$tag."}</span>\n";
            }
            $content .="</p>\n";
            $content .= "</td><td width=\"*\">\n";
            foreach($arr['setting'] as $setting_name => $setting_des) {
                $keyname = $this->get_keyname($page . "_". $setting_name);
                $value = $this->settings[$keyname];
                if(!$value && isset($arr['default'][$setting_name])) {
                    $value = $arr['default'][$setting_name];
                }
                $content .="<p>".form_input("modcfg[$keyname]", $value, 'txtbox')." {$setting_des} </p>\n";
            }
            $content .= "</td></tr>\n";
        }
        $content .="</table>";
        echo $content;
    }

    //通过页面name，获取保存时唯一字段名
    public function get_keyname($page_name) {
        return $this->keypre . $page_name;
    }

    //取得某个模块页面的seo设置数据
    public function page_setting($page_name) {
        $setting = $this->load_all_setting($this->module_flag);
        $result = array();
        if($setting) foreach ($setting as $key => $value) {
            $keyname = $this->get_keyname($page_name);
            if(substr($key, 0, strlen($keyname))==$keyname) {
                $result[substr($key,strlen($keyname)+1)] = $value;
            }
        }
        //如果数据库内无法找到相关的seo设置，是否存在默认的seo设置项
        if(!$this->pages[$page_name]['default']) return $result;
        foreach ($this->pages[$page_name]['default'] as $key => $value) {
            if(!$result[$key]) $result[$key] = $value;
        }
        return $result;
    }

    //取得某个模块的seo配置信息
    public function all_setting() {
        return $this->setting;
    }

    //设置标签和含义
    protected function set_tags($tag_name, $des) {
        $this->tags[$tag_name] = $des;
    }

    //设置页面和可用标签
    protected function set_page($name, $des, $matchs) {
        $this->pages[$name] = array($des, '');
    }

    //保存设置
    public function save_setting($post) {
        $set = array();
        foreach($post as $key => $value) {
            if(substr($key,0,strlen($this->keypre)) == $this->keypre) {
                //list($page_name,) = explode('_',substr($key,strlen($this->keypre)));
                //$setname = substr($key,strlen($this->keypre.$page_name)+1);
                //if($this->pages[$page_name]['default'][$setname] !== $value)
                    $set[$key] = $value;
            }
        }
        if(!$set) return;
        $C = $this->loader->model('config');
        $C->save($set, $this->module_flag);
    }

    //取得某个模块的全部seo设置数据
    protected function load_all_setting() {
        $setting = array();
        $config = $this->loader->variable('config', $this->module_flag);
        foreach($config as $key => $value) {
            if(substr($key,0,strlen($this->keypre)) == $this->keypre) {
                $setting[$key] = $value;
            }
        }
        return $setting;
    }

    //子类覆盖本方法，对$this->pages进行自定义赋值
    abstract protected function _init();

}
?>