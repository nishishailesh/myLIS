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



function select_examination(classs,id,hiddenn){
	found_elements = document.getElementsByClassName(classs);
	for(var x=0;x<found_elements.length;x++){
		if(found_elements[x].id==document.getElementById(id).id)
		{
			if(found_elements[x].style.backgroundColor=="pink")
			{
				found_elements[x].style.backgroundColor="white";
				document.getElementById(hiddenn).value=\'\';	
			}
			else
			{
				found_elements[x].style.backgroundColor="pink";
				document.getElementById(hiddenn).value=\'yes\';					
			}
			
		}
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

function togglee(self,id,idd,classs,classss) {
	
	all_scope = document.getElementsByClassName(classs);
	for(var x=0;x<all_scope.length;x++){
		all_scope[x].style.display="none";
    }	
    document.getElementById(id).style.display = "block";

	all_profile = document.getElementsByClassName(classss);
	for(var x=0;x<all_profile.length;x++){
		all_profile[x].style.display="none";
    }	
    document.getElementById(idd).style.display = "block";	
	
        
	all_tabs = document.getElementsByClassName("toggle");
	for(var x=0;x<all_tabs.length;x++){
		
		all_tabs[x].style.backgroundColor = "lightblue";
		if(all_tabs[x].id==self)
		{
			all_tabs[x].style.backgroundColor="pink";
		}
    }
}

function my_radio(id,classs,hiddenn){
	all_department = document.getElementsByClassName(classs);
	for(var x=0;x<all_department.length;x++){
		all_department[x].style.backgroundColor = "lightgray";
		if(all_department[x].id==document.getElementById(id).id)
		{
			all_department[x].style.backgroundColor="pink";
			document.getElementById(hiddenn).value=all_department[x].textContent;	
		}
    }

   
}


    
    
</script>';

/*
 * 
border-color: black;
  border-style: solid;
  background: yellow;

		alert(id+hiddenn);
	
	if(document.getElementById(id).innerHTML == "<h5>"+examination+"</h5>"+method)
	{
		document.getElementById(id).innerHTML = "<h1>"+examination+"</h1>"+method;

		all_department[x].style.backgroundColor = "white";

		document.getElementById(x).value="yes";

	}
	else
	{
		document.getElementById(id).innerHTML = "<h5>"+examination+"</h5>"+method;
		document.getElementById(x).value="no";
	}

 
document.getElementById(id).style.backgroundColor="pink";
	document.getElementById(hiddenn).value=document.getElementById(id).textContent;

function my_radio(id,class,hidden){
	all_department = document.getElementsByClassName(class);
	for(var x=0;x<all_department.length;x++){
		all_department[x].innerHTML = "<h5>"+all_department[x].textContent+"</h5>";
    }

	document.getElementById(id).innerHTML= "<h2 style=\"background:lightpink;\">"+document.getElementById(department).textContent+"</h2>";
	document.getElementById(hidden).value=document.getElementById(id).textContent;
}




<button onclick="togglee('pppp')">XX</button>
<div><input id=pppp style="display:none;" type=text></div>


if(document.getElementById(id).style.display == "none")
	{
		document.getElementById(id).style.display = "block";
		
	}
	else if(document.getElementById(id).style.display == "block")
	{
		document.getElementById(id).style.display = "none";

	}	




*/

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
		echo '<button	style="width:100%;"
						id		="'.$clinician_array['clinician'].'"
						type	=button
						class	=department
						name	=department
						value	=\''.$clinician_array['clinician'].'\'';
						
				//echo	'onclick	="doo(\''.$clinician_array['clinician'].'\',\'department\',\'selected_department\')"';
				echo	'onclick	="my_radio(\''.$clinician_array['clinician'].'\',\'department\',\'selected_department\')"';
				
				echo 	'><h5>'
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
		if($i%1==1){echo '<tr>';}
		echo '<td>';
		echo '<button	style="width:100%"
						id		="'.$sample_details_array['sample_details'].'"
						type	=button
						class	=sample_details
						name	=sample_details
						value	=\''.$sample_details_array['sample_details'].'\'
						onclick	="my_radio(\''.$sample_details_array['sample_details'].'\',\'sample_details\',\'selected_sample_details\')"><h5>'
						.$sample_details_array['sample_details'].
			'</h5></button>';
		echo '</td>';
		if($i%1==0){echo '</tr>';}
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
						class	=unit
						name	=unit
						value	=\''.$unit_array['unit'].'\'
						onclick	="my_radio(\''.$unit_array['unit'].'\',\'unit\',\'selected_unit\')"><h5>'
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
						class	=location
						name	=location
						value	=\''.$location_array['location'].'\'
						onclick	="my_radio(\''.$location_array['location'].'\',\'location\',\'selected_location\')"><h5>'
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
						class	=sample_type
						name	=sample_type
						value	=\''.$sample_type_array['sample_type'].'\'
						onclick	="my_radio(\''.$sample_type_array['sample_type'].'\',\'sample_type\',\'selected_sample_type\')"><h5>'
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
						class	=preservative
						name	=preservative
						value	=\''.$preservative_array['preservative'].'\'
						onclick	="my_radio(\''.$preservative_array['preservative'].'\',\'preservative\',\'selected_preservative\')"><h5>'
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
						<td colspan=10 >Patient Name: <input  type=text name=name> MRD: SUR/'.date("y").'/<input type=number id=mrd name=mrd size=10 maxlength=8></td>
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


function get_patient_data()
{
		echo	'<table   style="background:light;"><form method=post>';
		
		echo '<tr>';								

		echo'				<td>
								<button type=submit name=action value=insert_new_1 style="font-size:200%;">Submit</button>
							</td>';
		
		echo '</tr>		
					<tr  style="background:lightpink;">
						<td colspan=10 >Patient Name: <input  type=text name=name> MRD: SUR/'.date("y").'/<input onblur="checkInp(\'mrd\')" type=number id=mrd name=mrd size=10 maxlength=8></td>
					</tr>
					<tr>';
		//echo 	'<td style="background:lightgreen;">Department</td>';
		
		echo 	'<td  style="background:lightgreen;">
							<input type=hidden readonly id="selected_department" name="selected_department"/>';
							list_department();
		echo 			'</td>';
		
		//For OPD unit=-
		//echo 	'<td   style="background:lightgray;">Unit</td>';
		//echo 	'<td   style="background:lightgray;">';
		echo '					<input type=hidden readonly value=\'-\' id="selected_unit" name="selected_unit"/>';
							//list_unit();
		//echo 			'</td>';
		
		
		//For OPD location=OPD
		//echo '				<td   style="background:lightblue;">';
		echo '					<input type=hidden readonly value=\'OPD\' id="selected_location" name="selected_location"/>';
							//list_location();
		//echo 			'</td>';
		
		echo '				<td   style="background:lightyellow;">
							<input type=hidden readonly id="selected_sample_details" name="selected_sample_details"/>';
							list_sample_details();
		echo 			'</td>';
				
		echo				'</tr></table>';
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
		echo 	'<td   style="background:lightgreen;">
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


function prepare_toggle($sample_type,$preservative)
{
		$sql='select * from scope where sample_type=\''.$sample_type.'\' and preservative=\''.$preservative.'\' 
						order by name_of_examination';
	
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	if(mysql_num_rows($result)==0){return;}
	
	
	echo '<th colspan=20 >
				
				<button type=button
				style="background:lightblue;border:1;" 
				class=toggle
				id=\''.$sample_type.'-'.$preservative.'\'
				onclick="togglee(\''.$sample_type.'-'.$preservative.'\',\''.$sample_type.'|'.$preservative.'\',\''.$sample_type.'='.$preservative.'\',\'scope_table\',\'profile_table\')">'
				.$sample_type.' | '.$preservative.
				'</button>
				
				</th>';
	
}


function list_scope_by_stp($sample_type,$preservative)
{
	//$sql='select * from scope where sample_type=\''.$sample_type.'\' and preservative=\''.$preservative.'\' 
	//					order by name_of_examination';
	//
	
	//to include only Available examinations
		$sql='select * from scope 
			where 
					sample_type=\''.$sample_type.'\' and 
					preservative=\''.$preservative.'\' and
					Available=\'yes\'
						order by name_of_examination';
						
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	//if(mysql_num_rows($result)==0){return;}
	
	echo '<div class="scope_table" style="display:none;" id="'.$sample_type.'|'.$preservative.'">';//whole table needs to be covered by div

	echo '<table style="background:lightblue;">';
	$i=1;
	while($scope=mysql_fetch_assoc($result))
	{
		if($i%5==1){echo '<tr >';}
		echo '<td>';
		echo '	<button	style="width:100%;background:white;"
						id		="'.$scope['id'].'"
						type	=button
						name	=id
						class	=examination
						value	=\''.$scope['id'].'\'
						onclick	="select_examination(
											\'examination\',
											\''.$scope['id'].'\',
											\'examination|'.$scope['id'].'\')"
				>
						<h5>'.$scope['name_of_examination'].'</h5>'.$scope['method_of_analysis'].
				'</button>
				<input type=hidden readonly id="examination|'.$scope['id'].'" name="examination|'.$scope['id'].'">';
		echo '</td>';
		if($i%5==0){echo '</tr>';}
		$i=$i+1;
	}
	echo '</table>';
	echo '</div>';

}

function list_profile_by_stp($sample_type,$preservative)
{
	$sql='select * from profile where sample_type=\''.$sample_type.'\' and preservative=\''.$preservative.'\' order by profile';
	
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	//if(mysql_num_rows($result)==0){return;}
	
	echo '<div class="profile_table" style="display:none;" id="'.$sample_type.'='.$preservative.'">';//whole table needs to be covered by div

	echo '<table style="background:lightblue;border: 2px solid black;">';
	$i=1;
	while($profile=mysql_fetch_assoc($result))
	{
		if($i%12==1){echo '<tr>';}
		echo '<td>';
		echo '<button	style	="width:100%;background:white;"
						id		="'.$profile['profile'].'"
						type	=button
						name	=profile
						class	=profile						
						value	=\''.$profile['profile'].'\'
						onclick	="select_examination(\'profile\',\''.$profile['profile'].'\',\'profile|'.$profile['profile'].'\')"><h5>'.$profile['profile'].'</h5></button>
				<input type=hidden readonly id="profile|'.$profile['profile'].'" name="profile|'.$profile['profile'].'">';
		echo '</td>';
		if($i%12==0){echo '</tr>';}
		$i=$i+1;
	}
	echo '</table>';
	echo '</div>';
}


function get_examination_data_grand()
{
	$sql_st='select * from sample_type';
	$link=start_nchsls();
	$result_st=mysql_query($sql_st,$link);

echo '<table  style="background:lightblue;"><tr style="background:lightgray;"><td>';
	echo '<button type=submit name=action style="font-size:200%;" value=insert_new_2>Save</button>';
echo '</td></tr>';
	
	
	echo '<tr><td>';	
		echo '<table border=0><tr style="background:lightblue;">';	
		while($st_array=mysql_fetch_assoc($result_st))
		{
			$sql_p='select * from preservative';
			$link=start_nchsls();
			$result_p=mysql_query($sql_p,$link);
	
			while($p_array=mysql_fetch_assoc($result_p))
			{
				prepare_toggle($st_array['sample_type'],$p_array['preservative']);
			}
		}
		echo '</table>';
echo '</td></tr><tr><td>';

	echo '<table></tr><tr><td>';
		$result_st=mysql_query($sql_st,$link);	
		while($st_array=mysql_fetch_assoc($result_st))
		{
			$sql_p='select * from preservative';
			$link=start_nchsls();
			$result_p=mysql_query($sql_p,$link);
	
			while($p_array=mysql_fetch_assoc($result_p))
			{
				list_profile_by_stp($st_array['sample_type'],$p_array['preservative']);
				list_scope_by_stp($st_array['sample_type'],$p_array['preservative']);
			}
		}

	echo '</td></tr></table>';
echo '</td></tr></table>';				
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
		echo 'confirm_next_sample_id() '.mysql_error().'<br>';
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
		
		$sample_id_array=array(); //('Blood(Plasma,Serum)|None'=>34)
		
		$link=start_nchsls();
		foreach($req as $key=>$value)
		{
			$sql='select * from scope where id=\''.$value.'\'';
			$result=mysql_query($sql,$link);if($result===FALSE){echo mysql_error(); return FALSE;}
			$return_array=mysql_fetch_assoc($result);
			
			if(array_key_exists($return_array['sample_type'].'|'.$return_array['preservative'],$sample_id_array))
			{
				$sample_id=$sample_id_array[$return_array['sample_type'].'|'.$return_array['preservative']];
				insert_single_examination($sample_id,$value);
			}
			else
			{
				$sample_id=confirm_next_sample_id($post['selected_location']);		//new sample_id inserted
				
				$sample_array['sample_id']=$sample_id;
				$sample_array['patient_id']='SUR/'.date("y").'/'.str_pad($post['mrd'],8,'0',STR_PAD_LEFT);
				$sample_array['patient_name']=$post['name'];
				$sample_array['clinician']=$post['selected_department'];
				$sample_array['unit']=$post['selected_unit'];
				$sample_array['location']=$post['selected_location'];
				$sample_array['sample_type']=$return_array['sample_type'];
				$sample_array['preservative']=$return_array['preservative'];
				$sample_array['sample_details']=$post['selected_sample_details'];
				$sample_array['urgent']='N';
				$sample_array['status']='entered';
				$sample_array['sample_receipt_time']=strftime("%Y-%m-%d %H:%M:%S");
				$sample_array['sample_collection_time']=strftime("%Y-%m-%d %H:%M:%S");
				save_sample($sample_array);												//data filled
				
				$sample_id_array[$return_array['sample_type'].'|'.$return_array['preservative']]=$sample_id;	//added in array
				insert_single_examination($sample_id,$value);							//Examination inserted
			}
			
		}


		//echo '<pre>';
		//print_r($sample_id_array);
		//echo '</pre>';

		$list_of_samples='';
		foreach($sample_id_array as $key=>$value)
		{			
			$list_of_samples=$list_of_samples.'|'.$value;
		}
		
		echo '<table><tr>';
		echo '<td>';
		
		echo '<form method=post target=_blank action=print_sample_barcode.php>';
			echo '<button  style="background:lightpink;" type=submit name=list_of_samples value=\''.$list_of_samples.'\''.'>Barcode</button>';
		echo '</form>';				
		
		echo '</td>';
		
		foreach($sample_id_array as $key=>$value)
		{			
			$list_of_samples=$list_of_samples.'|'.$value;
			if(strpos($key,"None")){$style="border:2px solid red;color:red;font-weight:bolder;";}
			else if(strpos($key,"Fluoride")){$style="border:2px solid black;color:black;font-weight:bolder;";}
			else{$style="border:2px solid black;font-color:black;";}
			
			echo '<td style=\''.$style.'\'>'.$key.'=>'.$value.'</td>';
		}
		echo '</tr></table>';
				
	
	
		echo '<div id=display>';

		foreach($sample_id_array as $key=>$value)
		{			
			edit_sample($value,'','disabled','no');
			edit_examination($value,'','disabled');
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
				strlen($_POST['selected_location'])==0	||
				strlen($_POST['selected_sample_details'])==0
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
		echo '<td><input type=text readonly name=selected_sample_details value=\''.$_POST['selected_sample_details'].'\'></td>';
		echo '</tr><tr>';
		get_examination_data_grand();
		echo '</form>';

		}
	}

	if($_POST['action']=='insert_new_2')
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

/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/
?>

