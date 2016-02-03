<?php 

require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');
// calibration, EQC,Kit,checklist

$reflex='yes';
					//if 'no'=>it will bypass reflex testing
date_default_timezone_set('Asia/Kolkata');

/////////////////////////////////
function login_varify()
{
return mysql_connect('127.0.0.1',$_SESSION['login'],$_SESSION['password']);
}

/////////////////////////////////
function select_nchsls($link)
{
	return mysql_select_db('alllab',$link);
}

///////////////////////////////////
function start_nchsls()
{
	if(!$link=login_varify())
	{
		exit();
	}


	if(!select_nchsls($link))
	{
		exit();
	}
return $link;
}

function my_mysql_query($sql,$link)
{
	if(strpos($sql,'update')>=0 || strpos($sql,'delete')>=0||strpos($sql,'insert'>=0))
	{
		$log='insert into log values(\'\',sysdate(),current_user(),\''.mysql_real_escape_string($sql,$link).'\')';
		//echo $log;
		mysql_query($log,$link);
	}
	return mysql_query($sql,$link);
}

/*

select resident in charge
grant previlages and create users

TAT- sample and exam wise OK
EQA-data and comment      
Monthly summary
calibration data
kit management-batch management
complain,feedback
repeat-batch to know pending, not-entered receipt, not-run sample
printed-dispatched

 */
function main_menu()
{
	
echo '<table border=3 style="border-collapse:collapse;">';
echo '<tr>';
echo 	'<td>';
echo		'<a href=new_request_barcode_general_option2.php>New Request</a>';
echo 	'</td>';
echo 	'<td>';
echo		'<a href=request_report.php>Request Report</a>';
echo 	'</td>';
echo '</tr>';
echo '</table>';
/*
	echo '<link rel="stylesheet" type="text/css" href="horizontal.css">
	<script type="text/javascript" href=menu.js></script>
	<table border=0><tr><td>
	<div id="navbar">
	<ul>
	<li><a href="#">New</a>
		<ul>
			<li><a href=new_request.php>New</a>
			<li><a href=new_request_barcode_general_option2.php>New General</a>
			<li><a href=request_report.php>Request Report</a>
		</ul>
	<li><a href="#">Edit</a>
		<ul>
			<li><a href=edit_request.php>Edit</a>
			<li><a href=delete_sample_request.php>Delete Sample</a>
			<li><a href=delete_examination_request.php>Delete Examination</a>
			<li><a href=attachment.php>Manage Attachment</a>
		</ul>
	<li><a href="#">Worklist</a>
		<ul>
			<li><a href=1_by_1.php>1-by-1</a>
			<li><a href=send_sample_to_XL_640.php>Send to XL-640</a>
			<li><a href=manage_barcoded_batch_of_XL_640.php>Manage Barcoded Batch of XL-640</a>
			<li><a href=send_sample_to_miura.php>Send to Miura-300</a>
			<li><a href=examination_wise_worklist.php>Examination wise worklist</a>
			<li><a href=sample_wise_worklist.php>Sample wise worklist</a>
		</ul>
	<li><a href="#">Result</a>
		<ul>
			<li><a href=import_results_XL_640.php>Import from Erba XL-640</a>';

//comment below this when autoverification needs to be turned off	
//<li><a href=print_report_pdf.php>Print-PDF-A5</a>		
	echo '	<li><a href=import_autoverify_results_XL_640.php>Import-cum-autoverify Erba XL-640</a>
			<li><a href=autoverify.php>Autoverify 1-by-1</a>
			<li><a href=search_autoverify.php>Search-cum-Autoverify</a>';
			
	echo '	<li><a href=critical_report.php>Critical Report</a>
			<li><a href=examination_wise_results.php>Examination wise Entry</a>	
			<li><a href=import_results_miura.php>Import from Miura-300</a>
		</ul>
	<li><a href="#">Report</a>
		<ul>
			<li><a href=print_report.php>Print</a>
			<li><a href=print_report_pdf_A4.php>Print-PDF-A4</a>
			<li><a href=print_report_pdf_A4_discontinuous.php>Print(barcode)</a>
		</ul>
	<li><a href="#">Quality</a>
		<ul>
			<li><a href=import_qc.php>Import QC</a>
			<li><a href=repeat_check.php>Repeat Check</a>
			<li><a href=lj_chart.php>LJ chart/ insert/comment QC</a>
			<li><a href=refrigerator_temperature.php>Refrigerator Temperatures</a>
			<li><a href=environmental_parameter.php>Environmental Parameter</a>
			<li><a href=equipment_log.php>Equipment Log</a>
			<li><a href=equipment_record.php>Equipment Record</a>
			<li><a href=nc.php>Nonconformity Records</a>
			<li><a href=consumable.php>Inventory Records</a>
			<li><a href=reagent.php>Reagents</a>
			<li><a href=print_label_48.php>Print 48 lables on A4</a></li>
			<li><a href=print_lables.php>Print barcode lables</a></li>
			<li><a href=calibration.php>Calibrations</a>
			<li><a href=get_monthly_qc_data.php>Monthly QC Records</a>
		</ul>
	<li><a href="#">Documents/Records</a>
			<ul>
				<li><a target=_blank href="NCHSLS/c/001 Biochemistry/Documents/Internal Documents/BI 0015 Standard Oprating Procedures Manual/SOP">SOP</a>		
				<li><a target=_blank href=view_scope.php>Scope</a></li>
				<li><a href=view_data.php>View (from database)</a>
				<li><a href=NCHSLS target="_blank">View(from file system)</a>
				<li><a href=upload.php>Upload</a>
				<li><a href="monthly_sample_id_change.php">update sample_id</a>
				<li><a href=letter.php>Letter</a>
				<li><a href="NCHSLS/c/001%20Biochemistry/Documents/Internal%20Documents/BI%200026%20Physical%20copy%20of%20online%20LIS%20user-manual/BI%200025%20Physical%20copy%20of%20online%20LIS%20user-manual.pdf">User Manual</a>
			</ul>
	
	<li><a href="schedule.php">Reminders('.count_cron().')</a>

	<li><a href="#">'.$_SESSION['login'].'</a>
			<ul>
				<li><a href="logout.php">Logout</a>
				<li><a href="change_password.php">Change Password</a>
				<li><a href="mlamp.php" target=_blank>About</a>	
				<li><a href="suggestion.php" target=_blank>Suggestion</a>
			</ul>
	</ul></div>

	</td></tr></table>';
*/

}

