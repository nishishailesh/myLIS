<?php
session_start();

/*
Automatic opd and ward id, so must be given during sample receipt
10 digits

barcode-stick to tube and paper
if barcode not working, write full everywhere except epindorf cups

Keep manual entry option in saparate PHP


id
	mrd				SUR/15/00002345
	sample id		
					     1-100000 BI-Ward
					100001-200000 BI-OPD 
					200001-300000 CP
									200001-250000 OPD
									250001-300000 Ward
					300001-400000 HI
					400001-500000 HP
					500001-600000 CP
					600001-700000 BC
					700001-800001 SR

	examination id	
	
After a month
       YYMMXXXXXX (10 Digits)
	
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


function toggle_section_stp(self,id_of_stp_table,class_of_section_button,class_of_stp_table) {
	
	all_ = document.getElementsByClassName(class_of_stp_button);
	for(var x=0;x<all_stp.length;x++){
		all_stp[x].style.display="none";
    }	
    
    document.getElementById(id_of_sectio).style.display = "block";


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


echo '</head>';
echo '<body>';

include 'common.php';

$sample_type='Blood(Serum,Plasma)';
$location='OPD';
$unit='-';



//http://127.0.0.1/alllab/new_request_barcode_general_option2.php

function find_next_sample_id($section,$location)
{

//first and last are not included in allocation
	$sample_id_allocation=array(
							'BIO'=>array('Ward'=>array(0,100000),'OPD'=>array(100000,200000)),
							 'CP'=>array('Ward'=>array(200000,250000),'OPD'=>array(250000,300000)),							
							 'HE'=>array('Ward'=>array(300000,350000),'OPD'=>array(350000,400000)),	
							 'HP'=>array('Ward'=>array(400000,450000),'OPD'=>array(450000,500000)),	
							 'CY'=>array('Ward'=>array(500000,550000),'OPD'=>array(550000,600000)),	
							 'BEC'=>array('Ward'=>array(600000,650000),'OPD'=>array(650000,700000)),	
							 'SER'=>array('Ward'=>array(700000,750000),'OPD'=>array(750000,800000)),
							 'SK'=> array('Ward'=>array(800000,850000),'OPD'=>array(850000,900000)),
							 'IHBT'=>array('Ward'=>array(900000,950000),'OPD'=>array(950000,1000000)),	 	
								);
	
	//$ym will decide if sample_id is 10 digits or not (YYMMXXXXXX)
	$ym=0;
		
	if($location!='OPD')
	{
		$first_sample_id=$sample_id_allocation[$section]['Ward'][0];
		$last_most_sample_id=$sample_id_allocation[$section]['Ward'][1];
	}
	else
	{
		$first_sample_id=$sample_id_allocation[$section]['OPD'][0];
		$last_most_sample_id=$sample_id_allocation[$section]['OPD'][1];
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
		echo 'new_request_barcode_general_option2.php, find_next_sample_id:'.mysql_error().'<br>';
		return FALSE;
	}
	else
	{
		$max_id_array=mysql_fetch_assoc($result);
		//print_r($max_id_array);echo '<br>';
		$last_sample_id=$max_id_array['msi'];
		
		if($last_sample_id===NULL)
		{
			$next_sample_id=$first_sample_id+1;
			//echo 'next_sample_id='.$next_sample_id.'<br>';
		}
		else
		{
			//display error for non allocation of sample_id
			if($last_sample_id+1>=$last_most_sample_id) //not tested when NULL
			{
				$string='<h4>section='.$section.' and location='.$location.
				', allowed range=]'.$first_sample_id.','.$last_most_sample_id.'[,\
				 last allocated='.$last_sample_id.'\
				, free sample_id in the range</h4>';
		
	//			$string='sssss';
		///////escape from php code, break line after
		?>
		<script  type="text/javascript">
		document.getElementById('sample_list_box').innerHTML=document.getElementById('sample_list_box').innerHTML  + <?php echo '\''.$string.'\''; ?>;	
							
		</script>
		<?php
		/////////return to php code
		
				return FALSE;
			}
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
					<td colspan=10>
						<table><tr>
						<td colspan=3 >Patient Name: <input  type=text name=name> MRD: SUR/'.date("y").'/<input onblur="checkInp(\'mrd\')" type=number id=mrd name=mrd size=10 maxlength=8></td>
						<td colspan=10 >request_id:<input onblur="checkInp(\'mrd\')" type=number id=request_id name=request_id size=10 maxlength=8></td>
						<td colspan=10 >extra:<textarea id=extra name=extra rows=1 cols=40></textarea></td>
						</tr></table>
					</td>
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

		echo '				<td   style="background:lightblue;">
							<input type=hidden readonly id="selected_location" name="selected_location"/>';
							list_location();
		echo 			'</td>';
		
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



function prepare_toggle_section($sample_type,$preservative)
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


function list_scope_by_stp_section($sample_type,$preservative)
{
	$sql='select * from section';
	
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	//if(mysql_num_rows($result)==0){return;}

	
	
	while($section=mysql_fetch_assoc($result))
	{
		$section_name=$section['section'];
		$sql='select * from scope 
				where 
						sample_type=\''.$sample_type.'\' and 
						preservative=\''.$preservative.'\' and
						Available=\'yes\' and
						section=\''.$section_name.'\'
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
							<h5>'.$scope['name_of_examination'].'('.$section_name.')</h5>'.$scope['method_of_analysis'].
					'</button>
					<input type=hidden readonly id="examination|'.$scope['id'].'" name="examination|'.$scope['id'].'">';
			echo '</td>';
			if($i%5==0){echo '</tr>';}
			$i=$i+1;
		}
		echo '</table>';
		echo '</div>';		
	}
	
	

	
}

function list_scope_by_stp($sample_type,$preservative)
{
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
				//list_scope_by_stp($st_array['sample_type'],$p_array['preservative']);
				list_scope_by_stp_section($st_array['sample_type'],$p_array['preservative']);
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
					//list_scope_by_stp($sample_type,$preservative);
					list_scope_by_stp_section($sample_type,$preservative);
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
		if(($exploded_result[0]=='examination' || $exploded_result[0]=='mexamination' )&& $value=='yes')
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


/*
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
*/

