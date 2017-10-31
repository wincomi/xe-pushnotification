function doDeleteDevice(srl, confirm_message)
{
	if(!confirm(confirm_message))
	{
		return false;
	}

	var params = {}
	params['srl'] = srl;

	console.log(params);
	
	exec_json('pushnotification.procPushnotificationDeleteDevice', params, function() {
		location.reload();
	});
}
