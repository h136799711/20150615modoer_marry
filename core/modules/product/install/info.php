<?php
!defined('IN_MUDDER') && exit('Access Denied');
// 标识，唯一值
$newmodule['flag'] = 'product';
// 扩展版本
$newmodule['extra'] = 'pro';
// 名称
$newmodule['name'] = '商城';
// 依赖,建立于某够模块之上,核心模块除外，这里写其他模块的标识
$newmodule['reliant'] = '';
// 介绍
$newmodule['introduce'] = '用于商铺类产品销售';
// 作者
$newmodule['author'] = 'moufer,风格店铺';
// 网址
$newmodule['siteurl'] = 'http://www.modoer.com';
// 邮件
$newmodule['email'] = 'moufer@163.com,service@cmsky.org';
// 版权
$newmodule['copyright'] = 'MouferStudio,风格店铺';
// 版本
$newmodule['version'] = '3.4';
// 发布时间
$newmodule['releasetime'] = '2014-12-1';
// 版本检测 返回一个最新版本号，用于比较当前的版本
$newmodule['checkurl'] = 'http://www.modoer.com/info/module/product.php';
// 是否支持多城市
$newmodule['support_mc'] = '3.4';
?>