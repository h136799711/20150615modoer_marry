<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

//加载父类
_G('loader')->model(':member', false, null, false);
/**
 * UC会员操作
 */
class msm_ucenter_member extends msm_member
{
	//跳过UC注册
	public $jump_uc_register      = false;

	//跳过UC帐号检测是否存在(check_username_exists,check_email_exists)
	public  $jump_uc_check_exists = true;

	//检测头像是否设置
	function check_avatar($uid)
	{
		return uc_check_avatar($uid) == 1;
	}

	//会员名检测，覆盖父类
	function check_username_exists($username, $without_uid = null)
	{
		$exists = parent::check_username_exists($username, $without_uid);
		if($exists || $without_uid > 0) return $exists;

		if($this->jump_uc_check_exists) return false;

		//因为 UC API 没有提供某个 ID 例外的参数，所以下面的判断在特例的情况下不执行
		$result = uc_user_checkname($username);
		return $result < 0;
	}

	//会员名检测，覆盖父类
	function check_email_exists($email, $without_uid = null)
	{
		$exists = parent::check_email_exists($email, $without_uid);
		if($exists || $without_uid > 0) return $exists;

		if($this->jump_uc_check_exists) return false;

		//因为 UC API 没有提供某个 ID 例外的参数，所以下面的判断在特例的情况下不执行
		$result = uc_user_checkemail($email);
		return $result < 0;
	}

	//检查UID冲突问题
	public function check_uid_clash($uid)
	{
		$member = $this->read($uid, 0, 'uid,username', false);
		if($member)
		{
			$this->add_error('UCenter会员表与本站会员表数据冲突，请网站管理员先将本站会员数据导入UCenter会员表。');
			return false;
		}
		else
		{
			return true;
		}
	}

	//在更新本地会员前，先更新UC表
	protected function _save_befor()
	{
		$edit = $this->_save_on == 'edit';
		if($edit)
		{
			//修改密码或email，则先对uc表进行更新
			if($this->_save_data['password'] || $this->_save_data['email'])
			{
				if(!$this->_save_data['username']) {
					$member_data = parent::read($this->_save_pkid);
					$username = $member_data['username'];
				} else {
					$username = $this->_save_data['username'];
				}
				$oldpw = '';
				$newpw = $this->_save_data['password'] ? $this->_save_data['password'] : '';
				$ignoreoldpw = 1;
				$uid = uc_user_edit($username, $oldpw, $newpw, $this->_save_data['email'], $ignoreoldpw);
				if(!is_numeric($uid) || $uid < 0)
				{
					$this->add_error('ucenter_member_edit_' . $uid);
					return;
				}
				else
				{
					//if($keyid = $uid) ...
				}
			}
		}
		elseif(!$this->jump_uc_register)
		{
			if(!$this->_save_data['password']) $this->_save_data['password'] = random();
 			//UC表进行注册
			$uid = uc_user_register(
				$this->_save_data['username'],
				$this->_save_data['password'],
				$this->_save_data['email']
			);

			//UC注册出错
			if($uid < 0)
			{
				$this->add_error('ucenter_member_add_' . $uid);
				return;
			}
			else
			{
				//UID冲突文件
				// if(!$this->_check_uid_clash($post['uid'])) {
				// 	return;
				// }
				//系统录入时，指定UID号，保持modoer和uc的统一
				$this->_save_data['uid'] = $uid;
			}
		}

		if( ! $this->has_error())
		{
			parent::_save_befor();
		}
	}

	//删除UC表数据
	protected function _delete_befor($ids)
	{
		//uc删除操作
		$succeed = uc_user_delete($ids);
		if(!$succeed)
		{
			$this->add_error('UCenter表会员删除失败！');
			return;
		}

		//删除之前其他行为
		if( ! $this->has_error())
		{
			parent::_delete_befor($ids);
		}
	}

}

/** end **/