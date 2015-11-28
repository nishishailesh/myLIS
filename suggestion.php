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

function read_suggestion($user)
{
	echo '<table  border=1>	
		<form method=post>';
	echo '<tr>';
	echo '<tr><th>Suggestions by '.$user.'</th></tr>';
	echo '<tr><td><textarea cols=80 rows=5 name=suggestion></textarea></td></tr>';	
	echo '<tr><th>Dear '.$user.', your valuable suggestions will be reviewed and action taken will be displayed soon</th></tr>';	
	echo '<tr><td colspan=2 align=center><button type=submit name=action value=save>save</button></td></tr>';
	echo '</form></table>';
}



function view_suggestion()
{
	$link=start_nchsls();

	$first_data='yes';
	
	$sql='select * from suggestion where display=\'Yes\' order by id desc limit 100';
	//echo $sql;
	if(!$result=mysql_query($sql,$link)){echo mysql_error();}
	echo '<table border=1><tr><th colspan=20>List of recent suggestions</th></tr>';
	
	$first_data='yes';


	echo '<tr>';
		echo '<th>id</th>';
		echo '<th>suggestion</th>';
		echo '<th>action_taken</th>';
	echo '</tr>';	
	while($array=mysql_fetch_assoc($result))
	{

		echo '<tr>';
			echo '<td>'.$array['id'].'</td>';
			echo '<td><textarea readonly cols=40 rows=3>'.$array['suggestion'].'</textarea></td>';
			echo '<td><textarea readonly cols=40 rows=3>'.$array['action_taken'].'</textarea></td>';
		echo '</tr>';

	}
	echo '</table>';
}

if(!login_varify())
{
	exit();
}

main_menu();



if(isset($_POST['action']) && strlen($_POST['suggestion'])>20)
{
	$link=start_nchsls();
	$sql='insert into suggestion values(\'\',\''.$_POST['suggestion'].'\',\''.$_SESSION['login'].'\',\'No action taken for the suggestion\',\'no\')';
	//echo $sql;
	if(! mysql_query($sql,$link))
	{
		echo mysql_error();
	}
	else
	{
		echo '<h3>Thanks. Your suggession is under review</h3>';
	}
}


read_suggestion($_SESSION['login']);

view_suggestion();




?>
