<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<form method="post" action="">
    <div class="form-item">
        <label>菜单名称：</label>
        <input type="text" name="name" value="" class="txtbox2" />
        <span class="helper">微信内一级菜单最多4个汉字，二级菜单最多7个汉字，多出来的部分将会以“...”代替。</span>
    </div>
    <div class="form-item">
        <label>菜单类型：</label>
        <select name="type">
            <option value="root">父菜单</option>
            <option value="view">页面链接(view)</option>
            <option value="click">指令点击(click)</option>
        </select>
    </div>
    <div class="form-item none" data-name="view" data-type="option">
        <label>页面URL：</label>
        <input type="text" name="url" id="type_url" value="http://" class="txtbox2" />
        <span class="helper">请输入手机web页面url</span>
    </div>
    <div class="form-item none" data-name="click" data-type="option">
        <label>请输入指令标志：</label>
        <input type="text" name="key" id="type_key" value="" class="txtbox3 inline" />
        <select id="cmds" onchange="$('#type_key').val($(this).find(':selected').val());">
            <option>==已安装指令==</option>
            <?foreach ($cmds as $key => $value):?>
                <?$mark=call_user_func("$value::get_mark");?>
                <option value="<?=$mark?>"><?=$mark?></option>
            <?endforeach;?>
        </select>
        <span class="helper">填写指令标志或其他指令识别码</span>
    </div>
    <center>
        <button type="button" class="btn" data-type="submit">添加</button>
        <button type="button" class="btn unimportant" data-type="close">关闭</button>
    </center>
</form>