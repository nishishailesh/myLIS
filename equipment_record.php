<?php 
session_start();


echo '
<link rel="stylesheet" type="text/css" href="date/datepicker.css" /> 
<script type="text/javascript" src="date/datepicker.js"></script>
<script type="text/javascript" src="date/timepicker.js"></script>';


include 'common.php';

/*

CREATE TABLE `equipment` (
 `equipment` varchar(50) NOT NULL,
 PRIMARY KEY (`equipment`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

CREATE TABLE `equipment_record_type` (
 `equipment_record_type` varchar(100) NOT NULL,
 PRIMARY KEY (`equipment_record_type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1



CREATE TABLE `equipment_record` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `date` date NOT NULL,
 `equipment` varchar(50) NOT NULL,
 `equipment_record_type` varchar(100) NOT NULL,
 `description` varchar(5000) NOT NULL,
 `attach` int(11) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1
 


*/

function delete_equipment_record($id)
{
	$link=start_nchsls();
	$sql='delete from equipment_record where id=\''.$id.'\'';
	if(!$result=mysql_query($sql,$link)){echo mysql_error();return FALSE;}
}



function search()
{
	$link=start_nchsls();
	$sql='desc equipment_record';
	if(!$result=mysql_query($sql,$link)){echo mysql_error();}
	$tr=1;
	echo '<table border=1><form method=post>';
	echo '	<tr>
				<td 
					colspan=5 
					title=\'1) Tickmark to include the field for search. 2) Use % as wildcard.\'>
					<input type=submit name=action value=show_equipment_record></td></tr>';
					
	while($ar=mysql_fetch_assoc($result))
	{
		
		echo '<tr>';
			echo '<td><input type=checkbox name=\'chk_'.$ar['Field'].'\' ></td><td>'.$ar['Field'].'</td><td>';
			if(!mk_select_from_sql('select `'.$ar['Field'].'` from `'.$ar['Field'].'`',$ar['Field'],'',''))
			{
				if($ar['Type']=='date')
				{
					echo '<input readonly id=date class="datepicker" size="10" name=\''.$ar['Field'].'\'>';
				}
				elseif($ar['Field']=='attach')
				{
					echo 'attach';
				}
				else
				{
				  echo '<input type=text name=\''.$ar['Field'].'\' >';
				}
			}
			echo '</td>';
		echo '</tr>';
	}
	echo '</form></table>';
}

function show_equipment_record($sql)
{
	$link=start_nchsls();

	if(!$result=mysql_query($sql,$link)){echo 'show_equipment_record() error'.mysql_error(); return;}

	echo '<table border=1>';
	echo '<tr><td>Action</td><td>ID</td><td>Date</td><td>Equipment</td><td>Record Type</td><td>Description</td><td colspan=2>Attachment</td></tr>';
	while($array=mysql_fetch_assoc($result))
	{
		echo '<form method=post>';
		echo '<tr>';
		echo 	'<td>	
					<button type=submit name=action value=delete_equipment_record>X</button>
					<button type=submit name=action value=edit_equipment_record>E</button>
				</td>';
		echo '<input type=hidden name=id value=\''.$array['id'].'\'>';
		echo '<td>'.$array['id'].'</td><td>'.$array['date'].'</td><td>'.$array['equipment'].'</td><td>'.$array['equipment_record_type'].'</td><td><pre><textarea>'
				.$array['description'].'</textarea></pre></td>';		
		
		echo '</form>';
		
		echo '<td><form method=post action=manage_blob.php target=_blank>';
			manage_blob(current_database_name(),'equipment_record',array('id'=>$array['id']),$array['attach']);
		echo 'attachment id:('.$array['attach'].')';
		echo '</tr>';
	}
	echo '</table>';

}


function edit_equipment_record($id)
{
	$link=start_nchsls();

	$sql='select * from equipment_record where id=\''.$id.'\'';
	if(!$result=mysql_query($sql,$link)){echo 'edit_equipment_record() error'.mysql_error(); return;}
	$array=mysql_fetch_assoc($result);	
	
	echo '<table  border=1>	<form method=post>';
	echo '<tr>';
	echo '<tr><th colspan=5>Edit Equipment Record</th></tr>';

	echo '<tr><td>ID</td><td>';
	echo '<input type=text readonly name=id value=\''.$array['id'].'\'>';
	echo '</td><td>Unique identification</td></tr>';	
	
	echo '<tr><td>date</td><td>';
	echo '<input readonly id=date class="datepicker" size="10" name=date value=\''.$array['date'].'\'>';
	echo '</td><td>Date of recording</td></tr>';
	
	echo '<tr><td>Equipment</td><td>';
	mk_select_from_sql('select equipment from equipment','equipment','',$array['equipment']);
	echo '</td><td>Select Equipment for which record is made</td></tr>';
	
	echo '<tr><td>Record Type</td><td>';
	mk_select_from_sql('select equipment_record_type from equipment_record_type','equipment_record_type','',$array['equipment_record_type']);
	echo '</td><td>Select type of record</td></tr>';
		
	echo '<tr><td>Description</td><td><textarea name=description>'.$array['description'].'</textarea></td><td>Write observation/action taken</td></tr>';

	
	echo '<tr><td colspan=3><button type=submit name=action value=save_edit_equipment_record>save</button></td></tr>';
	echo '</form>';
	
	echo '<tr><td>Attachment</td><td><form method=post action=manage_blob.php target=_blank>';
	manage_blob(current_database_name(),'equipment_record',array('id'=>$array['id']),$array['attach']);
	echo 'attachment id:('.$array['attach'].')';
	echo '</td>';
	echo '</td><td>Insert, Edit Delete attachment</td></tr>';	
	
	echo '</form></table>';

}