function confirm_next_sample_id($section,$location)
{
	$sample_id=find_next_sample_id($section,$location);
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


function get_scope_info($ex_id)
{	
	$link=start_nchsls();
	$sql='select * from scope where id=\''.$ex_id.'\'';
	$result=mysql_query($sql,$link);if($result===FALSE){echo mysql_error(); return FALSE;}
	return $return_array=mysql_fetch_assoc($result);	
	//print_r($return_array);
}

//sample_id=YYMMxxxxxx
//separate section=separate tube

function insert_required_samples($post)
{
	//echo $post['selected_ex'];
	//$selected_ex=explode('|',$post['selected_ex']);
	
	$required_sample=array();
	//print_r($selected_ex);
	$selected_ex=analyse_examination_request($post);

//prepare array of required samples	and examinations to be done in each
	foreach($selected_ex as $value)
	{
		if(strlen($value)>0)
		{
			$ex_info=get_scope_info($value);
			//print_r($ex_info);
			$required_sample[$ex_info['section']][$ex_info['sample_type']][$ex_info['preservative']][]=$ex_info['id'];
		}
	}
	//echo '<pre>';
	//print_r($required_sample);
	//echo '</pre>';

//insert require sample and examinations to be done in each	
	$inserted_sample='';
	foreach($required_sample as $section=>$section_value)
	{
		foreach($section_value as $sample_type=>$sample_type_value)
		{
				foreach($sample_type_value as $preservative=>$preservative_value)
				{
						//echo 'insert a sample:'.$section.'-'.$sample_type.'-'.$preservative.'-'.$value.'<br>';
						$sample_id=confirm_next_sample_id($section, $post['selected_location']);
						if($sample_id===FALSE)
						{
							echo 'can not allocate sample_id: insert_required_samples($post)';
							return;
						}
						else
						{
							$inserted_sample=$inserted_sample.'|'.$sample_id;
						}	
						$st='<h4>';
							if ($preservative=='None'){$st='<h4 style="border:2px;color:red">';}
							elseif ($preservative=='Fluoride'){$st='<h4 style="border:2px;color:gray">';}
							if ($preservative=='EDTA'){$st='<h4 style="border:2px;color:purple">';}
										
						$string=$st.$section.'-'.$sample_type.'-'.$preservative.'-SAMPLE_ID='.$sample_id.'</h4>';

						?>
						<script  type="text/javascript">
						document.getElementById('sample_list_box').innerHTML=document.getElementById('sample_list_box').innerHTML  + <?php echo '\''.$string.'\''; ?>;	
						
						</script>
						<?php
						$sample_array['sample_id']=$sample_id;
						$sample_array['patient_id']='SUR/'.date("y").'/'.str_pad($post['mrd'],8,'0',STR_PAD_LEFT);
						$sample_array['patient_name']=$post['name'];
						$sample_array['clinician']=$post['selected_department'];
						$sample_array['unit']=$post['selected_unit'];
						$sample_array['location']=$post['selected_location'];
						$sample_array['sample_type']=$sample_type;
						$sample_array['preservative']=$preservative;
						$sample_array['sample_details']=$post['selected_sample_details'];
						$sample_array['urgent']='N';
						$sample_array['status']='entered';
						$sample_array['sample_receipt_time']=strftime("%Y-%m-%d %H:%M:%S");
						$sample_array['sample_collection_time']=strftime("%Y-%m-%d %H:%M:%S");
						$sample_array['section']=$section;
						$sample_array['request_id']=$post['request_id'];
						$sample_array['extra']=$post['extra'];
						
						save_sample($sample_array);
						foreach($preservative_value as $value)
						{
							//echo 'insert an examination:'.$value.'<br>';
							insert_single_examination($sample_id,$value);
						}
						edit_sample($sample_id,'','disabled','no');
						edit_examination($sample_id,'','disabled');
				}
		}

	

	}
		//echo $inserted_sample;
		
		$barcode_html='<form target=_blank  method=post action=print_sample_barcode.php><button type=submit  name=list_of_samples value=\''.$inserted_sample.'\'>Print Barcodes</button></form>';

		?>
		<script  type="text/javascript">
		// ' " with javascript and PHP mix create many problem
		document.getElementById('sample_list_box').innerHTML=document.getElementById('sample_list_box').innerHTML  + <?php echo '"'.$barcode_html.'"'; ?>;	
		</script>
		<?php	
}


function get_examination_data_grand_section()
{
	list_macro();
	list_section();
	list_stp();
	list_ex();
}

function list_section()
{
echo '<script  type="text/javascript">
function show_stp(self,id) {

	all_tabs = document.getElementsByClassName(\'b4^s\');
	for(var x=0;x<all_tabs.length;x++){
		all_tabs[x].style.backgroundColor = "lightblue";
    }
	document.getElementById(self).style.backgroundColor="pink";
    
	all_sstp = document.getElementsByClassName(\'t4^sstp\');
	for(var x=0;x<all_sstp.length;x++){
		all_sstp[x].style.display="none";
    }
    
	all_stp = document.getElementsByClassName(\'t4^s\');
	for(var x=0;x<all_stp.length;x++){
		all_stp[x].style.display="none";
    }
    document.getElementById(id).style.display = "block";
}
</script>';

	
	$sql='select section from section';
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	if(mysql_num_rows($result)==0){return;}

	echo '<table  style="background:lightgreen"><tr>';	
	while($section=mysql_fetch_assoc($result))
	{

			echo '<th colspan=20 >
				<button type=button
				style="background:lightblue;border:1;" 
				class=b4^s
				id=\'b4^s^'.$section['section'].'\'
				onclick="show_stp(\'b4^s^'.$section['section'].'\',\'t4^s^'.$section['section'].'\')">'
				.$section['section'].
				'</button>
			</th>';	
	}
	echo '</tr></table>';
}

function list_stp()
{
echo '<script  type="text/javascript">
function show_ex(self,id) {
	all_tabs = document.getElementsByClassName(\'b4^sstp\');
	for(var x=0;x<all_tabs.length;x++){
		all_tabs[x].style.backgroundColor = "lightblue";
    }
	document.getElementById(self).style.backgroundColor="pink";
    
	all_stp = document.getElementsByClassName(\'t4^stp\');
	for(var x=0;x<all_stp.length;x++){
		all_stp[x].style.display="none";
    }

	all_sstp = document.getElementsByClassName(\'t4^sstp\');
	for(var x=0;x<all_sstp.length;x++){
		all_sstp[x].style.display="none";
    }
    
    document.getElementById(id).style.display = "block";
}
</script>';

	$sql='select section from section';
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	if(mysql_num_rows($result)==0){return;}

	while($section=mysql_fetch_assoc($result))
	{
		echo '<div style="display:none;" class=t4^s id=\'t4^s^'.$section['section'].'\'';
		echo '<table  style="background-color:green;"><tr>';	
		$sql_stp='select distinct sample_type, preservative from scope where section=\''.$section['section'].'\'  order by sample_type,preservative';
		$result_stp=mysql_query($sql_stp,$link);
		if(mysql_num_rows($result_stp)>0)
		{
				while($array_stp=mysql_fetch_assoc($result_stp))
				{
					$id='sstp^'.$section['section'].'^'.$array_stp['sample_type'].'^'.$array_stp['preservative'];
					echo '<th colspan=20 >
						<button type=button
						style="background:lightblue;border:1;" 
						class=b4^sstp
						id=\'b4^'.$id.'\'
						onclick="show_ex(\'b4^'.$id.'\',\'t4^'.$id.'\')">'
						.$array_stp['sample_type'].'^'.$array_stp['preservative'].
						'</button>
					</th>';	
				}
				
		}
		echo '</tr></table>';
		echo '</div>';
	}
}






function list_ex()
{
echo  '<script  type="text/javascript">
	function toggle_ex(id,ex_id) {
				if(id.style.backgroundColor=="white")
				{
					id.style.backgroundColor="pink";
				}
				else
				{
					id.style.backgroundColor="white";
				}
				
				if(document.getElementById(ex_id).value=="no")
				{
					document.getElementById(ex_id).value="yes";
				}
				else
				{
					document.getElementById(ex_id).value="no";
				}
					
				//document.getElementById(\'selected_ex\').value=	document.getElementById(\'selected_ex\').value + "|" + ex_id;			
	}
</script>';


	$sql='select section from section';
	//echo $sql.'<br>';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	if(mysql_num_rows($result)==0){return;}

	//echo '<input type=text readonly id="selected_ex" name="selected_ex">';

	while($section=mysql_fetch_assoc($result))
	{
		$sql_stp='select distinct sample_type, preservative from scope where section=\''.$section['section'].'\' order by sample_type';
		//echo $sql_stp;
		$result_stp=mysql_query($sql_stp,$link);
		if(mysql_num_rows($result_stp)>0)
		{
			while($array_stp=mysql_fetch_assoc($result_stp))
			{
					
				$sql_ex='select * from scope where 
								section=\''.$section['section'].'\'	and
								sample_type=\''.$array_stp['sample_type'].'\' and
								preservative=\''.$array_stp['preservative'].'\' order by name_of_examination';
				//echo $sql_ex;			
				$result_ex=mysql_query($sql_ex,$link);
							
				if(mysql_num_rows($result_ex)>0)
				{
					echo '<div style="display:none;" class=t4^sstp 
					id=\'t4^sstp^'.$section['section'].'^'.$array_stp['sample_type'].'^'.$array_stp['preservative'].'\'>';
					echo '<table style="background:lightgreen;">';
					$i=1;
					while($array_ex=mysql_fetch_assoc($result_ex))
					{					
						if($i%5==1){echo '<tr>';}

						echo '<th colspan=20 >
							<button 
								onclick="toggle_ex(this,\'examination|'.$array_ex['id'].'\')" 
								type=button 
								style="width:100%;background:white;">
							<h5>'.$array_ex['name_of_examination'].'</h5>'.$array_ex['method_of_analysis'].
							'</button>
							<input type=hidden value=no readonly id="examination|'.$array_ex['id'].'" name="examination|'.$array_ex['id'].'">
						</th>';	

						if($i%5==0){echo '</tr>';}
						$i=$i+1;
					}
					
					echo '</tr></table>';
					echo '</div>';	
				}
				
			}
		}
	}
}



function list_macro()
{
	
	echo  '<script  type="text/javascript">
	function toggle_macro(id,cname) {
				if(id.style.backgroundColor=="white")
				{
					id.style.backgroundColor="pink";
				}
				else
				{
					id.style.backgroundColor="white";
				}
	
	
	xx = document.getElementsByClassName(cname);
	for(var x=0;x<xx.length;x++){
					if(xx[x].value=="yes")
					{
						xx[x].value="no";	
					}
					else
					{
						xx[x].value="yes";	
					}
					}			
					
	}
	</script>';


	$sql='select * from macro';
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	if(mysql_num_rows($result)==0){return;}

	$macro=array();

	while($ar=mysql_fetch_assoc($result))
	{
		$macro[$ar['name']][]=$ar['examination_id'];
	}


echo '<table style="background-color:pink;">';
echo '<tr><th colspan=25>Macro</th></tr>';
echo '<tr>';

$i=1;

	foreach($macro as $k=>$v)
	{
	if($i%5==1){echo '<tr>';}
		echo '<td><button	style	="width:100%;background-color:white;"
						id		="macro_'.$k.'"
						title=\''.print_r($v,true).'\'
						type	=button
						name	="macro_'.$k.'"
						onclick="toggle_macro(this,\'mmacro_'.$k.'\')" 
						value	='.$k.'>'.$k.'</button></td>';
	
		foreach($v as $vv)
		{
		echo '<input type=hidden
						value=no 
						readonly 
						id="mexamination|'.$vv.'" 
						class="mmacro_'.$k.'"
						name="mexamination|'.$vv.'">'; 
		}
	
	if($i%5==0){echo '</tr>';}
	$i=$i+1;
	}

echo '</table>';
	return $macro;
}


if(!login_varify())
{
exit();
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
		echo '<table>';
		echo '<tr>';								

		echo'				<td>
								<button type=submit name=action value=insert_new_2 style="font-size:200%;">Submit</button>
							</td>';
		
		echo '</tr>	<tr>';
		
		echo '<td><input type=text readonly name=name value=\''.$_POST['name'].'\'></td>';
		echo '<td><input type=text readonly name=mrd value=\''.$_POST['mrd'].'\'></td>';
		echo '<td><input type=text readonly name=request_id value=\''.$_POST['request_id'].'\'></td>';
		echo '<td><input type=text readonly name=selected_department  value=\''.$_POST['selected_department'].'\'></td>';
		echo '<td><input type=text readonly name=selected_unit value=\''.$_POST['selected_unit'].'\'></td>';
		echo '<td><input type=text readonly name=selected_location value=\''.$_POST['selected_location'].'\'></td>';
		echo '<td><input type=text readonly name=selected_sample_details value=\''.$_POST['selected_sample_details'].'\'></td>';
		//textarea to preserve line breaks
		echo '<td><textarea readonly name=extra >'.$_POST['extra'].'</textarea></td>';
		echo '</tr>';

		//get_examination_data_grand();
		get_examination_data_grand_section();
		echo '</form>';


		}
	}

	if($_POST['action']=='insert_new_2')
	{
		echo '<div id=sample_list_box></div>';
		
		if(insert_required_samples($_POST)===FALSE)
		{
			echo "<h3>Something was not selected/filled at all. Re enter</h3><br>";
		}
		//echo '<form method=post>';
		//get_patient_data();
		//echo '</form>';
	}
		           
}
else
{
	get_patient_data();
}

/*
echo '<pre>';
//print_r(list_macro());

print_r($_POST);
echo '</pre>';
*/

?>

