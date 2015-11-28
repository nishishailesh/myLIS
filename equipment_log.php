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
	$sql_field='select * from `'.$field.'`';
	$sql_table='desc `'.$table.'`';
	
	if(!$result_table=mysql_query($sql_table,$link)){echo mysql_error();return FALSE;}
	if(!$result_field=mysql_query($sql_field,$link)){echo mysql_error();return FALSE;}
	
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
				if($array_table['Field']=='equipment-log')
				{
					echo '	<td>
								<table><tr>
								<td>
									<input readonly type=text name=\''.$array_table['Field'].'_'.$counter.'\' value=\''.$array_field['equipment-log'].'\'>
								</td>
								<td><font size=0>
									[Acceptable:'.$array_field['acceptable'].']
									</font>
								</td>
								</tr></table>
							</td>';
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
		if($tkn[0]=='equipment-log' && strlen($post['observation_'.$tkn[1]])>0)
		{
			$insert_str='	insert into `equipment-log-monitor` (`equipment-log`,time,observation,observer,comment) values(
							\''.$post['equipment-log_'.$tkn[1]].'\',
							\''.$post['time_'.$tkn[1]].'\',
							\''.$post['observation_'.$tkn[1]].'\',
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
	$sql_field='select * from `equipment-log`';
	if(!$result_field=mysql_query($sql_field,$link)){echo mysql_error();return FALSE;}

	while($array_field=mysql_fetch_assoc($result_field))
	{
		$sql_table='select * from `equipment-log-monitor` where `equipment-log`=\''.$array_field['equipment-log'].'\' and time like \''.$time_str.'\'';
		//echo $sql_table;
		if(!$result_table=mysql_query($sql_table,$link)){echo mysql_error();}
		if(mysql_num_rows($result_table)>=1)
		{

			$array_table=mysql_fetch_assoc($result_table);
			echo '<table border=1>';
			echo '<tr><th colspan=10 bgcolor=lightgreen>equipment log chart</th></tr>';
			echo '<tr><th bgcolor=lightblue colspan=10>'.$array_field['equipment-log'].'</th></th></tr><tr></tr></th><th bgcolor=lightpink>Period:</th><th bgcolor=lightpink colspan=2>'.$time_str.'</th></tr>';
			echo '<tr><th bgcolor=lightgreen colspan=10>Acceptable: '.$array_field['acceptable'].'</th></tr><tr><th colspan=5 bgcolor=lightblue>'.$array_field['contract'].':'.$array_field['start'].' to '.$array_field['end'].'</th></tr>';
					
			foreach($array_table as $key=>$value)
			{
				if($key!='equipment-log')
				{
					echo '<th>'.$key.'</th>';
				}
			}
			echo '</tr>';

			if(!$result_table=mysql_query($sql_table,$link)){echo mysql_error();}
			if(mysql_num_rows($result_table)<1){echo mysql_error();}
			while($array_table=mysql_fetch_assoc($result_table))
			{			
				echo '<tr>';
				foreach($array_table as $key=>$value)
				{
					if($key!='equipment-log')
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
mk_form_from_table('equipment-log','equipment-log-monitor');

echo '<form method=post>';
echo 'Show equipment log chart of following year and month';
read_date_time(2);
echo '<input type=submit name=submit value=show>';
echo '</form>';

save_temperature($_POST);


if(isset($_POST['submit']))
{
	if($_POST['submit']=='show')
	{
		//echo $_POST['year'].'-'.$_POST['month'].'-%';
		show_refrigerator($_POST['year'].'-'.$_POST['month'].'-%');
	}
	else
	{
		//echo '3333';
		show_refrigerator(strftime('%Y-%m-%%'));
	}
}
else
{
	//echo '3333';
	show_refrigerator(strftime('%Y-%m-%%'));
}






?>
