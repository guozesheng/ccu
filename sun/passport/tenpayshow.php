<?php
require('../rte.php');
?>
<html>
<title>tenpay.result</title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $rtc['language']?>" />
<body>
<?
  import_request_variables("gpc", "frm_");
	echo $frm_msg;
?>
</body>
</html>