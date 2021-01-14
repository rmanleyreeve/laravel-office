function checkNotify(){
 return ('Notification' in window);
}

if(checkNotify() && Notification && Notification.permission === 'default') {
	Notification.requestPermission().then(function(permission) {
		if(!('permission' in Notification)) {
			Notification.permission = permission;
		}
	});
}

function sendDesktopNotification(title,text,autoclose) {
	if(!checkNotify()) { return; }
	if (Notification.permission === "granted") {
		var notification = new Notification(title, {
			icon: '/assets/images/date_time.png',
			body: text,
			tag: 'office.re-media.biz Notification',
			requireInteraction: !autoclose
		});
		notification.onclick = function() {
			parent.focus();
			window.focus();
			this.close();
		};
		if(autoclose) { setTimeout(notification.close.bind(notification), 5000); }
	}
}

function setICheckbox() {
	$('input:not(.js-switch)').iCheck({
		checkboxClass: 'icheckbox_square-aero',
		radioClass: 'iradio_square-aero'
	});
	$('input:not(.js-switch)').on('ifToggled', function (e) {
		$(this).parents('li').toggleClass('closed');
	});
}

function updateClock(){
	var currentTime = new Date( );
	var currentHrs = currentTime.getHours();
	var currentMins = currentTime.getMinutes ();
	var currentSecs = currentTime.getSeconds();
	if(0==currentHrs && 0==currentMins && currentSecs<4) {
		location.reload();
	}
	currentHrs = ( currentHrs < 10 ? "0" : "" ) + currentHrs;
	currentMins = ( currentMins < 10 ? "0" : "" ) + currentMins;
	var currentTimeString = currentHrs + ":" + currentMins;
	$("#cal-time").html(currentTimeString);
}

// set notification counter from session on page load
var globalNotificationBadgeNum = 0;
fetch('/ajax/header-notifications-count')
.then(function(response) {
	return response.json()
}).then(function(responseJson) {
	globalNotificationBadgeNum = responseJson.count;
}).catch(function(ex) {
	console.log('set globalNotificationBadgeNum failed', ex)
});

function getNewNotifications(){
	var _notifications = [];
	var _unread = [];
	var _read = [];
	fetch('/ajax/header-notifications')
	.then(function(response) {
		return response.json()
	}).then(function(responseJson) {
		//console.log(responseJson);
		$('#header-notifications-list li').remove();
		$('#mark-all-read').data('uids','');
		$.each(responseJson, function(i,e) {
			//console.log(e);
			var _class = "unread";
			_notifications.push(e.uid);
			if(e.notification_read > 0) { _read.push(e.uid); _class = "read"; } else { _unread.push(e.uid); }
			var li = '<li data-uid="' + e.uid + '" class="notification ' + _class + '"><a href="javascript:void(0);">';
			li += ('ENTRY' == e.activity) ?
			'<div class="icon-circle bg-success"><i class="material-icons">person</i></div>' :
			'<div class="icon-circle bg-warning"><i class="material-icons">person_outline</i></div>';
			li += '<div class="menu-info"><h4>' + e.firstname + ' ' + e.surname + ' ' + e.activity + '</h4>';
			li += '<p><i class="material-icons">access_time</i> '+ e.time +'</p></div></a></li>'
			;
			//console.log(li);
			$('#header-notifications-list').append( $(li) );
		});
		$('#mark-all-read').data('uids',_unread);
		var count = (_notifications.length - _read.length);
		if(count > 0) {
			$('#header-notifications span.label-count').html(count).show(1000);
			if(count > globalNotificationBadgeNum) {
				//console.log('new activity detected: ',count,globalNotificationBadgeNum);
				var msg = responseJson[0].firstname + ' ' + responseJson[0].surname + ' ' + responseJson[0].activity + ' ' + responseJson[0].time;
				sendDesktopNotification('RE Media Office',msg,true);
			}
		} else {
			$('#header-notifications span.label-count').html('0').hide(1000);
		}
		globalNotificationBadgeNum = count;
	}).catch(function(ex) {
		console.log('getNewNotifications failed', ex)
	});
}

function getAlerts(){
	fetch('/ajax/header-alerts')
	.then(function(response) {
		return response.json()
	}).then(function(responseJson) {
		//console.log(responseJson);
		$('#header-alerts-list li').remove();
		$.each(responseJson, function(i,e) {
			//console.log(i,e);
			var li = '<li class="unread"><a href="/admin/check-attendance">';
			li +=  '<div class="icon-circle bg-warning"><i class="material-icons">warning</i></div>';
			li += '<div class="menu-info"><h4>' + e.name + ' ' + e.date + '</h4><p>';
			$.each(e.activity, function(ii,ee) {
				li += ee.activity + ' '+ ee.time + (ii < e.activity.length-1 ? '<br>':'');
			});
			li += '</p></div></a></li>'
			;
			$('#header-alerts-list').append( $(li) );
		});
		var count = Object.keys(responseJson).length;
		if(count > 0) {
			$('#header-alerts span.label-count').html(count).show(1000);
			sendDesktopNotification('Attention','There are '+count+' attendance errors requiring repair!',false);
		} else {
			$('#header-alerts span.label-count').html('0').hide(1000);
		}
	}).catch(function(ex) {
		console.log('getAlerts failed', ex)
	});
}

