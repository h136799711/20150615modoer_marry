<?php
/**
* 会员头像组件
*/
class mc_member_face extends ms_base
{
	public static $pic_ext = '.jpg';
	public static $save_dir = 'faces';
	
	function __construct()
	{
		parent::__construct();
	}

	static function get_filename($uid, $size = 'small') 
	{
	    $sizes = array('small','big');
	    $size = strtolower($size);
	    if(!in_array($size, $sizes)) $size='big';

		$uid = abs(intval($uid));
	    $uid = sprintf("%09d", $uid);
	    $dir1 = substr($uid, 0, 3);
	    $dir2 = substr($uid, 3, 2);
	    $dir3 = substr($uid, 5, 2);

	    $size=='small' && $size='';
	    $filename = substr($uid, -2).($size?"_{$size}":'').self::$pic_ext;
	    $filepath = self::$save_dir.'/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.$filename;
	    return $filepath;
	}

	function upload($uid, $source_file)
	{
		$img_obj = new ms_image();
		$sizeinfo = $img_obj->is_image($source_file);
		if(!$sizeinfo) {
			$this->add_error('上传的图片无效或不存在。');
			return;
		}
		//判断图片尺寸
		if($sizeinfo[0]<120||$sizeinfo[1]<120) {
			$this->add_error('上传的图片尺寸宽和高不能小于120像素。');
			return;
		}
		$filename = self::get_filename($uid, 'big');
		//创建目录
		if(!$this->create_dir(dirname($filename))) return;

		//大图
		$filename = MUDDER_UPLOAD.str_replace('/',DS,$filename);

		$img_obj->thumb($source_file, $filename, 120, 120);
		//小图
		$filename = self::get_filename($uid, 'small');
		$filename = MUDDER_UPLOAD.str_replace('/',DS,$filename);
		$img_obj->thumb($source_file, $filename, 48, 48);

		return true;
	}

	function create_dir($dir)
	{
		$dirs = explode('/', $dir);
        $newdir = MUDDER_UPLOAD;
		foreach ($dirs as $d) {
			$newdir .= $d.DS;
			if( ! is_dir($newdir)) {
				if(! mkdir($newdir, 0777)) {
					$path = str_replace(MUDDER_DATA, './', $newdir);
					$this->add_error("目录($path)无法创建，请检查文件夹权限。");
					return;
				}
			}
		}
		return true;
	}
}