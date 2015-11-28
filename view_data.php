<?php
session_start();


include 'common.php';

if(!login_varify())
{
exit();
}

main_menu();



function get_sql()
{
	$link=start_nchsls();
	if(!$result=mysql_query('select * from view_data',$link)){echo mysql_error();}
	echo '<form method=post>';
	echo '<table border=1><tr><th colspan=20>Select the data to view</th></tr>';
	
	$first_data='yes';
	
	while($array=mysql_fetch_assoc($result))
	{
		if($first_data=='yes')
		{
			echo '<tr>';
			foreach($array as $key=>$value)
			{
				echo '<th bgcolor=lightgreen>'.$key.'</th>';
			}
			echo '</tr>';
			$first_data='no';
		}
		foreach($array as $key=>$value)
		{
			if($key=='id')
			{
				echo '<td><input type=submit name=id value=\''.$value.'\'></td>';
			}
			else
			{
				echo '<td>'.$value.'</td>';
			}
		}
		echo '</tr>';

	}
	echo '</table>';
	echo '</form>';
}




echo '<h2 style="page-break-before: always;"></h2>';	
if(isset($_POST['id']))
{
	view_data($_POST['id']);
}
get_sql();
?>
