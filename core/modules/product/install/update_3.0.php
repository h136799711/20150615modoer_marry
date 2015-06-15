<?php
!defined('IN_MUDDER') && exit('Access Denied');
class model_update extends ms_model {

    public $flag = 'product';
    public $moduleid = 0;

    public $setp = 0;
    public $start = 0;
    public $index = 0;
    public $params = array();
    public $progress = 0;

    public $total_setp = 8;
    public $next_setp = false;

    private $old_ver = '';
    private $new_ver = '3.0';

    public function __construct($moduleid,$old_ver) {
        parent::__construct();
        $this->loader->helper('sql');
        $this->moduleid = $moduleid;
        $this->old_ver = $old_ver;
        $this->_check_version();
        $this->step = (int) _get('step');
        $this->step = $this->step < 1 || !$this->step ? 1 : $this->step;
        $this->start = (int) _get('start');
        $this->start = $this->start < 0 || !$this->start ? 0 : $this->start;
        $this->index = (int) _get('index');
        $this->index = $this->index < 0 || !$this->index ? 0 : $this->index;
    }

    public function updating() {
        $method = '_step_' . $this->step;
        if(method_exists($this, $method)) {
            $this->$method();
        } else {
            echo sprintf('The required method "%s" does not exist for %s.', $method, get_class($this));
            exit;
        }
        return $this;
    }

    public function completed() {
        $this->params['moduleid'] = $this->moduleid;
        $this->params['step'] = $this->step;
        if($this->next_setp) {
            $this->start = $this->index = 0;
        }
        $this->params['start'] = $this->start;
        $this->params['index'] = $this->index;
        $this->progress = round($this->step / $this->total_setp, 2);
        if($this->progress>1) $this->progress = 1;
        return $this->step > $this->total_setp;
    }

