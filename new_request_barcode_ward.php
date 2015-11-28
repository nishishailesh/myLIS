<?php
session_start();

//change profile T6-T10 as integer
//Change profile name to be diff from examination code

echo '<html>';
echo '<head>';

echo '<script  type="text/javascript">
function doo(department,name_of_list,selected_department){
	all_department = document.getElementsByName(name_of_list);
	for(var x=0;x<all_department.length;x++){
		all_department[x].innerHTML = "<h5>"+all_department[x].textContent+"</h5>";
    }

	document.getElementById(department).innerHTML= "<h2 style=\"background:lightpink;\">"+document.getElementById(department).textContent+"</h2>";
	document.getElementById(selected_department).value=document.getElementById(department).textContent;
}


function select_examination(examination){
	var x="examination|"+examination;
	if(document.getElementById(examination).innerHTML == "<h5>"+examination+"</h5>")
	{
		document.getElementById(examination).innerHTML = "<h1>"+examination+"</h1>";
		document.getElementById(x).value="yes";

	}
	else
	{
		document.getElementById(examination).innerHTML = "<h5>"+examination+"</h5>";
		document.getElementById(x).value="no";
	}
		
}

function select_profile(examination){
	var x="profile|"+examination;
	if(document.getElementById(examination).innerHTML == "<h5>"+examination+"</h5>")
	{
		document.getElementById(examination).innerHTML = "<h1>"+examination+"</h1>";
		document.getElementById(x).value="yes";

	}
	else
	{
		document.getElementById(examination).innerHTML = "<h5>"+examination+"</h5>";
		document.getElementById(x).value="no";
	}
		
}

function showhide() {
	if(document.getElementById("showhide").innerHTML == "show")
	{
		document.getElementById("display").style.display = \'block\';
		document.getElementById("showhide").innerHTML = \'hide\';
	}
	else if(document.getElementById("showhide").innerHTML == "hide")
	{
		document.getElementById("display").style.display = \'none\';
		document.getElementById("showhide").innerHTML = \'show\';
	}	
    
}

</script>';


echo '</head>';
echo '<body>';

include 'common.php';

$sample_type='Blood(Serum,Plasma)';
//$location='OPD';
//$unit='-';

function find_next_sample_id_for_ward()
{
	//$ym=date("ym");
	//echo 'year and date string='.$ym.'<br>';;
	//$first_sample_id=    	$ym*1000000+100000;
	//$last_most_sample_id=	$ym*1000000+999999;
	$first_sample_id=0;
	$last_most_sample_id=99999;
	
	//echo 'first_sample_id='.$first_sample_id.'<br>';
	//echo 'last_most_sample_id='.$last_most_sample_id.'<br>';
	$sql='select max(sample_id) msi from sample 
				where 
					sample_id>\''.$first_sample_id.'\' and 
					sample_id<\''.$last_most_sample_id.'\'';
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	if($result===FALSE)
	{
		echo 'new_request_with_lable.php, find_next_sample_id_for_OPD():'.mysql_error().'<br>';
		return FALSE;
	}
	else
	{
		$max_id_array=mysql_fetch_assoc($result);
		//print_r($max_id_array);echo '<br>';
		$last_sample_id=$max_id_array['msi'];
		//echo 'last_sample_id='.$last_sample_id.'<br>';

		if($last_sample_id===NULL)
		{
			$next_sample_id=$first_sample_id+1;
			//echo 'next_sample_id='.$next_sample_id.'<br>';
		}
		else
		{
			$next_sample_id=$last_sample_id+1;
			//echo 'next_sample_id='.$next_sample_id.'<br>';
		}
	}
	return $next_sample_id;
}




function find_next_sample_id($location)
{
	//$ym=date("ym");
	//echo 'year and date string='.$ym.'<br>';
	
	if($location!='OPD')
	{
		$first_sample_id=0;
		$last_most_sample_id=99999;
	}
	else
	{
		$first_sample_id=100000;
		$last_most_sample_id=200000;
	}
	
	//echo 'first_sample_id='.$first_sample_id.'<br>';
	//echo 'last_most_sample_id='.$last_most_sample_id.'<br>';
	$sql='select max(sample_id) msi from sample 
				where 
					sample_id>\''.$first_sample_id.'\' and 
					sample_id<\''.$last_most_sample_id.'\'';
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	if($result===FALSE)
	{
		echo 'new_request_with_lable.php, find_next_sample_id_for_OPD():'.mysql_error().'<br>';
		return FALSE;
	}
	else
	{
		$max_id_array=mysql_fetch_assoc($result);
		//print_r($max_id_array);echo '<br>';
		$last_sample_id=$max_id_array['msi'];
		//echo 'last_sample_id='.$last_sample_id.'<br>';

		if($last_sample_id===NULL)
		{
			$next_sample_id=$first_sample_id+1;
			//echo 'next_sample_id='.$next_sample_id.'<br>';
		}
		else
		{
			$next_sample_id=$last_sample_id+1;
			//echo 'next_sample_id='.$next_sample_id.'<br>';
		}
	}
	return $next_sample_id;
}


