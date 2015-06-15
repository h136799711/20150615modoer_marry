<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class sitemap_baidu extends se_sitemap  {

    public function get_url_xml() {

        $content = '<?xml version="1.0" encoding="utf-8"?>';
        //$content = str_replace('UTF-8', $this->_charset, $content) . "\n";
        $content .= "\n<urlset>\n";
        if($this->_urls) {
            $index = 1;
            foreach ($this->_urls as $key => $urls) {
                $content .= "<url>\n";
                foreach ($urls as $key => $value) {
                    if(!$value) continue;
                    if($this->_charset!='utf-8') $value = charset_convert($value, $this->_charset, 'utf-8');
                    $content .= "\t<$key>$value</$key>\n";
                }
                $content .= "\t<data>\n";
                $content .= "\t\t<display>\n";
                $content .= "\t\t\t<html5_url>".$urls['loc']."</html5_url>\n";
                $content .= "\t\t</display>\n\t</data>\n";
                $content .= "</url>\n";
                if($index++ > 50000) break;
            }
        }
        $content .= "</urlset>";

        return $content;
    }
}
?>