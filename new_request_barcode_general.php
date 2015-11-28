<?php
session_start();

/*
Automatic opd and ward id, so must be given during sample receipt
10 digits

barcode-stick to tube and paper
if barcode not working, write full everywhere except epindorf cups

Keep manual entry option in saparate PHP

*/


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


function select_examination(examination,method,id){
	var x="examination|"+id;
	if(document.getElementById(id).innerHTML == "<h5>"+examination+"</h5>"+method)
	{
		document.getElementById(id).innerHTML = "<h1>"+examination+"</h1>"+method;
		document.getElementById(x).value="yes";

	}
	else
	{
		document.getElementById(id).innerHTML = "<h5>"+examination+"</h5>"+method;
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
$location='OPD';
$unit='-';

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
		//echo '<tr>';
		if($i%2==1){echo '<tr>';}
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
		if($i%2==0){echo '</tr>';}
		//echo '</tr>';
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
		if($i%5==1){echo '<tr>';}
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
		if($i%5==0){echo '</tr>';}
		//echo '</tr>';
		$i=$i+1;
	}
	echo '</table>';
}

function list_sample_type()
{
	$sql='select * from sample_type';
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	echo '<table>';
	$i=1;
	while($sample_type_array=mysql_fetch_assoc($result))
	{
		//echo '<tr>';
		if($i%1==1){echo '<tr>';}
		echo '<td>';
		echo '<button	style="width:100%"
						id		="'.$sample_type_array['sample_type'].'"
						type	=button
						name	=sample_type
						value	=\''.$sample_type_array['sample_type'].'\'
						onclick	="doo(\''.$sample_type_array['sample_type'].'\',\'sample_type\',\'selected_sample_type\')"><h5>'
						//doo(department,name_of_list,selected_department)
						.$sample_type_array['sample_type'].
			'</h5></button>';
		echo '</td>';
		if($i%1==0){echo '</tr>';}
		//echo '</tr>';
		$i=$i+1;
	}
	echo '</table>';
}

