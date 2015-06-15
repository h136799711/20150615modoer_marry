<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
return array(

    'member_operation_title' => '我的助手',
    'member_login_title' => '会员登录',
    'member_reg_title' => '用户注册',
    'member_changepw_title' => '修改密码',

    'member_update_point_des' => '用户行为积分变更(%s)',

    'member_not_login' => '对不起，本次操作需要登录后才能执行，请先登录网站。',
    'member_login_logined' => '您已经登录，无需再次登录。',
    'member_login_lost' => '对不起，您填写的帐号有误，请返回重写。',
    'member_op_not_login' => '对不起，请先登录网站后，再进行操作。',
    'member_forget_invalid' => '您的账户或者Email地址有误，不能使用取回密码功能，如有疑问请与管理员联系。',
    'member_login_succeed' => '登录成功，欢迎您回来！',
    'member_empty' => '用户不存在。',
    'member_passport_enable' => '对不起，其他网站帐号链接功能未开启。',
    'member_reg_banip' => '对不起，您的IP被禁止注册。',
    'member_login_banip' => '对不起，您的IP被禁止登录。',

    'member_reg_ajax_name_empty' => '<font color="red">您未填写用户名</font>',
    'member_reg_ajax_name_exists' => '<font color="red">您填写的用户名已存在</font>',
    'member_reg_ajax_name_normal' => '<font color="green">您可以使用这个用户名</font>',
    'member_reg_ajax_email_empty' => '<font color="red">您未填写的E-mail地址</font>',
    'member_reg_ajax_email_invalid' => '<font color="red">您填写的E-mail地址格式不正确</font>',
    'member_reg_ajax_email_exists' => '<font color="red">您填写的E-mail地址已存在</font>',
    'member_reg_ajax_email_normal' => '<font color="green">您可以使用这个E-Mail地址</font>',
    'member_reg_ajax_mobile_empty' => '<font color="red">您未填写用户名手机号</font>',
    'member_reg_ajax_mobile_exists' => '<font color="red">您填写的手机号已绑定</font>',
    'member_reg_ajax_mobile_invalid' => '<font color="red">您填写的手机号格式有误</font>',

    'member_post_empty_name' => '对不起，您未填写用户名，请返回填写。',
    'member_post_exists_name' => '对不起，您填写的用户名已存在，请返回重写。',
    'member_post_empty_pw' => '对不起，您未填写密码，请返回填写。',
    'member_post_empty_pw_len' => '对不起，你设置的密码长度过短，不能少于 %d 个字符，请返回修改。',
    'member_post_empty_pw_error' => '对不起，您输入的密码不正确。',
    'member_post_empty_eq_pw' => '对不起，您2次输入的密码不一致，请返回填写。',
    'member_post_empty_email' => '对不起，您未填写email或者格式错误，请返回重写。',
    'member_post_exists_email' => '对不起，您填写的e-mail已存在，请返回重写。',
    'member_post_empty_group' => '对不起，您未选择一个有效的会员组。',
    'member_post_registered_again' => '对不起，同一IP允许连续注册帐号，请返回。',

    'member_reg_logined' => '您目前处于登录状态，无法注册新帐户，请退出后再进行注册。',
    'member_reg_closed' => '对不起，网站关闭了会员注册功能。',
    'member_reg_name_len_min' => '<font color="red">用户名不能小于2个字符</font>',
    'member_reg_name_len_max' => '<font color="red">用户名不能大于15个字符</font>',
    'member_reg_name_sensitive_char' => '用户名不得包含(,)、(*)、(")、([TAB])、([SPACE])、([\r])、([\n])、(&lt;)、(&gt;)、(&amp;)其中之一',
    'member_reg_name_limit' => '<font color="red">管理员限制了您输入的用户名进行注册</font>',
    'member_reg_pw_limit' => '<font color="red">密码中只能包含数字，字母以及"_ ~ ! @ #"!</font>',
    'member_reg_succeed' => '注册成功，欢迎您来到我们的大家庭！',
    'member_reg_msg_subject' => '欢迎注册成为我们的会员！',
    'member_reg_msg_content' => "敬爱的会员：\$username\r\n欢迎您注册成为 [\$sitename] 会员。\r\n\r\n\$sitename\r\n\$time",
    'member_reg_succeed_verify' => '注册成功，请进入您的注册邮箱 %s ，帐号激活！',
    'member_mobile_verify_message' => "\$sitename 用户手机认证验证码：\$serial",
    'member_reg_mobile_verify_invalid' => '对不起，您的手机号未认证！',
    'member_reg_invite_maxnum' => '您今日[%s]邀请人数限额已满[%d人]，此后邀请注册的用户将不再奖励积分。',
    'member_reg_lost' => '对不起，注册失败。',

    'member_verify_code_subject' => '【{sitename}】您的操作验证码',
    'member_verify_code_message' => '您的验证码为 {verify_code} ，请不要泄露给其他人【{sitename}】',

    'member_pm_inbox' => '收件箱',
    'member_pm_outbox' => '发件箱',
    'member_pm_not_exists' => '对不起，短信息不存在。',
    'member_pm_not_exists_2' => '对不起，短信息不存在或已删除。',
    'member_pm_dnot_send_self' => '对不起，您无法给自己发送短消息。',
    'member_pm_empty_recv' => '对不起，您未填写发送对象。',
    'member_pm_empty_subject' => '对不起，您未填写短信主题。',
    'member_pm_empty_content' => '对不起，您未填写短信内容。',
    'member_pm_strlen_subject' => '对不起，您填写的短信息主题不能超过 %d 个字符，请返回修改。',
    'member_pm_strlen_content' => '对不起，您填写的短信息内容不能超过 %d 个字符。',
    'member_pm_send_total' => '对不起，单次发送短信息不能超过 %d 个。',
    'member_pm_empty_member' => '对不起，您的发送对象不存在。',

    'member_point_exchange_des' => '积分兑换',
    'member_point_exchange_des_out' => '积分兑换-兑出',
	'member_point_exchange_des_in' => '积分兑换-兑入',
	'member_point_pay_des' => '积分充值',
	'member_point_rmb' => '现金',
    'member_point_level' => '等级积分',
    'member_point_less_point_self' => '很遗憾，您的积分不足 %d 个，无法完成本次操作。',
    'member_point_less_coin_self' => '很遗憾，您的金币不足 %d 个，无法完成本次操作。',
    'member_point_less_point' => '很遗憾，%s 的积分不足 %d 个，无法完成本次操作。',
    'member_point_less_coin' => '很遗憾，%s 的金币不足 %d 个，无法完成本次操作。',

	'member_assistant_menuid' => '对不起，系统未设置我的助手菜单组，请到后台设置。',

    'member_effect_empty_id' => '对不起，你未设置id号。',
    'member_effect_unkown_idtype' => '未知的会员参与种类。',
    'member_effect_unkown_effect' => '不存在的会员参与类型。',
    'member_effect_submitted' => '您已经做出过选择。',

    'member_friend_exists' => '您添加的好友已经存在了。',
    'member_friend_uid_invalid' => '您添加的好友ID无效。',
    'member_friend_not_found' => '您添加的好友不存在。',
    'member_friend_add_self' => '不能添加自己为好友。',

    'member_friend_msg_subject' => '你是我的好友啦!',
    'member_friend_msg_message' => '我已经把你加入我的好友了，很高兴认识你，请多多指教!',

    'member_forget_mail_subject' => '{sitename}会员，找回密码',
    'member_forget_mail_message' => "敬爱的{username}：\r\n您于 {nowtime} 使用了找回密码功能，请及时通过下面的链接来更新你的密码，本次链接有效期为{endday}天，将于 {endtime} 后过期。\r\n密码更新地址：{forgeturl}\r\n请将密码更新地址复制到浏览器地址栏中打开。\r\n\r\n{sitename}({siteurl})\r\n{nowtime}",
    'member_forget_mail_succeed' => '密码更新操作已发送到您的邮箱中，请登录您的邮箱查收邮件，及时更改密码。',

    'member_verify_mail_subject' => '{sitename}会员激活',
    'member_verify_mail_message' => "敬爱的{username}：\r\n您于 {nowtime} 在{sitename}注册帐号，请及时通过下面的链接来激活你的帐号。\r\n激活帐号地址：{verifyurl}\r\n请将激活地址复制到浏览器地址栏中打开，完成激活操作。\r\n\r\n{sitename}\r\n{siteurl}\r\n{nowtime}",
    'member_verify_succeed' => '恭喜您，您的帐号已成功激活！',
    'member_verify_param_empty' => '对不起，会员激活参数无效。',
    'member_verify_empty' => '会员激活的信息不存在。',
    'member_verify_timeout' => '对不起，本次会员激活已超时，请重新再发送一份激活邮件。',
    'member_verify_invalid' => '对不起，会员激活信息验证失败。',
    'member_verify_username_empty' => '对不起，用户信息不存在。',
    'member_verify_groupid_invalid' =>  '您已经是正式会员，不需要再次激活。',
    'member_verify_send_mail_invalid' => '激活邮件发送失败，请联系管理员。',
    'member_verify_send_mail' => '激活邮件发送成功，您的帐号目前属于待激活状态，请进入您的注册邮箱 %s ，激活您的帐号。',
    'member_verify_no_seccode' => '对不起，您未提供验证码。',

    'member_getpassword_param_empty' => '对不起，找回密码的参数无效。',
    'member_getPassword_empty' => '找回密码的信息不存在。',
    'member_getpassword_timeout' => '对不起，本次密码找回已超时，请重新操作一次找回密码。',
    'member_getpassword_invalid' => '对不起，找回密码信息验证失败。',
    'member_getpassword_username_empty' => '对不起，用户信息不存在。',
    'member_getpassword_succeed' => '恭喜您，密码更新成功！',

    'member_usergroup_empty'  => '对不起，用户组信息不存在。',

    'member_access_forbidden' => '对不起，您的帐号已被管理员设置禁止访问本站。<a href="'.url('member/login/op/logout').'">点击这里退出</a>',

    'member_passport_type_weibo' => '新浪微博',
    'member_passport_type_qq' => '腾讯QQ',
    'member_passport_type_taobao' => '淘宝',
    'member_passport_type_txweibo' => '腾讯微博',
    'member_passport_type_google' => 'Google',
    'member_passport_type_facebook' => 'Facebook',
    'member_passport_reg' => '欢迎来自 %s 的 <span style="color:#0033ff;">%s</span>，完善信息后即可使用 <span style="color:#0033ff;">%s</span> 帐号登录网站。',
    'member_passport_login' => '欢迎来自 %s 的 <span style="color:#0033ff;">%s</span>，输入你在本站已有的帐号即可绑定成功 。',
    'member_passport_bind_exists' => '对不起，您准备绑定的帐号已经被其他帐号绑定，无法进行再次绑定。',
    'member_passport_bind' => '对不起，您的帐号已绑定。',
    'member_passport_bind_login_succeed' => '对不起，您的帐号已绑定。',
    'member_passport_test_access' => '对不起，当前帐号未获得测试权限。页面即将关闭。',
    'member_passport_type_unknow' => '对不起，无效或未知的第三方登录接口。',
    'member_passport_bind_invalid' => '请重新登录后，再进行绑定！',
    'member_passport_succeed' => '恭喜！第三方登录账号授权成功！',
    'member_passport_lost' => '对不起，第三方登录账号授权失败！',
    'member_passport_getinfo_error' => '账号信息获取失败，请尝试重新登陆。',
    'member_passport_token_error' => '对不起，您的登录授权失败！',

    'member_tasktype_flag_empty' => '任务类文件标识不存在，请返回。',
    'member_tasktype_flag_exitst' => '对不起，任务类型已经存在，请返回。',
    'member_tasktype_empty' => '对不起，您选择的任务类型不存在，请返回。',
    'member_tasktype_form_empty' => '没有附加的完成条件.',

    'member_task_empty' => '对不起，您选择的任务不存在。',
    'member_task_post_flag_empty' => '对不起，您未选择任务类型，请返回选择。',
    'member_task_post_title_empty' => '对不起，您未填写任务标题，请返回填写。',
    'member_task_post_desc_empty' => '对不起，您未填写任务介绍，请返回填写。',
    'member_task_post_starttime_invalid' => '对不起，您填写的任务开始时间有误，请返回选择。',
    'member_task_post_endtime_invalid' => '对不起，您填写的任务时间有误，请返回选择。',
    'member_task_post_time_invalid' => '对不起，任务开始时间不能大于结束时间，请返回修改。',
    'member_task_post_period_invalid' => '对不起，您填写的任务周期有误，请返回填写。',
    'member_task_post_period_week_invalid' => '对不起，当您选择以“周”为周期单位时，周期值只能填写1（周一）至7（周日），请返回修改。',
    'member_task_post_period_month_invalid' => '对不起，当您选择以“月”为周期单位时，周期值只能填写1至29、30、31，请返回修改。',
    'member_task_post_pointtype_empty' => '对不起，您未选择奖励的积分类型。',
    'member_task_post_point_invalid' => '对不起，您未填写有效的奖励积分值，请返回填写。',
    'member_task_post_access_groupids_empty' => '对不起，你没有选择允许申请任务的会员组，请返回选择。',

    'member_group_upgrade_day_invalid' => '对不起，您未选择升级天数。',
    'member_group_upgrade_empty' => '对不起，会员组不存在。',
    'member_group_upgrade_invalid' => '对不起，不能升级到此会员组。',
    'member_group_upgrade_insufficient_funds' => '对不起，您的余额不足，无法完成本次操作！',
    'member_group_upgrade_do_without' => '您的账号已经是终身%s，不需要进行升级。',
    'member_group_upgrade_des' => '升级用户组:%s(%d天)',

    'member_task_apply_succeed' => '恭喜，任务申请成功！',
    'member_task_delete_succeed' => '任务已放弃，您可以继续申请完成其他任务。',
    'member_task_apply_cktime_-1' => '对不起，任务尚未上线，暂时无法申请。',
    'member_task_apply_cktime_-2' => '对不起，任务已经结束，无法进行申请。',
    'member_task_apply_access' => '抱歉，您没有权限申请这个任务。',
    'member_task_apply_exists' => '抱歉，您已经申请了该任务，无法重复申请。',
    'member_task_apply_not' => '抱歉，您没有申请该任务，请返回。',
    'member_task_apply_not_myself' => '抱歉，这不是您申请的任务，请返回。',
    'member_task_apply_delete_done' => '抱歉，无法删除已经完成的任务，请返回。',
    'member_task_finished' => '抱歉，您的任务已经领取了奖励，请返回。',
    'member_task_failed' => '抱歉，您的任务已经失败，请返回。',
    'member_task_point_log_des' => '完成任务《%s》获得奖励',
    'member_task_finish_succeed' => '恭喜，您已经顺利完成任务并获得了奖励！',
    'member_task_not_finished' => '抱歉，您的任务尚未完成。',
    'member_task_autoapply_notice' => '系统自动为您申请了一些任务。<a href="%s" target="_blank">查看</a>',

    'member_follow_succeed' => '关注成功!',
    'member_follow_submitted' => '您已经关注了该会员。',
    'member_follow_self' => '对不起，您不能关注自己。',
    'member_notice_follow' => '%s 关注了您，已成为您的粉丝',
    'member_unfollow_access' => '本条关注不属于您，无法进行任何操作。',

    'member_task_avatar_title' => '头像类任务',
    'member_task_profile_title' => '用户资料完善任务',

    'member_task_feed_add_icon' => 'task',
    'member_task_feed_add_title_template' => '{username} 申请了一个任务',
    'member_task_feed_add_body_template' => '{title}',

    'member_task_feed_done_icon' => 'task',
    'member_task_feed_done_title_template' => '{username} 完成了一个任务',
    'member_task_feed_done_body_template' => '{title}',
);
?>