<?php
session_start();

echo '<pre>';
print_r($GLOBALS);
echo '</pre>';

include 'common.php';

if(!login_varify())
{
exit();
}

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
	
}

main_menu();
if(!isset($_POST['import']))
{
echo '<table><tr>';
echo '<td colspan=10><h1>Import QC data from file</h1></td></tr>';
echo '<form method=post enctype="multipart/form-data">';
echo '<tr><td>FileName:</td><td><input type=file name=import_file ></td></tr>';
echo '</tr><td>Equipment:</td><td><input type=submit name=import value=Erba_XL_640><td></tr>';
echo '</form>';
echo '</table>';
}
else
{
$link=start_nchsls();
$uploaddir = '/';
$uploadfile = $uploaddir . basename($_FILES['import_file']['name']);
echo 'uploading from:'.$uploadfile.'<br>';

	echo '<pre>';
		if($handle = fopen($_FILES['import_file']['tmp_name'], "r"))
		{
			while (($data = fgetcsv($handle, 0, chr(9))) !== FALSE) 
			{
				print_r($data);
			}
		}
	echo '</pre>';
}
?>
