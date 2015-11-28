<?php
session_start();

echo '<html>';
echo '<head>';
echo '</head>';
echo '<body>';

/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/

include 'common.php';


/////////// Report specific data////////////////
$lab_name='New Civil Hospital Surat Laboratory Services';
$section_name='Biochemistry Section';
$address_phone='2nd Floor, Near Blood Bank, NCH Surat(Guj) Ph: 2224445 Ext:317,366';
$nabl_symbol='nabl.jpg';
$blank_symbol='blank.jpg';
$nabl_cert_no='Cert. No:X-1234';
$blank_cert_no='';
$bypass_autoverification='no';		//if 'yes'=>it will bypass autoverification

if(!login_varify())
{
exit();
}

function search_form($filename)
{
	$link=start_nchsls();
	$sql='desc sample';
	if(!$result=mysql_query($sql,$link)){echo mysql_error();}
	$tr=1;
	echo '<table border=1><form action=\''.$filename.'\' method=post>';
	echo '	<tr>
				<td title=\'1) Tickmark to include the field for search. 2) Use % as wildcard. e.g. [%esh = Mahesh,Jignesh] [Mahesh%=Mahesh,Maheshbhai, Maheshkumar]\'><input type=submit name=submit value=\'Find Critical\'></td>
				<td>Technician:</td><td>';
					mk_select_from_table('technician','','');
		  echo '</td>';
		echo   '<td>Autorized Signatory:</td><td>';
					mk_select_from_table('authorized_signatory','','');
		  echo '</td>';
		  echo '</tr>';
	while($ar=mysql_fetch_assoc($result))
	{
		if($tr%3==1){echo '<tr>';}
		
		if($ar['Field']=='sample_id')
		{
			echo '<td><input type=checkbox checked name=\'chk_from_'.$ar['Field'].'\' ></td><td>from_'.$ar['Field'].'</td>';
			echo '<td><input type=text name=\'from_'.$ar['Field'].'\' ></td>';
			echo '<td><input type=checkbox name=\'chk_to_'.$ar['Field'].'\' ></td><td>to_'.$ar['Field'].'</td>';
			echo '<td><input type=text name=\'to_'.$ar['Field'].'\' >';
			$tr++;
		}
		
		else
		{		
			echo '<td><input type=checkbox name=\'chk_'.$ar['Field'].'\' ></td><td>'.$ar['Field'].'</td><td>';
			if(!mk_select_from_table($ar['Field'],'',''))
			{
				  echo '<input type=text name=\''.$ar['Field'].'\' >';
			}
		}
		echo '</td>';
		if($tr%3==0){echo '</tr>';}
		$tr++;
	}
	echo '</form></table>';
}



//function check_critical_abnormal_reportable($sample_id,$code)
function prompt_critical_reportable($sample_id)
{
	$all_details=get_all_details_of_a_sample($sample_id);
	if($all_details==FALSE){return;}
	$critical='no';
	foreach($all_details as $ex_data)
	{
		//echo '<pre>';
		//print_r($ex_data);
		
		//$sample_type,$code,$result
		$ret=check_critical_abnormal_reportable($ex_data['sample_type'],$ex_data['code'],$ex_data['result']);
		//echo '<h1>'.$ex_data['result'].'-'.$ret.'</h1>';
		if($ret=='1' || $ret=='2' ||$ret=='4' || $ret=='3')
		{
			$critical='yes';
			$result=get_examination_result_id(1005,$sample_id);
			check_critical_reporting($sample_id);
			echo '	<tr>
					<td>'.$sample_id.'</td>
					<td>'.$ex_data['patient_id'].'</td>
					<td>'.$ex_data['patient_name'].'</td>
					<td>'.$ex_data['sample_type'].'</td>
					<td>'.$ex_data['preservative'].'</td>
					<td>'.$ex_data['code'].'</td>					
					<td>'.$ex_data['result'].'</td>
					<td>'.$ex_data['clinician'].'</td>
					<td>'.$ex_data['unit'].'</td>	
					<td>'.$ex_data['location'].'</td>
					</tr>';
					
		}

	}
	
	if($critical=='yes')
	{
		echo '	<tr>
				<td>'.$ex_data['location'].'</td>
				<td colspan=10>
					<input type=text  size="80" name=\'result_1005_'.$sample_id.'\' value=\''.$result.'\'>
				</td>
				</tr><tr bgcolor=lightpink><td colspan=10>.....</td></tr>';
	}
}


main_menu();

search_form('critical_report.php');	
echo '<h2 style="page-break-before: always;"></h2>';

$search_str='select sample_id from sample '; 
$where=array();

if(isset($_POST['submit']))
{
	foreach($_POST as $key=>$value)
	{
		if(substr($key,0,4)=='chk_' && $value=='on')
		{
			//echo substr($key,4).'='.$_POST[substr($key,4)].'<br>';
			$where[substr($key,4)]=$_POST[substr($key,4)];
		}
	}
}

//print_r($where);

$sample_id_where='';
if(isset($where['from_sample_id']) && isset($where['to_sample_id']) )
{
$sample_id_where='sample_id between  \''.$where['from_sample_id'].'\' and \''.$where['to_sample_id'].'\' ';
}
elseif(isset($where['from_sample_id']))
{
$sample_id_where=' sample_id=\''.$where['from_sample_id'].'\' ';
}
elseif(isset($where['to_sample_id']))
{
$sample_id_where=' sample_id=\''.$where['to_sample_id'].'\' ';
}

$other_wheree='';
foreach($where as $key=>$value)
{
	if($key!='from_sample_id' && $key!='to_sample_id' )
	{
		$other_wheree=$other_wheree.' '.$key.' like \''.$value.'\' and';
	}
}
$other_where=substr($other_wheree,0,-3);


if(strlen($sample_id_where)>0 && strlen($other_where)>0)
{
$search_str=$search_str.' where '.$sample_id_where.' and '.$other_where.' order by location';
}
elseif(strlen($sample_id_where)>0 && strlen($other_where)==0)
{
$search_str=$search_str.' where '.$sample_id_where.' order by location';
}
elseif(strlen($sample_id_where)==0 && strlen($other_where)>0)
{
$search_str=$search_str.' where '.$other_where.' order by location';
}

$printed=array();

if(isset($_POST['submit']) && substr($search_str,-7)!='sample ')
{
	$link=start_nchsls();
	if(!$search_result=mysql_query($search_str,$link)){echo mysql_error();}
	echo '<form method=post>';
	echo '<table border=1 bgcolor=lightblue>';
	echo '<tr><td><input type=submit name=critical value=save></td></tr>';
	while($ar=mysql_fetch_assoc($search_result))
	{
		prompt_critical_reportable($ar['sample_id']);
	}
	echo '</table>';
	echo '</form>';
}
else
{
	echo '<h1>No coditions are given for selecting records</h1>';
}

/*
             [critical] => save
            [result_1005_102907] => 2012-10-09 09:25:30=>root=>TO_XYZ
            [result_1005_102922] => 2012-10-09 09:25:05=>root=>TO_XYZ
            [result_1005_102934] => 2012-09-27 16:50:01=>root=>Not Done
            [result_1005_102952] => 2012-10-09 09:25:05=>root=>TO_XYZ
 */

if(isset($_POST['critical']))
{
	foreach($_POST as $key=>$value)
	{
		if($key!='critical')
		{
			$expl=explode('_',$key);
			save_single_examination($expl[2],$expl[1],$value);
			echo $key.'=>'.$value.'<br>';
		}
	}
}

?>
