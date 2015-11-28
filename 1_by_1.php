<?php
session_start();

echo '<html>';
echo '<head>';

echo '</head>';
echo '<body>';


//echo '<pre>';
//print_r($GLOBALS);
//echo '</pre>';

include 'common.php';


if(!login_varify())
{
exit();
}
main_menu();

if(isset($_POST['submit']) && isset($_POST['sample_id']))
{
		if($_POST['submit']=='next')
		{
			$sample_id=$_POST['sample_id']+1;
		}
		if($_POST['submit']=='prev')
		{
			$sample_id=$_POST['sample_id']-1;
		}
	
		if($_POST['submit']=='OK')
		{
			$sample_id=$_POST['sample_id'];
		}
		
}

else
{
	$sample_id=1;
}
	

echo '<form method=post name=\'1_by_1.php\'>';
echo '<td><input type=hidden name=sample_id value=\''.$sample_id.'\'></td>';	
echo '<tr><td><input type=submit value=prev name=submit ></td></tr>';
echo '<tr><td><input type=submit value=next name=submit ></td></tr>';
echo '</form>';



echo '<form method=post name=\'1_by_1.php\'>';
echo '<td><input type=text name=sample_id value=\''.$sample_id.'\'></td>';	
echo '<tr><td><input type=submit value=OK name=submit ></td></tr>';
echo '</form>';
print_sample_worklist($sample_id);

?>