    private function _step_1() {
        $tables = array(
            array(
                "dbpre_product_data",
                " id mediumint(8) unsigned NOT NULL auto_increment,
                pid mediumint(8) unsigned NOT NULL,
                fieldid mediumint(8) unsigned NOT NULL,
                fieldname varchar(60) NOT NULL,
                `value` text NOT NULL,
                PRIMARY KEY  (id),
                UNIQUE KEY pid (pid,fieldid)",
            ),
            array(
                "dbpre_product_gcategory",
                "catid mediumint(8) unsigned NOT NULL auto_increment,
                pid mediumint(8) unsigned NOT NULL default '0',
                `level` tinyint(1) unsigned NOT NULL default '0',
                `name` varchar(20) NOT NULL default '',
                listorder smallint(5) unsigned NOT NULL default '0',
                enabled tinyint(1) unsigned NOT NULL default '1',
                subcats varchar(255) NOT NULL default '',
                nonuse_subcats varchar(255) NOT NULL default '',
                attcat varchar(255) NOT NULL default '',
                modelid smallint(5) unsigned NOT NULL default '0',
                title varchar(500) NOT NULL default '',
                keywords varchar(500) NOT NULL default '',
                description varchar(500) NOT NULL default '',
                PRIMARY KEY  (catid),
                KEY pid (pid)",
            ),
            array(
                "dbpre_product_cart",
                "cid mediumint(8) unsigned NOT NULL auto_increment,
                cartid varchar(80) NOT NULL default '',
                sid mediumint(8) unsigned NOT NULL default '0',
                pid mediumint(8) unsigned NOT NULL default '0',
                pname varchar(255) NOT NULL default '',
                uid tinyint(1) unsigned NOT NULL default '0',
                p_image varchar(255) NOT NULL default '',
                quantity int(10) unsigned NOT NULL default '0',
                price decimal(9,2) unsigned NOT NULL default '0.00',
                overdue int(10) unsigned NOT NULL default '0',
                PRIMARY KEY  (cid),
                KEY pid (pid),
                KEY cartid (cartid)",
            ),
            array(
                "dbpre_product_order",
                "orderid int(10) unsigned NOT NULL auto_increment,
                ordersn varchar(20) NOT NULL default '',
                orderstyle tinyint(1) unsigned NOT NULL default '1',
                sellerid int(10) unsigned NOT NULL default '0',
                sellername varchar(100) default '',
                buyerid int(10) unsigned NOT NULL default '0',
                buyername varchar(100) default '',
                buyeremail varchar(60) NOT NULL default '',
                `status` tinyint(1) unsigned NOT NULL default '1',
                addtime int(10) unsigned NOT NULL default '0',
                sid mediumint(8) unsigned NOT NULL default '0',
                payment_id int(10) unsigned default '0',
                paymentname varchar(100) default '',
                paymentcode varchar(20) NOT NULL default '',
                paytime int(10) unsigned default '0',
                paymessage varchar(255) NOT NULL default '',
                shiptime int(10) unsigned default '0',
                invoice_no varchar(255) default '',
                finishedtime int(10) unsigned NOT NULL default '0',
                goods_amount decimal(9,2) unsigned NOT NULL default '0.00',
                order_amount decimal(10,2) unsigned NOT NULL default '0.00',
                integral int(10) unsigned NOT NULL default '0',
                integral_pointtype varchar(15) NOT NULL default '',
                is_serial tinyint(1) unsigned NOT NULL default '0',
                remark varchar(255) NOT NULL default '',
                PRIMARY KEY  (orderid),
                KEY ordersn (ordersn,sellerid),
                KEY sellername (sellername),
                KEY buyername (buyername),
                KEY addtime (sid,addtime),
                KEY paytime (sid,paytime),
                KEY sid (sid,`status`,orderid),
                KEY buyerid (buyerid,`status`,orderid)",
            ),
            array(
                "dbpre_product_orderextm",
                "orderid int(10) unsigned NOT NULL default '0',
                username varchar(60) NOT NULL default '',
                address varchar(255) default '',
                zipcode int(6) unsigned default '0',
                mobile varchar(60) default '',
                shipid int(10) unsigned default '10',
                shipname varchar(100) default '',
                shipfee decimal(9,2) NOT NULL default '0.00',
                PRIMARY KEY  (orderid),
                KEY username (username)",
            ),
            array(
                "dbpre_product_ordergoods",
                "gid int(10) unsigned NOT NULL auto_increment,
                orderid int(10) unsigned NOT NULL default '0',
                pid int(10) unsigned NOT NULL default '0',
                pname varchar(255) NOT NULL default '',
                price decimal(9,2) unsigned NOT NULL default '0.00',
                quantity int(10) unsigned NOT NULL default '1',
                goods_image varchar(255) default '',
                commented tinyint(1) unsigned NOT NULL default '0',
                PRIMARY KEY  (gid),
                KEY orderid (orderid,pid)",
            ),
            array(
                "dbpre_product_orderlog",
                "logid int(10) unsigned NOT NULL auto_increment,
                orderid int(10) unsigned NOT NULL default '0',
                operator varchar(60) NOT NULL default '',
                order_status varchar(60) NOT NULL default '',
                changed_status varchar(60) NOT NULL default '',
                remark varchar(255) default '',
                log_time int(10) unsigned NOT NULL default '0',
                PRIMARY KEY  (logid),
                KEY orderid (orderid)",
            ),
            array(
                "dbpre_product_serial",
                "id mediumint(8) unsigned NOT NULL auto_increment,
                pid mediumint(8) unsigned NOT NULL default '0',
                oid mediumint(8) NOT NULL default '0',
                uid mediumint(8) NOT NULL default '0',
                `serial` varchar(255) NOT NULL default '',
                `status` tinyint(1) unsigned NOT NULL default '1',
                dateline int(10) unsigned NOT NULL default '0',
                sendtime int(10) unsigned NOT NULL default '0',
                PRIMARY KEY  (id),
                KEY pid (pid)",
            ),
            array(
                "dbpre_product_shipping",
                "shipid mediumint(8) unsigned NOT NULL auto_increment,
                sid mediumint(8) unsigned NOT NULL default '0',
                shipname varchar(100) NOT NULL default '',
                shipdesc varchar(255) NOT NULL default '',
                price decimal(9,2) unsigned NOT NULL default '0.00',
                enabled tinyint(1) unsigned NOT NULL default '1',
                PRIMARY KEY  (shipid),
                KEY uid (sid)",
            ),
        );
        if($table = $tables[$this->start]) {
            sql_create_table(str_replace('dbpre_','', $table[0]), $table[1]);
            $this->start++;
        } else {
            if($this->start >= count($tables)) {
                $this->step++;
                $this->next_setp = true;
            }
        }
    }

