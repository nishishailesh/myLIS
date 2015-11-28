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

function read_sample_id($value)
{
	echo '<table  border=1>	
		<form method=post   action=new_request.php>';
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
read_sample_id('new_sample');
}

elseif(isset($_POST['sample_id']) && isset($_POST['action']))
{
	if($_POST['action']=='new_sample')
	{
		$link=start_nchsls();
		$mrd='SUR/'.strftime('%y').'/';
		if(! mysql_query('insert into sample (sample_id,sample_receipt_time,patient_id,status) values (\''.$_POST['sample_id'].'\',\''.strftime("%Y-%m-%d %H:%M:%S").'\',\''.$mrd.'\',\'entered\')',$link))
		{
			echo mysql_error();
		}
		else
		{
			edit_sample($_POST['sample_id'],'new_request.php','','');
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
			edit_sample($_POST['sample_id'],'new_request.php','disabled','no');
			select_profile($_POST['sample_id'],'new_request.php');
			select_examination($_POST['sample_id'],'new_request.php','');
		}
	}
	elseif($_POST['action']=='profile')
	{
		insert_profile($_POST['sample_id'],$_POST['profile']);
		
		edit_sample($_POST['sample_id'],'new_request.php','disabled','no');
		edit_examination($_POST['sample_id'],'new_request.php','');
		select_profile($_POST['sample_id'],'new_request.php');
		select_examination($_POST['sample_id'],'new_request.php','');
	}
	
	elseif($_POST['action']=='save_examination')
	{
		save_examination($_POST);
		
		edit_sample($_POST['sample_id'],'new_request.php','disabled','no');
		edit_examination($_POST['sample_id'],'new_request.php','disabled');
		
		echo '<form method=post target=_blank action=print_sample_barcode.php>';
			echo '<button  style="background:lightpink;" type=submit name=list_of_samples value=\''.$_POST['sample_id'].'\''.'>Barcode</button>';
		echo '</form>';	
		
		select_profile($_POST['sample_id'],'new_request.php');
		select_examination($_POST['sample_id'],'new_request.php','');
	}
	
	elseif($_POST['action']=='select_examination')
	{
		insert_single_examination($_POST['sample_id'],$_POST['id']);
		
		edit_sample($_POST['sample_id'],'new_request.php','disabled','no');
		edit_examination($_POST['sample_id'],'new_request.php','');
		select_profile($_POST['sample_id'],'new_request.php');
		select_examination($_POST['sample_id'],'new_request.php','');
	}
	
}




?>
