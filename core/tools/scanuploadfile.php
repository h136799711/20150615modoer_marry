<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool',FALSE);
class msm_tool_scanuploadfile extends msm_tool {

    protected $name = '扫描上传文件夹可疑文件';
    protected $descrption = '扫描uplaods目录下的非图片文件，可用查找可能隐藏的木马文件。';
    protected $acttype = 'other';

    private $files = array();
    private $basedir = '';

    public function run() {
        $this->basedir = MUDDER_ROOT . 'uploads';
        $wtypes = _get('wtypes', '', MF_TEXT);
        $this->wtypes = explode(',',strtolower(str_replace(' ','',trim($wtypes,','))));
        $this->scan($this->basedir);
        $this->display();
        output();
    }

    public function create_form() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] = 
            array(
            'title' => '排除扫描的文件类型',
            'des' => '默认不扫描图片文件，多个用逗号分隔',
            'content' => form_input('wtypes', 'jpg,gif,jpeg,png', 'txtbox2 max'),
        );
        return $elements;
    }

    private function scan($dir) {
        $dh = opendir($dir);
        if(!$dh) return;
        while ($file = readdir($dh)) {
            if($file != "." && $file != "..") {
                $fullpath = $dir . DS . $file;
                if(is_file($fullpath)) {
                    if($this->wtypes) {
                        $ext = strtolower(pathinfo($fullpath, PATHINFO_EXTENSION));
                        if(in_array($ext, $this->wtypes)) continue;
                    }
                    if(basename($fullpath)=='index.html') {
                        if(filesize($fullpath)<5) continue;
                    }
                    $this->files[] = $fullpath;
                } elseif(is_dir($fullpath)) {
                    $this->scan($fullpath);
                }
            }
        }
        closedir($dh);
    }

    private function display() {
        if(!$this->files) redirect('没有找到任何文件。');
        echo '<table width="100%" border="1" cellspacing="1" cellpadding="5" style="border:1px solid #ccc">';
        echo '<tr><th width="*">文件名</th>'.
            '<th width="120">修改时间</th>'.
            '<th width="120">大小(字节)</th></tr>';
        foreach ($this->files as $file) {
            echo '<tr><td>'.str_replace(MUDDER_ROOT,DS,$file)
            .'</td><td>'.date('Y-m-d H:i:s',filemtime($file))
            .'</td><td>'.filesize($file).'</td></tr>';
        }
        echo '</table>';
    }

}
/* end */