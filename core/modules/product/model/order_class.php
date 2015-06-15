<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_order extends ms_model 
{

    var $table = 'dbpre_product_order';
    var $key = 'orderid';
    var $model_flag = 'product';

    /**
     * 浮点数字就行对比
     * @param  float  $f1        [description]
     * @param  float  $f2        [description]
     * @param  integer $precision [description]
     * @return boolean             
     */
    public static function floatcmp($f1,$f2,$precision = 10) {
        $e = pow(10,$precision);
        $i1 = intval($f1 * $e);
        $i2 = intval($f2 * $e);
        return ($i1 == $i2);  
    }
    
    function __construct()
    {
        parent::__construct();
        $this->model_flag = 'product';
        $this->init_field();
        $this->modcfg = $this->variable('config');
    }

    function init_field()
    {
        $this->add_field('ordersn,orderstyle,sellerid,sellername,buyerid,buyername,status,addtime,paymentname,paytime,shiptime,invoice_no,kdcom,finishedtime,goods_amount,order_amount,integral,remark,delivery_time');
        $this->add_field_fun('orderstyle,sellerid,buyerid,status,addtime,paytime,shiptime,finishedtime,integral,delivery_time', 'intval');
        $this->add_field_fun('ordersn,sellername,buyername,buyeremail,paymentname,invoice_no,kdcom,remark', '_T');
        $this->add_field_fun('goods_amount,order_amount', 'floatval');
    }

    function read($orderid,$issn=FALSE,$select_extm=null)
    {
        if($select_extm) {
            $this->db->join($this->table, 'o.sid', 'dbpre_subject', 's.sid', 'LEFT JOIN');
            $this->db->join_together('o.orderid', 'dbpre_product_orderextm', 'e.orderid', 'LEFT JOIN');
        } else {
            $this->db->join($this->table, 'o.sid', 'dbpre_subject', 's.sid', 'LEFT JOIN');
        }
        $this->db->select('o.*,s.name,s.subname');
        $select_extm && $this->db->select($select_extm);
        $this->db->where('o.orderid',$orderid);
        if($issn) $this->db->where('o.ordersn',$ordersn);
        $result = $this->db->get_one();
        return $result;
    }

    function find($select, $where, $order_by, $start, $offset, $total = TRUE, $select_subject=null)
    {
        if($select_subject) {
            $this->db->join($this->table, 'o.sid', 'dbpre_subject', 's.sid', 'LEFT JOIN');
        } else {
            $this->db->from($this->table, 'o');
        }
        $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) return $result;
            $this->db->sql_roll_back('from,where');
        }
        $this->db->select($select);
        $select_subject && $this->db->select($select_subject);
        $this->db->order_by($order_by);
        $this->db->limit($start,$offset);
        $result[1] = $this->db->get();
        return $result;
    }

    //发货，修改订单
    function chang_ship($orderid, $post) {
        if(!$detail = $this->read($orderid, FALSE, 'e.*')) {
            redirect('订单不存在或者是无效的订单');
        }
        if($detail['status'] != '2' && $detail['status'] != '3') {
            redirect('订单不是已付款或待发货状态，无法进行操作。');
        }
        if($detail['orderstyle'] == '2') {
            //检查是否已经发货
            $serial = $this->loader->model('product:serial')->getlist($orderid, $detail['buyerid']);
            if($serial) redirect('已发货，不能重复发货。');
            $this->_send_serial($detail); //发货
        } else {
            if(!$post['invoice_no']) redirect('未填写快递单号，请返回填写。');
            if(!$post['kdcom']) redirect('未选择快递公司，请返回填写。');
            $set = array();
            $set['shiptime'] = $this->timestamp; //发货更新时间
            $set['status'] = '4'; //已发货
            $set['invoice_no'] = _T($post['invoice_no']);
            $set['remark'] = _T($post['remark']);
            $set['kdcom'] = _T($post['kdcom']);
            //更新
            $this->db->from($this->table);
            $this->db->set($set);
            $this->db->where('orderid', $orderid);
            $this->db->update();
            //notice
            if($detail['status'] < 4) {
                mc_product_notice::send_goods($detail);
            }
        }
    }

    //修改订单
    function edit_ship($orderid, $post) {
        if(!$detail = $this->read($orderid, FALSE, 'e.*')) {
            redirect('订单不存在或者是无效的订单');
        }
        if($detail['p_style'] == '2') return;
        if(!$post['invoice_no']) redirect('未填写物流单号，请返回填写。');
        if(!$post['kdcom']) redirect('未选择快递公司，请返回填写。');
        $set = array();
        $set['invoice_no'] = _T($post['invoice_no']);
        $set['kdcom'] = _T($post['kdcom']);
        $set['remark'] = _T($post['remark']);
        //更新
        $this->db->from($this->table);
        $this->db->set($set);
        $this->db->where('orderid', $orderid);
        $this->db->update();
    }

    //删除订单
    function delete($orderids) {
        $orderids = parent::get_keyids($orderids);
        $this->db->from($this->table)
            ->where('orderid',$orderids)
            ->delete();
    }

    //调整费用
    function change_amount($order, $goodsamount, $orderamount, $shipfee) {
        if($this->floatcmp($order['order_amount'], $orderamount)) {
            //redirect('订单支付金额未有更改！');
        }
        $order_amount = $goodsamount + $shipfee - $order['integral_amount'];
        //验证支付总价
        if(!$this->floatcmp($order_amount, $orderamount)) {
            //redirect('提交的订单总价('.$orderamount.')和系统计算订单总价('.$order_amount.')不一致，请返回刷新页面。');
        }
        //vp($order['order_amount'],$orderamount,$order_amount);exit;
        $this->db->from('dbpre_product_order');
        $this->db->where('orderid', $order['orderid']);
        $this->db->set('goods_amount', $goodsamount);
        $this->db->set('order_amount', $orderamount);
        $this->db->set_add('amount_changed', 1);
        $this->db->update();

        $this->db->from('dbpre_product_orderextm');
        $this->db->where('orderid', $order['orderid']);
        $this->db->set('shipfee', $shipfee);
        $this->db->update();

        //提醒消费者，支付金额变动
        mc_product_notice::change_amount($order, $orderamount);
    }

    //确认收货
    function order_confirm($orderid) {
        //判断
        if(!$detail = $this->read($orderid)) redirect('无效的订单！');
        if($detail['buyerid'] != $this->global['user']->uid) redirect('当前订单不是您下单购买，无法确认收货！');
        if($detail['status'] != '4') redirect('当前订单不是已发货状态，无法确认收货！');

        //计算商家获得金额（网站分成）
        $brokerage = $brokerage_price = 0; //网站佣金比率
        if($this->modcfg['brokerage_enable']) {
            $brokerage = $this->get_brokerage($detail['sid']);
        }
        if($brokerage > 0) {
            //计算佣金
            if($this->modcfg['brokerage_add_shipfee']) {
                //加入运费计算
                $need_amount = $detail['order_amount']; //即支付现金(产品总价 - 抵价积分 + 运费)
            } else {
                //不加入运费
                $need_amount = $detail['goods_amount'] - $detail['integral_amount']; //产品总价 - 抵价积分
            }
            $brokerage_price = round($need_amount * ( $brokerage / 100), 2);
            $amount = $detail['order_amount'] - $brokerage_price; //商家所得 = 销售总额 - 佣金
        } else {
            $amount = $detail['order_amount'];
        }

        //更新订单
        $this->db->from($this->table);
        $this->db->where('orderid', $orderid);
        $this->db->set('finishedtime', $this->global['timestamp']);
        $this->db->set('brokerage', $brokerage_price);
        $this->db->set('status', '5');
        $this->db->update();

        //不是货到付款或商家线下收款的，扣除佣金后现金进入商家账号
        if(!$detail['is_cod'] && $detail['is_offline_pay']!='admin') {
            $P =& $this->loader->model('member:point');
            $P->update_point2($detail['sellerid'], 'rmb', $amount, 
                lang('product_sell_gain_price', array($detail['ordersn'], $brokerage_price)));
        }
        
        //订单完成通知商家
        mc_product_notice::deal_close($detail);

        //赠送积分进入买家账号
        $addintegral = $this->giveintegral($orderid);
        if($addintegral > 0 && $this->modcfg['pointgroup']) {
            $this->loader->model('member:point')->update_point2($detail['buyerid'], 
                $this->modcfg['pointgroup'], $addintegral, 
                lang('product_order_succeed_give_point', $detail['ordersn'])
            );
        }
    }

    //计算佣金
    function get_brokerage($sid) {
        //从商家设置里获取佣金
        $PS = $this->loader->model('product:subjectsetting');
        $brokerage = $PS->read($sid, 'brokerage');
        if(!$brokerage) {
            //使用全局佣金设置
            $global_brokerage = $this->modcfg['brokerage'];
            if(!$global_brokerage || $global_brokerage < 0) return 0;
            return $global_brokerage;
        }
        if($brokerage < 0) return 0;
        return $brokerage;
    }

    //会员积分变化, 消费积分抵现金
    function member_coin($uid, $point, $type, $ordersn) {
        $P =& $this->loader->model('member:point');
        $P->update_point2($uid, $type, $point, lang('product_pay_point_order',$ordersn));
    }

    //销售额统计
    function totalcount($sid, $timetype, $starttime, $endtime) {
        $this->db->from($this->table);
        if($sid > 0) $this->db->where('sid', $sid);
        $this->db->where_between_and($timetype, strtotime($starttime.' 00:00:00'), strtotime($endtime.' 23:59:59'));

        // if($_GET['starttime'] && !$_GET['endtime']) {
        //     $this->db->where_between_and('addtime', strtotime($_GET['starttime']),$this->global['timestamp']);
        // }
        // if(!$_GET['starttime'] && $_GET['endtime']) {
        //  $nonstime = $this->global['timestamp'] - 30*24*3600;
        //  $this->db->where_between_and('addtime', $nonstime,strtotime($_GET['endtime'].' 23:59:59'));
        // }
        // if($_GET['starttime'] && $_GET['endtime']) {
        //     $this->db->where_between_and('addtime', strtotime($_GET['starttime']), strtotime($_GET['endtime'].' 23:59:59'));
        // }
        //$this->db->where('status', 2);
        $this->db->select('order_amount', 'totalprice', 'SUM( ? )');
        $this->db->select('brokerage', 'brokerage', 'SUM( ? )');
        $this->db->select('status');
        $this->db->group_by('status');
        if(!$r = $this->db->get()) return null;
        $result = array();
        while ($v = $r->fetch_array()) {
            $result[$v['status']]['totalprice'] = $v['totalprice'];
            $result[$v['status']]['brokerage']= $v['brokerage'];
        }
        $r->free();

        $this->db->sql_roll_back('from,group_by,where');
        $this->db->select('*', 'count', 'COUNT( ? )');
        $this->db->select('status');
        $r = $this->db->get();
        while ($v = $r->fetch_array()) {
            $result[$v['status']]['totalorder'] = $v['count'];
        }
        $r->free();

        return $result;

        //return $r['totalprice'];
    }

    function set_commented($orderid, $pid) {
        $this->db->from('dbpre_product_ordergoods');
        $this->db->where('orderid', $orderid);
        $this->db->where('pid', $pid);
        $this->db->set('commented', '1');
        $this->db->update();
    }

    //检测是否已经评论
    function check_comment_exists($orderid, $pid, $uid) {
        $this->db->from('dbpre_product_ordergoods');
        $this->db->where('orderid', $orderid);
        $this->db->where('pid', $pid);
        $this->db->where('commented', '1');
        return $this->db->count() > 0;
        /*
        $this->db->from('dbpre_comment');
        $this->db->where('idtype','product');
        $this->db->where('id',$pid);
        $this->db->where('id',$pid);
        $this->db->where('extra_id',$orderid);
        return $this->db->count() > 0;
        */
    }

    //虚拟卡发货
    function _send_serial($order) {
        $orderid = $order['orderid'];

        $G = $this->loader->model('product:ordergoods');
        $ordergoods = $G->read($orderid, true);
        if(!$ordergoods) redirect('订单信息不存在！');

        $P = $this->loader->model(':product');
        $product = $P->read($ordergoods['pid']);
        if(!$product) redirect('产品信息不存在！');
        if($product['p_style'] != '2') return false;
        //处理虚拟礼品
        $sr = $this->loader->model('product:serial');
        $serial_ids = $sr->get_serial($ordergoods['pid'], $ordergoods['quantity']);
        if(!$serial_ids || count($serial_ids) < $ordergoods['quantity']) {
            $message = ($this->global['user']->uid == $order['buyerid'] ? '支付完成！' : '') . '自动发货库存不足，请等待卖家发货。';
            redirect($message, url('product/member/ac/m_order'));
        }
        //更新库存
        $sr->update_serial($serial_ids, $orderid, $order['buyerid']);
        //更新订单
        $this->db->from($this->table)
            ->set('status', 4)->set('shiptime', $this->global['timestamp'])
            ->where('orderid', $orderid)->update();
        //发短通知
        $subject = lang('product_message_subject');
        $content = lang('product_message_content', array($order['ordersn'], $ordergoods['pname']));
        $MSG =& $this->loader->model('member:message');
        $MSG->send((int)$order['sellerid'], $order['buyerid'], $subject, $content);
    }

    //取得赠送积分总数
    function giveintegral($orderid) {
        if($this->modcfg['integral_acctype']=='2'){
            $this->db->join('dbpre_product_ordergoods', 'o.pid', 'dbpre_product', 'p.pid', 'LEFT JOIN');
            $this->db->where('o.orderid', $orderid);
            $this->db->select('p.giveintegral,o.quantity');
            $r = $this->db->get();
            if(!$r) return 0;
            $x = 0;
            while ($v=$r->fetch_array()) {
                $x += $v['giveintegral'] * $v['quantity'];
            }
            $r->free_result();
            return $x;
        } else {
            $this->db->join('dbpre_product_ordergoods', 'o.pid', 'dbpre_product', 'p.pid', 'LEFT JOIN');
            $this->db->where('o.orderid', $orderid);
            $this->db->select('p.giveintegral', 'totalintegral', 'SUM( ? )');
            $r = $this->db->get_one();
            return (int)$r['totalintegral'];            
        }

    }

}

/* end */