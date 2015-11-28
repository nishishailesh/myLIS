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
		<form method=post   action=delete_examination_request.php	>';
	echo '<tr>';
	echo '<td>sample_id</td>';
	echo '<td><input type=text name=sample_id ></td>';	
	echo '</tr>';
	echo '<tr><td colspan=2 align=center><button type=submit name=action value='.$value.'>'.$value.'</button></td></tr>';
	echo '</form></table>';
}



function delete_examination($sample_id,$filename,$disabled)
{
	$link=start_nchsls();
	$sql='select * from examination where sample_id=\''.$sample_id.'\'';
	if(!$result=mysql_query($sql,$link)){echo 'No such Sample';return FALSE;}
	if(mysql_num_rows($result)<1){echo 'No examinations';return FALSE;}
	
	echo '<table border=1 bgcolor=lightgrey CELLPADDING=0 CELLSPACING=0>';
	echo '<form method=post action=\''.$filename.'\'>';
	
	echo '<tr><td>';
	echo '<input type=hidden name=sample_id value=\''.$sample_id.'\'>';	
	echo '<input type=hidden name=action 	value=delete_examination>';		
	echo '</td></tr>';
	echo '<tr><th colspan=18 align=left>Delete Examination Form</th></tr>';
	
	$first='true';
	while($ar=mysql_fetch_assoc($result))
	{
		echo '<tr>';
		if($first=='true')
		{
			foreach ($ar as $key => $value)   //for every row in scope
			{
				echo '<th nowrap>'.$key.'</th>';
			}
			$first='false';			
			echo '</tr>';
		}
		foreach ($ar as $key => $value)   //for every row in scope
		{
				if($key=='id')
				{
					echo '<td nowrap><input type=submit '.$disabled.' name=id \' value=\''.$value.'\'></td>';
				}
				else
				{
					echo '<td nowrap>'.$value.'</td>';
				}
		}
		echo '</tr>';		
	}
	echo '</form></table>';
}	



if(!login_varify())
{
exit();
}

main_menu();

if(!isset($_POST['sample_id']) || !isset($_POST['action']))
{
read_sample_id('delete_examination');
}

elseif(isset($_POST['sample_id']) && isset($_POST['action']))
{
	if($_POST['action']=='delete_examination')
	{
		if(isset($_POST['id']))
		{
			$link=start_nchsls();
			$sql='delete from examination where sample_id=\''.$_POST['sample_id'].'\' and id=\''.$_POST['id'].'\'';
			//echo $sql;
			$result=mysql_query($sql,$link);
			//echo 'deleted '.mysql_affected_rows($link).' examination<br>';	
		}
		edit_sample($_POST['sample_id'],'edit_request.php	','disabled','no');
		delete_examination($_POST['sample_id'],'delete_examination_request.php	','');
	}	
	
}




?>
