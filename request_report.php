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

function read_from_to()
{
	echo '<table  border=1>	
		<form target=_blank method=post>';
	echo '<tr>';
	echo '<td>from</td>';
	echo '<td><input type=text name=from ></td>';	
	echo '<td><input type=text name=to ></td>';	
	echo '</tr>';
	echo '<tr><td colspan=4 align=center><button type=submit name=action>Request Report</button></td></tr>';
	echo '</form></table>';
}



function get_all_ex($sample_id)
{
	$link=start_nchsls();
	$sql='select code from examination where sample_id=\''.$sample_id.'\'';

	$result=mysql_query($sql,$link);
	
	$ex_str='';
	
	while($ex=mysql_fetch_assoc($result))
	{
		$ex_str=$ex_str.','.$ex['code'];
	}
	return $ex_str;
	
}
function prepare_report($from,$to)
{
	
	$link=start_nchsls();
	$sql='select * from sample where sample_id between \''.$from.'\' and \''.$to.'\' order by section,request_id,sample_id';

	$result=mysql_query($sql,$link);
	
	$section='';
	$prev_section='';
	$start='yes';
	
	while($sample_array=mysql_fetch_assoc($result))
	{
		$section=$sample_array['section'];
		if($section!=$prev_section && $start=='no')
		{
			echo '</table>';
			echo '<h2>End of '.$prev_section.'</h2>';
			echo '<h2 style="page-break-before: always;"></h2>';
			echo '<table border=1 style="border-collapse:collapse;">';
			echo '<th colspan=10>'.$section.'</th>';
			echo '<tr>	<th>section</th>
						<th>request_id</th>
						<th>sample_id</th>
						<th>name</th>
						<th>mrd</th>
						<th>d/u/l</th>
						<th>examinations</th>
						<th>extra</th>
					</tr>';
		}
		
		if($start=='yes')
		{
			$start='no';
			//echo '<h2 style="page-break-before: always;"></h2>';
			echo '<table border=1 style="border-collapse:collapse;">';
			echo '<th colspan=10>'.$section.'</th>';
			echo '<tr>	<th>section</th>
						<th>request_id</th>
						<th>sample_id</th>
						<th>name</th>
						<th>mrd</th>
						<th>d/u/l</th>
						<th>examinations</th>
						<th>extra</th>
					</tr>';		}
		
		$all_ex=get_all_ex($sample_array['sample_id']);
		echo '<tr>
					<td>'.$sample_array['section'].'</td>
					<td>'.$sample_array['request_id'].'</td>
					<td>'.$sample_array['sample_id'].'</td>
					<td>'.$sample_array['patient_name'].'</td>
					<td>'.$sample_array['patient_id'].'</td>
					<td>'.$sample_array['clinician'].'/'.$sample_array['unit'].'/'.$sample_array['location'].'</td>
					<td>'.$all_ex.'</td>
					<td><pre>'.$sample_array['extra'].'</pre></td>
				</tr>';			
		$prev_section=$section;
	}
	echo '</table>';
	echo '<h2>End of '.$section.'</h2>';
}
if(!login_varify())
{
exit();
}



if(!isset($_POST['from']) || !isset($_POST['to']))
{
main_menu();
	read_from_to();
}

else
{
	//echo 'preparing to print requests from '.$_POST['from'].' to '.$_POST['to'];
	prepare_report($_POST['from'],$_POST['to']);
	
}


?>
