<?php
session_start();



//echo '<pre>';
//print_r($GLOBALS);
//echo '</pre>';

include 'common.php';

function import_results_miura()
{
	$link=start_nchsls();
	echo '<H1>Importing Results from Miura-300 file</H1>';
	$counter=0;
	$uploaddir = '/';
	$uploadfile = $uploaddir . basename($_FILES['import_file']['name']);
	if($handle = fopen($_FILES['import_file']['tmp_name'], "r"))
	{
		while (   ($data = fgetcsv($handle, 0, ';')) !== FALSE   )
		{
			if(isset($data[2]) && isset($data[6]) && isset($data[4]))
			{
				if(ctype_digit($data[2]) && is_numeric($data[6]) && $data[6]>0)
				{
					$sql='update examination set result=\''.$data[6].'\' , details=concat(str_to_date(\''.$data[10].'\',\'%Y/%m/%d_%H_%i_%S\'),\'|Miura-300\') 
					where sample_id=\''.$data[2].'\' 
					and code=\''.$data[4].'\'
					and strcmp(substr(result,1,1),\'(\')';
					//echo '<br>'.$sql;
					if(!mysql_query($sql,$link)){echo mysql_error();}
					else
					{
						echo '<br>['.mysql_affected_rows($link).']->'.$data[2].'->'.$data[4].'->'.$data[6];
						$counter=$counter+mysql_affected_rows($link);
						change_sample_status($data[2],'analysed');
					}
				}
				else
				{
					echo '<br>'.$data[2].':sample_id is not digits or '.$data[6].':result is not-numeric/0 or less';
				}	
				//print_r($data);
			}
			}
			fclose($handle);
			echo '<h1>Updated data='.$counter.'</h1>';
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

if(!isset($_FILES['import_file']))
{
	echo '<H1>Import Results from Miura-300 file</H1>';
	echo '<form method=post action=import_results_miura.php enctype="multipart/form-data">';
	echo 'FileName:<input type=file name=import_file >';
	echo '<br><input type=submit>';
	echo '</form>';
}
elseif(isset($_FILES['import_file']))
{
	import_results_miura();
}


?>
