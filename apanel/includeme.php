<?php		
if(!isset($_GET['page'])){
	require_once("dashboardd.php");
} else {
	if(file_exists($_GET['page']."/".$_GET['page'].".php"))	{		
			require_once($_GET['page']."/".$_GET['page'].".php");
	}else {
		require_once("dashboardd.php");
	}
}
?>