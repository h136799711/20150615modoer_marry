
function uyan_sync_comment_num() {
	var num = $('#uyan_acn').text();
	num = parseInt(num);
	if(num >= 0 && num==local_comments) {
		return true;
	}
	grade = 0;
	if(uyan_idtype!='' && uyan_id > 0 && num >= 0) {
		$.post(Url('comment/ajax/op/update_comments'), 
			{ 'idtype':uyan_idtype, 'id':uyan_id, 'comments':num, 'grade':grade, 'in_ajax':1 }, 
			function(data) {}
		);
	}
	return num > 0;
}

var uyan_time = window.setInterval(function(){
	if(uyan_sync_comment_num()) clearInterval(uyan_time);
}, 1000);