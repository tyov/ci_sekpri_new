<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Welcome to CodeIgniter</title>
	</head>
	<link rel="stylesheet" href="<?php echo base_url(); ?>static/lib/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>static/lib/bootstrap/css/bootstrap-responsive.min.css" />
	<link rel="stylesheet" href="<?php echo base_url(); ?>static/lib/bootstrap/css/bootstrap-twipsy.css" />
	<script src="<?php echo base_url(); ?>static/js/panel/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/plupload/browserplus-min.js'); ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('static/js/plupload/plupload.full.min.js'); ?>"></script>
	<script>
		document.documentElement.className += 'js';
		var web = { host: '<?php echo strtolower(site_url()); ?>', base: '<?php echo strtolower(base_url()); ?>' }
		
	</script>
	<body>
		<form enctype="multipart/form-data" method="post" id="formSimpan">
			<div class="controls">
				<div id="uploadcontainer1">
					<input type="hidden" name="keyId" value="<?php echo $keyId?>"/>
					<input type="hidden" name="username" value="<?php echo $username?>"/>
					<div id="filelist1" style="background: #EEE; padding: 10px 5px; border:3px #CCC dotted; margin:5px 0px"></div>
					<a id="pickfiles1" class="btn btn-success input_tooltips" style="width:28%; font-height:bold">PILIH FILE</a>
					<a class="btn btn-primary input_tooltips" style="width:28%; font-height:bold">SIMPAN</a>
					<a class="btn btn-warning input_tooltips" style="width:28%; font-height:bold">HAPUS</a>
					<a id="uploadfiles1" class="hidden">Unggah</a>
				</div>
			</div>
		</form>
		<b>FILE TERUPLOAD</b>
		<br>
		<ol>
		<?php
		$no = 1;
		foreach($listAttachment as $row){
			echo "<li><b>".$row->filename."</b> <i>".$row->filesize."Kb</i></li>";
		}
		?>
		</ol>
	</body>
	<script>
		$(document).ready(function(){
			$(".btn-primary").click(function(){
				$("#formSimpan").attr("action","<?php echo base_url("index.php/save/saveToDatabase")?>")
				$("#formSimpan").submit();
			});
			
			$(".btn-warning").click(function(){
				$("#formSimpan").attr("action","<?php echo base_url("index.php/save/unlikFile")?>")
				$("#formSimpan").submit();
			});
			uploader2 = new plupload.Uploader({
				max_file_size : '500mb', 
				url: web.host + 'index.php/upload/file?document=1',
				chunk_size: '1mb',
				browse_button : 'pickfiles1', 
				container : 'uploadcontainer1',
				runtimes : 'gears,html5,flash,silverlight,browserplus',
				flash_swf_url: web.base + 'static/js/plupload/plupload.flash.swf',
				silverlight_xap_url : web.base + 'static/js/plupload/plupload.silverlight.xap'
			});
			
			$('#uploadfiles1').click(function(e) {
				if ( $("#filelist1 .addedfile").length > 0 ) {
					uploader2.start();
				}
				return false;
			});
			
			uploader2.init();
			
			uploader2.bind('FilesAdded', function(up, files) {
				$.each(files, function(i, file) {
					$('#filelist1').append('<div class="addedfile uploadfile" id="' + file.id + '"><span class="filename">' + file.name + '</span> (' + plupload.formatSize(file.size) + ') <b></b>' + '</div>');
				});
				up.refresh();
				$('#uploadfiles1').click();
			});
			
			uploader2.bind('UploadProgress', function(up, file) {
				$('#' + file.id + " b").html(file.percent + "%");
			});
			
			uploader2.bind('Error', function(up, err) {
				$('#filelist1').append("<div class='alert alert-error'>Error: " + err.code + ", Message: " + err.message + (err.file ? ", File: " + err.file.name : "") + "</div>");
				up.refresh();
			});
			
			uploader2.bind('FileUploaded', function(up, file, jsonresp) {
				var div = $("#"+file.id);
				var json = eval('('+jsonresp.response+')');
				var fileName = json.fileName;
				
				if (json.error != null && json.error.code != null) {
					div.remove();
					// Func.show_notice({ title: 'Informasi', text: json.error.message });
					alert(json.error.message);
				}else{
					console.log(json);
					//div.removeClass('addedfile').addClass('completefile').find('b').html("100%");
					div.after('<div style="color:blue"><strong>siap diupload</strong></div>');
					div.after('<input type="hidden" name="fileSize[]" value="' + json.fileSize + '">');
					div.after('<input type="hidden" name="fileName[]" value="' + json.fileName + '">');
				}
				
				var $form = $("#form-item");
				if ( $("#filelist1 .addedfile").length == 0 && $form.data('isSubmit') == true ) {
					$form.removeData('isSubmit');
					$(".alert").remove();
					$form.submit();
				}
			});
		});
	</script>
</html>