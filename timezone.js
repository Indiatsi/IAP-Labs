$(()=>{
	var offset = new Date().getTimezoneOffset();
	var timestamp = new Date().getTime();
	var utc = (timestamp + (60 * 60 * 1000 * offset));
	$("input[name=offset]").val(offset);
	$("input[name=utc]").val(utc/1000);
});