<?php
session_start();

/*
echo '<html>';
echo '<head>';
echo '</head>';
echo '<body>';
*/
/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/

include 'common.php';


if(!login_varify())
{
exit();
}

function search_form($list_of_sample)
{
	echo '<form method=post>';
	echo '<table border=1 style="background:lightblue;">';
	echo '<tr>';
		echo '<th>';
		echo 'Enter Sample ID to be printed'.'<br>'.'one in each line and submit';
		echo '</th>';
	echo '</tr>';
	echo '<tr>';	
		echo '<td>';
			echo '<input autofocus type=number name=sample_id>';
			echo '<textarea hidden readonly name=list_of_sample>'.$list_of_sample.'</textarea>';
		echo '</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td>';
		echo '<button type=submit name=submit value=add style="font:200% bold black;">Add/Remove Sample</button></td><td>';
		echo 'Remove<input type=checkbox name=remove value=yes style="font:200% bold black;">';
		echo '</td>';
	echo '</tr>';
	echo '</form>';
	
	echo '</table>';
	echo '<table  style="background:lightpink;">';
	echo '<form method=post target=_blank>';
	echo '<tr>';	
		echo '<td>';
				echo '<textarea readonly rows=15  name=list_of_sample>'.$list_of_sample.'</textarea>';
		echo '</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td>';
		mk_select_from_table('authorized_signatory','','');
		echo '</td></tr><tr><td>';
		echo '<button type=submit name=submit value=print style="font:200% bold black;">Print</button>';
		echo '</td>';
	echo '</tr>';
	
	echo '</table>';
	echo '</form>';
}

if(isset($_POST['list_of_sample'])){$list_of_sample=$_POST['list_of_sample'];}
else{$list_of_sample='';}

if(isset($_POST['submit']))
{
	if($_POST['submit']=='add' && !isset($_POST['remove']))
	{
		main_menu();
		$old=explode('|',$list_of_sample);
		if(!in_array($_POST['sample_id'],$old))
		{
			$list_of_sample=$list_of_sample.'|'.$_POST['sample_id'];
		}
		
		search_form($list_of_sample);
	}
	if($_POST['submit']=='add' && isset($_POST['remove']))
	{
		main_menu();
		$old=explode('|',$list_of_sample);
		if(in_array($_POST['sample_id'],$old))
		{
			$key_array=array_keys($old,$_POST['sample_id']);
			unset($old[$key_array[0]]);
			$list_of_sample='';
			foreach($old as $value)
			{
				if(strlen($value)>0)
				{
					$list_of_sample=$list_of_sample.'|'.$value;
				}
			}
		}
		search_form($list_of_sample);
	}	
	
	
	else if($_POST['submit']=='print')
	{
		if(isset($_POST['authorized_signatory']))
		{
			if(strlen($_POST['authorized_signatory'])==0)
			{
				echo '<h4>No authorized Signatory given</h4>';
				//main_menu();
				exit(0);
			}
		}
		
		$temp_list_of_sample=explode("|",$list_of_sample);
		$final_list_of_sample=array();
		foreach($temp_list_of_sample as $value)
		{
			if(is_str_digit($value)!==FALSE)
			{
				if(in_array($value,$final_list_of_sample)===FALSE)
				{
					$final_list_of_sample[]=$value;
				}
			}
		}

		foreach($final_list_of_sample as $value)
		{
			if(get_sample_status($value)!='verified')
			{
				echo $value.' is not verified. PDF report can not be printed<br>';
				exit(0);
			}	
		}
		//print_r($final_list_of_sample);
		print_report_pdf_A4($final_list_of_sample,$_POST['authorized_signatory']);
	}
	
		
	
}
else
{
	main_menu();
	search_form($list_of_sample);
	echo '<h1>No coditions are given for selecting records</h1>';
}


?>
