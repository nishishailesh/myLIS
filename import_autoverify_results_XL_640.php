<?php
session_start();

//echo '<pre>';
//print_r($GLOBALS);
//echo '</pre>';

include 'common.php';

$batch_of_sample=array();

function import_results_XL_640()
{
	global $batch_of_sample;
//07/06/2011 09:41:27 XL
//2011-07-06 12:23:51 ideal
$link=start_nchsls();
echo '<H4>Importing Results from Erba XL-640</H4>';
$counter=0;
$uploaddir = '/';
$uploadfile = $uploaddir . basename($_FILES['import_file']['name']);

		if($handle = fopen($_FILES['import_file']['tmp_name'], "r"))
		{
			while (($data = fgetcsv($handle,0,chr(9))) !== FALSE) 
			{
				if(isset($data[2]) && isset($data[5]) && isset($data[4]))
				{
					if(ctype_digit($data[2]) && is_numeric($data[5]) && $data[5]>0)
					{
						$batch_of_sample[$data[2]]='';
						///////autoverify with no action
						//autoverify($data[2],'','no');						
						////////////////////////////////
						$sql='update examination set result=\''.$data[5].'\' , details=concat(str_to_date(\''.$data[8].'\',\'%m/%d/%Y %H:%i:%S\'),\'|Erba-XL-640\') 
								where sample_id=\''.$data[2].'\' 
								and code=\''.$data[4].'\'  
								and strcmp(substr(result,1,1),\'(\')';
						//echo '<br>'.$sql;
						if(!mysql_query($sql,$link)){echo mysql_error();}
						else
						{
							$affected=mysql_affected_rows($link);
							$counter=$counter+mysql_affected_rows($link);
							if(get_sample_status($data[2])!='verified')	//to prevent already verified sample status change
							{
								//echo '<br><font color=red>['.$affected.']->'.$data[2].'->'.$data[4].'->'.$data[5].'</font>';
								change_sample_status($data[2],'analysed');
							}
						}
					}
					else
					{
						//echo '<br>'.$data[2].':sample_id is not digits or '.$data[5].':result  is not-numeric/0 or less';
					}
				}
    		}
			fclose($handle);
			echo '<h4>Updated data='.$counter.'</h4>';
		}
		else
		{
			echo 'can not fopen';
		}	

}

if(!login_varify())
{
exit();
}
main_menu();

	echo '<H1>Import and autoverify Results from XL-640 file</H1>';
	echo '<form method=post enctype="multipart/form-data">';
	echo 'FileName:<input type=file name=import_file >';
	echo '<br><input type=submit>';
	echo '</form>';

if(isset($_FILES['import_file']))
{
	import_results_XL_640();							//import
	//print_r($batch_of_sample);
	
	foreach($batch_of_sample as $sample_id=>$status)
	{
		autoverify($sample_id,'','no');					//autoverify without action
	}
	
	import_results_XL_640();							//import reflex results

	foreach($batch_of_sample as $sample_id=>$status)
	{
		autoverify($sample_id,'autoverify_action.php','yes');		//final autoverify with action
	}
	
	
}
?>
