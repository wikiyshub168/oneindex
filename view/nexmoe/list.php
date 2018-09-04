<?php view::layout('layout')?>
<?php 
function file_ico($item){
  $ext = strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));
  if(in_array($ext,['bmp','jpg','jpeg','png','gif'])){
  	return "image";
  }
  if(in_array($ext,['mp4','mkv','webm','avi','mpg', 'mpeg', 'rm', 'rmvb', 'mov', 'wmv', 'mkv', 'asf'])){
  	return "ondemand_video";
  }
  if(in_array($ext,['ogg','mp3','wav'])){
  	return "audiotrack";
  }
  return "insert_drive_file";
}
?>

<?php view::begin('content');?>
	
<div class="mdui-container-fluid">
<?php if($head):?>
<div class="mdui-typo" style="padding: 20px;">
	<?php e($head);?>
</div>
<?php endif;?>

<div class="nexmoe-item">
<div class="mdui-row">
	<ul class="mdui-list">
		<li class="mdui-list-item th">
		  <div class="mdui-col-xs-12 mdui-col-sm-7">文件</div>
		  <div class="mdui-col-sm-3 mdui-text-right">修改时间</div>
		  <div class="mdui-col-sm-2 mdui-text-right">大小</div>
		</li>
		<?php if($path != '/'):?>
		<li class="mdui-list-item mdui-ripple">
			<a href="<?php echo get_absolute_path($root.$path.'../');?>">
			  <div class="mdui-col-xs-12 mdui-col-sm-7">
				<i class="mdui-icon material-icons">arrow_upward</i>
		    	..
			  </div>
			  <div class="mdui-col-sm-3 mdui-text-right"></div>
			  <div class="mdui-col-sm-2 mdui-text-right"></div>
		  	</a>
		</li>
		<?php endif;?>
		
		<?php foreach((array)$items as $item):?>
			<?php if(!empty($item['folder'])):?>

		<li class="mdui-list-item mdui-ripple">
			<a href="<?php echo get_absolute_path($root.$path.rawurlencode($item['name']));?>">
			  <div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
				<i class="mdui-icon material-icons">folder_open</i>
		    	<?php e($item['name']);?>
			  </div>
			  <div class="mdui-col-sm-3 mdui-text-right"><?php echo date("Y-m-d H:i:s", $item['lastModifiedDateTime']);?></div>
			  <div class="mdui-col-sm-2 mdui-text-right"><?php echo onedrive::human_filesize($item['size']);?></div>
		  	</a>
		</li>
			<?php else:?>
		<li class="mdui-list-item file mdui-ripple">
			<a href="<?php echo get_absolute_path($root.$path).rawurlencode($item['name']);?>" target="_blank">
			  <div class="mdui-col-xs-12 mdui-col-sm-7 mdui-text-truncate">
				<i class="mdui-icon material-icons"><?php echo file_ico($item);?></i>
		    	<?php e($item['name']);?>
			  </div>
			  <div class="mdui-col-sm-3 mdui-text-right"><?php echo date("Y-m-d H:i:s", $item['lastModifiedDateTime']);?></div>
			  <div class="mdui-col-sm-2 mdui-text-right"><?php echo onedrive::human_filesize($item['size']);?></div>
		  	</a>
		</li>
			<?php endif;?>
		<?php endforeach;?>
	</ul>
</div>
</div>
<?php if($readme):?>
<div class="mdui-typo mdui-shadow-3" style="padding: 20px;margin: 20px; 0">
	<div class="mdui-chip">
	  <span class="mdui-chip-icon"><i class="mdui-icon material-icons">face</i></span>
	  <span class="mdui-chip-title">README.md</span>
	</div>
	<?php e($readme);?>
</div>
<?php endif;?>
</div>
<script>
$ = mdui.JQ;

function downall() {
     let dl_link_list = Array.from(document.querySelectorAll("li a"))
         .map(x => x.href) // 所有list中的链接
         .filter(x => x.slice(-1) != "/"); // 筛选出非文件夹的文件下载链接

     let blob = new Blob([dl_link_list.join("\r\n")], {
         type: 'text/plain'
     }); // 构造Blog对象
     let a = document.createElement('a'); // 伪造一个a对象
     a.href = window.URL.createObjectURL(blob); // 构造href属性为Blob对象生成的链接
     a.download = "folder_download_link.txt"; // 文件名称，你可以根据你的需要构造
     a.click() // 模拟点击
     a.remove();

 }	

$(function(){
	$('.file a').each(function(){
		$(this).on('click', function () {
			var form = $('<form target=_blank method=post></form>').attr('action', $(this).attr('href')).get(0);
			$(document.body).append(form);
			form.submit();
			$(form).remove();
			return false;
		});
	});
});
</script>
<a href="javascript:downall();" class="mdui-fab mdui-fab-fixed mdui-ripple mdui-color-theme-accent"><i class="mdui-icon material-icons">file_download</i></a>
<?php view::end('content');?>
