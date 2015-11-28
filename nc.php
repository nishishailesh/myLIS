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

if(!login_varify())
{
exit();
}

main_menu();


$source_of_information=array(
							'Internal Quality Control',
							'External Quality Assessment Program',
							'Internal Audit',
							'External Audit',
							'Feedback',
							'Accidental Injury report',
							'Unclassified'
							);

function read_new_nc()
{
	global $source_of_information;
	echo '<table bgcolor=lightgreen border=1><tr>';
	echo '<td colspan=10><h1>Nonconformity</h1></td></tr>';
	
	echo '<form method=post>';
	//20111231246060
	echo '<tr><td bgcolor=lightpink><input type=submit name=action value=show_nc></td><td>Select date from below</td></tr>';
	echo '<tr><td bgcolor=lightblue>Date:</td><td>';read_date_time(6);	echo '</td>';

	echo '</tr>';
	echo '		
				<tr><td bgcolor=lightblue>source_of_information</td><td>';
				mk_select_from_array_return_value('source_of_information',$source_of_information,'','');
	echo '		
				<input title=\'check for source-specific search\' name=choice type=checkbox>
	
				</td></tr>	  	 
				<tr><td bgcolor=lightblue>description</td><td><textarea name=description rows=5 cols=80></textarea></td></tr>	 
				<tr><td bgcolor=lightblue>control</td><td><textarea name=control rows=2 cols=80></textarea></tr>	 
				<tr><td bgcolor=lightblue>correction</td><td><textarea name=correction rows=2 cols=80></textarea></tr>	 
				<tr><td bgcolor=lightblue>prevention</td><td><textarea name=prevention rows=2 cols=80></textarea></tr>
				<tr><td bgcolor=lightblue>location_of_detailed_report</td><td><textarea name=location_of_detailed_report rows=1 cols=80></textarea></tr>
				<tr><td bgcolor=lightpink><input type=submit name=action value=save_new_nc></td></tr>';

	echo '</form>';
	echo '</table>';
}

function save_new_nc()
{
		global $source_of_information;
		//12345678901234
		//yyyymmddhhmmss
		$nonconformity_ID=$_POST['year'].$_POST['month'].$_POST['day'].$_POST['hour'].$_POST['minute'].$_POST['second'];
		//printf("%s",$nonconformity_id);
		//print_r($_POST);
		$sql='insert into nonconformity_record values(
				\''.$nonconformity_ID.'\',
				\''.$_POST['source_of_information'].'\',
				\''.$_POST['description'].'\',				
				\''.$_POST['control'].'\',			
				\''.$_POST['correction'].'\',			
				\''.$_POST['prevention'].'\',			
				\''.$_POST['location_of_detailed_report'].'\'							
				)';
		//echo $sql;
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link))
		{
			echo mysql_error();
		}
}


function read_edit_nc()
{
		global $source_of_information;
		$nonconformity_ID = $_POST['nonconformity_ID'];
		$description = $_POST['description'];
		$control = $_POST['control'];
		$correction = $_POST['correction']; 
		$prevention = $_POST['prevention'];
		$location_of_detailed_report = $_POST['location_of_detailed_report'];
				
		echo '<table bgcolor=lightgreen border=1><tr>';
		echo '<td colspan=10><h1>Edit Nonconformity</h1></td></tr>';
		
		echo '<form method=post>';
		//20111231246060
		echo '		<tr><td bgcolor=lightblue>nonconformity_ID</td><td><textarea readonly name=nonconformity_ID rows=1 cols=15>'.$nonconformity_ID.'</textarea></td></tr>	
					<tr><td bgcolor=lightblue>source_of_information</td><td>';
					mk_select_from_array_return_value('source_of_information',$source_of_information,'',$_POST['source_of_information']);
		echo '		</td></tr>	  	 
					<tr><td bgcolor=lightblue>description</td><td><textarea name=description rows=5 cols=80>'.$description.'</textarea></td></tr>	 
					<tr><td bgcolor=lightblue>control</td><td><textarea name=control rows=2 cols=80>'.$control.'</textarea></tr>	 
					<tr><td bgcolor=lightblue>correction</td><td><textarea name=correction rows=2 cols=80>'.$correction.'</textarea></tr>	 
					<tr><td bgcolor=lightblue>prevention</td><td><textarea name=prevention rows=2 cols=80>'.$prevention.'</textarea></tr>
					<tr><td bgcolor=lightblue>location_of_detailed_report</td><td><textarea name=location_of_detailed_report rows=1 cols=80>'.$location_of_detailed_report.'</textarea></tr>
					<tr><td bgcolor=lightpink><input type=submit name=action value=save_edit_nc></td></tr>';
		echo '</form>';
		echo '</table>';	
}

