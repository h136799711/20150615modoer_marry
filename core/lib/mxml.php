<?php
class ms_mxml {

	public $parser;
	public $document;
	public $stack;
	public $data;
	public $last_opened_tag;
	public $attrs = array();
	public $failed = FALSE;

	//数组转换成xml格式
    public static function from_array($arr, $level = 1) {
        $s = $level == 1 ? "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n<root>\n" : '';
        $space = str_repeat("\t", $level);
        foreach($arr as $k => $v) {
            if(!is_array($v)) {
                $htmlon = true;//preg_match("/<[a-z\&\?]+\s+.+\\>/i", $v);
                $s .= $space."<item id=\"$k\">".($htmlon ? '<![CDATA[' : '').$v.($htmlon ? ']]>' : '')."</item>\n";
            } else {
                $s .= $space."<item id=\"$k\">\n".mxml::from_array($v, $level + 1).$space."</item>\n";
            }
        }
        $s = preg_replace("/([\x01-\x08\x0b-\x0c\x0e-\x1f])+/", ' ', $s);
        return $level == 1 ? $s."</root>" : $s;
    }

    //xml格式转换成数组形式
    public static function to_array($xmlfile,$is_file=false) {
        if($is_file) {
            $content = file_get_contents($xmlfile);
        } else {
            $content = $xmlfile;
        }
        $xml_parser = new self;
        $data = $xml_parser->parse($content);
        $xml_parser->destruct();
        return $data;
    }

	public function __construct() {
		$this->XMLparse();
	}

	public function destruct() {
		if($this->parser) xml_parser_free($this->parser);
	}

	public function XMLparse() {
		$this->parser = xml_parser_create('ISO-8859-1');
		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, 'open','close');
		xml_set_character_data_handler($this->parser, 'data');
	}

	public function parse(&$data) {
		$this->document = array();
		$this->stack = array();
		return xml_parse($this->parser, $data, true) && !$this->failed ? $this->document : '';
	}

	public function data(&$parser, $data) {
		if($this->last_opened_tag != NULL) {
			$this->data .= $data;
		}
	}

	public function open(&$parser, $tag, $attributes) {
		$this->data = '';
		$this->failed = FALSE;
		if(!$this->isnormal) {
			if(isset($attributes['id']) && !is_string($this->document[$attributes['id']])) {
				$this->document = &$this->document[$attributes['id']];
			} else {
				$this->failed = TRUE;
			}
		} else {
			if(!isset($this->document[$tag]) || !is_string($this->document[$tag])) {
				$this->document  = &$this->document[$tag];
			} else {
				$this->failed = TRUE;
			}
		}
		$this->stack[] = &$this->document;
		$this->last_opened_tag = $tag;
		$this->attrs = $attributes;
	}

	public function close(&$parser, $tag) {
		if($this->last_opened_tag == $tag) {
			$this->document = $this->data;
			$this->last_opened_tag = NULL;
		}
		array_pop($this->stack);
		if($this->stack) {
			$this->document = &$this->stack[count($this->stack)-1];
		}
	}
}

/** end **/