function confUpdate(obj) {
	if((obj.type=='date' || obj.type=='time')) {
		if(obj.defaultValue==obj.value) {
			return;
		} else {
			obj.defaultValue=obj.value;
		}
	}
	$(obj).closest('td').css('backgroundColor','');
	let form= {
		item:$(obj).attr('item'),
		cat:$(obj).attr('cat'),
		pos:$(obj).attr('pos'),
		club:(obj.type=='checkbox' ? (obj.checked ? 1 : 0) : obj.value),
	}
	$.getJSON('./configure-updateWinners.php', form, function(data) {
		if(data.reload==1) {
			location.reload();
			return;
		}
		if(form.item=='ALLCLUBS') {
			$.each(data.ret, function() {
				$('[item="CLUB"][cat="'+this.cat+'"][pos="'+this.pos+'"]')
					.val(this.team)
					.closest('td').css('backgroundColor', data.error==0 ? 'green' : 'red');
			});
			$(obj).val('');
		} else {
			$(obj).closest('td').css('backgroundColor', data.error==0 ? 'green' : 'red');
		}
		if(data.msg) {
			$.alert({
				content:data.msg,
				boxWidth: '50%',
				useBootstrap: false,
				title: '',
			});
		}
	});
}

function alertUpdate(obj) {
	$.confirm({
		content: MsgConfirm,
		boxWidth: '50%',
		useBootstrap: false,
		title: '',
		buttons: {
			cancel: {
				text: CmdCancel,
				btnClass: 'btn-blue' // class for the button
			},
			unset: {
				text: CmdConfirm,
				btnClass: 'btn-red', // class for the button
				action: function () {
					confUpdate(obj);
				}
			}
		},
		escapeKey: true,
		backgroundDismiss: true
	})
}

function uploadResults(obj) {
	var form={
		item:'GETCOMPETITIONS',
		cat:'',
		pos:''};
	$.getJSON('configure-updateWinners.php', form, function(data) {
		var content='<select id="competition"><option value="0">---</option>';
		$.each(data.data, function() {
			content+='<option value="'+this.val+'">'+this.txt+'</option>'
		})
		content+='</select>';
		$.confirm({
			title:'',
			content:content,
			boxWidth:'33%',
			useBootstrap:false,
			buttons:{
				cancel: {text:CmdCancel},
				ok: {
					text:CmdConfirm,
					action:function() {
						if($('#competition').val()=='0') {
							return false;
						}
						form.item='SETCOMPETITIONDISTANCE';
						form.day=$(obj).attr('ref');
						form.comp=$('#competition').val();
						$.getJSON('configure-updateWinners.php', form, function(data){
							if(data.error==0) {
								$(obj).addClass('text-success').removeClass('text-secondary');
							} else if(data.msg>='') {
								$.alert({
									title:'',
									content:data.msg,
									boxWidth:'33%',
									useBootstrap:false,
								})
							}
						});
					}
				},
			}
		});
	});
}
