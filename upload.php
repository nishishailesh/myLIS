<?php
session_start();
include "common.php";

/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/

if(!login_varify())
{
exit();
}


if($_SESSION['login']!='root' && substr($_SESSION['login'],0,3)!='Dr.')
{
echo 'This user is not authorized to use this menu'; 
exit();
}

main_menu();
$grand_array=array();

function list_dir($dir,$exclude_dir)
{
	global $grand_array;
	$array_dir=scandir($dir);
	foreach($array_dir as $key=>$value)
		{
	
			if(is_dir($dir.'/'.$value) && $value!='.' && $value!='..' && !in_array($dir.'/'.$value,$exclude_dir))
			{
				//echo $dir.'/'.$value.'<br>';
				$grand_array[]=$dir.'/'.$value;
				list_dir($dir.'/'.$value,$exclude_dir);				
			}		
		}
}

$base_dir=$_SERVER['DOCUMENT_ROOT'].'/NCHSLS';
$exclude_dir=array($base_dir.'/admin');
list_dir($base_dir,$exclude_dir);


echo '<html>';
echo '<body>';
echo '<form method=post enctype=\'multipart/form-data\'>';
echo '<table>';
echo '<tr>';

	echo '<td bgcolor=lightblue>Give File name to upload</td>';
	echo '<td bgcolor=lightblue><input type=file name=file></td>';
echo '</tr>';
echo '<tr>';
	echo '<td bgcolor=lightgreen>Select location for storage at server</td>';
	echo '<td  bgcolor=lightgreen>';
		mk_select_from_array_return_value('storage_path', $grand_array,'','');
	echo '</td>';
echo '</tr>';
echo '<tr>';
	echo '<td><input type="submit" name="submit" value="Submit"></td>';
echo '<tr></form>';

echo '</body></html> ';


if(isset($_FILES['file']))
{
		if ($_FILES["file"]["error"] > 0)
		{
			echo "Error, Return Code: " . $_FILES["file"]["error"] . "<br />";
		}
		else
		{
			echo "Trying to upload file: " . $_FILES["file"]["name"] . "<br />";
			if (file_exists($_POST['storage_path'].'/'.$_FILES["file"]["name"]))
			{
				echo $_FILES["file"]["name"] . " already exists.<h5>overwriting...</h5>";
			}
			if(!move_uploaded_file($_FILES["file"]["tmp_name"],$_POST['storage_path'].'/' .$_FILES["file"]["name"]))
			{
				echo '<h1>Failed upload.........</h1>';
			}
			else
			{
				echo "<h2>Stored as: " .$_POST['storage_path'].'/' .$_FILES["file"]["name"].'</h2>';
			}
		}
}
?>