function confirm_next_sample_id($location)
{
	$sample_id=find_next_sample_id($location);
	$link=start_nchsls();
	if(! mysql_query('insert into sample (sample_id) values (\''.$sample_id.'\')',$link))
	{
		echo 'confirm_next_sample_id() '.mysql_error().'<br>';
		return FALSE;
	}
	else
	{
		return $sample_id;
	}
}

function list_department()
{
	$sql='select * from clinician order by clinician';
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	echo '<table>';
	$i=1;
	while($clinician_array=mysql_fetch_assoc($result))
	{
		if($i%2==1){echo '<tr>';}
		echo '<td>';
		echo '<button	style="width:100%"
						id		="'.$clinician_array['clinician'].'"
						type	=button
						name	=department
						value	=\''.$clinician_array['clinician'].'\'
						onclick	="doo(\''.$clinician_array['clinician'].'\',\'department\',\'selected_department\')"><h5>'
						.$clinician_array['clinician'].
			'</h5></button>';
		echo '</td>';
		if($i%2==0){echo '</tr>';}
		$i=$i+1;
	}
	echo '</table>';
}


function list_sample_details()
{
	$sql='select * from sample_details';
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	echo '<table>';
	$i=1;
	while($sample_details_array=mysql_fetch_assoc($result))
	{
		echo '<tr>';
		echo '<td>';
		echo '<button	style="width:100%"
						id		="'.$sample_details_array['sample_details'].'"
						type	=button
						name	=sample_details
						value	=\''.$sample_details_array['sample_details'].'\'
						onclick	="doo(\''.$sample_details_array['sample_details'].'\',\'sample_details\',\'selected_sample_details\')"><h5>'
						.$sample_details_array['sample_details'].
			'</h5></button>';
		echo '</td>';
		echo '</tr>';
		$i=$i+1;
	}
	echo '</table>';
}


function list_scope()
{
	global $sample_type;
	
	$sql='select * from scope where sample_type=\''.$sample_type.'\' order by code';
	
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	echo '<table   style="background:lightblue;">';
	$i=1;
	while($scope=mysql_fetch_assoc($result))
	{
		if($i%15==1){echo '<tr>';}
		echo '<td>';
		echo '<button	title=\''.$scope['name_of_examination'].'\' style="width:100%"
						id		="'.$scope['code'].'"
						type	=button
						name	=code
						value	=\''.$scope['code'].'\'
						onclick	="select_examination(\''.$scope['code'].'\')"><h5>'.$scope['code'].'</h5></button>
				<input type=hidden readonly id="examination|'.$scope['code'].'" name="examination|'.$scope['code'].'">';
		echo '</td>';
		if($i%15==0){echo '</tr>';}
		$i=$i+1;
	}
	echo '</table>';
}

function list_profile()
{
	global $sample_type;
	$sql='select * from profile where sample_type=\''.$sample_type.'\' ';
	
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	echo '<table   style="background:lightyellow;">';
	$i=1;
	while($profile=mysql_fetch_assoc($result))
	{
		if($i%10==1){echo '<tr>';}
		echo '<td>';
		echo '<button	style	="width:100%"
						id		="'.$profile['profile'].'"
						type	=button
						name	=profile
						value	=\''.$profile['profile'].'\'
						onclick	="select_profile(\''.$profile['profile'].'\')"><h5>'.$profile['profile'].'</h5></button>
				<input type=hidden readonly id="profile|'.$profile['profile'].'" name="profile|'.$profile['profile'].'">';
		echo '</td>';
		if($i%10==0){echo '</tr>';}
		$i=$i+1;
	}
	echo '</table>';
}

