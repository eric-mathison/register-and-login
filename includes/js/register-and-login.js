(function ($) {
	$(".ral-wrapper #loginform p.login-password").append(
		'<span toggle="#user_pass" class="field-icon toggle-password"><img src="/wp-content/plugins/register-and-login/assets/svg/eye.svg" /></span>'
	);

	$(".toggle-password").click(function () {
		$(this).toggleClass("toggled");
		var image = $(this).find("img");
		if ($(this).hasClass("toggled")) {
			image.attr(
				"src",
				"/wp-content/plugins/register-and-login/assets/svg/eye-slash.svg"
			);
		} else {
			image.attr(
				"src",
				"/wp-content/plugins/register-and-login/assets/svg/eye.svg"
			);
		}

		var input = $($(this).attr("toggle"));
		if (input.attr("type") == "password") {
			input.attr("type", "text");
		} else {
			input.attr("type", "password");
		}
	});

	var strength = {
		0: "Worst ☹",
		1: "Bad ☹",
		2: "Weak ☹",
		3: "Good ☺",
		4: "Strong ☻",
	};

	var password = document.getElementById("password");
	var pass2 = document.getElementById("pass2");
	var meter = document.getElementById("password-strength-meter");
	var text = document.getElementById("password-strength-text");

	if (password != undefined || password != null) {
		password.addEventListener("input", function () {
			var val = password.value;
			var result = zxcvbn(val);

			// Update the password strength meter
			meter.value = result.score;

			// Update the text indicator
			if (val !== "") {
				text.innerHTML =
					"Strength: " +
					"<strong>" +
					strength[result.score] +
					"</strong>" +
					"<span class='feedback'>" +
					result.feedback.warning +
					" " +
					result.feedback.suggestions +
					"</span";
			} else {
				text.innerHTML = "";
			}

			$("#meter-value").val(meter.value);

			if (meter.value < 3) {
				$("#register-form input.register-button").prop("disabled", true);
				$("#register-form input.register-button").addClass("disabled");

				$(".ral-wrapper #resetpassform #reset-button").prop("disabled", true);
				$(".ral-wrapper #resetpassform #reset-button").addClass("disabled");
			}

			if (meter.value >= 3) {
				$("#register-form input.register-button").prop("disabled", false);
				$("#register-form input.register-button").removeClass("disabled");

				$(".ral-wrapper #resetpassform #reset-button").prop("disabled", false);
				$(".ral-wrapper #resetpassform #reset-button").removeClass("disabled");
			}

			if (pass2 != undefined || pass2 != null) {
				if (password.value != pass2.value) {
					$("#password-match").html(
						"<span class='match-wrapper'><img src='/wp-content/plugins/register-and-login/assets/svg/x.svg' /> Match</span>"
					);
				} else if (!password.value) {
					$("#password-match").html("");
				} else {
					$("#password-match").html(
						"<span class='match-wrapper'><img src='/wp-content/plugins/register-and-login/assets/svg/check.svg' /> Match</span>"
					);
				}
			}
		});
	}

	if (pass2 != undefined || pass2 != null) {
		pass2.addEventListener("input", function () {
			if (password.value != pass2.value) {
				$("#password-match").html(
					"<span class='match-wrapper'><img src='/wp-content/plugins/register-and-login/assets/svg/x.svg' /> Match</span>"
				);
			} else if (!password.value) {
				$("#password-match").html("");
			} else {
				$("#password-match").html(
					"<span class='match-wrapper'><img src='/wp-content/plugins/register-and-login/assets/svg/check.svg' /> Match</span>"
				);
			}
		});
	}
})(jQuery);
