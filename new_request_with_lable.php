<?php
session_start();

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
	var x="examination_"+examination;
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
	var x="profile_"+examination;
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

</script>';

/*
if(document.getElementById(examination).innerHTML == "<h5>"+examination+"</h5>"){
		document.getElementById(examination).innerHTML = "<h2>"+examination+"</h2>"
    }
 */

echo '</head>';
echo '<body>';

include 'common.php';

$sample_type='Blood(Serum,Plasma)';
$location='OPD';
$unit='-';

function find_next_sample_id_for_OPD()
{
	$ym=date("ym");
	//echo 'year and date string='.$ym.'<br>';;
	$first_sample_id=$ym*1000000;
	$last_most_sample_id=$ym*1000000+999999;
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
		if($i%4==1){echo '<tr>';}
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
		if($i%4==0){echo '</tr>';}
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
				<input type=hidden readonly id="examination_'.$scope['code'].'" name="examination_'.$scope['code'].'">';
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
				<input type=hidden readonly id="profile_'.$profile['profile'].'" name="profile_'.$profile['profile'].'">';
		echo '</td>';
		if($i%10==0){echo '</tr>';}
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
						<td   style="background:lightgreen;">Department</td>
						<td  style="background:lightgreen;">
							<input type=hidden readonly id="selected_department" name="selected_department"/>';
							list_department();
		echo 			'</td>
						<td   style="background:lightgray;">Sample Type</td>
						<td   style="background:lightgray;">
							<input type=hidden readonly id="selected_sample_details" name="selected_sample_details"/>';
							list_sample_details();
		echo 			'</tr><tr>
						<td   style="background:lightgray;">Profile</td>
						<td   colspan=10 style="background:lightgray;">';
							list_profile();
		echo 			'</tr>
						<tr>
							<td   colspan=10 style="background:lightgray;">';
							list_scope();
		echo 				'</td>
							<td   style="background:lightblue;"><h1><button type=input style="height:100%;width:100%;padding:0;margin:0;" type=submit name=action value=insert_new><h1>Submit</button></h1></td>
						</tr>
						</table></form>';
}

/*
    [_POST] => Array
        (
            [name] => Prashantbhai
            [mrd] => 34200909
            [selected_department] => OG
            [selected_sample_details] => Fasting
            [profile_Glucose] => yes
			[examination_PCHRO] => yes
            [submit] => submit
*/
///////Main////////////
if(!login_varify())
{
exit();
}

main_menu();
echo '<h2 style="color:red;">Only for location=OPD, Unit=- sample_type=Blood(Serum,Plasma) </h2>';
//find_next_sample_id_for_OPD();
//if(isset($_POST['submit']
get_patient_and_sample_data();

echo '<pre>';
print_r($GLOBALS);
echo '</pre>';

?>