function list_preservative()
{
	$sql='select * from preservative';
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	echo '<table>';
	$i=1;
	while($preservative_array=mysql_fetch_assoc($result))
	{
		//echo '<tr>';
		if($i%1==1){echo '<tr>';}
		echo '<td>';
		echo '<button	style="width:100%"
						id		="'.$preservative_array['preservative'].'"
						type	=button
						name	=preservative
						value	=\''.$preservative_array['preservative'].'\'
						onclick	="doo(\''.$preservative_array['preservative'].'\',\'preservative\',\'selected_preservative\')"><h5>'
						//doo(department,name_of_list,selected_department)
						.$preservative_array['preservative'].
			'</h5></button>';
		echo '</td>';
		if($i%1==0){echo '</tr>';}
		//echo '</tr>';
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
		if($i%9==1){echo '<tr>';}
		echo '<td>';
		echo '<button	style	="width:100%"
						id		="'.$profile['profile'].'"
						type	=button
						name	=profile
						value	=\''.$profile['profile'].'\'
						onclick	="select_profile(\''.$profile['profile'].'\')"><h5>'.$profile['profile'].'</h5></button>
				<input type=hidden readonly id="profile|'.$profile['profile'].'" name="profile|'.$profile['profile'].'">';
		echo '</td>';
		if($i%9==0){echo '</tr>';}
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
					<tr>';
		//echo 	'<td style="background:lightgreen;">Department</td>';
		
		echo 	'<td  style="background:lightgreen;">
							<input type=hidden readonly id="selected_department" name="selected_department"/>';
							list_department();
		echo 			'</td>';
		//echo 	'<td   style="background:lightgray;">Unit</td>';
		echo 	'<td   style="background:lightgray;">
							<input type=hidden readonly id="selected_unit" name="selected_unit"/>';
							list_unit();
		echo 			'</td>';
		//				<td   style="background:lightgray;">location</td>
		echo '				<td   style="background:lightgray;">
							<input type=hidden readonly id="selected_location" name="selected_location"/>';
							list_location();
		echo 			'</td>';								
/*							
		echo 	'<td   style="background:lightgray;">
							<input type=hidden readonly id="selected_sample_type" name="selected_sample_type"/>';
							list_sample_type();
		echo 			'</td>';
		echo 	'<td   style="background:lightgray;">
							<input type=hidden readonly id="selected_preservative" name="selected_preservative"/>';
							list_preservative();
		echo 			'</td>';									
*/
		echo 			'</tr><tr>';

		echo 			'</td>';
						//<td   style="background:lightgray;">Sample Type</td>

		echo '<tr><td colspan=10>';
		//======================
		echo '<table><tr>';
		echo 	'<td style="background:lightgray;">
							<input type=hidden readonly id="selected_sample_details" name="selected_sample_details"/>';
							list_sample_details();
		
		//echo			'<td   style="background:lightgray;">Profile</td>';
		echo '				<td   colspan=10 style="background:lightgray;">';
							list_profile();
		echo'				<td   style="background:lightblue;"><h1><button type=input style="height:100%;width:100%;padding:0;margin:0;" type=submit name=action value=insert_new><h1>Submit</button></h1></td>';
		
		echo '</tr></table>';
		//========================
		echo '</td></tr>';
		echo 			'</tr>
						<tr>
							<td   colspan=10 style="background:lightgray;">';
							list_scope();
		echo 				'</td>
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


function get_patient_data()
{
		echo	'<table   style="background:light;"><form method=post>
					<tr  style="background:lightpink;">
						<td colspan=10 >Patient Name: <input  type=text name=name> MRD: SUR/'.date("y").'/<input onblur="checkInp(\'mrd\')" type=number id=mrd name=mrd size=10 maxlength=8></td>
					</tr>
					<tr>';
		//echo 	'<td style="background:lightgreen;">Department</td>';
		
		echo 	'<td  style="background:lightgreen;">
							<input type=hidden readonly id="selected_department" name="selected_department"/>';
							list_department();
		echo 			'</td>';
		//echo 	'<td   style="background:lightgray;">Unit</td>';
		echo 	'<td   style="background:lightgray;">
							<input type=hidden readonly id="selected_unit" name="selected_unit"/>';
							list_unit();
		echo 			'</td>';
		//				<td   style="background:lightblue;">location</td>
		echo '				<td   style="background:lightblue;">
							<input type=hidden readonly id="selected_location" name="selected_location"/>';
							list_location();
		echo 			'</td></tr><tr>';								

		echo'				<td   style="background:lightyellow;">
								<h1><button type=input style="height:100%;width:100%;padding:0;margin:0;" 
								type=submit name=action value=insert_new_1><h1>Submit</button>
								</h1>
							</td>';
		
		echo '</tr></table>';
		//========================
		echo '</form>';
}

function get_sample_data()
{
		echo	'<table   style="background:light;">
					<tr  style="background:lightpink;">';

		echo 	'<td   style="background:lightgray;">
							<input type=hidden readonly id="selected_sample_type" name="selected_sample_type"/>';
							list_sample_type();
		echo 			'</td>';
		echo 	'<td   style="background:lightpink;">
							<input type=hidden readonly id="selected_preservative" name="selected_preservative"/>';
							list_preservative();
		echo 			'</td>';									
		echo 			'<td style="background:lightblue;">
							<input type=hidden readonly id="selected_sample_details" name="selected_sample_details"/>';
							list_sample_details();		
		echo 			'</td>';
		echo 			'</tr><tr>';

		echo 			'</td>';
		echo'				<td   style="background:lightyellow;"><h1>
							<button type=input style="height:100%;width:100%;padding:0;margin:0;" 
							type=submit name=action value=insert_new_2><h1>Submit</button></h1></td>';

		echo 			'</tr></table>';

}




function list_scope_by_stp($sample_type,$preservative)
{
	$sql='select * from scope where sample_type=\''.$sample_type.'\' and preservative=\''.$preservative.'\' 
						order by name_of_examination';
	
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	if(mysql_num_rows($result)==0){return;}
	echo '<table   style="background:lightblue;">';
	echo '<tr><th colspan=20>sample_type='.$sample_type.' presevative='.$preservative.'</th></tr>';
	$i=1;
	while($scope=mysql_fetch_assoc($result))
	{
		if($i%5==1){echo '<tr>';}
		echo '<td>';
		echo '<button	style="width:100%"
						id		="'.$scope['id'].'"
						type	=button
						name	=id
						value	=\''.$scope['id'].'\'
						onclick	="select_examination(
											\''.$scope['name_of_examination'].'\',
											\''.$scope['method_of_analysis'].'\',
\''.$scope['id'].'\')"><h5>'.$scope['name_of_examination'].'</h5>'.$scope['method_of_analysis'].'</button>
				<input type=hidden readonly id="examination|'.$scope['id'].'" name="examination|'.$scope['id'].'">';
		echo '</td>';
		if($i%5==0){echo '</tr>';}
		$i=$i+1;
	}
	echo '</table>';
}

function list_profile_by_stp($sample_type,$preservative)
{
	global $sample_type;
	$sql='select * from profile where sample_type=\''.$sample_type.'\' and preservative=\''.$preservative.'\' order by profile';
	
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	echo '<table   style="background:lightyellow;">';
	$i=1;
	while($profile=mysql_fetch_assoc($result))
	{
		if($i%9==1){echo '<tr>';}
		echo '<td>';
		echo '<button	style	="width:100%"
						id		="'.$profile['profile'].'"
						type	=button
						name	=profile
						value	=\''.$profile['profile'].'\'
						onclick	="select_profile(\''.$profile['profile'].'\')"><h5>'.$profile['profile'].'</h5></button>
				<input type=hidden readonly id="profile|'.$profile['profile'].'" name="profile|'.$profile['profile'].'">';
		echo '</td>';
		if($i%9==0){echo '</tr>';}
		$i=$i+1;
	}
	echo '</table>';
}


function get_examination_data_grand()
{
	$sql_st='select * from sample_type';
	$link=start_nchsls();
	$result_st=mysql_query($sql_st,$link);
	while($st_array=mysql_fetch_assoc($result_st))
	{
		$sql_p='select * from preservative';
		$link=start_nchsls();
		$result_p=mysql_query($sql_p,$link);
		while($p_array=mysql_fetch_assoc($result_p))
		{
			list_scope_by_stp($st_array['sample_type'],$p_array['preservative']);
		}
	}
}


function get_examination_data($sample_type,$preservative)
{
		echo	'<table   style="background:light;">';
		echo'		<tr><td   style="background:lightblue;">
			
				<button type=input " 
				type=submit name=action value=insert_new_3><h1>Save + Same Patient</h1></button>
			
				<button type=submit name=action value=insert_new_4><h1>Save + New Patient</h1></button></td>
			
				</h1></td>';
		echo 		'</tr>';
		echo 	'<tr>';
		echo '		<td   style="background:lightgray;">';
					list_profile_by_stp($sample_type,$preservative);
		echo 		'</td><tr>
					<td   colspan=10 style="background:lightgray;">';
					list_scope_by_stp($sample_type,$preservative);
		echo 		'</td>
					</tr>';


		echo 		'</table>';
}



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
/*
            [name] => ABC
            [mrd] => 345
            [selected_department] => Dentistry
            [selected_unit] => 1
            [selected_location] => E4(510)
            [selected_sample_type] => Blood(Serum,Plasma)
            [selected_preservative] => Fluoride
            [selected_preservative] => Random
*/
	if(	strlen($post['name'])==0 				||
		strlen($post['mrd'])==0 				||
		is_str_digit($post['mrd'])==FALSE		|| 
		strlen($post['selected_department'])==0 ||
		strlen($post['selected_unit'])==0 ||
		strlen($post['selected_location'])==0 ||
		strlen($post['selected_sample_type'])==0 ||
		strlen($post['selected_preservative'])==0 ||
		strlen($post['selected_sample_details'])==0
		)
		{echo "<h3>Something was not selected at all</h3><br>";return $requested_examination;}



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
							if(in_array($value,$requested_examination)===FALSE)
							{
								$requested_examination[]=$value;
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

function confirm_next_sample_id($location)
{
	$sample_id=find_next_sample_id($location);
	$link=start_nchsls();
	if(! mysql_query('insert into sample (sample_id) values (\''.$sample_id.'\')',$link))
	{
		echo 'confirm_next_sample_id_for_OPD() '.mysql_error().'<br>';
		return FALSE;
	}
	else
	{
		return $sample_id;
	}
}

function insert_required_samples($post)
{
	echo '<button type=button  style="background:lightpink;" id=showhide onclick="showhide()">hide</button>';
	
		$req=analyse_examination_request($post);
		if(count($req)==0){return FALSE;};
		
		//echo '<pre>';
		//echo 'Reuqested Examinations';
		//print_r($req);
		//echo '</pre>';
		
		$sample_id=confirm_next_sample_id($post['selected_location']);
		
		echo '<div id=display>';
		$sample_array=array();

		if(isset($sample_id))
		{
			$sample_array['sample_id']=$sample_id;
			$sample_array['patient_id']='SUR/'.date("y").'/'.str_pad($post['mrd'],8,'0',STR_PAD_LEFT);
			$sample_array['patient_name']=$post['name'];
			$sample_array['clinician']=$post['selected_department'];
			$sample_array['unit']=$post['selected_unit'];
			$sample_array['location']=$post['selected_location'];
			$sample_array['sample_type']=$post['selected_sample_type'];
			$sample_array['preservative']='None';
			$sample_array['sample_details']=$post['selected_sample_details'];
			$sample_array['urgent']='N';
			$sample_array['status']='entered';
			$sample_array['sample_receipt_time']=strftime("%Y-%m-%d %H:%M:%S");
			$sample_array['sample_collection_time']=strftime("%Y-%m-%d %H:%M:%S");
			
			if(save_sample($sample_array)===FALSE)
			{
				echo '<h4>Can not insert the last ID found. Retry</h4>';
				return FALSE;
			}

			$ex_list='';
			foreach($req as $key=>$value)
			{
				insert_single_examination($sample_array['sample_id'],$value);
				$ex_list=$ex_list.','.get_code_from_id($value);
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


if(isset($_POST['action']))
{

	if($_POST['action']=='insert_new_1')
	{
			if(	strlen($_POST['name'])==0 				||
				strlen($_POST['mrd'])==0 				||
				is_str_digit($_POST['mrd'])==FALSE		|| 
				strlen($_POST['selected_department'])==0||
				strlen($_POST['selected_unit'])==0 		||
				strlen($_POST['selected_location'])==0
				)
		{
			echo "<h3>Something was not selected/filled at all. Re enter</h3><br>";
			get_patient_data();
		}
		else
		{
		echo '<form method=post>';
		echo '<table><tr>';
		echo '<td><input type=text readonly name=name value=\''.$_POST['name'].'\'></td>';
		echo '<td><input type=text readonly name=mrd value=\''.$_POST['mrd'].'\'></td>';
		echo '<td><input type=text readonly name=selected_department  value=\''.$_POST['selected_department'].'\'></td>';
		echo '<td><input type=text readonly name=selected_unit value=\''.$_POST['selected_unit'].'\'></td>';
		echo '<td><input type=text readonly name=selected_location value=\''.$_POST['selected_location'].'\'></td>';
		echo '</tr></table>';
		get_sample_data();
		echo '</form>';
		}
	}


	if($_POST['action']=='insert_new_2')
	{
		if(	
			strlen($_POST['selected_sample_type'])==0 	||
			strlen($_POST['selected_preservative'])==0 	||
			strlen($_POST['selected_sample_details'])==0
			)
		{
			echo "<h3>Something was not selected/filled at all. Re enter</h3><br>";
			echo '<form method=post>';
			echo '<table><tr>';
			echo '<td><input type=text readonly name=name value=\''.$_POST['name'].'\'></td>';
			echo '<td><input type=text readonly name=mrd value=\''.$_POST['mrd'].'\'></td>';
			echo '<td><input type=text readonly name=selected_department  value=\''.$_POST['selected_department'].'\'></td>';
			echo '<td><input type=text readonly name=selected_unit value=\''.$_POST['selected_unit'].'\'></td>';
			echo '<td><input type=text readonly name=selected_location value=\''.$_POST['selected_location'].'\'></td>';
			echo '</tr></table>';
			get_sample_data();
			echo '</form>';
		}
		else
		{
			echo '<form method=post>';
			echo '<table><tr>';
			echo '<td><input type=text readonly name=name value=\''.$_POST['name'].'\'></td>';
			echo '<td><input type=text readonly name=mrd value=\''.$_POST['mrd'].'\'></td>';
			echo '<td><input type=text readonly name=selected_department  value=\''.$_POST['selected_department'].'\'></td>';
			echo '<td><input type=text readonly name=selected_unit value=\''.$_POST['selected_unit'].'\'></td>';
			echo '<td><input type=text readonly name=selected_location value=\''.$_POST['selected_location'].'\'></td>';
			echo '</tr><tr><td><input type=text readonly name=selected_sample_type value=\''.$_POST['selected_sample_type'].'\'></td>';
			echo '<td><input type=text readonly name=selected_preservative value=\''.$_POST['selected_preservative'].'\'></td>';
			echo '<td><input type=text readonly name=selected_sample_details value=\''.$_POST['selected_sample_details'].'\'></td>';
			echo '</tr></table>';
			get_examination_data($_POST['selected_sample_type'],$_POST['selected_preservative']);
			echo '</form>';
		}
	}
	            
	if($_POST['action']=='insert_new_3')
	{
		if(insert_required_samples($_POST)===FALSE)
		{
			echo "<h3>Something was not selected/filled at all. Re enter</h3><br>";
			echo '<form method=post>';
			echo '<table><tr>';
			echo '<td><input type=text readonly name=name value=\''.$_POST['name'].'\'></td>';
			echo '<td><input type=text readonly name=mrd value=\''.$_POST['mrd'].'\'></td>';
			echo '<td><input type=text readonly name=selected_department  value=\''.$_POST['selected_department'].'\'></td>';
			echo '<td><input type=text readonly name=selected_unit value=\''.$_POST['selected_unit'].'\'></td>';
			echo '<td><input type=text readonly name=selected_location value=\''.$_POST['selected_location'].'\'></td>';
			echo '</tr><tr><td><input type=text readonly name=selected_sample_type value=\''.$_POST['selected_sample_type'].'\'></td>';
			echo '<td><input type=text readonly name=selected_preservative value=\''.$_POST['selected_preservative'].'\'></td>';
			echo '<td><input type=text readonly name=selected_sample_details value=\''.$_POST['selected_sample_details'].'\'></td>';
			echo '</tr></table>';
			get_examination_data($_POST['selected_sample_type'],$_POST['selected_preservative']);
			echo '</form>';
		}
		else
		{
			echo '<form method=post>';
			echo '<table><tr>';
			echo '<td><input type=text readonly name=name value=\''.$_POST['name'].'\'></td>';
			echo '<td><input type=text readonly name=mrd value=\''.$_POST['mrd'].'\'></td>';
			echo '<td><input type=text readonly name=selected_department  value=\''.$_POST['selected_department'].'\'></td>';
			echo '<td><input type=text readonly name=selected_unit value=\''.$_POST['selected_unit'].'\'></td>';
			echo '<td><input type=text readonly name=selected_location value=\''.$_POST['selected_location'].'\'></td>';
			echo '</tr></table>';
			get_sample_data();
			echo '</form>';
		}
	}

	if($_POST['action']=='insert_new_4')
	{
		if(insert_required_samples($_POST)===FALSE)
		{
			echo "<h3>Something was not selected/filled at all. Re enter</h3><br>";
		}
		echo '<form method=post>';
		get_patient_data();
		echo '</form>';
	}
		           
}
else
{
	get_patient_data();
}

//get_examination_data_grand();
/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/
?>
