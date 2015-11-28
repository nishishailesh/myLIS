<?php
session_start();


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



/*
123456789012
12345		=ward
123456		=OPD
QYYMMDDHH	=QC
YYMM123456	=Old samples
YYYYMM123456=universal samples id
*/


/*
Array
(
    [0] => Sr #
    [1] => Pat ID
    [2] => Sample ID
    [3] => Patient Name
    [4] => Test
    [5] => Result
    [6] => Unit
    [7] => Flag
    [8] => Result Date
    [9] => Curve #
    [10] => Mean
    [11] => SD
    [12] => 
)
Array
(
    [0] => 1
    [1] => 
    [2] => 511070609
    [3] => 
    [4] => Na
    [5] => 151.00
    [6] => mmol/l
    [7] => 
    [8] => 07/06/2011 09:41:27
    [9] => 0
    [10] => -
    [11] => -
    [12] => 
    [13] => 
)
*/

function get_field_detail($equipment_name)
{
	$link=start_nchsls();
	$sql='select * from qc_equipment';
	if(!$result=mysql_query($sql,$link)){return FALSE;}
	while($array=mysql_fetch_assoc($result))
	{
		if($array['equipment_name']==$equipment_name)
		{
			return $array;
		}
	}
}

//betadine ointment
//omez 20-mg



main_menu();
if(!isset($_POST['equipment_name']))
{
echo '<table><tr>';
echo '<td colspan=10><h1>Import QC data from file</h1></td></tr>';
echo '<form method=post enctype="multipart/form-data">';
echo '<tr><td>FileName:</td><td><input type=file name=import_file ></td></tr>';
echo '</tr><td>Equipment:</td><td><input type=submit name=equipment_name value=Erba_XL_640><input type=submit name=equipment_name value=Miura_300><td></tr>';
echo '</form>';
echo '</table>';
}
else
{
$file_info=get_field_detail($_POST['equipment_name']);
$delimiter=$file_info['delimiter'];
$sample_id_field=$file_info['sample_id_field'];
$equipment_name=$file_info['equipment_name'];
$repeat_id_field=$file_info['repeat_id_field'];
$date_field=$file_info['date_field'];
$code_field=$file_info['code_field'];
$result_field=$file_info['result_field'];
$date_str=$file_info['date_str'];

$link=start_nchsls();
$uploaddir = '/';
$uploadfile = $uploaddir . basename($_FILES['import_file']['name']);
echo 'uploading from:'.$uploadfile.'<br>';

	echo '<pre>';
		if($handle = fopen($_FILES['import_file']['tmp_name'], "r"))
		{
			while (($data = fgetcsv($handle, 0,$delimiter)) !== FALSE) 
			{
				print_r($data);
				if(isset($data[$sample_id_field]) && isset($data[$code_field]) && isset($data[$result_field]) 
						&& isset($data[$repeat_id_field]) && isset($data[$date_field]))
				{
					if(is_str_interger($data[$sample_id_field]) && is_str_num($data[$result_field]) && $data[$result_field]>0)
					{
						if 	
						(
							(($data[$sample_id_field]>500000000 && $data[$sample_id_field]<599999999))
							||
							(($data[$sample_id_field]>800000000 && $data[$sample_id_field]<899999999))
						)
						{
							$qc_value=get_target($data[$sample_id_field],$equipment_name,$data[$code_field]);
							$sql='insert into qc (equipment_name,sample_id,repeat_id,time_data,code,result,target,sd,lot,comment) values (
							\''.$equipment_name.'\' ,									
							\''.$data[$sample_id_field].'\' ,				
							\''.$data[$repeat_id_field].'\' ,				
							str_to_date(\''.$data[$date_field].'\',\''.$date_str.'\'),
							\''.$data[$code_field].'\' ,
							\''.$data[$result_field].'\' ,						
							\''.$qc_value['target'].'\' , 
							\''.$qc_value['sd'].'\' ,
							\''.$qc_value['lot'].'\' ,
							\'1\')';
							echo '<br>'.$sql;
							if(!$result=mysql_query($sql,$link))
							{
								echo 'insert error:'.mysql_error();
								$sql_update='update qc set 
										result=\''.$data[$result_field].'\',
										target=\''.$qc_value['target'].'\' ,
										sd=\''.$qc_value['sd'].'\',	
										lot=\''.$qc_value['lot'].'\'																			
										where	
										equipment_name=\''.$equipment_name.'\' and 									
										sample_id=\''.$data[$sample_id_field].'\' and			
										repeat_id=\''.$data[$repeat_id_field].'\' and			
										time_data=str_to_date(\''.$data[$date_field].'\',\''.$date_str.'\') and
										code=\''.$data[$code_field].'\'';
								echo $sql_update;
								if(!$result_update=mysql_query($sql_update,$link)){echo 'update error:'.mysql_error();}
								else{echo 'update:['.mysql_affected_rows($link).']['.$data[$sample_id_field].']['.$data[$code_field].']['.$data[$result_field].']<br>';}
							}
							else
							{
								echo 'insert:['.mysql_affected_rows($link).']['.$data[$sample_id_field].']['.$data[$code_field].']['.$data[$result_field].']<br>';
							}
						}
						else
						{
							echo 'Not QC Sample<br>';
						}
					}
					else
					{
						echo 'the line format is improper<br>';
					}
				}
			}
		}
	echo '</pre>';
	
	
}


?>
