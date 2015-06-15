<?php

return array(

		'group_title' => '小组',

		'group_cphome_topic_title' => '话题数量',
		'group_cphome_reply_title' => '话题回复数量',

		'group_status_1' => '<span class="font_1">正常</span>',
		'group_status_0' => '<span class="font_2">等待审核</span>',
		'group_status_-1' => '<span class="font_3">未通过审核</span>',
		'group_status_-2' => '<span class="font_4">已关闭</span>',

		'group_member_type_1' => '组长',
		'group_member_type_2' => '管理员',
		'group_member_type_10' => '成员',

		'group_member_status_1' => '正常',
		'group_member_status_0' => '待审核',
		'group_member_status_-1' => '禁言',

		'group_access_allow_create' => '对不起，您没有创建小组的权限。',
		'group_access_allow_delete' => '对不起，您没有删除小组的权限。',
		'group_access_post_invalid' => '对不起，请先加入本小组后才能发言。',

		'group_empty' => '对不起，您操作话题小组不存在。',
		'group_save_category_unselect' => '对不起，您未选择小组所属分类。',
		'group_save_category_empty' => '对不起，您选择的小组分类不存在。',
		'group_save_name_empty' => '对不起，您未填写小组名称。',
		'group_save_exists' => '对不起，您填写小组名称已存在。',
		'group_save_des_invalid' => '对不起，小组简介数字请控制在 %d-%d 以内。',

		'group_join_succeed' => '您已经成功加入小组。',
		'group_join_check_succeed' => '您的加入验证信息已提交，请等待管理员审核。',

		'group_notice_status_1' => '恭喜！您的小组【%s】已通过审核。',
		'group_notice_status_-1' => '您的小组【%s】未通过审核，原因：%s',
		'group_notice_status_-2' => '您的小组【%s】被关闭，原因：%s',

		'group_notice_ban_post' => '您被【{groupname}】小组{usertype}{username}禁言了，禁言理由：{message}，禁言期限至：{bantime}',
		'group_notice_join_check' => '您的小组【{groupname}】有新成员加入，<a href="{checkurl}">等待您的审核</a>。',
		'group_notice_join_check_1' => '恭喜！管理员{owner}审核通过了您加入小组【{groupname}】的申请。',
		'group_notice_join_check_-1' => '很遗憾，管理员{owner}拒绝了您加入小组【{groupname}】的申请。',

		'group_notice_delete' => '您的小组【{groupname}】被后台管理员 {adminname} 删除了。',
		'group_notice_change_owner_cancel' => '您被管理员 {adminname} 撤销了小组【{groupname}】的组长职务。',
		'group_notice_change_owner_add' => '您被管理员 {adminname} 任命为小组【{groupname}】组长。',

		'group_topic_empty' => '对不起，您查看的话题信息不存在。',
		'group_topic_subject_empty' => '对不起，话题所属的主题不存在！',
		'group_topic_not_audit' => '对不起，您查看的话题不存在或未审核。',

		'group_topic_post_subject_empty' => '对不起，您未填写话题的标题。',
		'group_topic_post_content_empty' => '对不起，您未填写话题内容。',
		'group_topic_post_gid_empty' => '对不起，您提交的话题没有指定小组。',
		'group_topic_post_content_strlen' => '对不起，您填写的话题内容字符数量请控制在 %d - %d 之间。',

		'group_reply_topic_not_audit' => '对不起，话题尚未审核，您不能进行回复。',
		'group_reply_topic_close' => '对不起，话题已经关闭回复，您不能就行回复。',
		'group_reply_empty' => '对不起，您查看的回复信息不存在。',
		'group_reply_post_content_empty' => '对不起，您未填写回复内容。',
		'group_reply_post_content_strlen' => '对不起，您填写的回复内容字符数量请控制在 %d - %d 之间。',
		'group_reply_post_topic_empty' => '对不起，对不起您回复的话题不存在！',

		'group_reply_post_access'=> '对不起，您没有权限操作。',

		'group_notice_new_reply' => '%s 回复了您的话题 %s',
		'group_notice_new_reply_at' => '%s 在话题 %s 中回复了你',
	    'group_topic_feed_icon' => 'thread',
	    'group_topic_feed_title_template' => '{username} 发布了一个话题',
	    'group_topic_feed_body_template' => '{title}',

	    'review_feed_add_icon' => 'group',
		'group_feed_add_title_template' => '{username} 创建了一个小组',
		'group_feed_add_body_template' => '{groupname}',

		'group_task_create_title' => '小组创建任务',
		'group_task_topic_title' => '话题发布任务',

		'group_point_add_digest' => '设置精华话题(+)',
		'group_point_dec_digest' => '取消精华话题(-)',
	);
?>