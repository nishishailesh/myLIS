<?php
session_start();

echo '<html>';
echo '<head>';
echo '</head>';
echo '<body>';

include 'common.php';

function read_password()
{
	echo '<table  border=1><form method=post>';
	echo '<tr><td>Old Password</td>			<td><input type=password name=old_password></td></tr>';	
	echo '<tr><td>New Password</td>			<td><input type=password name=password_1></td></tr>';	
	echo '<tr><td>Repeat New Password</td>	<td><input type=password name=password_2></td></tr>';	
	echo '<tr><td colspan=2 align=center><button type=submit name=action value=change_password>Change Password</button></td></tr>';
	echo '</form></table>';
}

function login_varify_again()
{
	return mysql_connect('127.0.0.1',$_SESSION['login'],$_POST['old_password']);
}

if(!login_varify())
{
	exit();
}

main_menu();

if(!isset($_POST['action']))
{
	read_password();
}

elseif($_POST['action']=='change_password')
{
	if(!login_varify_again())
	{
		echo '<h5>Wrong old password given<br></h5>';
		exit();
	}
	else
	{
		if($_POST['password_1']==$_POST['password_2'])
		{
			$sql='SET PASSWORD = PASSWORD(\''.$_POST['password_1'].'\')';
			$link=start_nchsls();
			if(!$result=mysql_query($sql,$link)){return FALSE;}
			else
			{
				echo '<h3>Password successfully changed. Logout and re-login</h3>';
			}
		}
		else
		{
			echo 'New password entered twice do not match';
		}
	}
}

/*
echo '<pre>';
print_r($_POST);
echo '</pre>';
*/

?>