function list_unit()
{
	$sql='select * from unit';
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	echo '<table>';
	$i=1;
	while($unit_array=mysql_fetch_assoc($result))
	{
		//echo '<tr>';
		if($i%1==1){echo '<tr>';}
		echo '<td>';
		echo '<button	style="width:100%"
						id		="'.$unit_array['unit'].'"
						type	=button
						name	=unit
						value	=\''.$unit_array['unit'].'\'
						onclick	="doo(\''.$unit_array['unit'].'\',\'unit\',\'selected_unit\')"><h5>'
						//doo(department,name_of_list,selected_department)
						.$unit_array['unit'].
			'</h5></button>';
		echo '</td>';
		if($i%1==0){echo '</tr>';}
		//echo '</tr>';
		$i=$i+1;
	}
	echo '</table>';
}


function list_location()
{
	$sql='select * from location order by location';
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	echo '<table>';
	$i=1;
	while($location_array=mysql_fetch_assoc($result))
	{
		//echo '<tr>';
		if($i%4==1){echo '<tr>';}
		echo '<td>';
		echo '<button	style="width:100%"
						id		="'.$location_array['location'].'"
						type	=button
						name	=location
						value	=\''.$location_array['location'].'\'
						onclick	="doo(\''.$location_array['location'].'\',\'location\',\'selected_location\')"><h5>'
						//doo(department,name_of_list,selected_department)
						.$location_array['location'].
			'</h5></button>';
		echo '</td>';
		if($i%4==0){echo '</tr>';}
		//echo '</tr>';
		$i=$i+1;
	}
	echo '</table>';
}


function get_patient_and_sample_data()
{
		echo	'<table   style="background:light;"><form method=post>
					<tr  style="background:lightpink;">
						<td colspan=10 >Patient Name: <input  type=text name=name> MRD: SUR/'.date("y").'/<input onblur="checkInp(\'mrd\')" type=number id=mrd name=mrd size=10 maxlength=8></td>
					</tr>
					<tr>
					
						<td  style="background:lightgreen;">
							<input type=hidden readonly id="selected_department" name="selected_department"/>';
							list_department();
		echo 			'</td>';

		echo 			'
						<td   style="background:lightgray;">
							<input type=hidden readonly id="selected_unit" name="selected_unit"/>';
							list_unit();							
							
		echo 			'</td>
						
						<td   style="background:lightblue;">
							<input type=hidden readonly id="selected_location" name="selected_location"/>';
							list_location();
		echo 			'
						<td   style="background:lightgray;">
							<input type=hidden readonly id="selected_sample_details" name="selected_sample_details"/>';
							list_sample_details();
							
		echo 			'</td></tr><tr>
						<td colspan=10>';
						//////
			echo 			'<table border=0><tr>
							<td style="background:lightgreen;">';
								list_profile();
								
			echo 			'</td><td rowspan=2><button type=input style="height:100%;width:100%;padding:0;margin:0;" 
								type=submit name=action value=insert_new><h1>Submit</h1></button>';
			echo 			'</td></tr>
							<tr>
								<td style="background:lightgray;">';
								list_scope();
			echo 			'</td></tr></table>';
						/////////			
		echo			'</td>
							
						</tr>
						</table></form>';
}

/*
             [name] => Jiteshbhai
            [mrd] => 34556678
            [selected_department] => Emergency Medicine
            [selected_sample_details] => Post_Prendial

            [profile_Protein_Electrophoresis] => yes
            [profile_RFT] => yes
           
           
            [examination_CHOH] => 
            [examination_CHOL] => 
            [action] => insert_new

*/
///////Main////////////
if(!login_varify())
{
exit();
}

function get_code_from_id($id)
{
	$link=start_nchsls();
	$sql='select id,code from scope where id=\''.$id.'\'';
	$result=mysql_query($sql,$link);
	$return_array=mysql_fetch_assoc($result);
	return $return_array['code'];
}

function get_id_from_code($code, $sample_type, $preservative)
{
	$link=start_nchsls();
	$sql='select id from scope where code=\''.$code.'\' and sample_type=\''.$sample_type.'\' and preservative=\''.$preservative.'\'';
	$result=mysql_query($sql,$link);
	$return_array=mysql_fetch_assoc($result);
	return $return_array['id'];
}

