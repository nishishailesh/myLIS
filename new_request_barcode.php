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

//change ip to server of hospital database
$my_ip='10.207.3.241';

function read_barcode($value)
{
	echo '<table  border=1>	
		<form method=post  action=new_request_barcode.php>';
	echo '<tr>';
	echo '<td>Barcode</td>';
	echo '<td><input type=text name=barcode></td>';	
	echo '</tr>';
	echo '<tr><td colspan=2 align=center><button type=submit name=action value='.$value.'>'.$value.'</button></td></tr>';
	echo '</form></table>';
}

/*
CREATE TABLE `sample` (
 `sample_id` bigint(12) NOT NULL AUTO_INCREMENT,
 `patient_id` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
 `patient_name` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
 `clinician` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
 `unit` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
 `location` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
 `sample_type` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
 `preservative` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
 `sample_details` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
 `urgent` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
 `status` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
 `sex_age` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
 `sample_receipt_time` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
 `sample_collection_time` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
 `diagnosis` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
 PRIMARY KEY (`sample_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9223372036854775807 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
/*
year is 14
MRD is 12345678
name is Chagan M Patel
dor is 2014-04-17
age_year is 34
age_month is 
age_day is 
dob is 
id_type is 
id is 
contact is 
department is DENT
unit is 5
location is G0MICU
visit is 1
dov is 2014-04-17
*/

function get_clinician_from_hmis_code($code)
{
	$link=start_nchsls();
	$sql='select * from clinician';
	if(!$result=mysql_query($sql,$link)){return FALSE;}
		while($result_array=mysql_fetch_assoc($result))
		{
			if($code==$result_array['code'])
			{
				return $result_array['clinician'];
			}
		}
		return FALSE;
}

function edit_sample_barcode($sample_array,$filename)
{
	$link=start_nchsls();
	$counter=1;

	echo '<form method=post action=\''.$filename.'\'>';
	echo '	<table  border=1 bgcolor=lightyellow CELLPADDING=0 CELLSPACING=0>	
			<tr>
			<td>
				<button type=submit name=action value=save_sample>save sample</button>
			</td>
				<th colspan=8 align=left>Sample Entry Form</th></tr>';
/*
year is 14
MRD is 12345678
name is Chagan M Patel
dor is 2014-04-17
age_year is 34
age_month is 
age_day is 
dob is 
id_type is 
id is 
contact is 
department is DENT
unit is 5
location is G0MICU
visit is 1
dov is 2014-04-17

<td></td><td><input type=text name= value=\''.$sample_array[''].'\'></td>
*/
	echo '	<tr>
				<td>sample_id</td><td><input type=text name=sample_id></td>
				<td>patient_id</td><td><input type=text name=patient_id value=\''.$sample_array['MRD'].'\'></td>
				<td>patient_name</td><td><input type=text name=patient_name value=\''.$sample_array['name'].'\'></td>
				<td>clinician</td><td>';
				mk_select_from_table('clinician','',get_clinician_from_hmis_code($sample_array['department']));
	echo		'</td>
			</tr>';
	
	echo '	<tr>
				<td>unit</td><td><input type=text 			name=unit 				value=\''.$sample_array['unit'].'\'></td>
				<td>location</td><td><input type=text 		name=location 			value=\''.$sample_array['location'].'\'></td>';
	echo		'</td>
			</tr>';
			

	echo '</form></table>';
	return TRUE;
}



if(!login_varify())
{
	exit();
}

main_menu();

if(!isset($_POST['barcode']) || !isset($_POST['action']))
{
	
	read_barcode('Get Details');
}

else
{
	$url='http://'.$my_ip.'/hospital/scan_barcode_txt_get_method.php?login='.$_SESSION['login'].'&password='.$_SESSION['password'].'&barcode='.$_POST['barcode'].'';
	$urloutput=file_get_contents($url);
	//echo '<pre>'.$urloutput;
	$array=explode("|",$urloutput);


$final_array=array();

	foreach ($array as $value)
	{
		$subarray=explode("^",$value);
		//echo $subarray[0].' is '. $subarray[1].'<br>';
		$final_array[$subarray[0]]=$subarray[1];
	}

//print_r($final_array);
edit_sample_barcode($final_array,'new_request_barcode.php');

}
	





?>
