<?php
if($_GET['goto']=='web') {
    set_cookie('auto_mobile', 'N');
    location(url('index'));
} elseif(_cookie('auto_mobile') != 'Y') {
    set_cookie('auto_mobile', 'Y');
}

if($d_city = get_default_city()) {
    if(!$_CITY['aid']) {
        init_city($d_city['aid']);
        $_CITY = $d_city;
    }
} else {
    show_error('global_area_city_empty');
}

echo $_SERVER['REQUEST_URI'];
output();
?>
