<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div class="dialog">
    <?=form_begin(cpurl($module,$act,'edit'))?>
        <div class="form-item">
            <label for="weekday" class="caption">每周:</label>
            <?php $weekday_lang=lang('global_week_day');?>
            <select name="weekday" id="weekday" class="max">
                <option value="*">*</option>
                <?php foreach ($weekday_lang  as $key => $value):?>
                    <option value="<?=$key?>"<?if(is_numeric($time_exp['weekday'])&&$time_exp['weekday']==$key)echo'selected="selected"';?>>星期 <?=$value?></option>
                <?php endforeach;?>
            </select>
            <span class="helper">设置星期后，将覆盖下面的日期设定。</span>
        </div>
        <div class="form-item">
            <label for="day" class="caption">每月:</label>
            <select name="day" id="day" class="max">
                <option value="*">*</option>
                <?php for ($i=1; $i <= 31; $i++):?>
                    <option value="<?=$i?>"<?if(is_numeric($time_exp['day'])&&$time_exp['day']==$i)echo'selected="selected"';?>><?=$i?> 日</option>
                <?endfor;?>
            </select>
        </div>
        <div class="form-item">
            <label class="caption">小时:</label>
            <select name="hour" id="hour" class="max">
                <option value="*">*</option>
                <?php for ($i=0; $i <= 23; $i++):?>
                    <option value="<?=$i?>"<?if(is_numeric($time_exp['hour'])&&$time_exp['hour']==$i)echo'selected="selected"';?>><?=$i?> 时</option>
                <?php endfor;?>
            </select>
        </div>
        <div class="form-item">
            <label class="caption">分钟:</label>
            <input type="text" name="minute" id="minute" value="<?=$time_exp['minute']?>" class="txtbox2 max" />
            <span class="helper">如果定义了每周，每月或小时，分钟最大时为59；仅设置分钟时，可以大于59。</span>
        </div>
        <div class="form-submit">
            <input type="hidden" name="id" value="<?=$detail['id']?>" />
            <?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?>
            <button type="button" data-type="close" class="btn unimportant">关闭</button>
        </div>
    <?=form_end()?>
</div>