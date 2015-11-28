<?php
session_start();

/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/

include 'common.php';

if(!login_varify())
{
	exit();
}
main_menu();
if($_SESSION['login']!='root')
{
	echo '<h3>You are not allowed to use this menu item!</h3>';
	exit();
}


if(isset($_POST['submit']))
{
	if($_POST['submit']=='make_sql')
	{
		echo '<h4>You are trying to alter sample_ids of '.$_POST['year'].'-'.$_POST['month'].'</h4>';
		echo '<h4>Current year and month is '.date("y").'-'.date("m").'</h4>';
		
		//echo strlen($_POST['year']) .',' .strlen($_POST['month']).'<br>';
		
		$all_data=get_all_details_of_a_sample(1);
		//do not allow update if sample_id=1 have sample_receipt_time not matching with what you entered
		//This means that you are trying to update wrong sample ids
		if(date("y",strtotime($all_data[0]['sample_receipt_time']))==date("y") 
		&& date("m",strtotime($all_data[0]['sample_receipt_time']))==date("m"))
		{
			echo '<h5>Look at sample_id=1. The sample_receipt_time is '.$all_data[0]['sample_receipt_time'];
			echo '<h5>It is different from the month '.$_POST['month'].' or the year '.$_POST['year'].' you entered</h5>';
			echo '<h5>Varify if sample id you wish to change are for the same month or not</h5>';
		}
		
		//echo '<pre>';
		//print_r($all_data);
		//echo '</pre>';
		
		//Do not update if strings are not two digits
		else if(strlen($_POST['month'])!=2 || strlen($_POST['year'])!=2)
		{
			echo '<h5>Year and months must be in two digit format</h5>';
		}


		//can not chage if today is not first day of the month
		else if(date("d")>1 || date("H")>13)
		{
			echo '<h5>You can not change sample ids on any date other than 1st of a month, that too before 13.00 hours</h5>';			
		}


		else
		{
			//echo 'making sql....<br>';
			$sql='	update sample 
						set sample_id=sample_id+'.$_POST['year'].$_POST['month'].'000000 where sample_id<200000';
			//echo $sql.'<br>';
			
			echo '	<form method=post><table border=1>
						<tr><th>Read SQL below carefully to see correct year and month for which data needed to be altered</th></tr>
						<tr><th>Read carefully. Get cross checked by one more person</th></tr>
						<tr><th>Click \'confirm\' if everything is ok</th></tr>
						<tr><th style="color:green;">'.$sql.'</th></tr>
						<input type=hidden name=sql value=\''.$sql.'\'>
						<input type=hidden name=year value=\''.$_POST['year'].'\'>
						<input type=hidden name=month value=\''.$_POST['month'].'\'>
						<tr><td><input type=submit name=submit value=confirm></form></td></tr>
					</table></form>';
		}
	}
	else if($_POST['submit']=='confirm')
	{
		echo $_POST['sql'];
		
		$link=start_nchsls();
		if(!$result=mysql_query($_POST['sql'],$link)){echo mysql_error();}
		echo '<h3>total sample_id change='.mysql_affected_rows($link).'</h3>';
		echo '<h5>Check for successful change using some of the OPD and ward sample_id</h5>';
	}
}
else
{
	echo '<form method=post><table>
	<th colspan=2>Monthly change of sample_id</th>
	<tr><td>Year</td><td><input type=text name=year></td></tr>
	<tr><td>Month</td><td><input type=text name=month></td></tr>
	<tr><td><input type=submit name=submit value=make_sql></td></tr>
	</table></form>';
}

?>


