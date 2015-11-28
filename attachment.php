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

function read_sample_id()
{
	echo '<table  bgcolor=lightpink border=1>	
		<form method=post>';
	echo '<tr>';
	echo '<td>sample_id</td>';
	echo '<td><input type=text name=sample_id></td>';
	echo '</tr>';
	echo '<tr><td colspan=2 align=center><button type=submit name=action value=list_attachment>List Attachment</button></td></tr>';
	echo '<tr><td colspan=2 align=center><button type=submit name=action value=add_attachment>Add Attachment</button></td></tr>';
	echo '<tr><td colspan=2 align=center><button type=submit name=action value=print_attachment>Print attachment</button></td></tr>';
	echo '</form></table>';
}
//echo '<form method=post enctype=\'multipart/form-data\'>';
function list_attachment($sample_id)
{
	$sql='select * from attachment where sample_id=\''.$sample_id.'\'';
	//echo $sql;
	$link=start_nchsls();
	if(!$result=mysql_query($sql,$link)){echo 'No attachment for '.$sample_id.'<br>';return FALSE;}
	echo '<h3>List of Attachment</h3>';
	while($array=mysql_fetch_assoc($result))
	{
			echo '<table border=1 bgcolor=lightblue><form method=post>';
			echo '	<tr>
						<td>sample_id:</td>
						<td><font color=red>'.$array['sample_id'].'</font></td>
						<input type=hidden name=sample_id value=\''.$array['sample_id'].'\'>
						<input type=hidden name=attachment_id value=\''.$array['attachment_id'].'\'>';
						
			echo 		'<td align=center><button type=submit name=action value=delete_attachment>Delete Attachment</button></td>
						<td align=center><button type=submit name=action value=edit_attachment>Edit Attachment</button></td>
					</tr>';
			
			echo '<tr><td>attachment_id</td><td>'.$array['attachment_id'].'</td></tr>';
			echo '<tr><td>description</td><td>'.$array['description'].'</td></tr>';
			echo '<tr><td>filetype</td><td>'.$array['filetype'].'</td></tr>';
			$filetype=$array['filetype'];
			if($filetype=='jpeg' || $filetype=='jpg' || $filetype=='gif')
			{
				$filename=create_file($array['file'],$array['sample_id'],$array['attachment_id'],$array['filetype']);
				echo '<tr><td>file</td><td><img src="'.$filename.'" height=300 width=300></td></tr>';	
			}
			elseif($filetype=='txt')
			{
				echo '<tr><td>file</td><td><pre>'.htmlspecialchars($array['file']).'</pre></td></tr>';
			}
			else
			{
				echo '	<tr>
				<td>file</td>
				<td>cannot display</td>
				<td align=center>upload:<input type=file name=file></td>
				</tr>';
			}			
			echo '</form></table>';
	}
}



function edit_attachment($sample_id,$attachment_id)
{
	$sql='select * from attachment where sample_id=\''.$sample_id.'\' and attachment_id=\''.$attachment_id.'\'';
	//echo $sql;
	$link=start_nchsls();
	if(!$result=mysql_query($sql,$link)){echo 'No such attachment_id=\''.$attachment_id.'\' , sample_id='.$sample_id.'<br>';return FALSE;}
	$array=mysql_fetch_assoc($result);
	
			echo '<table border=1 bgcolor=lightblue><form method=post enctype=\'multipart/form-data\'>';
			echo '<tr><th>Edit Attachment</th></tr>';
			echo '	<tr>
						<td>sample_id:</td>
						<td><font color=red>'.$array['sample_id'].'</font></td>
						<input type=hidden name=sample_id value=\''.$array['sample_id'].'\'>
						<input type=hidden name=attachment_id value=\''.$array['attachment_id'].'\'>';	
			echo 		'<td align=top><button type=submit name=action value=save_attachment>Save Attachment</button></td>
					</tr>';
			echo '<tr><td>attachment_id</td><td>'.$array['attachment_id'].'</td></tr>';
			echo '<tr><td>description</td><td><input type=text name=description value=\''.$array['description'].'\'></td></tr>';
			echo '<tr><td>filetype</td><td><input type=text name=filetype value=\''.$array['filetype'].'\'></td></tr>';

			$filetype=$array['filetype'];
			if($filetype=='jpeg' || $filetype=='jpg' || $filetype=='gif')
			{
				$filename=create_file($array['file'],$array['sample_id'],$array['attachment_id'],$array['filetype']);
				echo '	<tr>
							<td>file</td>
							<td><img src="'.$filename.'" height=300 width=300></td>
							<td valign=top>upload:<input type=file name=file></td>
						</tr>';	
			}
			elseif($filetype=='txt')
			{
				echo '	<tr>
							<td>file</td>
							<td><pre>'.htmlspecialchars($array['file']).'</pre></td>
							<td valign=top>upload:<input type=file name=file></td>
						</tr>';
			}
			else
			{
				echo '	<tr>
				<td>file</td>
				<td>cannot display</td>
				<td align=center>upload:<input type=file name=file></td>
				</tr>';
			}
						
			echo '</form></table>';
}

