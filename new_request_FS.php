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

function read_sample_id_FS($value)
{
	echo '<table  border=1>	
		<form method=post   action=new_request_FS.php>';
	echo '<tr>';
	echo '<td>from_sample_id</td>';
	echo '<td><input type=text name=from_sample_id ></td>';	
		echo '</tr>';
		echo '<tr>';
	echo '<td>to_sample_id</td>';
	echo '<td><input type=text name=sample_id ></td>';	
	echo '</tr>';
	echo '<tr><td colspan=2 align=center><button type=submit name=action value='.$value.'>new_sample Copy [F-S]</-></button></td></tr>';
	echo '</form></table>';
}	

if(!login_varify())
{
exit();
}

main_menu();

if(!isset($_POST['sample_id']) || !isset($_POST['action']))
{
read_sample_id_FS('new_sample');
}

elseif(isset($_POST['sample_id']) && isset($_POST['action']))
{
	if($_POST['action']=='new_sample')
	{
		$link=start_nchsls();
		if(! mysql_query('insert into sample(sample_id,sample_receipt_time,patient_id) values (\''.$_POST['sample_id'].'\',\''.strftime("%Y-%m-%d %H:%M:%S").'\',\'SUR/12/\')',$link))
		{
			echo mysql_error();
		}
		else
		{
			save_sample_FS($_POST['from_sample_id'],$_POST['sample_id']);
			edit_sample($_POST['sample_id'],'new_request_FS.php	','','');
		}
		
	}	
	elseif($_POST['action']=='save_sample')
	{
		if(!save_sample($_POST))
		{
			echo 'no sample saved<br>';
		}
		else
		{
			edit_sample($_POST['sample_id'],'new_request_FS.php	','disabled','no');
			select_profile($_POST['sample_id'],'new_request_FS.php	');
			select_examination($_POST['sample_id'],'new_request_FS.php	','');
		}
	}
	elseif($_POST['action']=='profile')
	{
		insert_profile($_POST['sample_id'],$_POST['profile']);
		
		edit_sample($_POST['sample_id'],'new_request_FS.php	','disabled','no');
		edit_examination($_POST['sample_id'],'new_request_FS.php	','');
		select_profile($_POST['sample_id'],'new_request_FS.php	');
		select_examination($_POST['sample_id'],'new_request_FS.php	','');
	}
	
	elseif($_POST['action']=='save_examination')
	{
		save_examination($_POST);
		
		edit_sample($_POST['sample_id'],'new_request_FS.php	','disabled','no');
		edit_examination($_POST['sample_id'],'new_request_FS.php	','disabled');
		select_profile($_POST['sample_id'],'new_request_FS.php	');
		select_examination($_POST['sample_id'],'new_request_FS.php	','');
	}
	
	elseif($_POST['action']=='select_examination')
	{
		insert_single_examination($_POST['sample_id'],$_POST['id']);
		
		edit_sample($_POST['sample_id'],'new_request_FS.php	','disabled','no');
		edit_examination($_POST['sample_id'],'new_request_FS.php	','');
		select_profile($_POST['sample_id'],'new_request_FS.php	');
		select_examination($_POST['sample_id'],'new_request_FS.php	','');
	}
	
}




?>
