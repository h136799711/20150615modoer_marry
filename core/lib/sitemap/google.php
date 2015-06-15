<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class sitemap_google extends se_sitemap  {

    public function get_url_xml() {

        $content = "<urlset xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/09/sitemap.xsd\" xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        if($this->_urls) {
            $index = 1;
            foreach ($this->_urls as $key => $urls) {
                $content .= "<url>";
                foreach ($urls as $key => $value) {
                    if(!$value) continue;
                    $content .= "<$key>$value</$key>";
                }
                $content .= "</url>\n";
                if($index++ > 50000) break;
            }
        }
        $content .= "</urlset>";

        return $content;
    }
}
?>