    private function _step_2() {
        $array = array(
            array('product', 'city_id', 'add', "city_id mediumint(8) unsigned NOT NULL default '0' AFTER sid"),
            array('product', 'pgcatid', 'add', "pgcatid mediumint(8) unsigned NOT NULL default '0' AFTER city_id"),
            array('product', 'gcatid', 'add', "gcatid mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER pgcatid"),
            array('product', 'user_price', 'add', "user_price varchar(255) NOT NULL default '' AFTER price"),
            array('product', 'usergroup', 'add', "usergroup varchar(255) NOT NULL default '' AFTER user_price"),
            array('product', 'stock', 'add', "stock int(11) unsigned NOT NULL DEFAULT '0' AFTER listorder"),
            array('product', 'p_style', 'add', "p_style tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER stock"),
            array('product', 'giveintegral', 'add', "giveintegral int(11) unsigned NOT NULL DEFAULT '0' AFTER p_style"),
            array('product', 'integral', 'add', "integral int(10) unsigned NOT NULL DEFAULT '0' AFTER giveintegral"),
            array('product', 'promote', 'add', "promote decimal(9,2) unsigned NOT NULL DEFAULT '0.0' AFTER integral"),
            array('product', 'promote_start', 'add', "promote_start int(10) unsigned NOT NULL DEFAULT '0' AFTER promote"),
            array('product', 'promote_end', 'add', "promote_end int(10) unsigned NOT NULL DEFAULT '0' AFTER promote_start"),
            array('product', 'is_on_sale', 'add', "is_on_sale tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER promote_end"),
            array('product', 'free_shipping', 'add', "free_shipping tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER is_on_sale"),
            array('product', 'tag_keyword', 'add', "tag_keyword varchar(255) NOT NULL DEFAULT '' AFTER free_shipping"),
            array('product', 'last_update', 'add', "last_update int(10) unsigned NOT NULL DEFAULT '0' AFTER tag_keyword"),
            array('product', 'sales', 'add', "sales mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER last_update"),
            array('product', 'finer', 'add', "finer tinyint(1) unsigned NOT NULL default '0' AFTER sales"),
            array('product', 'weight', 'add', "weight int(10) unsigned NOT NULL default '0' AFTER finer"),
            array('product', 'shipping', 'add', "shipping varchar(255) NOT NULL default '' AFTER weight"),

            array('product', 'closed_comment', 'drop', "closed_comment"),
        );
        if($detail = $array[$this->index]) {
            sql_alter_field($detail[0], $detail[1], $detail[2], $detail[3]);
        }
        $this->index++;
        $total = count($array);
        if($this->index >= $total) {
            $this->next_setp = true;
            $this->step++;
        }
    }

    //drop index
    private function _step_3() {
        $fields = array(
            array('product','catid'),
            array('productatt','attid'),
        );
        if($field = $fields[$this->start]) {
            $field[0] = str_replace('dbpre_','', $field[0]);
            if(sql_exists_table($field[0])) {
                if($field[1]=='PRIMARY KEY') {
                    sql_alter_pk($field[0], 'drop');
                } else {
                    sql_alter_index($field[0], 'drop', $field[1], $field[1]);
                }
            }
            $this->start++;
        } else {
            if($this->start >= count($fields)) {
                $this->step++;
                $this->next_setp = true;
            }
        }
    }

    //add index
    private function _step_4() {
        $fields = array(
            array('product','last_update','last_update(last_update)'),
            array('product','gcatid','gcatid(status,is_on_sale,gcatid,city_id,sales)'),
            array('product','pgcatid','pgcatid(status,is_on_sale,pgcatid,city_id,sales)'),
            array('product','promote','promote(promote,promote_start,promote_end,city_id)'),
            array('product','newproduct','newproduct(status,is_on_sale,city_id,dateline)'),
            array('product','catid','catid(sid,catid,sales)'),
            array('product','finer','finer(finer,pgcatid)'),

            array('productatt','sid','sid(pid,attid)'),
        );
        if($field = $fields[$this->start]) {
            $field[0] = str_replace('dbpre_','', $field[0]);
            if(sql_exists_table($field[0])) {
                if($field[1]=='PRIMARY KEY')
                    sql_alter_pk($field[0], 'add', $field[2]);
                else
                    sql_alter_index($field[0], 'add', $field[1], $field[2]);
            }
            $this->start++;
        } else {
            if($this->start >= count($fields)) {
                $this->step++;
                $this->next_setp = true;
            }
        }
    }

    private function _step_5() {
        $dbpre = _G('dns', 'dbpre');
        $this->db->from('dbpre_field');
        $this->db->where('idtype','product');
        $this->db->where('tablename','product');
        $this->db->where('fieldname','price');
        $this->db->delete();

        $this->db->from('dbpre_field');
        $this->db->where('idtype','product');
        $this->db->set('tablename','product_data');
        $this->db->update();

        $sqls = array(
            "INSERT INTO {$dbpre}product_gcategory (catid,pid,level,name,listorder,enabled,subcats,nonuse_subcats,attcat,modelid,title,keywords,description) 
                VALUES(1, 0, 1, '一级分类', 0, 1, '2', '', '', '0', '', '', '')",
            "INSERT INTO {$dbpre}product_gcategory (catid,pid,level,name,listorder,enabled,subcats,nonuse_subcats,attcat,modelid,title,keywords,description) 
                VALUES(2, 1, 2, '二级分类', 0, 1, '', '', '','0', '', '', '')",
        );
        foreach ($sqls as $sql) {
            $this->db->exec($sql);
        }

        $this->step++;
        $this->next_setp = true;
    }