function analyse_examination_request($post)
{
	
	$requested_examination=array();
	
	foreach($post as $key=>$value)
	{
		$exploded_result=explode("|",$key);
		//print_r($exploded_result);
		if($exploded_result[0]=='examination' && $value=='yes')
		{
			if(in_array($exploded_result[1],$requested_examination)===FALSE)
			{
				$requested_examination[]=$exploded_result[1];
			}
		}
		
		if($exploded_result[0]=='profile' && $value=='yes')
		{
			$link=start_nchsls();
			$sql='select * from profile where profile=\''.$exploded_result[1].'\'';
			$result=mysql_query($sql,$link);
				
			while($profile_row=mysql_fetch_assoc($result))
			{
				foreach ($profile_row as $key => $value)   //for every row in profile
				{
					if($key!='profile' && $key!='sample_type' && $key!='preservative')
					{
						if($value>0)
						{
							$temp_code=get_code_from_id($value);
							if(in_array($temp_code,$requested_examination)===FALSE)
							{
								$requested_examination[]=$temp_code;
							}
						}
					}
				}
			}
		}
	}

return $requested_examination;
}

function find_required_tubes($requested_examination)
{
	$total_examination=count($requested_examination);
	
	if($total_examination==0){return FALSE;}				//no request		no	sample
	else if($total_examination==1)
	{
		 if($requested_examination[0]=='GLC'){return 'F';}	//only glucose		one fluoride
		 else{return 'P';}									//only non glucose	one plain
	 }
	else
	{	 
		if(array_search('GLC',$requested_examination)===FALSE)
		{
			return 'P';										//only non-glucose	one plain
		}
		else
		{
			return 'PF';									//mixed				one plain + one fluoride
		}
	}
}

function confirm_next_sample_id_for_ward()
{
	$sample_id=find_next_sample_id_for_ward();
	$link=start_nchsls();
	if(! mysql_query('insert into sample (sample_id) values (\''.$sample_id.'\')',$link))
	{
		echo mysql_error();
		return FALSE;
	}
	else
	{
		return $sample_id;
	}
}

