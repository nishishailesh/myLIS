<?php
session_start();

echo '<html>';
echo '<head>';
echo '</head>';
echo '<body>';

/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/

include 'common.php';

function read_sample_id($value)
{
	echo '<table  border=1>	
		<form method=post   action=delete_sample_request.php	>';
	echo '<tr>';
	echo '<td>sample_id</td>';
	echo '<td><input type=text name=sample_id ></td>';	
	echo '</tr>';
	echo '<tr><td colspan=2 align=center><button type=submit name=action value='.$value.'>'.$value.'</button></td></tr>';
	echo '</form></table>';
}

if(!login_varify())
{
exit();
}

main_menu();

if(!isset($_POST['sample_id']) || !isset($_POST['action']))
{
read_sample_id('delete_sample');
}

elseif(isset($_POST['sample_id']) && isset($_POST['action']))
{
	if($_POST['action']=='delete_sample')
	{
		echo '
		<form method=post   action=delete_sample_request.php	>
		<button type=submit name=action value=confirmed_delete>Delete Sample</button>
		<input type=hidden name=sample_id value=\''.$_POST['sample_id'].'\'
		</form>';
		edit_sample($_POST['sample_id'],'edit_request.php	','disabled','no');
		edit_examination($_POST['sample_id'],'edit_request.php	','disabled');
	}

	if($_POST['action']=='confirmed_delete')
	{
		$link=start_nchsls();
		$sql='delete from sample where sample_id=\''.$_POST['sample_id'].'\'';
		//echo $sql;
		$result=mysql_query($sql,$link);
		echo 'deleted '.mysql_affected_rows($link).' sample<br>';	
		edit_sample($_POST['sample_id'],'edit_request.php	','disabled','no');
		edit_examination($_POST['sample_id'],'edit_request.php	','disabled');
	}		
}




?>
