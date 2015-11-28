<?php 
session_start();


include 'common.php';

/*


create database attachment


CREATE TABLE `attachment` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `attachment` mediumblob NOT NULL,
 `attachment_name` varchar(200) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1

*/


function file_to_str($file,$size)
{
	$link=start_nchsls();
	$fd=fopen($file,'r');
	$str=fread($fd,$size);
	return mysql_real_escape_string($str,$link);
}


function upload_download_delete_blob()
{
	
	echo '<table><form method=post enctype=\'multipart/form-data\'>';
	foreach($_POST as $key=>$value)
	{
		if($key!='action')
		{
			echo '<tr><td>'.$key.'</td><td><input type=text readonly name=\''.$key.'\' value=\''.$value.'\'></td></tr>';	
		}
	}

	$fname=get_file_name($_POST['_attachment_id']);
	
	echo '<tr><td>File to upload</td>';
	echo '<td><input type=file name=attachment></td><td><input type=reset value=clear></td></tr>';
	echo '<tr><td>Filename</td><td><input readonly type=text name=attachment_name value=\''.$fname.'\'></td></tr>';
	echo '<tr><td colspan=5 ><input type=submit name=action value=upload_file>';	
	echo '<input type=submit name=action value=download_file>';	
	echo '<input type=submit name=action value=delete_file></td></tr></table></form>';	

}

function upload_file()
{
	
	if(strlen($_FILES['attachment']['tmp_name'])>0)
	{
		$str=file_to_str($_FILES['attachment']['tmp_name'],$_FILES['attachment']['size']);
	}
	else
	{
		echo 'nothing to upload';
		return;
	}	
	
	$link=mysql_connect('127.0.0.1',$_SESSION['login'],$_SESSION['password']);
	mysql_select_db('attachment',$link);
	
	
	if($_POST['_attachment_id']>0)	//old attachment to be replaced
	{
		$sql='update attachment set attachment=\''.$str.'\' , attachment_name=\''.$_FILES['attachment']['name'].'\' 
				where id=\''.$_POST['_attachment_id'].'\'';
		//echo $sql.'<br>';
		if(!$result=mysql_query($sql,$link)){echo mysql_error();}
		echo mysql_affected_rows($link).' rows updated in attachment table of attachment database<br>';
		//no need to update the link to original database
	}
	else
	{
		$sql='insert into attachment values(\'\', \''.$str.'\' , \''.$_FILES['attachment']['name'].'\')';
		//echo $sql.'<br>';
		if(!$result=mysql_query($sql,$link)){echo mysql_error();}
		$attachment_id=mysql_insert_id($link);
		echo 'id of new upload is '.$attachment_id.' <br>';
		
		//save link in original database
		
		$wh='';
		foreach($_POST as $key=>$value)
		{
			$chr=str_split($key);
			if($chr[0]=='_' && $chr[1]=='p' && $chr[2]=='r' && $chr[3]=='i' && $chr[4]=='_')
			{
				$field='';
				for($i=5;$i<strlen($key);$i++)
				{
					$field=$field.$chr[$i];
				}
				$wh=$wh.' `'.$field.'`=\''.$value.'\' and ';
			}
			$final_wr=substr($wh,0,-4);
		}
		
		mysql_select_db($_POST['_database'],$link);
		$sql_link='update `'.$_POST['_tablename'].'` set attach=\''.$attachment_id.'\' where '.$final_wr;
		//echo $sql_link.'<br>';
		if(!$result=mysql_query($sql_link,$link)){echo mysql_error();}
		echo mysql_affected_rows($link).' rows updated in table '.$_POST['_tablename'].' of  '.$_POST['_database'].' database<br>';
	}
	
}

