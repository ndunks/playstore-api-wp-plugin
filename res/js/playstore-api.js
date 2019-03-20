(function ($, download_div_id) {
	console.log("Playstore Api JS Loaded");

	// Thumbnail popup
	if (!!$('#app-images .thumbnail')) {

		$('body').append('<div class="modal fade" id="api_imagemodal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"<div class="modal-body"><img src="" id="api_imagepreview" data-dismiss="modal" style="width: 100%; height: 100%" ></div></div></div></div>');
		$('#app-images .thumbnail').on('click', function (e) { $('#api_imagepreview').attr('src', $(e.target).attr('src')); $('#api_imagemodal').modal('show'); });
	}

	// Download Page
	if (window['PLAYSTORE_API_APK_URL']) {
		if (!$(download_div_id).length) {
			console.error("No " + download_div_id + ". check your page template");
		} else {
			var timer_id = null;
			$(download_div_id).append('<br/><b class="second">...</b>');
			function timer() {
				console.log("Timer id", timer_id);
				
				if (PLAYSTORE_API_DOWNLOAD_WAIT == 0) {
					clearInterval(timer_id);
					$(download_div_id).html('<a class="btn btn-lg btn-success" href="' + PLAYSTORE_API_APK_URL + '">Download Now!</a>');
					return false;
				}
				$(download_div_id + ' .second').html(--PLAYSTORE_API_DOWNLOAD_WAIT);
			}
			document.addEventListener('DOMContentLoaded', function () {
				timer_id = setInterval(timer, 1000);
			}, false);
		}
	}
})(jQuery, '#playstore_api_link')
