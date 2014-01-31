function k9ui() {

	this.bindEvents = function() {

		$('.a_signin, .btn_signin').click(function() {
			server.api("api/authority/signin", $("#signin"));
		});

		$('.a_register, .btn_register').click(function() {
			server.api("api/authority/register", $("#register"));
		});

		$('.a_signout, .btn_signout').click(function() {
			server.delete("api/authority/");
			document.location.reload();
		});

		$('.a_about, .btn_about').click(function() {
			server.api("about/", $("#about"));
		});

		$('.a_credits, .btn_credits').click(function() {
			server.api("credits/", $("#about"));
		});

		$(".cancel, .reload").click(function() {
			document.location.reload();
		});

	}

}