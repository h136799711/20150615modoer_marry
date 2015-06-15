<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_ucenter extends ms_base 
{
    function __construct(&$hook) 
    {
        parent::__construct();
        $hook->register(array('init'), $this);
    }

    function init()
    {
        if(!is_file(MUDDER_ROOT.'data'.DS.'config_uc.php')) return;

        $ucfg = $this->loader->variable('config','ucenter');
        if($ucfg['uc_enable']||defined('IN_ADMIN'))
        {
            require_once MUDDER_DATA.'config_uc.php';
        }

        if($ucfg['uc_enable'])
        {
            require_once MUDDER_ROOT.'uc_client'.DS.'client.php';
            //model mappingcon
            $this->loader->add_model_mapping(array(
                    'member:member'     => 'ucenter:member',
                    'member:register'   => 'ucenter:register',
                    'member:login'      => 'ucenter:login',
                    'member:feed'       => 'ucenter:feed',
                )
            );
            // $this->global['menu_mapping'][] = array(
            //     'src'=>'member/index/ac/pm',
            //     'dst'=>'ucenter/member/ac/pm'
            // );
        }
    }
}

/** end **/