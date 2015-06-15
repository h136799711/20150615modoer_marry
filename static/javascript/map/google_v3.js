var browser=navigator.appName;
var b_version=navigator.appVersion;
var trim_Version=b_version.replace(/[ ]/g,"");
var msie6 = browser == "Microsoft Internet Explorer" && trim_Version.indexOf('MSIE6.0')>0;

var map = null;
var service;
var myOptions = {
	zoom: view_level,
	mapTypeControl: true,
	scrollwheel:true,
	mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
	navigationControl: true,
	mapTypeId: google.maps.MapTypeId.ROADMAP,
	center: new google.maps.LatLng('30.2448','120.1519')
}

function init_map() {
	map = new google.maps.Map(document.getElementById(map_id), myOptions);
	if(p1 != '' && p2 != '') {
		var c = new google.maps.LatLng(p2, p1);
		map.setCenter(c, view_level);
		if(show > 0) {
			var marker = new google.maps.Marker({
				position: c, 
				map: map,
				title:title
			});
		}
	}
}

function markmap() {
    var Center = map.getCenter();
    var lat = new String(Center.lat());
    var lng = new String(Center.lng());
    setLatLng(lat, lng);
	var marker = new google.maps.Marker({
		position: new google.maps.LatLng(lat,lng),
		map: map,
		draggable: true,
		title: '拖动找到确定的位置放下'
	});
	google.maps.event.addListener(marker, 'dragstart', function() {
		try {
			map.closeInfoWindow();
		}
		catch (err){
		}
	});
	google.maps.event.addListener(marker, 'dragend', function() {
		var latlng = marker.getPosition();
		lng = String(latlng.lng());
        lat = String(latlng.lat());
        setLatLng(lat,lng);
	});
}

function setLatLng(lat,lng) {
    document.getElementById('point1').value = lng;
    document.getElementById('point2').value = lat;
}

window.onload = function () {
	init_map();
	if(height >= 300) {
		create_search_ui();
	}
}

function create_search_ui() {
	//搜索框
	var _abc= document.createElement('div');
	var p = document.getElementById(map_id);
	_abc.style.width = '350px';
	_abc.style.fontSize = '12px';
	_abc.style.padding = '2px 5px';
	document.body.appendChild(_abc);
	_abc.innerHTML = '<input type="text" id="k" placeholder="地址搜索" style="width:99%;">';
	if(msie6) {
		p.style.height = p.offsetHeight - 40;
		_abc.style.left = '60px';
		_abc.style.width = "280px";
		_abc.style.margin = "0 auto";
	} else {
		_abc.style.position = 'absolute';
		_abc.style.backgroundColor = '#FFF';
		_abc.style.border ='1px solid #ccc';
		var top = p.offsetHeight-_abc.offsetHeight-5;
		var left = Math.round(p.offsetWidth/2-_abc.offsetWidth/2);
		_abc.style.top = '3px';
		_abc.style.left = left + 'px';
		_abc.style.zIndex = 999;
	}
	init_search_box();
}

function init_search_box(){
	var input = (document.getElementById('k'));
	var searchBox = new google.maps.places.SearchBox(input);
	google.maps.event.addListener(searchBox, 'places_changed', function() {
	    var places = searchBox.getPlaces();

	    var bounds = new google.maps.LatLngBounds();
	    for (var i = 0, place; place = places[i]; i++) {
			bounds.extend(place.geometry.location);
	    }
	    map.fitBounds(bounds);
	    map.setZoom(view_level);
	});

	google.maps.event.addListener(map, 'bounds_changed', function() {
	    var bounds = map.getBounds();
	    searchBox.setBounds(bounds);
	});
}