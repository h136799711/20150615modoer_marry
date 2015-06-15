<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="sub-menu">
    <div class="sub-menu-heading">扩展积分管理</div>
    <a href="<?=cpurl($module,$act,'group')?>" class="sub-menu-item">积分字段设置</a>
    <a href="<?=cpurl($module,$act)?>" class="sub-menu-item">积分更新策略设置</a>
    <a href="<?=cpurl($module,$act,'log')?>" class="sub-menu-item selected">积分变更记录</a>
</div>
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">资金流向</td>
                <td width="350">
                    <?=form_radio('point_flow',array('all'=>'全部','in'=>'收入','out'=>'支出'),$_GET['point_flow'])?>
                </td>
                <td width="100" class="altbg1">积分类型</td>
                <td width="*">
                    <select name="point_type">
                    <option value="">==全部==</option>
                    <option value="rmb"<?if($_GET['point_type']=='rmb')echo' selected="selected"'?>>现金</option>
                    <?=form_member_pointgroup($_GET['point_type'])?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">会员名</td>
                <td><input type="text" name="username" class="txtbox2" value="<?=$_GET['username']?>" /></td>
                <td class="altbg1">金额区间</td>
                <td><input type="text" name="point_min" class="txtbox4" value="<?=$_GET['point_min']?>" />&nbsp;~&nbsp;
                    <input type="text" name="point_max" class="txtbox4" value="<?=$_GET['point_max']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">记录时间</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">显示数量</td>
                <td colspan="3">
                <select name="offset">
                    <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>每页显示20个</option>
                    <option value="50"<?=$_GET['offset']=='50'?' selected="selected"':''?>>每页显示50个</option>
                    <option value="100"<?=$_GET['offset']=='100'?' selected="selected"':''?>>每页显示100个</option>
                </select>&nbsp;
                <button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="130">时间</td>
                <td width="150">用户</td>
                <td width="80">积分类型</td>
                <td width="100" algin="right">收入</td>
                <td width="100">支出</td>
                <td width="100">余额</td>
                <td width="*">说明</td>
            </tr>
            <? if($total):?>
            <? while($val=$list->fetch_array()):?>
            <tr>
                <td><?=date('Y-m-d H:i:s',$val['dateline'])?></td>
                <td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
                <td style="color:<?=$val['point_flow']=='in'?'green':'red'?>;"><?=display('member:point',"point/$val[point_type]")?></td>
                <td style="color:green;"><?=$val['point_flow']=='in'?$val['point_value']:''?></td>
                <td style="color:red;"><?=$val['point_flow']=='out'?$val['point_value']:''?></td>
                <td><?=$val['amount']?$val['amount']:'-'?></td>
                <td><?=$val['remark']?></td>
            </tr>
            <? endwhile; ?>
            <? else: ?>
            <tr>
                <td colspan="8">暂无信息。</td>
            </tr>
            <? endif; ?>
        </table>
    </div>
    <div><?=$multipage?></div>
</form>
</div>