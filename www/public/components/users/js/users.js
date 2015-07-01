$(document).ready(
	function() {
		usersTable = $('#usersTable').DataTable(
			{
				"data": usersData,
				"pageLength": 10,
				"columns": usersColumns,
				"ordering": false,
				"language": usersLanguage
			}
		);
		$('#usersTable tbody tr').on(
			{
				mouseover: function() {$(this).css({'cursor':'pointer'});},
				mouseout: function() {$(this).css({'cursor':'default'});},
				click: function() {usersView(usersTable.row(this).index());}
			}
		);
	}
).keydown(
	function(e) {
		e = e || event;
		if (e.keyCode === 27 && $("#popupWnd").css('display') == 'block') hidePopup();
	}
);
function usersView(r)
{
	var d = usersTable.row(r).data(), x = -1, f = 0;
	d[2] = parseInt(d[2], 10);
	d[6] = parseInt(d[6], 10);
	x = usersGetIdx(usersInfo, d[2]);
	if (x != -1) {
		if (usersInfo[x][1] != '') f |= 1;
		if (usersInfo[x][2] != '') f |= 2;
		if (usersInfo[x][3] != '') f |= 4;
	}
	$('#usersInfo').html(
		'<div class="row">'+
		'<div class="col-xs-3">'+((d[5] != "")?'<img src="/public/images/users/'+d[5]+'">':'<img src="/public/components/users/images/user.gif">')+'</div>'+
		'<div class="col-xs-6">'+
		d[3]+'<br>'+
		d[4]+'<br>'+
		((d[6] == usersRights[2][0])?usersRights[2][1]:((d[6] == usersRights[1][0])?usersRights[1][1]:usersRights[0][1]))+'<br>'+
		((d[7] == usersStatus[0][0])?usersStatus[0][1]:((d[7] == usersStatus[1][0])?usersStatus[1][1]:((d[7] == usersStatus[2][0])?usersStatus[2][1]:usersStatus[3][1])))+
		'</div>'+
		'<div class="col-xs-3">'+
		'<a class="btn btn-xs btn-info" onClick="usersEdit('+r+');"><i class="fa fa-edit"></i> изменить</a>'+
		(
			(usersId != d[2])
			?
			'<br><br><a class="btn btn-xs btn-danger" onClick="usersRemove('+r+');"><i class="fa fa-trash-o"></i> удалить</a>'
			:
			''
		)+
		'</div>'+
		'</div>'+
		(
			(x != -1)
			?
			'<div class="row"><div class="col-xs-12">&nbsp;</div></div>'+
			'<div class="row"><div class="col-xs-3"><b>ФИО</b></div><div class="col-xs-9">'+((f)?((f & 0x1)?usersInfo[x][1]:'')+((f & 0x2)?' '+usersInfo[x][2]:'')+((f & 0x4)?' '+usersInfo[x][3]:''):'&mdash;')+'</div></div>'+
			'<div class="row"><div class="col-xs-3"><b>Паспорт</b></div><div class="col-xs-9">'+((usersInfo[x][4] != '')?usersInfo[x][4]:'&mdash;')+'</div></div>'+
			'<div class="row"><div class="col-xs-3"><b>Телефон</b></div><div class="col-xs-9">'+((usersInfo[x][5] != '')?usersInfo[x][5]:'&mdash;')+'</div></div>'+
			'<div class="row"><div class="col-xs-3"><b>Адрес</b></div><div class="col-xs-9">'+((usersInfo[x][6] != '')?usersInfo[x][6]:'&mdash;')+'</div></div>'
			:
			''
		)
	);
}
function usersEdit(r)
{
	var d = usersTable.row(r).data();
	d[2] = parseInt(d[2], 10);
	d[6] = parseInt(d[6], 10);
	showPopup();
	$('#popupCnt').html(
		'<form class="form-signin" name="users_form" method="post">'+
		'<h2 class="form-signin-heading">Изменение параметров</h2>'+
		'<div class="login-wrap">'+
			'<div class="user-login-info">'+
				'<div><input type="text" class="form-control black" name="user_login" value="'+d[3]+'" placeholder="логин" title="Логин"></div>'+
				'<div style="margin-top:5px"><input type="text" class="form-control black" name="user_email"'+((usersId != d[2])?'':' disabled="disabled"')+' value="'+d[4]+'" placeholder="e-mail" title="E-mail"></div>'+
				'<div style="margin-top:5px"><input type="password" class="form-control black" name="user_pass" value="" placeholder="пароль" title="Пароль, если не хотите менять - оставьте пустым"></div>'+
				'<div style="margin-top:5px">Роль:<br>'+
					'<select name="user_rights"'+((usersId != d[2])?'':' disabled="disabled"')+'>'+
					'<option value="'+usersRights[0][0]+'"'+((d[6] == usersRights[0][0])?' selected="selected"':'')+'>'+usersRights[0][1]+'</option>'+
					'<option value="'+usersRights[1][0]+'"'+((d[6] == usersRights[1][0])?' selected="selected"':'')+'>'+usersRights[1][1]+'</option>'+
					(
						(usersRights[2] != undefined)
						?
						'<option value="'+usersRights[2][0]+'"'+((d[6] == usersRights[2][0])?' selected="selected"':'')+'>'+usersRights[2][1]+'</option>'
						:
						''
					)+
					'</select>'+
				'</div>'+
				'<div style="float:left;margin-top:5px">Статус:<br>'+
					'<select name="user_status"'+((usersId != d[2])?'':' disabled="disabled"')+'>'+
					'<option value="'+usersStatus[0][0]+'"'+((d[7] == usersStatus[0][0])?' selected="selected"':'')+'>'+usersStatus[0][1]+'</option>'+
					'<option value="'+usersStatus[1][0]+'"'+((d[7] == usersStatus[1][0])?' selected="selected"':'')+'>'+usersStatus[1][1]+'</option>'+
					'<option value="'+usersStatus[2][0]+'"'+((d[7] == usersStatus[2][0])?' selected="selected"':'')+'>'+usersStatus[2][1]+'</option>'+
					'<option value="'+usersStatus[3][0]+'"'+((d[7] == usersStatus[3][0])?' selected="selected"':'')+'>'+usersStatus[3][1]+'</option>'+
					'</select>'+
				'</div>'+
				'<div style="float:right;margin-top:5px">Изображение:<br>'+
					'<select name="user_delimg"'+((d[5] != '')?'':' disabled="disabled"')+'>'+
					'<option value="0" selected="selected">не трогать</option>'+
					'<option value="1">удалить</option>'+
					'</select>'+
				'</div>'+
				'<div style="clear:both;font-size:0;line-height:0"></div>'+
			'</div>'+
			'<a class="btn btn-lg btn-block btn-white" onClick="usersSubmit('+r+');">Изменить</a>'+
			'<a class="btn btn-lg btn-block btn-white" onClick="hidePopup();">Отменить</a>'+
		'</div>'+
		'</form>'
	);
}
function usersSubmit(r)
{
	var d = usersTable.row(r).data(), v = {}, f = 0, t = null;
	d[2] = parseInt(d[2], 10);
	d[6] = parseInt(d[6], 10);
	v.id = d[2];
	t = $('input[name="user_login"]').val();
	if (t != d[3]) {
		v.login = t;
		if (!f) f = 1;
	}
	t = $('input[name="user_pass"]').val();
	if (t != '') {
		v.pass = t;
		if (!f) f = 1;
	}
	t = $('select[name="user_delimg"] option:selected').val();
	if (t == '1') {
		v.img = d[0];
		v.imgdel = 1;
		if (!f) f = 1;
	}
	t = parseInt($('select[name="user_rights"] option:selected').val(), 10);
	if (t != d[6]) {
		v.rights = t;
		if (!f) f = 1;
	}
	t = $('select[name="user_status"] option:selected').val();
	if (t != d[7]) {
		v.status = t;
		if (!f) f = 1;
	}
	hidePopup();
	if (f) {
		$.ajax({
			type: 'POST',
			url: '/users/action/change',
			data: v,
			dataType: 'text',
			success: function(data) {
				if (data == "OK") {
					var x = usersGetIdx('data', d[2]);
					if (x != -1) {
						t = '';
						if (v.login !== undefined) {
							usersData[x][3] = v.login;
							usersTable.cell(r, 3).data(v.login).draw();
						}
						t += usersData[x][3]+'<br>';
						t += usersData[x][4]+'<br>';
						if (v.rights !== undefined) {
							usersData[x][6] = v.rights;
							usersTable.cell(r, 6).data(v.rights).draw();
						}
						t += ((usersData[x][6] == usersRights[2][0])?usersRights[2][1]:((usersData[x][6] == usersRights[1][0])?usersRights[1][1]:usersRights[0][1]))+'<br>';
						if (v.status !== undefined) {
							usersData[x][7] = v.status;
							usersTable.cell(r, 7).data(v.status).draw();
						}
						t += ((usersData[x][7] == usersStatus[0][0])?usersStatus[0][1]:((usersData[x][7] == usersStatus[1][0])?usersStatus[1][1]:((usersData[x][7] == usersStatus[2][0])?usersStatus[2][1]:usersStatus[3][1])));
						if (v.imgdel !== undefined) {
							usersData[x][0] = '<img src="/public/components/users/images/user.gif">';
							usersTable.cell(r, 0).data('<img src="/public/components/users/images/user.gif">').draw();
							usersData[x][5] = '';
							usersTable.cell(r, 5).data('').draw();
						}
						usersData[x][1] = t;
						usersTable.cell(r, 1).data(t).draw();
						usersView(r);
					}
				} else {
					alert('Ошибка!\\nНе удалось изменить данные пользователя.');
				}
			},
			error: function() {
				alert('Ошибка!\\nНет ответа от сервера.');
			}
		});
	}
}
function usersRemove(r)
{
	if (confirm('Вы действительно хотите удалить пользователя?')) {
		var d = usersTable.row(r).data();
		d[2] = parseInt(d[2], 10);
		$.ajax({
			type: 'POST',
			url: '/users/action/remove',
			data: {'user_id':d[2]},
			dataType: 'text',
			success: function(data) {
				if (data == "OK") {
					var x = usersGetIdx('data', d[2]), i = 0, a = [];
					if (x != -1) {
						for (i=0; i<usersData.length; i++) {
							if (i != x) a[a.length] = usersData[i];
						}
						usersData = a;
					}
					if (a.length != 0) a = [];
					x = usersGetIdx('info', d[2]);
					if (x != -1) {
						for (i=0; i<usersInfo.length; i++) {
							if (i != x) a[a.length] = usersInfo[i];
						}
						usersInfo = a;
					}
					usersTable.row(r).remove().draw();
					$('#usersInfo').html('');
				} else {
					alert('Ошибка!\\nНе удалось удалить пользователя.');
				}
			},
			error: function() {
				alert('Ошибка!\\nНет ответа от сервера.');
			}
		});
	}
}
function usersGetIdx(o, v)
{
	var n = -1, i = x = 0;
	if (o == 'data') {
		o = usersData;
		x = 2;
	} else {
		o = usersInfo;
	}
	for (i=0; i<o.length; i++) {
		if (o[i][x] == v) {
			n = i;
			break;
		}
	}
	return n;
}