function new_equipment_record()
{
	echo '<table  border=1>	<form method=post>';
	echo '<tr>';
	echo '<tr><th colspan=5>New equipment_record</th></tr>';
	
	echo '<tr><td>date</td><td>';
	echo '<input readonly id=date class="datepicker" size="10" name=date value=\''.date('Y-m-d').'\'>';
	echo '</td><td>Date of recording</td></tr>';
	
	echo '<tr><td>Equipment</td><td>';
	mk_select_from_sql('select equipment from equipment','equipment','','');
	echo '</td><td>Select Equipment for which record is made</td></tr>';
	
	echo '<tr><td>Record Type</td><td>';
	mk_select_from_sql('select equipment_record_type from equipment_record_type','equipment_record_type','','');
	echo '</td><td>Select type of record</td></tr>';
		
	echo '<tr><td>Description</td><td><textarea name=description></textarea></td><td>Write observation/action taken</td></tr>';
	echo '<tr><td>Attachment</td><td>Edit after Save</td>';
	echo '</td><td>Location of detailed report</td></tr>';
	
	echo '<tr><td><button type=submit name=action value=save_new_equipment_record>save</button></td></tr>';
	echo '</form></table>';

	view_data_sql('select * from equipment_record_type');
}

/*
  [date] => 2013-12-25
            [equipment] => Remi Research Centrifuge PR-23 C-EQ-22
            [equipment_record_type] => Contect
            [description] => hjui
            [action] => save_new_equipment_record

 `id` int(11) NOT NULL AUTO_INCREMENT,
 `date` date NOT NULL,
 `equipment` varchar(50) NOT NULL,
 `equipment_record_type` varchar(100) NOT NULL,
 `description` varchar(5000) NOT NULL,
 `attachment` mediumblob NOT NULL,
*/

function save_new_equipment_record($array)
{
	$link=start_nchsls();
	$sql='insert into equipment_record values(
			\'\',
			\''.$array['date'].'\',
			\''.$array['equipment'].'\',
			\''.$array['equipment_record_type'].'\',
			\''.$array['description'].'\',
			\'0\'
			)';
	
	//echo $sql;	
	if(!$result=mysql_query($sql,$link))
	{
		echo 'save_equipment_record()'.mysql_error();
	}
	return mysql_insert_id($link);
}

function save_edit_equipment_record($array)
{
	$link=start_nchsls();
	$sql='update equipment_record set ';
		foreach ($array as $key => $value)
		{
			if($key!='action')
			{		
				$sql=$sql.' '.$key.'=\''.$value.'\' , ';
			}
		}

	$sql=substr($sql,0,-2);
	$sql=$sql.' where id= \''.$array['id'].'\'';
	//echo $sql;

	if(!mysql_query($sql,$link)){echo mysql_error(); return FALSE;}
	return TRUE;
}


function current_database_name()
{
	$link=start_nchsls();
	$sql='select database()';
	if(!$result=mysql_query($sql,$link)){echo mysql_error();return FALSE;}
	$array=mysql_fetch_assoc($result);
	return $array['database()'];
}




if(!login_varify())
{
exit();
}
main_menu();

echo '<form method=post>';
echo '<table><tr>';
echo '<td><button type=submit name=action value=search>Search</button></td>';
echo '<td><button type=submit name=action value=new_equipment_record>New equipment_record</button></td>';
echo '</tr></table>';
echo '</form>';

if(isset($_POST['action']))
{
	if($_SESSION['login']!='root' && substr($_SESSION['login'],0,3)!='Dr.')
	{
		echo '<h2><font color=red>You are not authorized to access the menu. Thank you</font></h2>'; 
	}
	else
	{
		if($_POST['action']=='search')
		{
			search();
		}

		if($_POST['action']=='show_equipment_record')
		{
			$sql='select * from equipment_record where ';
			$where='';
			foreach($_POST as $key=>$value)
			{
				$ex=explode('_',$key);
				if($ex[0]=='chk')
				{
					$fld=substr($key,4);
					$where=$where.' `'.$fld.'` like \''.$_POST[$fld].'\' and ';
				}
			}
			$where_final=substr($where,0,-4);
			$sql=$sql.$where_final;

			//echo $sql.'<br>';
			//echo substr($sql,-6).'<br>';
			if(substr($sql,-6)!='where ')
			{
				show_equipment_record($sql);
				$_SESSION['sql']=$sql;
			}
			else
			{
				echo 'nothing to search<br>';
			}
		}


		if($_POST['action']=='edit_equipment_record')
		{
			edit_equipment_record($_POST['id']);
		}
		if($_POST['action']=='save_edit_equipment_record')
		{
			save_edit_equipment_record($_POST);
			show_equipment_record('select * from equipment_record where id=\''.$_POST['id'].'\'');
		}


		if($_POST['action']=='delete_equipment_record')
		{
			delete_equipment_record($_POST['id']);
			show_equipment_record($_SESSION['sql']);
		}

		
		if($_POST['action']=='new_equipment_record')
		{
			new_equipment_record();
		}
		
		if($_POST['action']=='save_new_equipment_record')
		{
			$new_id=save_new_equipment_record($_POST);
			echo '<h3>Search the record id \''.$new_id.'\' and Manage attachment if required</h3>';
		}
	}
}

//update_equipment_record();
//edit_equipment_record(1);
/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/

?>
