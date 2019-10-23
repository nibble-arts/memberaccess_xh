function ma_admin_init(text) {

	// add user delete window
	jQuery(".ma_delete").click(function (e) {

		e.preventDefault();

		r = confirm(text);

		if (r) {
			window.location = e.currentTarget.href;
		}
	});
}