    private function _step_6() {
        //把产品附表写入到 product_data 内
        $this->db->from('dbpre_product_model');
        $this->db->order_by('modelid');
        $r = $this->db->get();
        if(!$r) {
            $this->step++;
            $this->next_setp = true;
            return;
        }
        $tables = array();
        while ($v = $r->fetch_array()) {
            if($v['tablename']=='product_data') continue;
            $tables[] = array(
                'modelid' => $v['modelid'],
                'table' => 'dbpre_' . $v['tablename'],
            );
        }
        if($table = $tables[$this->index]) {
            $return = $this->_copy_field($table['modelid'], $table['table'], $this->start, 300);
            if($return) {
                $this->start += 300;
            } else {
                $this->index++;
                $this->start = 0;
            }
        } else {
            if($this->index >= count($tables)) {
                $this->step++;
                $this->next_setp = true;
            }
        }
    }

    private function _step_7() {
        $dbpre = _G('dns', 'dbpre');
        //处理模型，写入三级分类中
        $this->db->from('dbpre_product_model');
        $r = $this->db->get();
        if(!$r) {
            $this->step++;
            $this->next_setp = true;
            return;
        }

        $subcats = array();
        while ($v = $r->fetch_array()) {
            $SQL = "INSERT INTO {$dbpre}product_gcategory (pid,level,name,listorder,enabled,subcats,nonuse_subcats,attcat,modelid,title,keywords,description) 
                VALUES(2, 3, '$v[name]', 0, 1, '', '', '', '$v[modelid]', '', '', '')";
            $this->db->exec($SQL);
            $catid = $this->db->insert_id();
            $subcats[] = $catid;
            //更新产品的gcatid分类
            $this->db->from('dbpre_product');
            $this->db->where('modelid',$v['modelid']);
            $this->db->set('pgcatid','1');
            $this->db->set('gcatid',$catid);
            $this->db->update();
        }

        //更新上级表的subcatis字段
        $this->db->from('dbpre_product_gcategory');
        $this->db->where('catid',2);
        $this->db->set('subcats', implode(',', $subcats));
        $this->db->update();

        $this->step++;
        $this->next_setp = true;
    }

    private function _step_8() {
        $this->db->from('dbpre_adv_place');
        $this->db->where('name','商城_首页通栏');
        $exists = $this->db->count() > 0;

        if(!$exists) {
            $set = array();
            $set['templateid'] = 0;
            $set['name'] = '商城_首页通栏';
            $set['des'] = '产品商城首页模版，分类下面一栏960px';
            $set['template'] = "{get:adv ad=getlist(apid/_APID_/cachetime/1000)}\r\n<div>\$ad[code]</div>\r\n{getempty(ad)}\r\n<div>960*60通栏广告位一</div>\r\n{/get}";
            $set['enabled'] = 'Y';
            $this->db->from('dbpre_adv_place')->set($set)->insert();
        }
        $this->step++;
        $this->next_setp = true;
    }

    private function _copy_field($modelid, $table, $start, $offset) {
        $fields = $this->_get_fields($modelid);
        if(!$fields) return false;

        $this->db->from($table);
        $this->db->order_by('pid');
        $this->db->limit($start, $offset);
        $r = $this->db->get();
        if(!$r) return false;

        while ($v = $r->fetch_array()) {
            $pid = $v['pid'];
            foreach ($v as $key => $value) {
                if($key == 'pid') continue;
                $set = array();
                $set['pid'] = $pid;
                $set['fieldid'] = $fields[$key];
                if(!$set['fieldid']) continue;
                $set['fieldname'] = $key;
                $set['value'] = $value;
                //vp($set);exit;
                $this->db->from('dbpre_product_data');
                $this->db->set($set);
                $this->db->insert();
            }
        }
        return true;
    }

    private function _get_fields($modelid) {
        $this->db->from('dbpre_field');
        $this->db->where('idtype','product');
        $this->db->where('id',$modelid);
        $this->db->order_by('fieldid');
        $r = $this->db->get();
        if(!$r) return false;
        $fields = array();
        while ($v = $r->fetch_array()) {
            $fields[$v['fieldname']] = $v['fieldid'];
        }
        //vp($fields);
        return $fields;
    }

    private function _check_version() {
    }

}
?>