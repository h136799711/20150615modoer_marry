<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
defined('IN_MUDDER') or exit('Access Denied');

$I =& $_G['loader']->model(MOD_FLAG.':subject');
define('SCRIPTNAV', 'item_subject_tops');

if($catid = _get('catid',null,MF_INT_KEY)) {
    $I->get_category($catid);
    if(!$pid = $I->category['catid']) {
        redirect('item_cat_empty');
    }
    $category = $_G['loader']->variable('category_' . $pid, 'item');
    //判断子分类是否禁用
    if(!$category[$catid]['enabled']) redirect('item_cat_disabled');
    if($catid>0) {
        $category_level = $category[$catid]['level'];
        $subcats = $category[$catid]['subcats'];
    }
    $mypid = $catid;
    $rogid = $I->category['review_opt_gid'];
    $model = $_G['loader']->model('item:itembase')->get_model($pid,true);
    $where_cityid = $model['usearea'] ? $_CITY['aid'] : 0;

    $tops = array();
    $tops[] = array (
        'title'  => '最佳' . $category[$catid]['name'],
        'name'  => '综合分',
        'params'=>array(
            'city_id' => $where_cityid,
            'catid' => $catid,
            'field' => 'avgsort',
            'orderby' => 'avgsort DESC',
            'rows' => 10,
        ),
    );
    $tops[] = array(
        'title'  => '本周热门',
        'name'  => '热门度',
        'params'=>array(
            'city_id'=>$where_cityid,
            'catid'=>$catid,
            'field'=>'pageviews',
            'orderby'=>'pageviews DESC',
            'rows'=>10,
        ),
    );
    $reviewpot = $_G['loader']->variable('opt_' . $rogid, 'review');
    foreach($reviewpot as $key => $val) {
        $tops[] = array(
            'title' => $val['name'] . '最佳',
            'name'  => '得分',
            'params'=>array(
                'city_id'=>$where_cityid,
                'catid' => $catid,
                'field' => $val['flag'],
                'orderby' => "{$val['flag']} DESC",
                'rows'=>10,
            ),
        );
    }
    $tops[] = array(
        'title' => '编辑推荐',
        'name'  => '推荐度',
        'params'=>array(
            'city_id'=>$where_cityid,
            'catid'=>$catid,
            'field'=>'finer',
            'orderby'=>'finer DESC',
            'rows'=>10,
        ),
    );
    $tops[] = array(
        'title' => '最新加入',
        'name'  => '浏览量',
        'params'=>array(
            'city_id'=>$where_cityid,
            'catid'=>$catid,
            'field'=>'pageviews',
            'orderby'=>'addtime DESC',
            'rows'=>10,
        ),
    );
    $tops[] = array(
        'title' => '随机推荐',
        'name'  => '点评量',
        'params'=>array(
            'city_id'=>$where_cityid,
            'catid'=>$catid,
            'field'=>'avgsort',
            'orderby'=>'rand()',
            'rows'=>10,
        ),
    );

}

$active['catid'][(int)$catid] = ' class="selected"';

$SEO->tags->current_category_name = $catid ? display('item:category',"catid/$catid") : '综合';
//解析seo设置赋值
$_HEAD['title'] = $SEO->pares('tops')->title;
$_HEAD['keywords'] = $SEO->keywords;
$_HEAD['description'] = $SEO->description;

$_G['show_sitename'] = FALSE;

include template('item_tops');
?>