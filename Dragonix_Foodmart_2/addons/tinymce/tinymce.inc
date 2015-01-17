<?php 
 if ($use_wysiwyg == 1) {
?>	 
	<script language="javascript" type="text/javascript" src="addons/tinymce/jscripts/tiny_mce/tiny_mce_gzip.php"></script>
	<script language="javascript" type="text/javascript">
tinyMCE.init({
   button_tile_map : "true",
   mode : "textareas",
   width: "100%",
   height: "400",
   theme : "advanced",
   elements : "ajaxfilemanager",
relative_urls :false,
remove_script_host : false,
document_base_url : "<?php echo $shopurl; ?>",
   plugins : "spellchecker,style,layer,table,save,advhr,preview,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras",
   theme_advanced_source_editor_width : "100%",
   theme_advanced_blockformats : "p,h1,h2,h3,h4,h5,h6",
   theme_advanced_buttons1_add : "forecolor,backcolor",
   theme_advanced_buttons2_add_before: "cut,copy,paste,separator,search,replace,preview,separator",
   theme_advanced_buttons3_add : "emotions,flash,advhr,separator,ibrowser,fontselect,fontsizeselect",
   theme_advanced_toolbar_location : "top",
   theme_advanced_toolbar_align : "left",
   theme_advanced_path_location : "bottom",
   file_browser_callback : "ajaxfilemanager",
   custom_undo_redo_levels : 20
});

function ajaxfilemanager(field_name, url, type, win) {
			var ajaxfilemanagerurl = "../../plugins/ajaxfilemanager/ajaxfilemanager.php";
			switch (type) {
				case "image":
					ajaxfilemanagerurl += "?type=img";
					break;
				case "media":
					ajaxfilemanagerurl += "?type=media";
					break;
				case "flash": //for older versions of tinymce
					ajaxfilemanagerurl += "?type=media";
					break;
				case "file":
					ajaxfilemanagerurl += "?type=files";
					break;
				default:
					return false;
			}
			var fileBrowserWindow = new Array();
			fileBrowserWindow["file"] = ajaxfilemanagerurl;
			fileBrowserWindow["title"] = "Ajax File Manager";
			fileBrowserWindow["width"] = "782";
			fileBrowserWindow["height"] = "480";
			fileBrowserWindow["close_previous"] = "no";
			tinyMCE.openWindow(fileBrowserWindow, {
			  window : win,
			  input : field_name,
			  resizable : "yes",
			  inline : "yes",
			  editor_id : tinyMCE.getWindowArg("editor_id")
			});
			
			return false;
		}
	</script>	 
<?php
 }
 if ($use_wysiwyg == 2) {
?>	 	
<script language="javascript" type="text/javascript" src="addons/tinymce/jscripts/tiny_mce/tiny_mce_gzip.php"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "simple",
        width: "100%",
        height: "300"
	});
</script>
<?php
 }
?> 