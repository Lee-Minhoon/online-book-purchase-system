function checkbox(form){
	var length = form.elements['checkbox[]'].length;
	if(typeof(length) == 'undefined'){
		if (form.elements['checkbox[]'].checked == true){
			form.elements['hidden[]'].value = 1;
		}
	}
	for(i = 0; i < length; i++){
		if (form.elements['checkbox[]'][i].checked == true){
			form.elements['hidden[]'][i].value = 1;
		}
	}
}

function go_back(){
	history.back();
}