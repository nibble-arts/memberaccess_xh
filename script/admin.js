function ma_admin_init(text) {

	// add delete window
	jQuery(".delete").click(function (e) {

		e.preventDefault();

		r = confirm(text);

		if (r) {
			window.location = e.currentTarget.href;
		}
	});
}