function save_edit_nc()
{
		$sql='update nonconformity_record set 
				source_of_information=\''.$_POST['source_of_information'].'\',
				description=\''.$_POST['description'].'\',				
				control=\''.$_POST['control'].'\',			
				correction=\''.$_POST['correction'].'\',			
				prevention=\''.$_POST['prevention'].'\',			
				location_of_detailed_report=\''.$_POST['location_of_detailed_report'].'\' where
				nonconformity_id=\''.$_POST['nonconformity_ID'].'\'						
				';
		//echo $sql;
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link))
		{
			echo mysql_error();
		}	
}


function delete_nc()
{
		$sql='	delete from nonconformity_record 
				where
				nonconformity_id=\''.$_POST['nonconformity_ID'].'\'						
				';
		//echo $sql;
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link))
		{
			echo mysql_error();
		}	
}


function show_nc()
{
	//if no date found
	if(isset($_POST['year']) && isset($_POST['month']))
	{
		$nonconformity_ID=$_POST['year'].$_POST['month'].'00000000';
	}
	else
	{
		$nonconformity_ID=date("Ym"."00000000");
	}
	
	//echo $nonconformity_ID;

	
	if(isset($_POST['source_of_information']) && isset($_POST['choice']))
	{
				$sql='select * from nonconformity_record where nonconformity_ID>
						\''.$nonconformity_ID.'\' and source_of_information=\''.$_POST['source_of_information'].'\' order by  nonconformity_ID  limit 50';
	}
	else
	{
				$sql='select * from nonconformity_record where nonconformity_ID>
						\''.$nonconformity_ID.'\' order by  nonconformity_ID  limit 50';
	}


		//echo $sql;
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link))
		{
			echo mysql_error();
		}

		echo '<table><tr bgcolor=lightpink><th  colspan=20  >Nonconformity Records</th></tr>
		<tr>
		<td bgcolor=lightblue>nc_ID</td>
		<td bgcolor=lightblue>source</td>
		<td bgcolor=lightblue>description</td>
		<td bgcolor=lightblue>control</td>
		<td bgcolor=lightblue>correction</td>
		<td bgcolor=lightblue>prevention</td>
		<td bgcolor=lightblue>location</td></tr>';
		
		while($array=mysql_fetch_assoc($result))
		{
		//print_r($array);
		echo '<form method=post>';
			echo '

	<tr>
	<td valign=top>	<button type=submit title="click to delete" name=action value=delete_nc>x</button>
					<button type=submit title="click to edit" name=action value=read_edit_nc>'.$array['nonconformity_ID'].'</button>
					
	
	</td>
	<input type=hidden name=nonconformity_ID value=\''.$array['nonconformity_ID'].'\'>
	<td valign=top><textarea readonly name=source_of_information rows=4 cols=20>'.$array['source_of_information'].'</textarea></td>
	<td valign=top><textarea readonly name=description rows=4 cols=40>'.$array['description'].'</textarea></td>
	<td valign=top><textarea readonly name=control rows=4 cols=20>'.$array['control'].'</textarea>
	<td valign=top><textarea readonly name=correction rows=4 cols=20>'.$array['correction'].'</textarea>
	<td valign=top><textarea readonly name=prevention rows=4 cols=20>'.$array['prevention'].'</textarea>
	<td valign=top><textarea readonly name=location_of_detailed_report rows=4 cols=20>'.$array['location_of_detailed_report'].'</textarea></tr>';
			echo '</form>';
		}

		echo '</table>';	
}



if(!isset($_POST['action']))
{
		read_new_nc();
}

else
{
	if($_POST['action']=='show_nc')
	{
		read_new_nc();
	}
	if($_POST['action']=='save_new_nc')
	{
		save_new_nc();
		read_new_nc();
	}
	if($_POST['action']=='read_edit_nc')
	{
		read_edit_nc(); //not new
	}	
	if($_POST['action']=='save_edit_nc')
	{
		save_edit_nc();
		read_new_nc();
	}
	if($_POST['action']=='delete_nc')
	{
		delete_nc();
		read_new_nc();
	}
}


	show_nc();

?>
