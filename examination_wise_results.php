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

function select_examination_for_results($filename)
{
	$link=start_nchsls();
	$sql='select code from scope group by code order by code';
	//echo $sql;
	$result=mysql_query($sql,$link);

	echo '<table border=1 id=\'id\' bgcolor=lightgrey CELLPADDING=0 CELLSPACING=0>';
	echo '<form method=post action=\''.$filename.'\'>';
	echo '	<tr>
				<td colspan=4>from_sample_id:<input type=text name=from_sample_id></td>
				<td colspan=4>to_sample_id:<input type=text name=to_sample_id></td>
			</tr>
			<tr>
				<th colspan=18 align=left>Select Examination code</th>
			</tr>';
		$counter=1;
	while($ar=mysql_fetch_assoc($result))
	{
			if($counter%6==1){echo '<tr>';}
			foreach ($ar as $key => $value)   //for every row in scope
			{
						echo '<td nowrap><button type=submit name=\''.$key.'\' value=\''.$value.'\'>'.$value.'</button></td>';
			}
			if($counter%6==0){echo '</tr>';}
			$counter++;
	}
	echo '</form></table>';

}	

function read_examination_wise_results($from_sample_id,$to_sample_id,$code,$filename)
{
		$link=start_nchsls();
		$counter=1;
		echo '<table border=1>';
		echo '<form method=post action=\''.$filename.'\'>';
		echo '<th colspan=18>Results for '.$code.' from '.$from_sample_id.' to '.$to_sample_id.'</th>';
		$sql='select sample_id,result from examination where sample_id between \''.$from_sample_id.'\' and  \''.$to_sample_id.'\' and code=\''.$code.'\'';
		//echo $sql;
		echo '<tr><td colspan=20><input type=submit name=submit value=save_result></td></tr>';
		echo '<tr><td><input type=hidden name=code value=\''.$code.'\'></td></tr>';
		$result=mysql_query($sql,$link);		
		while($post_array=mysql_fetch_assoc($result))
		{
			if($counter%6==1){echo '<tr>';}
			echo '<td>';
			echo $post_array['sample_id'].'</td><td><input size=5 type=text name=\''.$post_array['sample_id'].'\' value=\''.$post_array['result'].'\'';
			echo '</td>';			
			if($counter%6==0){echo '</tr>';}
			$counter++;
		}
		echo '</form>';
		echo '</table>';
}

if(!login_varify())
{
exit();
}
main_menu();
echo '<h2 style="page-break-before: always;">Examination wise result entry</h2>';	
if(isset($_POST['from_sample_id']) && isset($_POST['to_sample_id']))
{
	read_examination_wise_results($_POST['from_sample_id'],$_POST['to_sample_id'],$_POST['code'],'examination_wise_results.php');
}
elseif(isset($_POST['submit']))
{
	if($_POST['submit']=='save_result')
	{
		$link=start_nchsls();
		$counter=0;
		foreach($_POST as $key=>$value)
		{
			if($key!='submit')
			{
			$sql='update examination set result=\''.$value.'\' where sample_id=\''.$key.'\' and code=\''.$_POST['code'].'\'';
			if(!mysql_query($sql,$link)){echo mysql_error();}
			else{$counter=$counter+mysql_affected_rows($link);}
			}
		}
		echo $counter.' results updated<br>';
	}
}
else
{
	select_examination_for_results('examination_wise_results.php');
}

?>
