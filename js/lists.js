var alertChange = function(name, id) {
	if(document.querySelector("#" + name).checked){
		document.querySelector("#toggle-form-" + id).action += "&checked=true";
		console.log(document.querySelector("#toggle-form-" + id).action);
		document.querySelector("#toggle-form-" + id).submit();
	}
	else{
		document.querySelector("#toggle-form-" + id).action += "&checked=false";
		console.log(document.querySelector("#toggle-form-" + id).action);
		document.querySelector("#toggle-form-" + id).submit();
	}
};