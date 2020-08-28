require('./bootstrap');

window.getParam = function (name) {
	var params = location.search.substr(location.search.indexOf("?") + 1);
	var sval = "";
	params = params.split("&");
	for (var i = 0; i < params.length; i++) {
		temp = params[i].split("=");
		if ([temp[0]] == name) { 
			sval = temp[1]; 
		}
	}
	return sval;
}

window.integerCheck = function(obj) {
	var value = obj.value;
	var regex = /[^0-9]/gi;
	obj.value = value.replace(regex, "");
};

toastr.options.closeMethod = 'fadeOut';
toastr.options.closeDuration = 300;
toastr.options.closeEasing = 'swing';
toastr.options.positionClass = 'toast-bottom-right';