function add_attachment($sample_id)
{
			echo '<table border=1 bgcolor=lightblue><form method=post enctype=\'multipart/form-data\'>';
			echo '<tr><th>Add Attachment</th></tr>';
			echo '	<tr>
						<td>sample_id:</td>		<td><font color=red><input type=text readonly name=sample_id value=\''.$sample_id.'\'></font></td>
						<td align=top><button type=submit name=action value=insert_attachment>Insert Attachment</button></td>
					</tr><tr>
						<td>attachment_id</td>	<td><input type=text name=attachment_id></td>
					</tr>';
			echo '<tr><td>description</td><td><input type=text name=description></td></tr>';
			echo '<tr><td>filetype</td><td><input type=text name=filetype></td></tr>';
			echo '	<tr><td valign=top>upload</td><td><input type=file name=file></td></tr>';		
			echo '</form></table>';
}

function file_to_str($file)
{
	$link=start_nchsls();
	$fd=fopen($file['tmp_name'],'r');
	$size=$file['size'];
	$str=fread($fd,$size);
	return mysql_real_escape_string($str,$link);
}

/*
   [_FILES] => Array
        (
            [file] => Array
                (
                    [name] => view_data.sql
                    [type] => text/x-sql
                    [tmp_name] => /tmp/phpoeEMAz
                    [error] => 0
                    [size] => 2202
                )

*/
function save_attachment($array,$files)
{	
if(strlen($files['file']['tmp_name'])>0)
{
		$str=file_to_str($files['file']);
		$sql='update attachment set 
				description=\''.$array['description'].'\',
				filetype=\''.$array['filetype'].'\',
				file="'.$str.'"
				where
				sample_id=\''.$array['sample_id'].'\' and 
				attachment_id=\''.$array['attachment_id'].'\'';
		//echo $sql;
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link)){echo mysql_error();}
		else
		{
			echo 'success';
		}
}
else
{
		$sql='update attachment set 
				description=\''.$array['description'].'\',
				filetype=\''.$array['filetype'].'\'
				where
				sample_id=\''.$array['sample_id'].'\' and 
				attachment_id=\''.$array['attachment_id'].'\'';
		//echo $sql;
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link)){echo mysql_error();}
		else
		{
			echo 'success';
		}
}

}

function insert_attachment($array,$files)
{
	
if(strlen($files['file']['tmp_name'])>0)
{
		$str=file_to_str($files['file']);
		$sql='insert into attachment values(
				\''.$array['sample_id'].'\', 
				\''.$array['attachment_id'].'\',
				\''.$array['description'].'\',
				\''.$array['filetype'].'\',
				\''.$str.'\')';
		//echo $sql;
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link)){echo mysql_error();}
		else
		{
			echo 'success';
		}
}
else
{
	echo 'no file to attach<br>';
}

}

function delete_attachment($sample_id,$attachment_id)
{
	$sql='delete from attachment 				
			where
				sample_id=\''.$sample_id.'\' and 
				attachment_id=\''.$attachment_id.'\'';
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link)){echo mysql_error();}
		else
		{
			echo 'success';
		}
	
}

function update_cross_reference($sample_id)
{
	$sql='select * from attachment where sample_id=\''.$sample_id.'\'';
	//echo $sql;
	$link=start_nchsls();
	if(!$result=mysql_query($sql,$link)){return FALSE;}
	$str='';
	while($array=mysql_fetch_assoc($result))
	{
		$str=$str.','.$array['attachment_id'];
	}
		insert_single_examination($sample_id,1008);
		save_single_examination($sample_id,1008,$str);
}


if(!login_varify())
{
exit();
}



main_menu();

read_sample_id();
if(isset($_POST['action']))
{
	if($_POST['action']=='list_attachment')
	{
		list_attachment($_POST['sample_id']);
	}
	if($_POST['action']=='edit_attachment')
	{
		edit_attachment($_POST['sample_id'],$_POST['attachment_id']);
	}
	if($_POST['action']=='add_attachment')
	{
		add_attachment($_POST['sample_id']);
	}
	if($_POST['action']=='save_attachment')
	{
		if(isset($_FILES))
		{
			save_attachment($_POST,$_FILES);
		}
		list_attachment($_POST['sample_id']);
	}
	if($_POST['action']=='insert_attachment')
	{
		if(isset($_FILES))
		{
			insert_attachment($_POST,$_FILES);
		}
		update_cross_reference($_POST['sample_id']);		
		list_attachment($_POST['sample_id']);
	}
	if($_POST['action']=='delete_attachment')
	{
		if(isset($_FILES))
		{
			delete_attachment($_POST['sample_id'],$_POST['attachment_id']);
		}
		update_cross_reference($_POST['sample_id']);
		list_attachment($_POST['sample_id']);
	}
	if($_POST['action']=='print_attachment')
	{
		echo '<h2 style="page-break-before: always;"></h2>';
		print_attachment($_POST['sample_id']);
	}
}


?>
