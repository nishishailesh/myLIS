<?php
session_start();

echo '<html>';
echo '<head>';
echo '</head>';
echo '<body>';

//audit data: ip,user,database,table,primary-key,what: trigger
//cron: delete everyday- data of >30 days old

/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/

include 'common.php';
function mk_form_from_table($field,$table)
{
	$link=start_nchsls();
	$sql_field='select * from '.$field;
	$sql_table='desc refrigerator_temperature';
	
	if(!$result_table=mysql_query($sql_table,$link)){return FALSE;}
	if(!$result_field=mysql_query($sql_field,$link)){return FALSE;}
	
	echo '<table border=1><form method=post>';
	echo '<tr>';
	while($array_table=mysql_fetch_assoc($result_table))
	{
		echo '<th>'.$array_table['Field'].'</th>';
	}
	echo '</tr>';

	$counter=1;
	while($array_field=mysql_fetch_assoc($result_field))
		{
			echo '<tr>';
			if(!$result_table=mysql_query($sql_table,$link)){return FALSE;}
			while($array_table=mysql_fetch_assoc($result_table))
			{
				if($array_table['Field']=='refrigerator')
				{
					echo '<td><input readonly type=text name=\''.$array_table['Field'].'_'.$counter.'\' value=\''.$array_field['refrigerator'].'\'> [Target \'C:'.$array_field['low_target'].'-'.$array_field['high_target'].']</td>';
				}
				elseif($array_table['Field']=='time')
				{
					echo '<td><input type=text name=\''.$array_table['Field'].'_'.$counter.'\' value=\''.strftime('%Y-%m-%d %H:%M').'\'></td>';
				}	
				elseif($array_table['Field']=='observer')
				{
					echo '<td><input type=text name=\''.$array_table['Field'].'_'.$counter.'\' value=\''.$_SESSION['login'].'\'></td>';
				}				
				else
				{
					echo '<td><input type=text name=\''.$array_table['Field'].'_'.$counter.'\'></td>';
				}
			}
			echo '</tr>';$counter++;
		}
	echo '<input type=submit name=submit value=save>';	
	echo '</form></table>';
	return TRUE;
}


function save_temperature($post)
{
	foreach($post as $key=>$value)
	{
		$tkn=explode('_',$key);
		//echo $tkn[1];
		if($tkn[0]=='refrigerator' && strlen($post['temperature_'.$tkn[1]])>0)
		{
			$insert_str='	insert into refrigerator_temperature (refrigerator,time,temperature,observer,comment) values(
							\''.$post['refrigerator_'.$tkn[1]].'\',
							\''.$post['time_'.$tkn[1]].'\',
							\''.$post['temperature_'.$tkn[1]].'\',
							\''.$post['observer_'.$tkn[1]].'\',							
							\''.$post['comment_'.$tkn[1]].'\')';
							
			//echo $insert_str.'<br>';
			$link=start_nchsls();
			mysql_query($insert_str,$link);
		}				
	}
}

if(!login_varify())
{
exit();
}

function show_refrigerator($time_str)
{
	$link=start_nchsls();
	$sql_field='select * from refrigerator';
	if(!$result_field=mysql_query($sql_field,$link)){return FALSE;}

	while($array_field=mysql_fetch_assoc($result_field))
	{
		$sql_table='select * from refrigerator_temperature where refrigerator=\''.$array_field['refrigerator'].'\' and time like \''.$time_str.'\'';
		
		if(!$result_table=mysql_query($sql_table,$link)){}

		if(mysql_num_rows($result_table)>=1)
		{
			$array_table=mysql_fetch_assoc($result_table);
			echo '<table border=1>';
			echo '<tr><th colspan=10 bgcolor=lightgreen>Refrigerator temperature chart</th></tr>';
			echo '<tr><th colspan=10 bgcolor=lightblue>'.$array_field['refrigerator'].'</th></tr>';
			echo '<tr><th bgcolor=lightgreen>Target:'.$array_field['low_target'].'-'.$array_field['high_target'].'</th><th bgcolor=lightpink>Period:</th><th bgcolor=lightpink>'.$time_str.'</th></tr>';
					
			foreach($array_table as $key=>$value)
			{
				if($key!='refrigerator')
				{
					echo '<th>'.$key.'</th>';
				}
			}
			echo '</tr>';

			if(!$result_table=mysql_query($sql_table,$link)){}
			if(mysql_num_rows($result_table)<1){echo mysql_error();}
			while($array_table=mysql_fetch_assoc($result_table))
			{			
				echo '<tr>';
				foreach($array_table as $key=>$value)
				{
					if($key!='refrigerator')
					{
						echo '<td>'.$value.'</td>';
					}
				}
				echo '</tr>';
			}
			echo '</table>';
			echo '<h2 style="page-break-before: always;"></h2>';
		}
	}
}



main_menu();
mk_form_from_table('refrigerator','refrigerator_temperature');

echo '<form method=post>';
echo 'Show temperature chart of following year and month';
read_date_time(2);
echo '<input type=submit name=submit value=show>';
echo '</form>';

save_temperature($_POST);


if(isset($_POST['submit']))
{
	if($_POST['submit']=='show')
	{
		show_refrigerator($_POST['year'].'-'.$_POST['month'].'-%');

	}
	else
	{
		show_refrigerator(strftime('%Y-%m-%%'));

	}
}
else
{
	show_refrigerator(strftime('%Y-%m-%%'));
}






?>
