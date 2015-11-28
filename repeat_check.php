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

function read_data()
{
	echo '<table  border=1>	
		<form method=post   action=repeat_check.php>';
	echo '<tr>';
	echo '<td>sample_id</td>';
	echo '<td><input type=text name=sample_id ></td>';	
	echo '<td>code</td>';
	echo '<td><input type=text name=code ></td>';
	echo '<td>result</td>';
	echo '<td><input type=text name=result ></td>';
	echo '<td>analysis_time</td>';
	echo '<td><input type=text name=analysis_time value=\''.strftime('%Y-%m-%d %H:%M:%S').'\' ></td>';
	echo '</tr>';
	echo '<tr><td align=center><button type=submit name=submit>submit</button></td></tr>';
	echo '</form></table>';
}

function insert_repeat($sample_id,$code,$result,$analysis_time)
	{
		$link=start_nchsls();
		$all_data=get_all_data_of_examination($code,$sample_id);
		$sql='insert into repeat_examination 
		(sample_id,code,analysis_time,result,previous_result,previous_result_analysis_time)
		values(\''.$sample_id.'\',\''.$code.'\',\''.$analysis_time.'\',\''.$result.'\',\''.$all_data['result'].'\',\''.$all_data['sample_receipt_time'].'\')';
			//echo $sql;
			if(!mysql_query($sql,$link))
			{
				echo mysql_error();
			}
					
	}

if(!login_varify())
{
exit();
}

main_menu();
if(isset($_POST['submit']))
{
	insert_repeat($_POST['sample_id'],$_POST['code'],$_POST['result'],$_POST['analysis_time']);
}
read_data();
view_data(16);


?>
