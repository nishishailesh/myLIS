<?php
session_start();

echo '<html>';
echo '<head>';

echo '</head>';
echo '<body>';


/*echo '<pre>';
print_r($GLOBALS);
echo '</pre>';*/


include 'common.php';

if(!login_varify())
{
exit();
}

main_menu();


$source_of_information=array(
							'Reagent',
							'Instrument',
							'Glassware/Plasticware',
							'Linen',
							'Unclassified'						
							);


function read_new_Item()
{
	global $source_of_information;
	echo '<table bgcolor=lightgreen border=1><tr>';
	echo '<td colspan=10><h1>Inventory</h1></td></tr>';
	
	echo '<form method=post>';
	//20111231246060
	echo '<tr><td bgcolor=lightpink><input type=submit name=action value=show_Item></td></tr>';
	
	echo '		
				<tr><td bgcolor=lightblue>source_of_information</td><td>';
				mk_select_from_array_return_value('source_of_information',$source_of_information,'','');
	echo '		</td></tr>	  	 
				<tr><td bgcolor=lightblue>Item_Name</td><td><textarea name=Item_Name rows=2 cols=80></textarea></td></tr>	 
				<tr><td bgcolor=lightblue>Specification</td><td><textarea name=Specification rows=5 cols=80></textarea></tr>	 
				<tr><td bgcolor=lightblue>Details</td><td><textarea name=Details rows=5 cols=80></textarea></tr>				
				<tr><td bgcolor=lightpink><input type=submit name=action value=save_new_Item></td></tr>';

	echo '</form>';
	echo '</table>';
}

function save_new_Item()
{
		global $source_of_information;
		//12345678901234
		//yyyymmddhhmmss
		//INSERT INTO `Inventory_record`(`source_of_information`, `Item_Name`, `Specification`, `Details`) VALUES ([value-1],[value-2],[value-3],[value-4])
		//printf("%s",$nonconformity_id);
		//print_r($_POST);
		$sql='insert into Inventory_record values(
				\''.$Inventory_ID.'\',
				\''.$_POST['source_of_information'].'\',
				\''.$_POST['Item_Name'].'\',				
				\''.$_POST['Specification'].'\',			
				\''.$_POST['Details'].'\'					
				)';
		
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link))
		{
			echo mysql_error();
		}
}


function read_edit_Item()
{
		global $source_of_information;
		
		$Inventory_ID = $_POST['Inventory_ID'];
		$Item_Name = $_POST['Item_Name'];
		$Specification = $_POST['Specification'];
		$Details = $_POST['Details']; 
		
				
		echo '<table bgcolor=lightgreen border=1><tr>';
		echo '<td colspan=10><h1>Edit Item</h1></td></tr>';
		
		echo '<form method=post>';
		//20111231246060
		echo '		<tr><td bgcolor=lightblue>Inventory_ID</td><td><textarea readonly name=Inventory_ID rows=1 cols=15>'.$Inventory_ID.'</textarea></td></tr>	
					<tr><td bgcolor=lightblue>source_of_information</td><td>';
					mk_select_from_array_return_value('source_of_information',$source_of_information,'','');
		echo '		</td></tr>	  	 
					<tr><td bgcolor=lightblue>Item_Name</td><td><textarea name=Item_Name rows=5 cols=80>'.$Item_Name.'</textarea></td></tr>	 
					<tr><td bgcolor=lightblue>Specification</td><td><textarea name=Specification rows=2 cols=80>'.$Specification.'</textarea></tr>	 
					<tr><td bgcolor=lightblue>Details</td><td><textarea name=Details rows=2 cols=80>'.$Details.'</textarea></tr>	 
					
					<tr><td bgcolor=lightpink><input type=submit name=action value=save_edit_Item></td></tr>';
		echo '</form>';
		echo '</table>';	
}

function save_edit_Item()
{
		$sql='update Inventory_record set 
				source_of_information=\''.$_POST['source_of_information'].'\',
				Item_Name=\''.$_POST['Item_Name'].'\',				
				Specification=\''.$_POST['Specification'].'\',			
				Details=\''.$_POST['Details'].'\'		
				 where
				Inventory_ID=\''.$_POST['Inventory_ID'].'\'						
				';
		//echo $sql;
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link))
		{
			echo mysql_error();
		}	
}


function show_Item()
{
		
		$sql='select * from Inventory_record where Inventory_ID>
				\''.$Inventory_ID.'\' order by  Inventory_ID desc  limit 100';
		//echo $sql;
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link))
		{
			echo mysql_error();
		}

		echo '<table><tr bgcolor=lightpink><th  colspan=20  >Inventory Records</th></tr>
		<tr>
		<td bgcolor=lightblue>Inventory_ID</td>
		<td bgcolor=lightblue>source</td>
		<td bgcolor=lightblue>Item_Name</td>
		<td bgcolor=lightblue>Specification</td>
		<td bgcolor=lightblue>Details</td>
		</tr>';
		
		while($array=mysql_fetch_assoc($result))
		{
		//print_r($array);
		echo '<form method=post>';
			echo '

	<tr>
	<td valign=top><button type=submit name=action value=read_edit_Item>'.$array['Inventory_ID'].'</button></td>
	<input type=hidden name=Inventory_ID value=\''.$array['Inventory_ID'].'\'>
	<td valign=top><textarea readonly name=source_of_information rows=4 cols=20>'.$array['source_of_information'].'</textarea></td>
	<td valign=top><textarea readonly name=Item_Name rows=4 cols=40>'.$array['Item_Name'].'</textarea></td>
	<td valign=top><textarea readonly name=Specification rows=4 cols=20>'.$array['Specification'].'</textarea>
	<td valign=top><textarea readonly name=Details rows=4 cols=20>'.$array['Details'].'</textarea>
	</tr>';
			echo '</form>';
		}

		echo '</table>';	
}



if(!isset($_POST['action']))
{
		read_new_Item();
}
else
{
	if($_POST['action']=='show_Item')
	{
		read_new_Item();
		show_Item();
	}
	if($_POST['action']=='save_new_Item')
	{
		save_new_Item();
		read_new_Item();
	}
	if($_POST['action']=='read_edit_Item')
	{
		read_edit_Item();
	}	
	if($_POST['action']=='save_edit_Item')
	{
		save_edit_Item();
		read_new_Item();
	}
}	


?>