function mk_select_from_table($field,$disabled,$default)
{
	$link=start_nchsls();
	//$sql='select * from '.$field;
	$sql='select `'.$field.'` from '.$field;
	if(!$result=mysql_query($sql,$link)){return FALSE;}
	
		echo '<select  '.$disabled.' name='.$field.'>';
		while($result_array=mysql_fetch_assoc($result))
		{
		if($result_array[$field]==$default)
		{
			echo '<option selected  > '.$result_array[$field].' </option>';
		}
		else
			{
				echo '<option  > '.$result_array[$field].' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}

function mk_select_from_sql($sql,$name,$disabled,$default)
{
	$link=start_nchsls();
	if(!$result=mysql_query($sql,$link)){return FALSE;}
	
		echo '<select  '.$disabled.' name='.$name.'>';
		while($result_array=mysql_fetch_assoc($result))
		{
		if($result_array[$name]==$default)
		{
			echo '<option selected  > '.$result_array[$name].' </option>';
		}
		else
			{
				echo '<option  > '.$result_array[$name].' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}


function mk_select_from_array_return_key($name, $select_array,$disabled,$default)
{
		echo '<select  '.$disabled.' name='.$name.'>';
		foreach($select_array as $key=>$value)
		{
		if($value==$default)
		{
			echo '<option selected value=\''.$key.'\' > '.$value.' </option>';
		}
		else
			{
				echo '<option  value=\''.$key.'\' > '.$value.' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}

function mk_select_from_array_return_value($name, $select_array,$disabled,$default)
{
		echo '<select  '.$disabled.' name='.$name.'>';
		foreach($select_array as $key=>$value)
		{
		if($value==$default)
		{
			echo '<option selected value=\''.$value.'\' > '.$value.' </option>';
		}
		else
			{
				echo '<option  value=\''.$value.'\' > '.$value.' </option>';
			}
		}
		echo '</select>';	
		return TRUE;
}

function edit_sample($sample_id,$filename,$disabled,$type_preservative_section_change)
{
	$link=start_nchsls();
	$counter=1;
	$sql_sample_data='select * from sample where sample_id='.$sample_id;

	if(mysql_num_rows($result_sample_data=mysql_query($sql_sample_data,$link))!=1){echo 'No such Sample';return FALSE;}
	$sample_array=mysql_fetch_assoc($result_sample_data);

			
	echo '<form method=post action=\''.$filename.'\'>';
	echo '	<table  border=1 bgcolor=lightyellow CELLPADDING=0 CELLSPACING=0>	
			<tr>
			<td>
				<button type=submit name=action '.$disabled.' value=save_sample>save sample</button>
			</td>
				<th colspan=8 align=left>Sample Entry Form</th></tr>';
	foreach($sample_array as $key=>$value)
	{
		if($counter%4==1){echo '<tr>';}
		if($key=='sample_id')
		{
			echo '<td>'.$key.'</td><td><input type=text  readonly value=\''.$value.'\' name='.$key.'></td>';
		}
		
		elseif( ($key=='sample_type' || $key=='preservative' || $key=='section') && $type_preservative_section_change=='no')
		{
			echo '<td>'.$key.'</td><td><input type=text  readonly value=\''.$value.'\' name='.$key.'></td>';
		}
		
		else
		{
			echo '<td>'.$key.'</td><td>';
			if(!mk_select_from_table($key,$disabled,$value))
			{
				echo '<input type=text  '.$disabled.' value=\''.$value.'\' name='.$key.'>';
			}
			echo '</td>';
		}
		if($counter%4==0){echo '<tr>';}
		$counter++;
	}
	echo '</form></table>';
	return TRUE;
}


function save_sample($post_array)
{
	$link=start_nchsls();
	$sql='update sample set ';
		foreach ($post_array as $key => $value)
		{
			if($key!='action')
			{		
			$sql=$sql.' '.$key.'=\''.$value.'\' , ';
			}
		}

	$sql=substr($sql,0,-2);
	$sql=$sql.' where sample_id= \''.$post_array['sample_id'].'\'';
	//echo $sql;

	if(!mysql_query($sql,$link)){echo mysql_error(); return FALSE;}
	return TRUE;
}

function change_sample_status($sample_id,$str)
{
	$link=start_nchsls();
	$sql='update sample set status=\''.$str.'\' where sample_id=\''.$sample_id.'\'';
	if(!mysql_query($sql,$link)){echo mysql_error(); return FALSE;}
	return TRUE;
}


function sample_printed($sample_id_array)
{
	$link=start_nchsls();
	foreach($sample_id_array as $sample_id)
	{
		$sql='update sample set details=\'printed\' where sample_id=\''.$sample_id.'\'';
		if(!mysql_query($sql,$link)){echo mysql_error();}
	}
}
function save_sample_FS($copy_sample_id,$paste_sample_id)
{
$link=start_nchsls();

$csql='select * from sample where sample_id=\''.$copy_sample_id.'\'';
$cresult=mysql_query($csql,$link);
if(mysql_num_rows($cresult)!=1)
{
	echo 'from_sample_id do not exist<br>';
	return;
}
$ar=mysql_fetch_assoc($cresult);

$sql='update sample set ';

	foreach ($ar as $key => $value)
	{
		if($key=='sample_id')
		{
			$sql=$sql.' '.$key.'=\''.$paste_sample_id.'\' , ';
		}
		else if($key=='preservative' && $value=='None')
		{
			$sql=$sql.' '.$key.'=\''.'Fluoride'.'\' , ';
		}
		else if($key=='preservative' && $value=='Fluoride')
		{
			$sql=$sql.' '.$key.'=\''.'None'.'\' , ';
		}
		else
		{
			$sql=$sql.' '.$key.'=\''.$value.'\' , ';	
		}
	}

$sql=substr($sql,0,-2);
$sql=$sql.' where sample_id= \''.$paste_sample_id.'\'';
//echo $sql;

if(!mysql_query($sql,$link)){echo mysql_error();}
}


function select_profile($sample_id,$filename)
{
	$link=start_nchsls();
	$sql_sample_data='select sample_type,preservative from sample where sample_id='.$sample_id;
	if(mysql_num_rows($result_sample_data=mysql_query($sql_sample_data,$link))!=1){echo 'No such Sample';return FALSE;}
	$sample_array=mysql_fetch_assoc($result_sample_data);

	$sql='select * from profile where (sample_type=\''.$sample_array['sample_type'].'\' and preservative=\''.$sample_array['preservative'].'\') or profile like \'Z_%\' ';

	$result=mysql_query($sql,$link);

	echo '<form method=post action=\''.$filename.'\'>';
	echo '<table border=1 bgcolor=lightyellow CELLPADDING=0 CELLSPACING=0 >	
			<tr><th colspan=8 align=left>Profiles</th>';
	echo '<th nowrap><input type=hidden name=sample_id value=\''.$sample_id. '\'> </th>';
	$counter=0;
	echo '</tr><tr>';
	echo '<input type=hidden name=action value=profile></td>';
	while($ar=mysql_fetch_assoc($result))
	{
		foreach ($ar as $key => $value)
		{		if($key=='profile')
				{
					echo '<td nowrap><button type=submit name=profile value=\''.$value.'\'>'.$value.'</button></td>';
					$counter++;
				}
				if($counter%5==0){echo '</tr><tr>';}
		}
	}

	echo '</tr></form></table>';
}

function insert_single_examination($sample_id,$id)
	{
		$link=start_nchsls();
		$sql='select * from scope where id=\''.$id.'\'';
		$result=mysql_query($sql,$link);
		
		if(!$post_array=mysql_fetch_assoc($result))
		{
			echo 'insert_single_examination($sample_id,$id)'.mysql_error();
			return;
		}
		
		$sql='insert into examination (`sample_id`, `id`, `name_of_examination`, `sample_type`, `preservative`, `method_of_analysis`, `result`, `unit`, `referance_range`, `code`, `details`,`NABL_Accredited`,`section`,`note`) 
					values ('.
					'\''.$sample_id.'\' , '.
					'\''.$post_array['id'].'\' , '.
					'\''.$post_array['name_of_examination'].'\' , '.
					'\''.$post_array['sample_type'].'\' , '.
					'\''.$post_array['preservative'].'\' , '.
					'\''.$post_array['method_of_analysis'].'\' , '.
					'\''.$post_array['result'].'\' , '.
					'\''.$post_array['unit'].'\' , '.
					'\''.$post_array['referance_range'].'\' , '.
					'\''.$post_array['code'].'\' , '.
					'\'\' , '.
					'\''.$post_array['NABL_Accredited'].'\' , '.
					'\''.$post_array['section'].'\', '.
					'\''.$post_array['note'].'\' )';
			//$sql=substr($sql,0,-2);
			//$sql=$sql.')';
			//echo $sql;
			if(!mysql_query($sql,$link))
			{
				//echo mysql_error();
			}
			else
			{
				return mysql_affected_rows($link);
			}
					
	}


function insert_profile($sample_id,$profile)
{
	$link=start_nchsls();
		$sql='select * from profile where profile=\''.$profile.'\'';
		$result=mysql_query($sql,$link);
		
	while($profile_row=mysql_fetch_assoc($result))
	{
		foreach ($profile_row as $key => $value)   //for every row in profile
		{
			if($key!='profile' && $value!=NULL && $key!='sample_type' && $key!='preservative')
			{
				insert_single_examination($sample_id,$value);
			}
		}
	}
}


function edit_examination($sample_id,$filename,$disabled)
{
	$link=start_nchsls();
	$sql='select * from examination where sample_id=\''.$sample_id.'\'';
	if(!$result=mysql_query($sql,$link)){echo 'No such Sample';return FALSE;}
	if(mysql_num_rows($result)<1){echo 'No examinations';return FALSE;}
	
	echo '<table border=1 bgcolor=lightgrey CELLPADDING=0 CELLSPACING=0>';
	echo '<form method=post action=\''.$filename.'\'>';
	echo '<input type=hidden name=sample_id value=\''.$sample_id.'\'';		  
	echo '<tr><td><input type=submit value=save_examination  '.$disabled.' name=action></td></td><th colspan=18 align=left>Examination Result Entry Form</th></tr>';
	
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
		if($ar['id']<1000)
		{
			foreach ($ar as $key => $value)   //for every row in scope
			{
					if($key=='result')
					{
						//echo '<td nowrap><input type=text '.$disabled.' name=\''.$ar['id'].'\' value=\''.$value.'\'></td>';
						echo '<td nowrap><textarea '.$disabled.' name=\''.$ar['id'].'\'>'.$value.'</textarea></td>';
					}
					else
					{
						echo '<td nowrap>'.$value.'</td>';
					}
			}
		}
		elseif($ar['id']>=1000)
		{
			foreach ($ar as $key => $value)   //for every row in scope
			{
					if($key=='result')
					{
						echo '<td><b>'.trim($ar['name_of_examination'],'Z_').':</b></td><td colspan=20><input size=80 type=text '.$disabled.' name=\''.$ar['id'].'\' value=\''.$value.'\'></td>';
					}
			}
		}
		echo '</tr>';		
	}
	echo '</form></table>';
}	

/*
array(
action=>save_examination
sample_id=>1234
12=>1.2
34=>34
	)
*/
function save_examination($post_array)
{
	$link=start_nchsls();
	foreach ($post_array as $key => $value)
	{
		if($key!='action')
		{
			$sql='update examination set result=\''.$value.'\' where sample_id=\''.$post_array['sample_id'].'\' and id=\''.$key.'\'';
			if(!mysql_query($sql,$link)){echo mysql_error();}
		}
	}
}

function save_single_examination($sample_id,$examination_id,$result)
{
	$link=start_nchsls();
	$sql='update examination set result=\''.$result.'\' where sample_id=\''.$sample_id.'\' and id=\''.$examination_id.'\'';
	if(!mysql_query($sql,$link)){echo mysql_error();}
}


function save_single_examination_code($sample_id,$code,$result)
{
	$link=start_nchsls();
	$sql='update examination set result=\''.$result.'\' where sample_id=\''.$sample_id.'\' and code=\''.$code.'\'';
	if(!mysql_query($sql,$link)){echo mysql_error();}
}

function append_single_examination($sample_id,$examination_id,$result)
{
	$link=start_nchsls();
	$sql='update examination set result=concat(result,\''.$result.'\') where sample_id=\''.$sample_id.'\' and id=\''.$examination_id.'\'';
	if(!mysql_query($sql,$link)){echo mysql_error();}
}

function select_examination($sample_id,$filename,$disabled)
{
	$link=start_nchsls();
	$sql_sample_data='select section,sample_type,preservative from sample where sample_id='.$sample_id;
	
	if(mysql_num_rows($result_sample_data=mysql_query($sql_sample_data,$link))!=1){echo 'No such Sample';return FALSE;}
	
	$sample_array=mysql_fetch_assoc($result_sample_data);
	//$sql='select * from scope where sample_type=\''.$sample_array['sample_type'].'\' and preservative=\''.$sample_array['preservative'].'\' or name_of_examination like \'Z_%\' order by name_of_examination';
	$sql='select * from scope where 
				
				section=\''.$sample_array['section'].'\' and
				((sample_type=\''.$sample_array['sample_type'].'\' and 
				preservative=\''.$sample_array['preservative'].'\' ) 
				
				or 
				
				name_of_examination like \'Z_%\' )
				
				and Available=\'yes\' order by name_of_examination';
	
	//echo $sql;
	$result=mysql_query($sql,$link);

	echo '<table border=1 id=\'id\' bgcolor=lightgrey CELLPADDING=0 CELLSPACING=0>';
	echo '<form method=post action=\''.$filename.'\'>';
	echo '<input type=hidden name=sample_id value=\''.$sample_id.'\'';	
	  
	echo '<tr>
			<input type=hidden value=select_examination  '.$disabled.' name=action>
			<th colspan=18 align=left>Scope</th>
		</tr>';
	$first='true';
	while($ar=mysql_fetch_assoc($result))
	{
		if($first=='true')
		{
			foreach ($ar as $key => $value)   //for every row in scope
			{
				echo '<th nowrap>'.$key.'</th>';
			}
			$first='false';			
			
		}
		echo '<tr>';
			foreach ($ar as $key => $value)   //for every row in scope
			{
					if($key=='id')
					{
						echo '<td nowrap><button type=submit '.$disabled.' name=\''.$key.'\' value=\''.$value.'\'>'.$value.'</button></td>';
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

function print_sample_worklist($sample_id)
{
		$link=start_nchsls();
		$sql='select * from examination where sample_id=\''.$sample_id.'\'';
		$result=mysql_query($sql,$link);
		echo '<table border=1 bgcolor=lightblue>';
		echo '<tr><td colspan=2 align=left>-----['.$sample_id.']-----<td>';
		while($post_array=mysql_fetch_assoc($result))
		{
			echo '<tr><td>'.$post_array['name_of_examination'].'</td><td>'.$post_array['code'].'</td><td>'.$post_array['result'].'</td></tr>';
		}
		echo '</table>';
}


///////////////////////autoverify functions

//used to find if result have anything other than 0-9 and .
//even a space in the string means that it is not numeric






function is_str_interger($str)
{
$nm=array('0','1','2','3','4','5','6','7','8','9');

$strr=str_split($str);

	foreach($strr as $key=>$value)
		{
		if(!in_array($value,$nm))
			{
			return FALSE;
			}
		}
return $str;
}
					
function is_str_num($str)
{
$nm=array('0','1','2','3','4','5','6','7','8','9','.');

$strr=str_split($str);

	foreach($strr as $key=>$value)
		{
		if(!in_array($value,$nm))
			{
			return FALSE;
			}
		}
return $str;
}

function is_str_digit($str)
{
$nm=array('0','1','2','3','4','5','6','7','8','9');

$strr=str_split($str);

	foreach($strr as $key=>$value)
		{
		if(!in_array($value,$nm))
			{
			return FALSE;
			}
		}
return $str;
}

/*
$error_code_text=array(	'-1'=>'mysql error',
					'-2'=>'not done',
					'-3'=>'not numeric',
					'-4'=>'no such record',
					'0'=>'autoverified',
					'1'=>'low absurd',
					'2'=>'high absurd',					
					'3'=>'low critical',
					'4'=>'high critical',
					'5'=>'low abnormal',
					'6'=>'high abnormal',
					'7'=>'critical alert not reported',
					'8'=>'critical alert reporting incomplate',
					'11'=>'dbil>tbil and differance is more than 10 persant'
					);
*/
				
function check_critical_abnormal_reportable($sample_type,$code,$result)
{
	$link=start_nchsls();
	
	if(strlen($result)==0){return -2;}
	if(!is_str_num($result)){return -3;}
	//echo '<pre>';
	//echo 'check_critical_abnormal_reportable()'.$sample_type.'-'.$code.'-'.$result.'<br>';
	//echo '</pre>';

	$link=start_nchsls();
	
////reportable
	$sql_reportable='select * from reportable_alert where code=\''.$code.'\' and sample_type=\''.$sample_type.'\'';
	if(!$result_reportable=mysql_query($sql_reportable,$link))
	{
		echo 'check_critical_abnormal_reportable(reportable):'.mysql_error();
		return -1;
	}
	
	while($data_reportable=mysql_fetch_assoc($result_reportable))
	{
		//echo '<pre>';
		//print_r($data_reportable);
		//echo '</pre>';	
		if($data_reportable['operator']=='less_than' && $result<$data_reportable['value'])
		{
			return 1;
		}
		if($data_reportable['operator']=='more_than' && $result>$data_reportable['value'])
		{
			return 2;
		}			
	}

////critical
	$sql_critical='select * from critical_alert where code=\''.$code.'\' and sample_type=\''.$sample_type.'\'';
	if(!$result_critical=mysql_query($sql_critical,$link))
	{
		echo 'check_critical_abnormal_reportable(critical):'.mysql_error();
		return -1;
	}
	
	while($data_critical=mysql_fetch_assoc($result_critical))
	{
		//echo '<pre>';
		//print_r($data_critical);
		//echo '</pre>';	
		if($data_critical['operator']=='less_than' && $result<$data_critical['value'])
		{
			return 3;
		}
		if($data_critical['operator']=='more_than' && $result>$data_critical['value'])
		{
			return 4;
		}			
	}

////abnormal
	$sql_abnormal='select * from abnormal_alert where code=\''.$code.'\' and sample_type=\''.$sample_type.'\'';
	if(!$result_abnormal=mysql_query($sql_abnormal,$link))
	{
		echo 'check_critical_abnormal_reportable(abnormal):'.mysql_error();
		return -1;
	}
	
	while($data_abnormal=mysql_fetch_assoc($result_abnormal))
	{
		//echo '<pre>';
		//print_r($data_abnormal);
		//echo '</pre>';	
		if($data_abnormal['operator']=='less_than' && $result<$data_abnormal['value'])
		{
			return 5;
		}
		if($data_abnormal['operator']=='more_than' && $result>$data_abnormal['value'])
		{
			return 6;
		}			
	}
			
	return 0;
}

function get_all_details_of_a_sample($sample_id)
{
	$link=start_nchsls();
	$sql_sample_examination='select 
						*
					from 
						examination
						join sample ON sample.sample_id = examination.sample_id
					where 
						sample.sample_id=\''.$sample_id.'\' ';
	
	if(!$result_sample_examination=mysql_query($sql_sample_examination,$link)){echo mysql_error();return FALSE;}
	while ($array_sample_examination=mysql_fetch_assoc($result_sample_examination))
	{	
		$return_array[]=$array_sample_examination;
	}

		//echo '<pre>';
		//print_r($return_array);
		//echo '</pre>';	
        if(!isset($return_array)){return FALSE;}
		return $return_array;

}


function get_patient_id($sample_id)
{
	$all_details=get_all_details_of_a_sample($sample_id);
	if(substr_compare($all_details[0]['patient_id'],'SUR',0,3)==0)
	{
		return $all_details[0]['patient_id'];
	}
	else
	{
		return FALSE;
	}
}

function get_sample_status($sample_id)
{
	$all_details=get_all_details_of_a_sample($sample_id);
	return $all_details[0]['status'];
}
function get_all_sample_id_from_patient_id($patient_id)
{
	$link=start_nchsls();
		$sql='select sample_id from sample where patient_id=\''.$patient_id.'\' order by sample_receipt_time desc';
		if(!$result=mysql_query($sql,$link)){echo mysql_error();return FALSE;}
		while ($array_sample_id=mysql_fetch_assoc($result))
			{	
				$return_array[]=$array_sample_id['sample_id'];
				
			}

				//echo '<pre>';
				//print_r($return_array);
				//echo '</pre>';	
				return $return_array;
}

function get_all_sample_id_from_sample_id($sample_id)
{
	$patient_id=get_patient_id($sample_id);
	if($patient_id!=FALSE)
	{
		return get_all_sample_id_from_patient_id($patient_id);
	}
	else
	{
		return FALSE;
	}
}

// -2=not requested,  -1=not done
function get_examination_result($code,$sample_id)
{
$all_array=get_all_details_of_a_sample($sample_id);
if($all_array)
{
	foreach($all_array as $key=>$value)
		{
		foreach($value as $keyy=>$valuee)
			{
			if($valuee==$code && $keyy=='code')
				{
					return $value['result'];
				}
			}
		}
}
return FALSE;
}

function get_examination_result_id($id,$sample_id)
{
$all_array=get_all_details_of_a_sample($sample_id);
if($all_array)
{
	foreach($all_array as $key=>$value)
		{
		foreach($value as $keyy=>$valuee)
			{
			if($valuee==$id && $keyy=='id')
				{
					return $value['result'];
				}
			}
		}
}
return FALSE;
}

function get_all_data_of_examination($code,$sample_id)
{
$all_array=get_all_details_of_a_sample($sample_id);
if($all_array)
{
	foreach($all_array as $key=>$value)
		{
			if($value['code']==$code)
			{
				return $value;
			}
		}
}
return FALSE;
}

function get_chronology_of_an_examination($code,$sample_id)
{
$chronology=FALSE;
if($sample_id_array=get_all_sample_id_from_sample_id($sample_id)) //return false if start not with SUR
{
foreach($sample_id_array as $key=>$value)
	{
			$chronology[$value]=get_all_data_of_examination($code,$value);
	}
return $chronology;
}
return FALSE;
}

function print_chronology_of_an_examination($chronology)
{
	if($chronology!=FALSE)
	{	
		foreach($chronology as $sample_id=>$data)
		{
			echo '<tr><td>'.$data['code'].'</td><td>'.$data['result'].'</td><td>'.$data['sample_details'].'</td><td>'.$data['sample_receipt_time'].'</td><td>'.$data['sample_id'].'</td><td>'.$data['patient_id'].'</td><td>'.$data['patient_name'].'</td></tr>';
		}
		
		echo '<tr><td colspan=10 bgcolor=blue></td></tr>';
	}
	else
	{
		echo '<tr><td>MRD number format is wrong. Deltacheck terminated</td></tr>';
				echo '<tr><td colspan=10 bgcolor=blue></td></tr>';
	}
}

function print_chronology_of_a_sample($sample_id)
{
	$all_array=get_all_details_of_a_sample($sample_id);
	if($all_array)																							//all tests
	{
		foreach($all_array as $all_ex_data)
		{
			$data=get_chronology_of_an_examination($all_ex_data['code'],$all_ex_data['sample_id']);
			echo '<table bgcolor=lightgreen border=1>';
				print_chronology_of_an_examination($data);
			echo '</table>';
		}
	
	}
}

function my_array_search($ar,$str)
{
	foreach($ar as $key=>$value)
	{
		if($value==$str)
		{
			return TRUE;
		}
	}
	return FALSE;
}


function check_critical_reporting($sample_id)
{
	insert_single_examination($sample_id,1005);
	$result=get_examination_result_id(1005,$sample_id);
	
	if(strlen($result==0))
	{
		save_single_examination($sample_id,1005,strftime('%Y-%m-%d %H:%M:%S').'=>'.$_SESSION['login'].'=>TO_XYZ');
		return 8;//incomplate reporting		
	}
	else
	{
		if(substr($result,-6,6)!='TO_XYZ')
		{
			return 0;
		}
		else
		{
			save_single_examination($sample_id,1005,strftime('%Y-%m-%d %H:%M:%S').'=>'.$_SESSION['login'].'=>TO_XYZ');
			return 8;//incomplate reporting
		}
	}
}

function append_if_not($hay,$sample_id,$examination_id)
{
	$value=get_examination_result_id($examination_id,$sample_id);
	$pos=strpos($value,$hay);
		if($pos===FALSE)
		{
			append_single_examination($sample_id,$examination_id,$hay);
		}
}
function indirect_bilirubin($sample_id)
{
	$tbil=get_examination_result('TBIL',$sample_id);
	$dbil=get_examination_result('DBIL',$sample_id);	
	if($tbil!=FALSE && $dbil!=FALSE)
	{
		if(is_str_num($tbil)!=FALSE && is_str_num($dbil)!=FALSE)
		{
			if($tbil>=$dbil)
			{
				save_single_examination_code($sample_id,'IBIL',$tbil-$dbil);
			}
			else
			{
				if(  (($dbil-$tbil)/$dbil)<0.1	)			//2 and 1.8 1 and 0.9 10 and 9
				{
					save_single_examination_code($sample_id,'IBIL','see remark');
					insert_single_examination($sample_id,1007);
					$str='The majority of bilirubin is conjugated in this patient.';
					append_if_not($str,$sample_id,1007);
				}
				else
				{
					return 11;
				}
			}
		}
	}
}



function lipid_profile($sample_id)
{
	//echo '111';
	$cho=get_examination_result('CHO',$sample_id);
	$choh=get_examination_result('CHOH',$sample_id);
	$chol=get_examination_result('CHOL',$sample_id);
	$chov=get_examination_result('CHOV',$sample_id);
	$tg=get_examination_result('TG',$sample_id);
	
	if($cho!=FALSE && $choh!=FALSE && $tg!=FALSE)  	//they are not null
	{
			//echo '222';
		if 	(is_str_num($cho)!=FALSE && is_str_num($choh)!=FALSE && is_str_num($tg)!=FALSE)	//they are number
		{
				//echo '333';
			if($tg<=400)
			{
					//echo '444';
				save_single_examination_code($sample_id,'CHOV',($tg/5));
				save_single_examination_code($sample_id,'CHOL',$cho - $choh -($tg/5));
			}
			else
			{
					//echo '555';
				save_single_examination_code($sample_id,'CHOV','see remark');
				save_single_examination_code($sample_id,'CHOL','see remark');
				insert_single_examination($sample_id,1007);
				$str='if TG>400 mg/dl then LDL can not be calculated. Direct-LDL assay is advised';
				append_if_not($str,$sample_id,1007);
			}
		}
	}
		//both(TandD) are not done, both are not number, dbil>tbil
}
//4->6
//31->7, 11.14,15,23.,65,
//7->31,11.14.23.., 15 ,65,7
function reflex_testing($sample_id,$examination_id,$acr_code)
{
	if($acr_code==1||$acr_code==3||$acr_code==5){$level='less_than';}
	elseif($acr_code==2||$acr_code==4||$acr_code==6){$level='more_than';}
	else{$level='';}
	
	$return_value=0;
	$sql='select * from reflex_examination where examination=\''.$examination_id.'\' and level=\''.$level.'\'';
	$link=start_nchsls();
	if(!$result=mysql_query($sql,$link)){echo mysql_error();return FALSE;}
	while ($array=mysql_fetch_assoc($result))
	{
		//echo '<pre>';
		//print_r($array);
		$ret=insert_single_examination($sample_id,$array['reflex']);
		if($ret>0){$return_value=$return_value+$ret;}
	}
	return $return_value;
}


function autoverify($sample_id,$filename,$action)
{
	global $reflex;
	if(get_sample_status($sample_id)=='verified'){return;}
	if(!$all_details=get_all_details_of_a_sample($sample_id)){return FALSE;}
	$verified=0;
	foreach ($all_details  as $key=>$value)
	{
		$error_code_array=array();
		
		//CSF and Blood(Serum,Plasma) have no diff
		//$acr_code=check_critical_abnormal_reportable($value['sample_id'],$value['code']);
		$acr_code=check_critical_abnormal_reportable(	$value['sample_type'],
														$value['code'],
														$value['result']);
		
		
		//////////empty
		if($acr_code==-2){$error_code_array[]=$acr_code;}								//-2 if empty
		
		//////////////critical 3,4
		if($acr_code==3 || $acr_code==4)
		{
			$error_code_array[]=check_critical_reporting($sample_id);					//8 if incomplate
		}
		//////////////absurd 1,2		
		if($acr_code==1 || $acr_code==2)
		{			
			insert_single_examination($value['sample_id'],1007);
			append_if_not('Send new sample to resolve absurd values.',$sample_id,1007);
			$error_code_array[]=$acr_code;												//1,2
		}
		
		//////////reflax if abnormal high(2,4,6)   //cholesterol=13
		if($acr_code==2 || $acr_code==4 ||$acr_code==6||$acr_code==1 || $acr_code==3 ||$acr_code==5)
		{
			if($value['location']=='F3N(503)' && $value['code']=='TBIL')
			{
				
			}
			else
			{ 
				if($reflex=='yes')
				{
					$any_ex_inserted=reflex_testing($sample_id,$value['id'],$acr_code);
					if($any_ex_inserted>0){$verified=1;}	// if any is inserted, verification require next round of autoverification
				}
			}
		}
		///////////////ibil
		if($value['code']=='IBIL')
		{
			$ibil=indirect_bilirubin($value['sample_id']);
			if($ibil=='11')
			{
				$error_code_array[]=11;													//11 if >10% diff
			}
		}


		///////////////LDL
		if($value['code']=='CHOL')			//CHOL and CHOV go hand in hand
		{
			lipid_profile($value['sample_id']);
		}

		//////total of error to decide if verified(=0)
		foreach($error_code_array as $error_value)
		{
			$verified=$verified+abs($error_value);
		}
		
		if($action=='yes')
		{
			///////action
			error_action($value['sample_id'],$value['code'],$error_code_array,$filename);
			///////
		}
	}
	
	////update status
	if($verified!=0){change_sample_status($sample_id,'verification failed');}
	else           {change_sample_status($sample_id,'verified');}
}

////donot change meaning of codes, it is dangerous to PHP code 
$error_code_text=array(	'-1'=>'mysql error',
					'-2'=>'not done',
					'-3'=>'not numeric',
					'-4'=>'no such record',
					'0'=>'autoverified',
					'1'=>'low absurd',
					'2'=>'high absurd',					
					'3'=>'low critical',
					'4'=>'high critical',
					'5'=>'low abnormal',
					'6'=>'high abnormal',
					'7'=>'critical alert not reported',
					'8'=>'critical alert reporting incomplate',
					'11'=>'dbil>tbil and differance is more than 10 persant'
					);
//'delete_examination'=>'delete examination',
$error_action_text=array(
		'1'=>'1. write result manually',																	//for blank results
		'2'=>'2. Import result',																			//blank results
		'3'=>'3. Write "Result awaited"',																	//for blank results
		'5'=>'5. write "not done" and exam. rejection "NOT DONE done because sample inadequate." ',			//for blank results
		'6'=>'6. write "not done" and exam. rejection "NOT DONE done because lab. resources inadequate." ',	//for blank results
		'7'=>'7. critical reporting entry'																	//for critical reporting
		);

function read_action($sample_id,$code,$error_code_array,$filename)
{
		global $error_action_text;

		echo '<tr><td>';
		echo '<form method=post action=\''.$filename.'\' target=_blank >';
		mk_select_from_array_return_key('action',$error_action_text,'','');
		echo '<input type=hidden name=code value=\''.$code.'\'>';
		echo '<button type=submit name=sample_id value=\''.$sample_id.'\'>OK</button>';
		echo '</form>';
		echo '</td><tr>';
}

//autoverification never successful: absurd, dbil>tbil(>10%)						
function error_action($sample_id,$code,$error_code_array,$filename)
{
	foreach($error_code_array as $error_value)
	{
	echo '<table border=3 bordercolor=green>';
	if($error_value=='-2')																//empty field, software action required
		{
			echo '<tr><th>The result field of ['.$sample_id.'] ['.$code.'] is <font color=red>EMPTY</font></th></tr>';
			read_action($sample_id,$code,$error_code_array,$filename);
		}
	elseif($error_value=='1' || $error_value=='2')										//absurd reporting, no software action required
		{
			echo '<tr><th>Result of ['.$sample_id.'] ['.$code.'] is <font color=red>ABSURD</font></th></tr>';
			echo '<tr><th>Repeat examination from new aliquot of primary sample at least twice</th></tr>';
			echo '<tr><th>Observe primary sample for visible changes of deterioration(Hemolysed, brown colored)</th></tr>';		
			echo '<tr><th>Observe primary sample container and preservative types, look for sample transposition</th></tr>';
			echo '<tr><th>is remarks inserted for repeat sample collection?</th></tr>';		
		}
	elseif($error_value=='8')																//critical reporting not complate, software action required
		{
			echo '<tr><th><font color=red>CRITICAL</font> value reporting of ['.$sample_id.'] ['.$code.'] is incomplate.</th></tr>';
			read_action($sample_id,$code,$error_code_array,$filename);
		}
		
	elseif($error_value=='11')																//dbil>tbil, no software action required
		{
			echo '<tr><th><font color=red>['.$sample_id.'] TBIL less than DBIL</font> with difference more than 10%</th></tr>';
			echo '<tr><th>Repeat examination. Review BIL results in other samples</th></tr>';			
			echo '<tr><th>Review QC. examine QC samples again.</th></tr>';
			echo '<tr><th>Perform manual measurement. Look for color of reaction by naked eye.</th></tr>';
			echo '<tr><th>Contact deputy technical manager before releasing report.</th></tr>';			
		}
	echo '</table>';	
	}
	
	//echo '['.$code;
	//echo ']';
	//print_r($error_code_array);
	//echo '<br>';

}

function read_date_time($type)
{
	date_default_timezone_set('Asia/Kolkata');
	$year=array(strftime('%Y')-1,strftime('%Y'),strftime('%Y')+1);
	$month=array('01','02','03','04','05','06','07','08','09','10','11','12');
	$day=array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');
	$hour=array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24');
	$minute=array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','26','27','28','29','30','32','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59','60');
	$second=array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','26','27','28','29','30','32','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59','60');

	if($type>=1)
	{
		mk_select_from_array_return_value('year',$year,'',strftime('%Y'));
	}
	if($type>=2)
	{
		mk_select_from_array_return_value('month',$month,'',strftime('%m'));
	}	
	if($type>=3)
	{
		mk_select_from_array_return_value('day',$day,'',strftime('%d'));
	}	
	if($type>=4)
	{
		mk_select_from_array_return_value('hour',$hour,'',strftime('%H'));
	}	
	if($type>=5)
	{
		mk_select_from_array_return_value('minute',$minute,'',strftime('%M'));
	}	
	if($type>=6)
	{
		mk_select_from_array_return_value('second',$second,'',strftime('%S'));
	}	
}


function edit_date_time($type,$d_year,$d_month,$d_day,$d_hour,$d_minute,$d_second)
{
	$year=array(strftime('%Y')-1,strftime('%Y'),strftime('%Y')+1);
	$month=array('01','02','03','04','05','06','07','08','09','10','11','12');
	$day=array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31');
	$hour=array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24');
	$minute=array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','26','27','28','29','30','32','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59','60');
	$second=array('01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','26','27','28','29','30','32','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59','60');

	if($type>=1)
	{
		mk_select_from_array_return_value('year',$year,'',$d_year);
	}
	if($type>=2)
	{
		mk_select_from_array_return_value('month',$month,'',$d_month);
	}	
	if($type>=3)
	{
		mk_select_from_array_return_value('day',$day,'',$d_day);
	}	
	if($type>=4)
	{
		mk_select_from_array_return_value('hour',$hour,'',$d_hour);
	}	
	if($type>=5)
	{
		mk_select_from_array_return_value('minute',$minute,'',$d_minute);
	}	
	if($type>=6)
	{
		mk_select_from_array_return_value('second',$second,'',$d_second);
	}		
}

function get_target($sample_id,$equipment_name,$code)
{
//XYYMMDDHH
//100000000
//$array=array('target'=>'','sd'=>'');	
	$link=start_nchsls();
	$sql=	'select * from qc_target where 
					equipment_name=\''.$equipment_name.'\' and
					code=\''.$code.'\' and
					qc_type=\''.(int)($sample_id/100000000).'\'
					
			';
	//echo $sql;		
	if(!$result=mysql_query($sql,$link)){return FALSE;}
	$array=mysql_fetch_assoc($result);
	return $array;	
}


///only used for  print_sample() to insert comment
$acr_check_code=array(	'-1'=>'',
					'-2'=>'',
					'-3'=>'',
					'0'=>'',
					'1'=>'low absurd',
					'2'=>'high absurd',					
					'3'=>'low critical',
					'4'=>'high critical',
					'5'=>'low abnormal',
					'6'=>'high abnormal');
					
function print_sample($sample_id,$Technician,$Doctor)
{
global $lab_name,$section_name,$address_phone,$nabl_symbol,$blank_symbol,$nabl_cert_no,$blank_cert_no,$bypass_autoverification;
$attachment_exist='no';
$link=start_nchsls();
$sql_sample_data='select * from sample where sample_id='.$sample_id;
$sql_examination_data='select * from examination where sample_id=\''.$sample_id.'\' order by name_of_examination';
global $acr_check_code;

////////find if any one is accredited
$NABL_acc_counter=0;
$result_examination_data_for_accr=mysql_query($sql_examination_data,$link);
while($acc_array=mysql_fetch_assoc($result_examination_data_for_accr))
{
	if($acc_array['NABL_Accredited']=='Yes')
	{
		$NABL_acc_counter++;
	}
}
if($NABL_acc_counter>0)
{
	$symbol=$nabl_symbol;
	$cert_no=$nabl_cert_no;
}
else
{
	$symbol=$blank_symbol;
	$cert_no=$blank_cert_no;
}
/////////////////////
					
if(mysql_num_rows($result_sample_data=mysql_query($sql_sample_data,$link))>0)
{
	$sample_array=mysql_fetch_assoc($result_sample_data);

		echo '
			<table border=0 style="border-collapse:collapse;">';
			
			if(strlen($Doctor)>0)
			{
			echo '<tr>
					<td colspan=20 align=center>
						<table border=0>
							<tr><th colspan=20><u><h2>'.$lab_name.'</h2></u></td></tr>
							<tr><th  colspan=2 align=center>'.$address_phone.'</td></td></tr>
							<tr>
								<td>
									<table border=0>
									<tr><td align=center ><img src="'.$symbol.'" height="96" width=90" /></td></tr>
									<tr><td width=10 align="center"><font size="1">'.$cert_no.'</font></td></tr>
									</table>
								</td>
								<td>
									<table border=0>
									<tr><th halign=center>Laboratory Examination Report</th></tr>
									<tr><th align=center>'.$section_name.'</th></tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>';
				}
				else
				{
					echo '<tr><th colspan=10><font color=red>THIS IS NOT A REPORT. DO NOT PRINT</font></th></tr>';
				}
				
				echo '<tr style="border:1px solid #000;">
								<td>
									<table border=0>
										<tr><td><b>Patient Name</b></td><td>'.$sample_array['patient_name'].'</td></tr>
										<tr><td><b>MRD Number</b></td><td>'.$sample_array['patient_id'].'</td></tr>
									</table>
								</td>	
								<td align=right>
									<table border=0>
										<tr><td><b>'.$sample_array['clinician'].'</td><td><b>Unit:</b>'.$sample_array['unit'].'</td></tr>
										<tr><td><b>Ward/OPD</b></td><td>'.$sample_array['location'].'</td></tr>
									</table>
								</td>						
				</tr>

				<tr style="border:1px solid #000;">
								<td>
									<table border=0>
										<tr><td style=\'font-size:150%\'>Sample ID:<b>'.$sample_array['sample_id'].'</b>(';

//comment line below when bypassing autoverification										
										echo $sample_array['status'];
										
										echo ')</td></tr>
										<tr><td><b>'.$sample_array['sample_details'].'</b></td></tr>
									</table>
								</td>	
								<td align=right>
									<table border=0>
										<tr><td><b>Sample Type:</b>'.$sample_array['sample_type'].'</td></tr>
										<tr><td><b>Preservative:</b>'.$sample_array['preservative'].'</td></tr>
									</table>
								</td>						
				</tr>';
			
				echo '
							<tr  style="border:1px solid #000;"><td colspan=3><b>Collection time/Diagnosis/Age/Sex:</b>'.$sample_array['sample_collection_time'].'</b></td></tr>
							<tr  style="border:1px solid #000;"><td><b>Receipt Time:</b>'.$sample_array['sample_receipt_time'].'</td><td align=right><b>Report time:</b>'.strftime('%Y-%m-%d %H:%M:%S').'</td></tr>
													
			';


		if(mysql_num_rows($result_examination_data=mysql_query($sql_examination_data,$link))>0)
		{
				//<table border=0 RULES=COLS FRAME=BOX style="border-collapse:collapse;  margin-top:50px">
			echo '
				<tr><td colspan=2>
				<table border=1 style="border-collapse:collapse;  margin-top:50px">
						<tr>
							<th>Accr.</th>
							<th>Examination</th>
							<th>Result</th>
							<th>Ref.R</th>
							<th>Alert</th>
							<th>Method</th>
						</tr>';
						
			while($examination_array=mysql_fetch_assoc($result_examination_data))
			{
				if($examination_array['id']<1000)
				{
					echo '<tr>';
					echo '<td>'.$examination_array['NABL_Accredited'].'</td>';
					echo '<td>'.$examination_array['name_of_examination'].'</td>';
					echo '<td>'.$examination_array['result'].'</td>';
					echo '<td>'.$examination_array['referance_range'].' '.$examination_array['unit'].'</td>';
					echo '<td>';
					echo $acr_check_code[check_critical_abnormal_reportable($sample_array['sample_type'],$examination_array['code'],$examination_array['result'])];
					echo '</td>';
					echo '<td>'.$examination_array['method_of_analysis'].'</td>';
					//echo '<td>'.$examination_array['details'].'</td>';
					echo '</tr>';		
				}
				elseif($examination_array['id']>=1000)
				{
					if($examination_array['id']==1008){$attachment_exist='yes';}
					echo '
						<tr>
							<td colspan="10" >
								<table border=0>
									<tr>
					<tr><td><b>'.trim($examination_array['name_of_examination'],'Z_').':</b></td><td>'.$examination_array['result'].'</td></tr>
									</tr>
								</table>	
							</td>
						</tr>';
				}
			}
			echo '<tr><th colspan=20>The tests marked with \'No\' in the first column are not accredited by NABL</th></tr>';

			echo '</table>';
		}	
			
				
			echo '	<tr>
						<td colspan=24 align=center>
							<table border=1 style="border-collapse:collapse;  margin-top:50px">
								<tr>
									<td>Sign:</td></td><td width=200></td><td rowspan=2 width=150></td>
									<td>Sign:</td><td width=200></td>
								</tr>
								
								<tr>
									<td align=center colspan=2>'.$Technician.'</td>
									<td   align=center colspan=2>'.$Doctor.'</td>
								</tr>
								
							</table>								
						</td>
					</tr>
						';	
		
			if($attachment_exist=='no')
			{
				echo '<tr><td align=center colspan=6>-----End of Report-----</td></tr>';	
			}
			echo '</table>';
			
			if($attachment_exist=='yes')
			{
				echo '<h2 style="page-break-before: always;"></h2>';
				print_attachment($sample_id);				
			}
			//return TRUE;

}
else
{
	return FALSE;
}
}

function print_examinations_tt($sample_id)
{
	$link=start_nchsls();

	$sql='select 
					sample.sample_id, code,
					sample_receipt_time as Received ,
					substr(examination.details,1,19) as Analysis_complated, 
					timediff(substr(examination.details,1,19),sample_receipt_time) as TAT 
				from 
					sample,examination 
				where 
					sample.sample_id=examination.sample_id 
					and 
					sample.sample_id=\''.$sample_id.'\'';
	$result=mysql_query($sql,$link);
	echo mysql_error();
	if(mysql_num_rows($result)>0)
	{
		echo '<table border=1 bgcolor=lightblue>';
		echo '<tr><td>Sample ID</td><td>Code</td><td>Received</td><td>Analysis complated</td><td>TAT(h:m:s)</td></tr>';
		while($ar=mysql_fetch_assoc($result))
		{
			echo '<tr><td>'.$ar['sample_id'].'</td><td>'.$ar['code'].'</td><td>'.$ar['Received'].'</td><td>'.$ar['Analysis_complated'].'</td><td>'.$ar['TAT'].'</td></tr>';
		}
	}
}

/*

function print_examinations_tt($sample_id)
{
	$link=start_nchsls();

	$sql_tt='select sample_receipt_time from sample where sample_id=\''.$sample_id.'\'';
	$sql='select id,name_of_examination,details,result,unit,referance_range from examination where sample_id=\''.$sample_id.'\' order by name_of_examination';

	//echo $sql_tt;
	$result_tt=mysql_query($sql_tt,$link);
	$sample_r_t=mysql_fetch_assoc($result_tt);
	$sample_receipt_time=$sample_r_t['sample_receipt_time'];

	if(mysql_num_rows($result=mysql_query($sql,$link))>0)
	{
	echo '<table border=1 bgcolor=lightblue>';
	echo '<tr><td>sample_id</td><td>Examination</td><td>turnaround time(Hours)</td></tr>';

		//$rcount=0;
		//  $tr='<tr>';
		
	while($ar=mysql_fetch_assoc($result))
	{
		if($ar['id']<1000)
		{
			$tat=find_turnaround_time($sample_id,$ar['id']);
			if($tat!=FALSE)
			{
				echo '<tr><td>'.$sample_id.'</td><td>'.$ar['name_of_examination'].'</td><td>'.$tat.'</td></tr>';
			}
			else
			{
				echo '<tr><td>'.$sample_id.'</td><td>'.$ar['name_of_examination'].'</td><td>NA</td></tr>';
			}
		}
	}

	}
}

*/

function find_turnaround_time($sample_id,$id)
{
$link=start_nchsls();

$sql_tt='select sample_receipt_time from sample where sample_id=\''.$sample_id.'\'';
$sql='select id,name_of_examination,details,result,unit,referance_range from examination where sample_id=\''.$sample_id.'\' and id=\''.$id.'\'';

//echo $sql_tt;
$result_tt=mysql_query($sql_tt,$link);
$sample_r_t=mysql_fetch_assoc($result_tt);
$sample_receipt_time=$sample_r_t['sample_receipt_time'];

if(mysql_num_rows($result=mysql_query($sql,$link))>0)
{
while($ar=mysql_fetch_assoc($result))
{
	if($ar['id']<1000)
	{
		$ex=explode('|',$ar['details']);
		$tt=get_time_difference($sample_receipt_time, $ex[0]);
		//print_r($tt);
		if($tt!=FALSE)
		{
			return $tt_in_hours=ceil($tt['days']*24+$tt['hours']+($tt['minutes']/60));
		}
		else
		{
			return FALSE;
		}
	}
}

}
}

function get_time_difference( $start, $end )
{
    $uts['start']      =    strtotime( $start );
    $uts['end']        =    strtotime( $end );
    if( $uts['start']!==-1 && $uts['end']!==-1 )
    {
        if( $uts['end'] >= $uts['start'] )
        {
            $diff    =    $uts['end'] - $uts['start'];
            if( $days=intval((floor($diff/86400))) )
                $diff = $diff % 86400;
            if( $hours=intval((floor($diff/3600))) )
                $diff = $diff % 3600;
            if( $minutes=intval((floor($diff/60))) )
                $diff = $diff % 60;
            $diff    =    intval( $diff );            
            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
        }
        else
        {
            return FALSE;//trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
        }
    }
    else
    {
        return FALSE;//trigger_error( "Invalid date/time data detected", E_USER_WARNING );
    }
    return( false );
}


function create_file($str,$sample_id,$attachment_id,$filetype)
{
	$filename='tmp/'.$sample_id.'-'.$attachment_id.'.'.$filetype;
	$fp=fopen($filename,'w+');
	fwrite($fp,$str);
	return $filename;
}

function print_single_attachment($array)
{
	echo '<table>';
	echo '<tr><td>(This attachment is invalid for clinical use unless accompanied by main report)</td></tr>';
	echo '<tr><th colspan=10 align=left>Attachment of sample_id='.$array['sample_id'].' and patient_id='.get_patient_id($array['sample_id']).'</th></tr>';
	echo '<tr><th colspan=10  align=left>Attachment:'.$array['attachment_id'].'</th></tr>';
	echo '<tr><th colspan=10  align=left>Description:'.$array['description'].'</th></tr>';
	$filetype=$array['filetype'];
	if($filetype=='jpeg' || $filetype=='jpg' || $filetype=='gif')
	{
		$filename=create_file($array['file'],$array['sample_id'],$array['attachment_id'],$array['filetype']);
		echo '<tr><td><img src="'.$filename.'" height=500 width=500></td></tr>';	
	}
	elseif($filetype=='txt')
	{
		echo '<tr><td><pre>'.htmlspecialchars($array['file']).'</pre></td></tr>';
	}
	else
	{
		echo '	<tr>
		<td>cannot display</td>
		</tr>';
	}			
	echo '</table>';
	
}

function print_attachment($sample_id)
{
	$sql='select * from attachment where sample_id=\''.$sample_id.'\'';
	//echo $sql;
	$link=start_nchsls();
	if(!$result=mysql_query($sql,$link)){return FALSE;}
	$number_of_attachment=mysql_num_rows($result);
	$counter=1;
	while($array=mysql_fetch_assoc($result))
	{
		print_single_attachment($array);
		if($counter==$number_of_attachment)
		{
			echo '<tr><td align=center colspan=6>-----End of Report-----</td></tr>';
		}
		else
		{
			echo '<h2 style="page-break-before: always;"></h2>';
			$counter++;
		}
	}
}


function view_data($id)
{
	$link=start_nchsls();

	if(!$result_id=mysql_query('select * from view_data where id=\''.$id.'\'',$link)){echo mysql_error();}
	$array_id=mysql_fetch_assoc($result_id);
	
	$sql=$array_id['sql'];
	$info=$array_id['info'];
		
	$first_data='yes';
	
	if(!$result=mysql_query($sql,$link)){echo mysql_error();}
	echo '<table border=1><tr><th colspan=20 bgcolor=lightblue>'.$info.'</th></tr>';
	
	$first_data='yes';
	
	while($array=mysql_fetch_assoc($result))
	{
		if($first_data=='yes')
		{
			echo '<tr bgcolor=lightgreen>';
			foreach($array as $key=>$value)
			{
				echo '<th>'.$key.'</hd>';
			}
			echo '</tr>';
			$first_data='no';
		}
		foreach($array as $key=>$value)
		{
			echo '<td>'.$value.'</td>';
		}
		echo '</tr>';

	}
	echo '</table>';
}

function view_data_sql($sql)
{
	$link=start_nchsls();

	if(!$result_id=mysql_query($sql,$link)){echo mysql_error();}
	$array_id=mysql_fetch_assoc($result_id);
	
	$first_data='yes';
	
	if(!$result=mysql_query($sql,$link)){echo mysql_error();}
	echo '<table border=1>';
	
	$first_data='yes';
	
	while($array=mysql_fetch_assoc($result))
	{
		if($first_data=='yes')
		{
			echo '<tr bgcolor=lightgreen>';
			foreach($array as $key=>$value)
			{
				echo '<th>'.$key.'</hd>';
			}
			echo '</tr>';
			$first_data='no';
		}
		foreach($array as $key=>$value)
		{
			echo '<td><pre>'.$value.'</pre></td>';
		}
		echo '</tr>';

	}
	echo '</table>';
}

function count_cron()
{
	$link=start_nchsls();
	$sql='select * from reminder';
	$result=mysql_query($sql,$link);
	return mysql_num_rows($result);
}

//blob can not be attached during insertion, it must be edited
function manage_blob($database,$tablename,$primary_key_array,$attachment_id)
{
	echo '<form method=post action=manage_blob.php>';
	echo '<button type=submit name=action value=manage_blob>Manage Attachment</button>';
	echo '<input type=hidden name=_database value=\''.$database.'\'>';
	echo '<input type=hidden name=_tablename value=\''.$tablename.'\'>';
	
	foreach($primary_key_array as $key=>$value)
	{
		echo '<input type=hidden name=\'_pri_'.$key.'\' value=\''.$value.'\'>';
	}
	
	echo '<input type=hidden name=_attachment_id value=\''.$attachment_id.'\'>';
	echo '</form>';
}

//////////////PDF functions///////////////

class MYPDF_NABL extends TCPDF {
	public $lab_name='NEW CIVIL HOSPITAL SURAT LABORATORY SERVICES';
	public $section_name='Biochemistry Section';
	public $section_address_phone='Near Blood Bank, 2nd Floor. Ext:317,366';
	public $address_phone='MAJURA GATE SURAT PHONE NO.0261-2244456';
	public $nabl_symbol='nabl.jpg';
	public $blank_symbol='blank.jpg';
	public $nabl_cert_no='M-0450';
	public $blank_cert_no='';
	public $bypass_autoverification='no';		//if 'yes'=>it will bypass autoverification
	public $sample_id_array;
	public $sample_id;
	public $doctor;
	public $login;
	public $header_y;
	public $completed;
	public $sample_type='';
	//Page header W=210 H=148
	//210 × 297 A4
	//$this->Write(0,$this->getPageWidth());
	
	
	public function Header() 
	{
		
//NCHSLS Header
		$y_counter=15;

		$this->SetFont('courier', 'B', 15);
		$this->SetXY(10,$y_counter);
		$this->Cell(190, $h=0, $txt=$this->lab_name, $border=0, $ln=0, $align='C', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

		$y_counter=$y_counter+5;
		$this->SetFont('courier', '', 15);
		$this->SetXY(10,$y_counter);
		$this->Cell(190, $h=0, $txt=$this->address_phone, $border=0, $ln=0, $align='C', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

		//Print NABL symbol if any one is accredited
		$linkk=start_nchsls();
		$sql_sample_data='select * from sample where sample_id='.$this->sample_id;
		$sql_examination_data='select * from examination where sample_id=\''.$this->sample_id.'\' order by name_of_examination';
		$NABL_acc_counter=0;
		$result_examination_data_for_accr=mysql_query($sql_examination_data,$linkk);
		$this->completed='';
		while($acc_array=mysql_fetch_assoc($result_examination_data_for_accr))
		{
			if($acc_array['NABL_Accredited']=='Yes')
			{
				$NABL_acc_counter++;
			}
			
			$exp=explode("|",$acc_array['details']);
			if(isset($exp[0]))
			{
				$this->completed=$exp[0];
			}
			
			
		}
		if($NABL_acc_counter>0)
		{
			$image_file = $this->nabl_symbol;
			$this->Image($image_file, 10, 10,275/12, 320/12, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
			$this->SetFont('courier', '', 10);
			$this->SetXY(10,36);//(Y=10+(320/12)=36)
			$this->Cell(275/12, $h=0, $txt=$this->nabl_cert_no, $border=0, $ln=0, $align='C', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
		}
		//////////////////////////////////////////////
		$y_counter=40;

		$this->Line(10,$y_counter,200,$y_counter);

/*		
		$y_counter=$y_counter+1;
		$this->SetFont('courier', 'B', 15);
		$this->SetXY(10,$y_counter);
		$this->Cell(190, $h=0, $txt=$this->section_name, $border=0, $ln=0, $align='C', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
		
		$y_counter=$y_counter+5;
		$this->SetFont('courier', '', 15);
		$this->SetXY(10,$y_counter);
		$this->Cell(190, $h=0, $txt=$this->section_address_phone, $border=0, $ln=0, $align='C', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
*/

//Biochemisty Header
		if(mysql_num_rows($result_sample_data=mysql_query($sql_sample_data,$linkk))>0)
		{
			$border=0;
			$sample_array=mysql_fetch_assoc($result_sample_data);	
			$this->section_name=$sample_array['section'];
			
		$y_counter=$y_counter+1;
		$this->SetFont('courier', 'B', 15);
		$this->SetXY(10,$y_counter);
		$this->Cell(190, $h=0, $txt=$this->section_name, $border=0, $ln=0, $align='C', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
		
		$y_counter=$y_counter+5;
		$this->SetFont('courier', '', 15);
		$this->SetXY(10,$y_counter);
		$this->Cell(190, $h=0, $txt=$this->section_address_phone, $border=0, $ln=0, $align='C', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			
			
			
			$this->sample_type=$sample_array['sample_type'];
			$this->SetFont('courier', 'B', 15);
//1		
			$y_counter=$y_counter+10;			
			$this->SetXY(15,$y_counter);
			$this->Cell(59, $h=0, $txt='Name:'.$sample_array['patient_name'], $border=0, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			$this->SetXY(15+60,$y_counter);
			$this->Cell(59, $h=0, $txt=' MRD:'.$sample_array['patient_id'], $border=0, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			$this->SetXY(15+60+60,$y_counter);
			$this->SetFont('courier', 'B', 15);
			$this->Cell(59, $h=0, $txt=' Sample ID:'.$sample_array['sample_id'], $border=0, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
		
//2
			$this->SetFont('courier', '', 10);
			$y_counter=$y_counter+10;		
			$this->SetXY(15,$y_counter);
			$this->Cell(59, $h=0, $txt='Clinician: '.$sample_array['clinician'], $border=0, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
			
			$this->SetXY(15+60,$y_counter);
			$this->Cell(59, $h=0, $txt=' Unit:'.$sample_array['unit'], $border=0, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			$this->SetXY(15+60+60,$y_counter);
			$this->Cell(59, $h=0, $txt=' Location(Ext): '.$sample_array['location'], $border=0, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
			
//3
			$y_counter=$y_counter+5;		
			$this->SetXY(15,$y_counter);
			$this->Cell(59, $h=0, $txt='Sample Type: '.$sample_array['sample_type'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			$this->SetXY(15+60,$y_counter);
			$this->Cell(59, $h=0, $txt=' Preservative: '.$sample_array['preservative'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			$this->SetXY(15+60+60,$y_counter);
			$this->Cell(59, $h=0, $txt=' Sample Detail: '.$sample_array['sample_details'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
//4
			$border=0;	
			$y_counter=$y_counter+5;		
			$this->SetFont('courier', '', 10);
			$this->SetXY(15,$y_counter);
			$this->Cell(59, $h=0, $txt='Sex/Age: '.$sample_array['sex_age'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			$this->SetFont('courier', '', 10);
			$this->SetXY(15+60,$y_counter);
			$this->Cell(59, $h=0, $txt=' Diagnosis: '.$sample_array['diagnosis'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			$this->SetFont('courier', 'B', 12);
			$this->SetXY(15+60+60,$y_counter);
			$this->Cell(59, $h=0, $txt=' Status: '.$sample_array['status'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');


//5


			$y_counter=$y_counter+5;	
			
			$this->SetFont('courier', '', 10);
			$this->SetXY(15,$y_counter);
			$this->Cell(60, $h=0, $txt='Collected: '.$sample_array['sample_collection_time'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
				
			$this->SetFont('courier', '', 10);
			$this->SetXY(15+60,$y_counter);
			$this->Cell(59, $h=0, $txt=' Received: '.$sample_array['sample_receipt_time'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			$this->SetFont('courier', '', 10);
			$this->SetXY(15+60+60,$y_counter);
			$this->Cell(59, $h=0, $txt=' Reported: '.$this->completed, $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			//$this->SetFont('courier', '', 10);
			//$this->SetXY(15+45+45+45,$y_counter);
			//$this->Cell(44, $h=0, $txt=' Printed: '.strftime('%Y-%m-%d %H:%M:%S'), $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
		}
		
				$y_counter=$y_counter+5;		
				$this->Line(10,$y_counter,200,$y_counter);

				$border=1;
				$y_counter=$y_counter+5;		
				$this->SetFont('courier', 'B', 10);
				$this->SetXY(10,$y_counter);
				$this->Cell($w=10, $h=0, 'NABL Accr.',$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
				
				$this->SetXY(20,$y_counter);
				$this->Cell($w=40, $h=0, 'Examination',$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

				$this->SetXY(60,$y_counter);
				$this->Cell($w=40, $h=0, 'Result',$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

				$this->SetXY(100,$y_counter);
				$this->Cell($w=40, $h=0, 'Referance range',$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

				$this->SetXY(140,$y_counter);
				$this->Cell($w=20, $h=0,'Alert',$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
				
				$this->SetXY(160,$y_counter);					
				$this->Cell($w=40, $h=0,'Method',$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');	

				$this->header_y=$y_counter;
				$this->SetMargins(0,$y_counter+10);
	}

	// Page footer
	public function Footer() 
	{
		$border=1;
		$this->SetFont('courier', 'B', 10);
		$this->SetXY(10,-10);
		$this->Cell(95, $h=0, $txt='Examinations marked \'No\' are not NABL Accredited.', $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
		$this->SetFont('courier', '', 10);
		$this->SetXY(105,-10);
		$this->Cell(95, $h=0, $txt='Page:'.$this->getPageNumGroupAlias().'/'.$this->getPageGroupAlias(), $border, $ln=0, $align='R', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

		//this start at Y=127
		//210/4=52 50-20=30,70   130,170
		$border=1;
		$this->SetFont('courier', '', 10);
		$this->SetXY(10,-20);
		//$this->Cell(95, $h=10, $txt="[Completed at: ".$this->completed."] ".$this->login."               ", $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
		$this->Cell(95, $h=10, $txt=$this->login, $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
		$this->SetXY(105,-20);
		$this->Cell(95, $h=10, $txt=$this->doctor, $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

	}
}


function print_attachment_A4($pdf,$sample_id,$counter_pixell)
{
	$counter_pixel=$counter_pixell;
	$sql='select * from attachment where sample_id=\''.$sample_id.'\'';
	//echo $sql;
	$link=start_nchsls();
	if(!$result=mysql_query($sql,$link)){return FALSE;}
	$number_of_attachment=mysql_num_rows($result);
	$counter=1;
	while($array=mysql_fetch_assoc($result))
	{
		$y=print_single_attachment_A4($pdf,$array,$counter_pixel);
		if($counter==$number_of_attachment)
		{
			//echo '<tr><td align=center colspan=6>-----End of Report-----</td></tr>';
		}
		else
		{
			//echo '<h2 style="page-break-before: always;"></h2>';
			$counter++;
		}
	}
	return $y;
}

function print_single_attachment_A4($pdf,$array,$counterr)
{
	$counter=$counterr;
	
	$filetype=$array['filetype'];
	if($filetype=='jpeg' || $filetype=='jpg' || $filetype=='gif')
	{
		$image_height=75;
		if($counter+$image_height>=260){$counter=$pdf->header_y+5;$pdf->AddPage();}
		$image_file=create_file($array['file'],$array['sample_id'],$array['attachment_id'],$array['filetype']);
		$counter=$counter+10;
		//210,297
		$pdf->Image($image_file, 10, $counter,100,$image_height, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		$counter=$counter+75;
	}
	elseif($filetype=='txt')
	{
		if($counter>=260){$counter=$pdf->header_y+5;$pdf->AddPage();}		
		$pdf->SetXY(20,$counter+85);

		$pdf->Write(0,$array['file']);
	}
	else
	return $counter;
}


function print_report_pdf_A4($sample_id_array,$doctor)
{
	$acr_check_code=array(	'-1'=>'',
					'-2'=>'',
					'-3'=>'',
					'0'=>'',
					'1'=>'low absurd',
					'2'=>'high absurd',					
					'3'=>'low critical',
					'4'=>'high critical',
					'5'=>'low abnormal',
					'6'=>'high abnormal');
	//A5=210,148
	$attachment_exist='no';
	$pdf = new MYPDF_NABL('P', 'mm', 'A4', true, 'UTF-8', false);
	$pdf->sample_id_array=$sample_id_array;
	$pdf->doctor=$doctor;
	$pdf->login=$_SESSION['login'];
	//$pdf->SetHeaderMargin(30);
	//$pdf->SetFooterMargin(30);
	// set default monospaced font
	//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	//set margins
	//$pdf->SetMargins(10, 100);
	//set auto page breaks

	//$pdf->SetAutoPageBreak(TRUE, 30);

	//set image scale factor
	//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	//$pdf->SetFont('times', '', 10);
	$pdf->SetFont('courier', '', 8);
	
	//$pdf->completed='';
	foreach($sample_id_array as $value)
	{
		$pdf->sample_id=$value;
		$pdf->startPageGroup();
		$pdf->AddPage();
		
		$linkk=start_nchsls();
		$sql_examination_data='select * from examination where sample_id=\''.$pdf->sample_id.'\' order by name_of_examination';
		$result_examination_data=mysql_query($sql_examination_data,$linkk);
		$counter=$pdf->header_y+5;
		$pdf->SetFont('courier','',10);
		$border=0;
		while($examination_array=mysql_fetch_assoc($result_examination_data))
		{
			$counter=$counter+5;
			if($examination_array['id']<1000)
			{	
				$pdf->SetFont('courier','',10);
				$pdf->SetXY(10,$counter);
				$pdf->Cell($w=10, $h=0, $examination_array['NABL_Accredited'],$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
				
				$pdf->SetXY(20,$counter);
				$pdf->Cell($w=40, $h=0, $examination_array['name_of_examination'],$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

/*
				$pdf->SetXY(60,$counter);
				$pdf->Cell($w=40, $h=0, $examination_array['result'],$border, $ln=0, $align='', $fill=false,
				 $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
*/
				$pdf->SetXY(60,$counter);
//				$pdf->Cell($w=40, $h=0, $examination_array['result'],$border, $ln=0, $align='', $fill=false, $link='', 
//					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
	//public function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=false) {
				$pdf->MultiCell($w=100, $h=0, $examination_array['result'], $border, 
							$align='', $fill=false, $ln=1, 
							$x=$pdf->GetX(), $pdf->GetY(), $reseth=true, $stretch=0, $ishtml=false, 
							$autopadding=true, $maxh=0, $valign='T', $fitcell=false);

/*
				$pdf->SetXY(100,$counter);
				$pdf->Cell($w=40, $h=0, $examination_array['referance_range'].' '.$examination_array['unit'],$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

				$pdf->SetXY(140,$counter);
				$acr=$acr_check_code[check_critical_abnormal_reportable($pdf->sample_type,$examination_array['code'],$examination_array['result'])];
				$pdf->Cell($w=20, $h=0,$acr,$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
				
				$pdf->SetXY(160,$counter);					
				$pdf->Cell($w=40, $h=0,$examination_array['method_of_analysis'],$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
*/					
					
				if($counter>=260){$counter=$pdf->header_y+5;$pdf->AddPage();}
				//148=120+28 297=270+27
			}
			else
			{
				if($examination_array['id']==1008){$attachment_exist='yes';}
				$counter=$counter+2;
				$pdf->SetFont('courier', 'B', 12);
				$pdf->SetXY(10,$counter);
				$pdf->Cell($w=50, $h=0, trim($examination_array['name_of_examination'],'Z_'),$border, $ln=0, $align='', $fill=false, $link='', 
				$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
				$pdf->SetFont('courier','',10);
				$pdf->SetXY(60,$counter);
				$pdf->Cell($w=140, $h=0, $examination_array['result'],$border, $ln=0, $align='', $fill=false, $link='', 
				$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
				if($counter>=260){$counter=$pdf->header_y+5;$pdf->AddPage();}
			}

		}
		
		if($attachment_exist=='yes')
		{
			//echo '<h2 style="page-break-before: always;"></h2>';
			$y=print_attachment_A4($pdf,$pdf->sample_id,$counter);
			$counter=$y;				
		}
		
	}
	
	$pdf->Output('report.pdf', 'I');
}






?>