function make_where($primary_key_array)
{
	//key starting from _pri_ are used to make where clause
	//include "where" by yourself
	//see if return string is zero or not
		$wh='';
		$final_wr='';
		foreach ($primary_key_array as $key=>$value)
		{
			$chr=str_split($key);
			if($chr[0]=='_' && $chr[1]=='p' && $chr[2]=='r' && $chr[3]=='i' && $chr[4]=='_')
			{
				$field='';
				for($i=5;$i<strlen($key);$i++)
				{
					$field=$field.$chr[$i];
				}
				$wh=$wh.' `'.$field.'`=\''.$value.'\' and ';
			}
			$final_wr=substr($wh,0,-4);
		}	
	//echo $final_wr;
	return $final_wr;
}



/*
 [_POST] => Array
        (
            [_database] => biochemistry
            [_tablename] => equipment_record
            [_pri_id] => 11
            [_attachment_id] => 15
            [attachment_name] => 
            [action] => download_file
        )

*/
function download_file()
{
	$link=mysql_connect('127.0.0.1',$_SESSION['login'],$_SESSION['password']);
	mysql_select_db('attachment',$link);

	$where_condition_final=make_where($_POST);
	if(strlen($where_condition_final)==0){return;}
	
	
	
	$sql='select * from attachment where id=\''.$_POST['_attachment_id'].'\'';	
	//echo $sql;
		if(!$result=mysql_query($sql,$link))
		{
			echo 'download_blob() error:'.mysql_error();
		}
		else
		{
			$result_array=mysql_fetch_assoc($result);	
			$name=$result_array['attachment_name'];
			$length=10000000000;
			$type='pdf';
			header('Content-Disposition: attachment; filename="'.$name.'"');
			header("Content-length: $length");
			header("Content-type: $type");		
			echo $result_array['attachment'];
		}
	exit(0);	
}

/*
			$result_array=mysql_fetch_assoc($result);	
			$name=$result_array[$valuee.'_name'];
			$length=10000000000;
			$type='pdf';
			header('Content-Disposition: attachment; filename=\''.$name.'\'");
			header("Content-length: $length");
			header("Content-type: $type");		
			echo $result_array[$valuee];
*/
function get_file_name($id)
{
		$sql_attachment='select * from attachment where id=\''.$id.'\'';
		
		$link=mysql_connect('127.0.0.1',$_SESSION['login'],$_SESSION['password']);
		mysql_select_db('attachment',$link);
		
		if(!$result=mysql_query($sql_attachment,$link)){echo mysql_error();}
		$ar=mysql_fetch_assoc($result);
		return $ar['attachment_name'];
}


function delete_file()
{
/*
[_POST] => Array
        (
            [_database] => biochemistry
            [_tablename] => equipment_record
            [_pri_id] => 11
            [_attachment_id] => 14
            [attachment_name] => 
            [action] => delete_file
        )
*/
	
	$wr=make_where($_POST);
	//echo $wr;
	if(strlen($wr)>0)
	{
		//echo $wr;
		
		$sql_attachment='delete from attachment where id=\''.$_POST['_attachment_id'].'\'';
		$sql_link='update `'.$_POST['_tablename'].'` set attach=\'0\' where '.$wr;
		
		//echo $sql_attachment.'<br>';
		//echo $sql_link.'<br>';
		
		$link=mysql_connect('127.0.0.1',$_SESSION['login'],$_SESSION['password']);
		mysql_select_db('attachment',$link);
		
		if(!$result=mysql_query($sql_attachment,$link)){echo mysql_error();}
		else
		{
			echo mysql_affected_rows($link).' attachment id='.$_POST['_attachment_id'].' deleted in attachment database<br>';
			mysql_select_db($_POST['_database'],$link);
			if(!$result=mysql_query($sql_link,$link)){echo mysql_error();}
		}
	}
}





if(!login_varify())
{
exit();
}



if(isset($_POST['action']))
{
		if($_POST['action']=='manage_blob')
		{
			main_menu();
			upload_download_delete_blob();
		}

		if($_POST['action']=='upload_file')
		{
			main_menu();
			upload_file();
		}
		
		if($_POST['action']=='download_file')
		{
			download_file();
		}

		if($_POST['action']=='delete_file')
		{
			main_menu();
			delete_file();
		}

}


/*

echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/

?>
