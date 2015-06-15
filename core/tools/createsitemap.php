<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool',FALSE);
class msm_tool_createsitemap extends msm_tool {

    protected $name = '生成Sitemap文件';
    protected $descrption = '生成Sitemap文件(XML格式)，让搜索引擎更易于抓取网站URL内容。xml文件存在在 ./uploads 目录内，
        您需要提交xml的url地址到相应的搜索引擎中。';

    private $_sitemap = '';
    private $_db = null;

    public function run() {
        $this->_db = _G('db');
        $this->_sitemap = new ms_sitemap();
        $class_methods = get_class_methods($this);
        foreach ($class_methods as $method_name) {
            if(substr($method_name, 0, 5) == '_url_') {
                $this->$method_name();
            }
        }
        if(!$this->_sitemap->write_xml()) {
            redirect('对不起，文件写入失败！');
        }
        $this->completed = true;
    }

    private function _url_1() {
        $r = $this->_db->from('dbpre_subject')
            ->select('sid,city_id,addtime')
            ->where('status',1 )
            ->order_by('addtime', 'DESC')
            ->limit(0, 30000)
            ->get();
        if(!$r) return;
        while ($v = $r->fetch_array()) {
            $url = url("item/detail/id/$v[sid]",'',true,false,$val['city_id']);
            $this->_sitemap->add($url, $v['addtime'], 'daily', '1.0');
        }
        $r->free_result();
    }

    private function _url_2() {
        if(!check_module('article')) return;
        $r = $this->_db->from('dbpre_articles')
            ->select('articleid,dateline')
            ->where('status', 1)
            ->order_by('dateline', 'DESC')
            ->limit(0, 2000)
            ->get();
        if(!$r) return;
        while ($v = $r->fetch_array()) {
            $url = url("article/detail/id/$v[articleid]",'',true);
            $this->_sitemap->add($url, $v['dateline'], 'daily', '1.0');
        }
        $r->free_result();
    }

    private function _url_3() {
        if(!check_module('coupon')) return;
        $r = $this->_db->from('dbpre_coupons')
            ->select('couponid,dateline')
            ->where('status', 1)
            ->order_by('dateline', 'DESC')
            ->limit(0, 1000)
            ->get();
        if(!$r) return;
        while ($v = $r->fetch_array()) {
            $url = url("coupon/detail/id/$v[couponid]",'',true);
            $this->_sitemap->add($url, $v['dateline'], 'daily', '1.0');
        }
        $r->free_result();
    }

    private function _url_4() {
        if(!check_module('group')) return;
        $r = $this->_db->from('dbpre_group_topic')
            ->select('tpid,dateline,replytime')
            ->where('status', 1)
            ->order_by('dateline', 'DESC')
            ->limit(0, 5000)
            ->get();
        if(!$r) return;
        while ($v = $r->fetch_array()) {
            $url = url("group/topic/id/$v[tpid]",'',true,false,0);
            $this->_sitemap->add($url, $v['replytime']?$v['replytime']:$v['dateline'], 'daily', '1.0');
        }
        $r->free_result();
    }

    private function _url_5() {
        if(!check_module('fenlei')) return;
        $r = $this->_db->from('dbpre_fenlei')
            ->select('fid,dateline')
            ->where('status', 1)
            ->order_by('dateline', 'DESC')
            ->limit(0, 5000)
            ->get();
        if(!$r) return;
        while ($v = $r->fetch_array()) {
            $url = url("fenlei/detail/id/$v[fid]",'',true);
            $this->_sitemap->add($url, $v['dateline'], 'daily', '1.0');
        }
        $r->free_result();
    }

    private function _url_6() {
        if(!check_module('product')) return;
        $r = $this->_db->from('dbpre_product')
            ->select('pid,dateline')
            ->where('status', 1)
            ->limit(0, 5000)
            ->get();
        if(!$r) return;
        while ($v = $r->fetch_array()) {
            $url = url("product/detail/id/$v[pid]",'',true);
            $this->_sitemap->add($url, $v['dateline'], 'daily', '1.0');
        }
        $r->free_result();
    }

    private function _url_7() {
        if(!check_module('tuan')) return;
        $r = $this->_db->from('dbpre_tuan')
            ->select('tid')
            ->where('status', 'new')
            ->get();
        if(!$r) return;
        while ($v = $r->fetch_array()) {
            $url = url("tuan/detail/id/$v[tid]",'',true);
            $this->_sitemap->add($url, $v['starttime'], 'daily', '1.0');
        }
        $r->free_result();
    }

}
/* end */