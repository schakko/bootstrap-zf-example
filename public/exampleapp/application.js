/**
 * Main application
 */
function Application() {
	var self = this;
	this.isStarted = false;

	/** composite manager registration */
	CompositeManager.register(this.component, this);
	this.component = 'application';

	this.implode = function(seperator, array) {
		var r = "";

		for (i = 0, m = array.length; i < m; i++) {
			r += array[i];

			if ((i + 1) != m) {
				r += seperator;
			}
		}

		return r;
	}

	this.init = function() {
		// notify event bus that the application is booting
		this.eventBus.publish("application:booting", {});

		// Append global error handler for AJAX requests
		$(document)
				.ajaxError(
						function(event, jqxhr, settings, exception) {
							var serverData = $.parseJSON(jqxhr.responseText);

							// formular errors
							if (serverData.id) {
								updateFormularErrors(serverData);
							}
							// other errors
							else {
								// disable global errors for this response
								if (settings.suppressErrors) {
									return;
								}

								var div = '<div class="alert-message error fade in" data-alert="alert">';
								div += '<a class="close" href="#">x</a><p><strong>You suck!</strong> '
										+ serverData.error + '</p>';
								if (serverData.trace) {
									div += "<p><strong>Stacktrace:</strong></p><p>";
									var trace = null;

									for (i = 0, m = serverData.trace.length; i < m; i++) {
										trace = serverData.trace[i];

										div += trace.file + " (line "
												+ +trace.line + "): "
												+ trace["class"] + "."
												+ trace["function"]
												+ " (arguments: "
												+ self.implode(",", trace.args)
												+ ")<br />";

										if (i == 5) {
											div += (m - i)
													+ " entries more... (skipped)";
											break;
										}
									}
									div += "</p>";
								}
								div += '</div>';
								$("#msgContainer").html(div);
								$(".alert-message").alert();
							}
						});

		// load needed templates for jQuery template
		$("#templates").load('/index/templates', function() {
			// notify bus that application has been started
			self.eventBus.publish("application:started", {});
		});
	};
};

/**
 * Initialize application
 */
$(document).ready(function() {
	(new Application()).init();
});

/** client side controllers */

/** LoginViewController handles the login form */
function LoginViewController() {
	var self = this;
	this.component = 'loginView';
	this.formName = '#formLogin';
	CompositeManager.register(this.component, this);

	this.init = function() {
		// after application has been started
		this.eventBus.subscribe('application:started', function() {
			// append click listener on form
			$(self.formName + " input.submit").click(
					function() {
						// serialize data and send it to backend
						$.post($(self.formName).attr('action'),
								$(self.formName).serialize(), function(data) {
									// on success, do a redirect
									window.location.href = data.redirect_to;
								});
					});
		});
	};
};

function CommentDeleteViewController() {
	var self = this;
	this.component = 'commentDeleteView';
	CompositeManager.register(this.component, this);

	this.init = function() {
		// after application has been started
		this.eventBus.subscribe('application:started', function() {
			$("#deleteCommentCancel").click(function() {
				window.location.href = $(this).attr('href');
			});
		});
	};
};

function CommentCreateViewController() {
	var self = this;
	this.component = 'commentCreateView';
	CompositeManager.register(this.component, this);

	this.init = function() {
		// after application has been started
		this.eventBus.subscribe('application:started', function() {
			$("#formCommentCreate input.submit").click(function() {
				$.post($("#formCommentcreate").attr('action'), $("#formCommentCreate").serialize(), 
					function(data) {
				});
			});
		});
	};
};
