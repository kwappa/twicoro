<?php
require_once './config.php' ;
global $image_files ;
if (defined('SECRET'))
{
	$secret = SECRET ;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>twitter アイコン変更ツール</title>
  <script language="javascript">
  <!--
  function confirmChange()
  {
	  return confirm('twitter アイコンを変更します。') ;
  }
  //-->
  </script>
</head>
<body>
<h1>twitter アイコン変更ツール</h1>
<table>
  <?php foreach ($image_files as $idx => $img) { ?>
    <tr>
      <td><a href="change.php?image=<?php echo $idx ?>&key=<?php echo $secret ?>" onclick="return confirmChange()"><?php echo $img ?></a></td>
      <td><img src="<?= $img ?>"></td>
    </tr>
  <?php } ?>
</table>

</body>
</html>
