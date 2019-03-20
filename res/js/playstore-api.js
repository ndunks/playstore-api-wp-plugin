if(!$ && jQuery) $ = jQuery;
$(function(){
	if(!$('#app-images .thumbnail')) return;
	$('body').append('<div class="modal fade" id="api_imagemodal" tabindex="-1" role="dialog" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"<div class="modal-body"><img src="" id="api_imagepreview" data-dismiss="modal" style="width: 100%; height: 100%" ></div></div></div></div>');
	$('#app-images .thumbnail').on('click',function(e){$('#api_imagepreview').attr('src', $(e.target).attr('src'));$('#api_imagemodal').modal('show');});
})