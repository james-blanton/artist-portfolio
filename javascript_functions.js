// this function is used to display a cautionary message to the user when they go delete artwork via the admin dashboard

function dropdownSelectionCheck(url){
	if (url.value != '#'){
		if (confirm('Are you sure you wish to navigate to '+url.value+'?')) {
			window.location.href=url.value;
		} else {
		    // Do nothing!
		}
	}
}