function insert_required_samples($post)
{
	global $sample_type,$location,$unit;
	
	echo '<button type=button  style="background:lightpink;" id=showhide onclick="showhide()">hide</button>';
	echo '<div id=display>';
		$req=analyse_examination_request($_POST);
	
		//echo '<pre>';
		//echo 'Reuqested Examinations';
		//print_r($req);
		//echo '</pre>';
		$req_tube=find_required_tubes($req);
		if($req_tube===FALSE){return FALSE;}
		//echo 'required tubes:'.$req_tube.'<br>';
		
	
		if($req_tube=='P')
		{
			$plain_sample_id=confirm_next_sample_id($post['selected_location']);
			if($plain_sample_id===FALSE)
			{
				return FALSE;
			}
		}
		else if($req_tube=='F')
		{
			$fluoride_sample_id=confirm_next_sample_id($post['selected_location']);
			if($fluoride_sample_id===FALSE)
				{
					return FALSE;
				}
		}
		else if($req_tube=='PF')
		{
			$fluoride_sample_id=confirm_next_sample_id($post['selected_location']);
			$plain_sample_id=confirm_next_sample_id($post['selected_location']);

			if($plain_sample_id===FALSE || $fluoride_sample_id===FALSE)
			{
				return FALSE;
			}			
		}
	
	

		$sample_array=array();

		if(isset($fluoride_sample_id))
		{
			$sample_array['sample_id']=$fluoride_sample_id;
			$sample_array['patient_id']='SUR/'.date("y").'/'.str_pad($post['mrd'],8,'0',STR_PAD_LEFT);
			$sample_array['patient_name']=$post['name'];
			$sample_array['clinician']=$post['selected_department'];
			$sample_array['unit']=$post['selected_unit'];
			$sample_array['location']=$post['selected_location'];
			$sample_array['sample_type']=$sample_type;
			$sample_array['preservative']='Fluoride';
			$sample_array['sample_details']=$post['selected_sample_details'];
			$sample_array['urgent']='N';
			$sample_array['status']='entered';
			$sample_array['sample_receipt_time']=strftime("%Y-%m-%d %H:%M:%S");			
			$sample_array['sample_collection_time']=strftime("%Y-%m-%d %H:%M:%S");
			
			save_sample($sample_array);
			$id=get_id_from_code('GLC',$sample_array['sample_type'],$sample_array['preservative']);
			insert_single_examination($sample_array['sample_id'],$id);
			$ex_list='GLC';
echo '<table style="background:lightblue;"><tr><td>';			
			echo '<form method=post target=_blank action=print_sample_barcode.php>';
			echo '<input type=hidden name=patient_name value=\''.$sample_array['patient_name'].'\'>';
			echo '<input type=hidden name=patient_id value=\''.$sample_array['patient_id'].'\'>';
			echo '<input type=hidden name=ex_list value=\''.$ex_list.'\'>';
			echo '<input type=hidden name=tube value=\'F\'>';			
			echo '<button style="background:lightpink;" type=submit name=sample_id value=\''.$sample_array['sample_id'].'\'>Barcode-'.$sample_array['sample_id'].'</button>';
			echo '</form>';	

			echo '<form method=post target=_blank action=edit_request.php>';
			echo '<input type=hidden name=sample_id value=\''.$sample_array['sample_id'].'\'>';
			echo '<button style="background:lightgreen;" type=submit name=action value=edit_sample>Edit-'.$sample_array['sample_id'].'</button>';
			echo '</form></td><td>';	

			
			edit_sample($sample_array['sample_id'],'','disabled','no');
			edit_examination($sample_array['sample_id'],'','disabled');
echo '</td></tr></table>';
		}
	

		if(isset($plain_sample_id))
		{
			$sample_array['sample_id']=$plain_sample_id;
			$sample_array['patient_id']='SUR/'.date("y").'/'.str_pad($post['mrd'],8,'0',STR_PAD_LEFT);
			$sample_array['patient_name']=$post['name'];
			$sample_array['clinician']=$post['selected_department'];
			$sample_array['unit']=$post['selected_unit'];
			$sample_array['location']=$post['selected_location'];
			$sample_array['sample_type']=$sample_type;
			$sample_array['preservative']='None';
			$sample_array['sample_details']=$post['selected_sample_details'];
			$sample_array['urgent']='N';
			$sample_array['status']='entered';
			$sample_array['sample_receipt_time']=strftime("%Y-%m-%d %H:%M:%S");
			$sample_array['sample_collection_time']=strftime("%Y-%m-%d %H:%M:%S");
			
			save_sample($sample_array);
			$ex_list='';
			foreach($req as $key=>$value)
				{
					if($value!='GLC')
					{
						$id=get_id_from_code($value,$sample_array['sample_type'],$sample_array['preservative']);
						insert_single_examination($sample_array['sample_id'],$id);
						$ex_list=$ex_list.','.$value;
					}
				}

echo '<table style="background:lightblue;"><tr><td>';
			echo '<form method=post target=_blank action=print_sample_barcode.php>';
			echo '<input type=hidden name=patient_name value=\''.$sample_array['patient_name'].'\'>';
			echo '<input type=hidden name=patient_id value=\''.$sample_array['patient_id'].'\'>';
			echo '<input type=hidden name=ex_list value=\''.$ex_list.'\'>';
			echo '<input type=hidden name=tube value=\'S\'>';			
			echo '<button  style="background:lightpink;" type=submit name=sample_id value=\''.$sample_array['sample_id'].'\'>Barcode-'.$sample_array['sample_id'].'</button>';
			echo '</form>';			

			echo '<form method=post target=_blank action=edit_request.php>';
			echo '<input type=hidden name=sample_id value=\''.$sample_array['sample_id'].'\'>';
			echo '<button style="background:lightgreen;" type=submit name=action value=edit_sample>Edit-'.$sample_array['sample_id'].'</button>';
			echo '</form></td><td>';
						
			edit_sample($sample_array['sample_id'],'','disabled','no');
			edit_examination($sample_array['sample_id'],'','disabled');
echo '</td></tr></table>';
		}
		
		
	
	echo '</div>';
}

main_menu();
//find_next_sample_id_for_OPD();
if(isset($_POST['action']))
{
	if($_POST['action']=='insert_new')
	{
		if(	is_str_digit($_POST['mrd'])!==FALSE 		&& 
			strlen($_POST['name'])!=0 					&&
			strlen($_POST['selected_department'])!=0 	&&
			strlen($_POST['selected_sample_details'])!=0) 		
		{
			if(strlen($_POST['mrd'])<=8)
			{
				if(insert_required_samples($_POST)===FALSE)
				{
					echo '<h2 style="color:red;">No examination selected or sample id  can not allocated</h2>';
				}
			}
			else
			{
				echo '<h2 style="color:red;">MRD number must be 8 or less digit</h2>';
			}
		}
		else
		{
			echo '<h2 style="color:red;">Name:(not blank) MRD:(only digits, not blank) (dept:select) (sample_details:select)</h2>';
		}
	}
}


	get_patient_and_sample_data();
	echo '<h2 style="color:red;">Only for sample_type=Blood(Serum,Plasma) </h2>';
	echo '<h2 style="color:red;">Never refresh the page</h2>';


/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/
?>
