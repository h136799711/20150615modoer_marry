var marker = Array(item_num);
var infow = Array(item_num);
var map = new BMap.Map( 'map_container' );
map.enableScrollWheelZoom();
map.addControl(new BMap.NavigationControl(null)); 
$(document).ready(function(){
    $('#subjects h3').each(function(i) {
        var name = $(this).text().trim();
        var mappoint = eval('('+$(this).attr('mappoint')+')');
        addMarker(name, new BMap.Point(mappoint.lng,mappoint.lat), i, $(this).attr('sid'));
    });
    if(marker[0]) {
        map.centerAndZoom(marker[0].getPosition(), 16);
    } else {
        map.centerAndZoom(new BMap.Point(city_p1,city_p2), 15);
    }
    map.enableAutoResize();
});

function addMarker(name, point, index, sid) {
    var myIcon = new BMap.Icon("http://api.map.baidu.com/img/markers.png", new BMap.Size(23, 25), {
        offset: new BMap.Size(10, 25),                  // 指定定位位置
        imageOffset: new BMap.Size(0, 0 - index * 25)   // 设置图片偏移
    });
    marker[index] = new BMap.Marker(point, {icon: myIcon});
    marker[index].setTitle(name);
    marker[index].addEventListener("click", function() {
        showMarker(sid,index);
    });
    map.addOverlay(marker[index]);
}

function showMarker(sid,index,move) {
    var ifw = new BMap.InfoWindow('载入中...');
    marker[index].openInfoWindow(ifw);
    if(move==true) map.setCenter(marker[index].getPosition());
    getSubject(sid,ifw,index);
}

function getSubject(sid,ifw,index) {
    if (!is_numeric(sid)) {
        alert('无效的SID'); return;
    }
    if(infow[index]!=undefined) {
        ifw.setContent(infow[index]);
        ifw.setWidth(400);
    } else {
        $.post(Url('item/map/op/detail'), { sid:sid, in_ajax:1}, 
        function(data) {
            infow[index] = data;
            ifw.setContent(data);
            ifw.setWidth(400);
        });
    }
}