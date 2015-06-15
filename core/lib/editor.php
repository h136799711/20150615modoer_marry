<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class ms_editor {

    var $id = '';
    var $name = '';
    var $width = '100%';
    var $height = '300px';
    var $css = '';
    var $content = '';
    var $item = 'basic';
    var $upimage = false;
    var $pagebreak = false;
    var $jsname = 'kindeditor.js';
    var $jslang = 'zh_CN.js';

    var $isload = false;
    var $items = array();

    function __construct($name) {
        $this->set_name($name);
        $langname = S('editor_lang')=='en' ? 'en' : 'zh_CN';
        $this->jslang = $langname;
    }

    function set_name($name) {
        if(is_array($name)) {
            $this->name = $name[0];
            $this->id = $name[1];
        } else {
            $this->name = $name;
            $this->id = $name;
        }
    }

    function create_html() {
        $this->_init_item();
        $content = '';
        if(!$this->isload) {
            //$content .= '<script type="text/javascript" charset="utf-8" src="'.URLROOT.'/static/editor/'.$this->jsname.'"></script>'."\r\n";
            $content .= '<script type="text/javascript" charset="utf-8" src="'.URLROOT.'/static/editor/'.$this->jsname.'"></script>'."\r\n";
            $content .= '<script type="text/javascript" charset="utf-8" src="'.URLROOT.'/static/editor/lang/'.$this->jslang.'.js""></script>';
        }
        $content .= "<script type=\"text/javascript\">\r\n";
        $content .= "var kd_{$this->id};\r\n";
        if(_G('in_ajax')) {
            $content .= "KindEditor.basePath = ".URLROOT."'/static/editor/';\r\n";
        } else {
            $content .= "KindEditor.ready(function(K) {\r\n";
        }
        $kdname = _G('in_ajax') ? 'KindEditor' : 'K';
        $content .= "\tkd_{$this->id} = {$kdname}.create('#{$this->id}', {\r\n";
        $content .= "\tthemeType:'simple',\r\n";
        $content .= "\tlangType:'{$this->jslang}',\r\n";
        $content .= "\tallowFlashUpload:false,\r\n";
        $content .= "\tallowMediaUpload:false,\r\n";
        $content .= "\tallowFileUpload:false,\r\n";
        $content .= "\tallowFileManager:false,\r\n";
        $content .= "\turlType:'absolute',\r\n";
        if($this->item != 'default')$content .= "\t\titems : {$this->items[$this->item]}\r\n";
        if(!_G('in_ajax')) $content .= "\t});\r\n";
        $content .= "});\r\n";
        $content .= "</script>\r\n";
        $content .= "<style type=\"text/css\">.ke-toolbar-table td{padding:0;}</style>";
        $content .= "<textarea id=\"$this->id\" name=\"$this->name\" usekd=\"YES\" style=\"width:$this->width;height:$this->height;visibility:hidden;\">$this->content</textarea>";

        $this->isload = true;

        return $content;
    }

    function _init_item() {
        $pagebreak = $this->pagebreak ? ",'pagebreak'" : '';
        $image = $this->upimage ? ",'image','multiimage'" : '';

        $this->items['default'] = "
            ['source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'code', 'cut', 'copy', 'paste',
        'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
        'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
        'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
        'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
        'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image', 'multiimage',
        'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'baidumap' $pagebreak,
        'anchor', 'link', 'unlink', '|', 'about']
        ";
        $this->items['admin'] = $this->items['default'];
        $this->items['basic'] = "
            ['fullscreen', 'undo', 'redo', 'cut', 'copy', 'paste', 'plainpaste', 'wordpaste', '|', 'fontname', 'fontsize', 'textcolor', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
            'removeformat', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist', 'insertunorderedlist', 
            'hr', 'link', 'unlink', 'advtable', 'wordpaste', 'flash', 'media' $image $pagebreak,'baidumap']
        ";
    }

}
?>