function updateRead(uid) {
	fetch('/ajax/header-notifications', {
		method: 'post',
		headers: {
			"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'uid=' + JSON.stringify(uid)
	})
	.then(function (response) {
		return response.text()
	}).then(function (responseText) {
		console.log(uid + " read " + responseText);
	}).catch(function (ex) {
		console.log(uid + ' read failed', ex)
	});
}

$(function(){

	getNewNotifications();
	setInterval(getNewNotifications, 20000);
	getAlerts();

	$('#mark-all-read').on('click',function(){
		var uids = $(this).data('uids');
		updateRead(uids);
		$('#header-notifications-list li').addClass("read").removeClass("unread");
		$('#header-notifications span.label-count').html('0').hide(1000);
	});

	var t;
	$(document).on('mouseenter', '#header-notifications-list li.unread', function(){
		var $el = $(this);
		var uid = $el.data('uid');
		t = setTimeout(function() {
			updateRead(uid);
			$el.addClass("read").removeClass("unread");
			var c = $('#header-notifications span.label-count').html();
			$('#header-notifications span.label-count').html(c-1);
		}, 1000);
	});
	$(document).on('mouseleave', '#header-notifications-list li.unread', function(){
		clearTimeout(t);
	});

	$('#header-notifications').on('show.bs.dropdown', function(e){
	});

	//tooltip init
	$('[data-toggle="tooltip"]').tooltip({
		container: 'body',
		trigger : 'hover'
	});

	// set BS modal title
	$('#bsModal').on('shown.bs.modal', function(e){
		$('#modalTitle').text( $('#dynamicModalTitle').text() );
	});
	// clear BS modal cache
	$('#bsModal').on('hidden.bs.modal', function(){
		$('#bsModal').removeData('bs.modal');
	});

	//Init switch buttons
	var $switchButtons = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
	$switchButtons.forEach(function (e) {
		var size = $(e).data('size');
		var options = {};
		options['color'] = '#009688';
		if (size !== undefined) options['size'] = size;
		var switchery = new Switchery(e, options);
	});

	//Init iCheckbox
	setICheckbox();

	// button alerts
	$(document).on('click','.confirm-delete', function(e){
		e.preventDefault();
		var href = $(this).attr('href');
		bootbox.confirm({
			size: "small",
			closeButton: false,
			message: 'Are you absolutely sure you wish to delete this record?',
			callback: function(r){ if(r){window.location=href;} }
		})
	});
	$(document).on('click','.delete-record-disabled', function(e){
		e.preventDefault();
		var dep = $(this).data('assoc');
		bootbox.alert({
			size: "small",
			closeButton: false,
			message: 'This record cannot be deleted because it has ' + dep + ' assigned.\nYou must delete or reassign the ' + dep + ' first.',
		});
	});
	$('.confirm-logoff').on('click',function(e) {
		var href = $(this).attr('href');
		e.preventDefault();
		bootbox.confirm({
			size: "small",
			closeButton: false,
			message: "Are you sure you want to log out?",
			callback: function(r){ if(r){window.location=href;} }
		})
	});

	//form validation
	$('form.validate').validate({
		rules: {
			username: { required: true, remote: { param:{url:"/users/check-username/",type:'post'}, depends:function(e){return ($(e).val() !== $('#original_username').val());} } },
		},
		highlight: function (element) {
			$(element).closest('.form-group').addClass('has-error');
		},
		unhighlight: function (element) {
			$(element).closest('.form-group').removeClass('has-error');
		},
		errorPlacement: function (error, element) {
			if(element.parent('.input-group').length) {
				error.insertAfter(element.parent());
			} else {
				error.insertAfter(element);
			}
		}
	});

	// add date to csv export files URL, so the file gets generated with this name
	$('a.add-date').on('click',function(e){
		var href = $(this).data('href');
		var t = $.datepicker.formatDate('yy-mm-dd', new Date());
		if(href.indexOf('.csv')==-1) {
			$(this).attr('href', href + '_'+t + '.csv');
			return true;
		}
		$(this).attr('href',href.replace('Export','Export_'+t));
		return true;
	});

});
