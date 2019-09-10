// init access tab
function access_init() {
	
	// set init state
	ma_access_change_tab(jQuery("#ma_active[type='checkbox']")[0]);


	// add event for change
	jQuery("#ma_active[type='checkbox']").bind("change", function () {
		ma_access_change_tab(this);
	});

}



// change tab css by active checkbox
function ma_access_change_tab(obj) {

	if (obj.checked) {
		jQuery("#xh_tab_ma_tab")
			.attr("style", "font-size: 110%; color: red; font-weight: bold;");
	}

	else {
		jQuery("#xh_tab_ma_tab")
			.removeAttr("style");
	}

}