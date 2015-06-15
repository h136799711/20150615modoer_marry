<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_sitemap {

    public  $charset = 'gb2312';
    private $_urls = array();
    //搜索引擎列表
    private $_se_list = array();

    function __construct() {
        $this->charset = _G('charset');
    }

    //新增一个地址
    function add($url,$lastmod='',$changefreq='',$priority='') {
        $this->_urls[md5($url)] = array(
            'loc' => "<![CDATA[{$url}]]>",
            'lastmod' => $lastmod>0 ? date('Y-m-d',$lastmod) : $lastmod,
            'changefreq' => $changefreq,
            'priority' => $priority,
            'display' => array(
                'html5_url' => "<![CDATA[{$url}]]>",
            ),
        );
    }

    //获取site map xml
    function write_xml() {
        $this->load_se();
        if(!$this->_se_list) return;
        foreach ($this->_se_list as $se) {
            $content = $se->set_charset($this->charset)
                ->set_urls($this->_urls)
                ->get_url_xml();
            if($content) {
                $result = $this->_write($se->get_sename(), $content);
            }
        }
        return $result;
    }

    //写入文件
    function _write($se_name, &$content) {
        $filename = MUDDER_ROOT . 'uploads' . DS . 'sitemap_' . $se_name . '.xml';
        return file_put_contents($filename, $content);
    }

    //加载搜索引擎类
    private function load_se() {
        $this->_se_list = array();
        $directory = MUDDER_ROOT . 'core' . DS . 'lib' . DS . 'sitemap' . DS;
        $iterator = new DirectoryIterator($directory);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile() && pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION) == 'php') {
                $apiname = $fileinfo->getBasename('.php');
                if(isset($this->_se_list[$apiname])) continue;
                $classname = 'sitemap_' . $apiname;
                if(!class_exists($classname)) include_once $fileinfo->getRealPath();
                if(get_parent_class($classname) == 'se_sitemap') {
                    $this->_se_list[$apiname] = new $classname();
                }
            }
        }
    }
}

abstract class se_sitemap {

    protected $_urls = array();
    protected $_charset = '';

    public function set_urls($url) {
        $this->_urls = $url;
        return $this;
    }

    public function set_charset($charset) {
        $this->_charset = $charset;
        return $this;
    }

    public function get_sename() {
        $name = get_class($this);
        return str_replace('sitemap_', '', $name);
    }

    abstract public function get_url_xml();

}

/* end */