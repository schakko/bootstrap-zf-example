/**
 * Returns a mock object if console implementation does not exist
 * @returns
 */
function ConsoleMock() {
	this.info = function() {
	};
	
	this.debug = function() {
	};

	this.warn = function() {
	};
}

/**
 * Returns a console object or a ConsoleMock
 * @returns
 */
function getConsole() {
	var refConsole = null;

	if (refConsole == null) {
		refConsole = (typeof (console) != "undefined") ? console
				: new ConsoleMock();
	}

	return refConsole;
}

/**
 * Debug print
 * 
 * @param args
 */
function $D(args) {
	getConsole().debug(args);
}

function updateFormularErrors(serverData) {
	var formId = serverData.id;
	// remove all error classes
	$("#" + formId + " div.error").removeClass("error");

	// remove all error strings
	$("[error-for=" + formId + "]").remove();

	jQuery.each(serverData.errors,
			function(idx, val) {
				var errorMessages = "<ul class='errortext' error-for='"
						+ formId + "'>";

				for (message in val) {
					errorMessages += "<li><span class='help-inline'>"
							+ val[message] + "</span></li>";
				}

				errorMessages += "</ul>";
				$("#" + formId + " [name=" + idx + "]").parent().parent().addClass("error");
				$("#" + formId + " [name=" + idx + "]").after(errorMessages);
			});
}
