<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="sub-menu">
    <div class="sub-menu-heading">后台用户</div>
    <a href="<?=cpurl($module,$act)?>" class="sub-menu-item">用户列表</a>
    <a href="<?=cpurl($module,$act,'add')?>" class="sub-menu-item<?if(!$detail):?> selected<?endif;?>">添加用户</a>
    <?if($detail):?>
    <a href="#" class="sub-menu-item selected">编辑用户</a>
    <?endif;?>
</div>
<form method="post" action="<?=cpurl($module,$act,'post');?>" onsubmit="return form_submit()">
    <input type="hidden" name="adminid" value="<?=$_GET['adminid']?>" />
    <div class="space">
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%">用户名：</td>
                <td width="*">
                    <?if($admin->is_founder):?>
                    <input type="input" name="admin[adminname]" class="txtbox" value="<?=$detail['adminname']?>" />
                    <?else:?>
                    <?=$detail['adminname']?>
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">E-mail：</td>
                <td><input type="text" name="admin[email]" value="<?=$detail['email']?>" class="txtbox"/></td>
            </tr>
            <tr><td colspan="2" class="altbg2"><strong>修改密码</strong></td></tr>
            <tr>
                <td class="altbg1">新密码：</td>
                <td><input type="password" name="admin[password]" class="txtbox" /><?if($op=='edit') {?>&nbsp;不修改则留空<? } ?></td>
            </tr>
            <tr>
                <td class="altbg1">确认密码：</td>
                <td><input type="password" name="password2" class="txtbox" /></td>
            </tr>
            <?if($admin->is_founder && $detail['is_founder']!='Y'):?>
            <tr><td colspan="2" class="altbg2"><strong>权限设置</strong></td></tr>
            <tr>
                <td class="altbg1"><strong>禁止登录：</strong>禁止本帐号在后台登陆权限</td>
                <td><?=form_bool('admin[closed]', $detail['closed']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>允许操作的分站城市：</strong>设置后台帐号的分站管理权限。</td>
                <td>
                    <?$citys=$_G['loader']->variable('area');?>
                    <select id="mycitys" name="admin[mycitys][]" multiple="true">
                        <?foreach($citys as $k => $v):?>
                        <option value="<?=$v['aid']?>"<?=$mycitys&&in_array($v['aid'],$mycitys)?' selected="selected"':''?>><?=$v['name']?></option>
                        <?endforeach;?>
                        <?if($admin->check_mycity(-1)):?>
                        <option value="-1"<?=$mycitys&&in_array(-1,$mycitys)?' selected="selected"':''?>>全局(需城市管理员兼任)</option>
                        <?endif;?>
                    </select>
                    <script type="text/javascript">
                    $('#mycitys').mchecklist();
                    </script>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>允许操作的模块：</strong>设置后台帐号的管理权限。</td>
                <td>
                    <div>
                        <input type="hidden" id="mymodules" name="admin[mymodules]" value="<?=$detail['mymodules']?>">
                        <ul id="tree" class="ztree"></ul>
                    </div>
                    <link rel="stylesheet" href="./static/images/zTreeStyle/zTreeStyle.css" type="text/css">
                    <script type="text/javascript" src="./static/javascript/jquery.ztree.min.js"></script>
                    <script type="text/javascript">
                        var setting = {
                            check: { enable: true, nocheckInherit: true },
                            data: { simpleData: { enable: true } }
                        };
                        <?php 
                            $id = 1;
                            $pid = 0;
                            $nodes =  $menus = '';
                            $cpmenu = new ms_cpmenu();
                            $nodes .= "\t\n{ id:$id , pId:$pid , name:\"系统框架\"" . (check_sub_module('modoer',$mymodules)?', checked:true':'') . " },";
                            foreach($cpmenu->load('modoer') as $k => $v) {
                                $id++;
                                $nodes .= "\t\n{ id:$id , pId:1 , name:\"$v\"" . (in_array($k,$mymodules)||in_array('modoer',$mymodules)?', checked:true':'') . " },";
                                $menus .= "\t\n\"$id\" : '$k' ,";
                            }
                            foreach($_G['modules'] as $key => $val) {
                                $id++;
                                $nodes .= "\t\n{ id:$id , pId:$pid , name:\"$val[name]\"" . (check_sub_module($val['flag'],$mymodules)?', checked:true':'') . " },";
                                $_menus = $cpmenu->load($val['flag']);
                                $_pid = $id;
                                if($_menus) foreach($_menus as $k => $v) {
                                    $id++;
                                    $nodes .= "\t\n{ id:$id , pId:$_pid , name:\"$v\"" . (in_array($k,$mymodules)||in_array($val['flag'],$mymodules)?', checked:true':'') . " },";
                                    $menus .= "\t\n\"$id\" : '$k' ,";
                                }
                            }
                        ?>

                        var zNodes = [ <?=trim($nodes,',')?>];
                        var zMenus = { <?=trim($menus,',')?> };

                        $(document).ready(function(){
                            $.fn.zTree.init($("#tree"), setting, zNodes);
                        });

                        function get_acccess_module() {
                            var treeObj = $.fn.zTree.getZTreeObj("tree");
                            var nodes = treeObj.getCheckedNodes(true);
                            var str = '';
                            for (var i = 0; i < nodes.length; i++) {
                                var node = zMenus[nodes[i].id];
                                if(node) str += node + ',';
                            };
                            return str;
                        }

                        function form_submit() {
                            var mymodules = get_acccess_module();
                            //alert(mymodules);
                            $('#mymodules').val(mymodules);
                            return true;
                        }
                    </script>
                </td>
            </tr>
            <?elseif(!$admin->is_founder):?>
            <tr>
                <td class="altbg1" valign="top"><strong>允许操作的模块：</strong>设置后台帐号的管理权限。</td>
                <td>
                    <?php 
                    $ckmodules = array();
                    $cpmenu = new ms_cpmenu();
                    foreach($mymodules as $flag) {
                        list($module,) = explode('|', $flag);
                        $menus = $cpmenu->load($module);
                        $ckmodules[$module][$flag] = $menus[$flag];
                    }
                    foreach ($ckmodules as $key => $value) {
                        echo '<div style="margin-bottom:5px;"><strong>' , $key=='modoer'?'系统框架':$_G['modules'][$key]['name'] , '</strong>:<br />';
                        echo implode(', ', $value);
                        echo '</div>';
                    }
                    ?>
                    <script type="text/javascript">
                        function form_submit() {
                            return true;
                        }
                    </script>
                </td>
            </tr>
            <?endif;?>
        </table>
        <center>
            <input type="hidden" name="do" value="<?=$op?>" />
            <input type="hidden" name="forward" value="<?=get_forward()?>" />
            <input type="submit" name="dosubmit" value=" 提交 " class="btn" />&nbsp;
            <input type="button" value=" 返回 " class="btn" onclick="history.go(-1);" />
        </center>
    </div>
</form>
</div>