<?php 
session_start();
include 'common.php';


/*

2014-10-30: display only 200 raws to preventload on server

change /etc/mysql/mysql.cnf
*=========================*

[mysqld]
#
# * Basic Settings
#
user		= mysql
pid-file	= /var/run/mysqld/mysqld.pid
socket		= /var/run/mysqld/mysqld.sock
port		= 3306
basedir		= /usr
datadir		= /var/lib/mysql
tmpdir		= /tmp
lc-messages-dir	= /usr/share/mysql
skip-external-locking
event-scheduler=On	

restart mysql service (service mysql restart)

CREATE TABLE `schedule` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `every` int(11) NOT NULL,
 `unit` varchar(30) NOT NULL,
 `starts` datetime NOT NULL,
 `description` varchar(500) NOT NULL,
 `authority` int(11) NOT NULL,
 PRIMARY KEY (`id`),
 UNIQUE KEY `name` (`id`)
) 

CREATE TABLE `reminder` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `schedule_id` int(11) NOT NULL,
 `time` datetime NOT NULL,
 `checked_by` varchar(30) NOT NULL,
 `remark` varchar(200) NOT NULL,
 `checked_at` datetime NOT NULL,
 `complated` int(11) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=840 DEFAULT CHARSET=latin1
* 
give authority in schedule
update schedule
create new reminder table

*/

function delete_cron($id)
{
	$link=start_nchsls();
	$sql='delete from reminder where id=\''.$id.'\'';
	//echo $sql;
	if(!$result=mysql_query($sql,$link)){echo mysql_error();return FALSE;}
}


function delete_schedule($id)
{
	$link=start_nchsls();
	$sql='delete from schedule where id=\''.$id.'\'';
	//echo $sql;
	if(!$result=mysql_query($sql,$link)){echo mysql_error();return FALSE;}
}

function get_schedule_info($id)
{
	$link=start_nchsls();
	$sql='select * from schedule where id=\''.$id.'\'';
	$result=mysql_query($sql,$link);
	return mysql_fetch_assoc($result);
}


function view_pending_cron_unitwise($unit)
{
	$link=start_nchsls();
	echo '<table bgcolor=lightblue>';

	if(isset($_POST['checkbox']))
	{
		$sql='select * from reminder order by id desc';
	}
	else
	{
		$sql='select * from reminder where complated=0 order by id desc';
	}
		


	$style='style="background-color:lightgray;"';
	echo '<tr '.$style.'><th  colspan=10>'.$unit.'</th>';
	$style='style="background-color:lightpink;"';
	echo '<tr  '.$style.'>';
//	echo '<th>schedule id</th>';
	echo '<th>description</th><th>remark</th><th>reminder id</th><th>authority</th>
			<th>checked_by</th><th>time of reminder</th><th>checked at</th><th>complated</th></tr>';
		
	$result=mysql_query($sql,$link);
	$count=0;				//prevent excess of data display
	while($ed=mysql_fetch_assoc($result))
	{
			$schedule_info=get_schedule_info($ed['schedule_id']);
			if($schedule_info['unit']==$unit)
			{
				echo '<form method=post>';
				echo '<tr>';
				echo '<input type=hidden name=id value=\''.$ed['id'].'\'>';		//id of reminder
				echo '<input type=hidden name=unit value=\''.$unit.'\'>';		//id of reminder
				//echo '<td>'.$ed['schedule_id'].'</td>';
				echo '<td>'.$schedule_info['description'].'</td>';
				echo '<td><input type=text name=remark value=\''.$ed['remark'].'\'></td>';
				echo '<td><button type=submit name=action value=save_cron>'.$ed['id'].'</td>';		
				echo '<td>'.$schedule_info['authority'].'</td>';	
				echo '<td>'.$ed['checked_by'].'</td>';		
				if($ed['complated']==0)
				{
					echo '<td><input type=checkbox name=complated></td>';
				}
				else if($ed['complated']==1)
				{
					echo '<td><input type=checkbox checked name=complated></td>';
				}			
				echo '<td>'.$ed['time'].'</td>';
				echo '<td>'.$ed['checked_at'].'</td>';

				echo '</tr>';
				echo '</form>';
				$count++;
			}
	if($count>200){echo '<H4>Too much of data!!! Displaying only first 200 raws.</H4>';break;}
	}
	echo '</table>';
}


function save_cron($post)
{
	if(isset($_POST['complated'])){$complated=1;}else{$complated=0;}
	$sql='update reminder set 
					checked_by=\''.$_SESSION['login'].'\' , 
					remark=\''.$post['remark'].'\' , 
					checked_at=now(),
					complated=\''.$complated.'\'
					where id=\''.$post['id'].'\'';	
	//echo $sql;	
	$link=start_nchsls();
	$result=mysql_query($sql,$link);
	echo mysql_error();
}


function update_schedule()
{
	$link=start_nchsls();
	$sql_all_schedule='show events';
	if(!$result_all_schedule=mysql_query($sql_all_schedule,$link)){echo 'update_schedule() error in listing all schedules'.mysql_error(); return;}
	while($array_all_schedule=mysql_fetch_assoc($result_all_schedule))
	{	
		$sql_delete_schedule='drop event if exists `'.$array_all_schedule['Name'].'`';
		if(!$result_delete_schedule=mysql_query($sql_delete_schedule,$link)){echo 'update_schedule() error in deleting schedule '.mysql_error(); return;}
	}
	
	$sql='select * from schedule';
	
	if(!$result=mysql_query($sql,$link)){echo 'update_schedule() error'.mysql_error(); return;}
	
	while($array=mysql_fetch_assoc($result))
	{

	$drop_event_sql='drop event if exists `'.$array['id'].'`';
		$create_event_sql='CREATE EVENT `'.$array['id'].'`
								ON SCHEDULE 
								EVERY  '.$array['every'].' '.$array['unit'].' 
								STARTS \''.$array['starts'].'\'
								DO
								insert into reminder values
								(\'\', \''.$array['id'].'\',now(),\'\',\'\',\'\',0)';

		if(!$drop_event_result=mysql_query($drop_event_sql,$link)){echo 'update_schedule() drop event error'.mysql_error();}
		if(!$create_event_result=mysql_query($create_event_sql,$link)){echo 'update_schedule() create event error'.mysql_error();}
		
		//echo $create_event_sql.'<br>';
	}	
}

function show_schedule()
{
	$link=start_nchsls();

	$sql='select * from schedule';
	if(!$result=mysql_query($sql,$link)){echo 'show_schedule() error'.mysql_error(); return;}

	echo '<table border=1>';
	echo '<tr><td>Action</td><td>ID</td><td>Every</td><td>Unit</td><td>Starts</td><td>Description</td><td>authority</td></tr>';
	while($array=mysql_fetch_assoc($result))
	{
		echo '<form method=post>';
		echo '<tr>';
		echo 	'<td>	
					<button type=submit name=action value=delete_schedule>X</button>
				</td>';
		echo '<input type=hidden name=id value=\''.$array['id'].'\'>';
		echo '<td>'.$array['id'].'</td><td>'.$array['every'].'</td><td>'.$array['unit'].'</td><td>'
				.$array['starts'].'</td><td>'.$array['description'].'</td><td>'		
				.$array['authority'].'</td>';		
		echo '</tr>';
		echo '</form>';
	}
	echo '</table>';


	
}


function new_schedule()
{
	echo '<table  border=1>	<form method=post>';
	echo '<tr>';
	echo '<tr><th colspan=5>New Schedule</th></tr>';
	echo '<tr><td>Every</td><td><input type=text name=every size=2></td><td>Write a number</td></tr>';
	echo '<tr><td>Period</td><td>
			<select name=unit>
					<option>minute</option>
					<option>hour</option>
					<option>day</option>
					<option>week</option>
					<option>month</option>
					<option>year</option>
			</select></td><td>Select period</td></tr>';
	echo '<tr><td>Start from</td><td><input type=text name=starts></td><td>Write starting time Example Format: 2013-11-29 09:10:00</td></tr>';
	echo '<tr><td>What to remind</td><td><input type=text name=description></td><td>Describe what to remind for</td></tr>';
	echo '<tr><td>Authority</td><td>';
	$authority_array=array('1','2','3','4','5');
	mk_select_from_array_return_value('authority',$authority_array,'','');
	echo '</td><td>Select authority who will verify. R1=1 R2=2 R3=3 Teaching staff=4 HOD=5</td></tr>';
	echo '<tr><td><button type=submit name=action value=save_new_schedule>save</button></td></tr>';
	echo '</form></table>';
}

function save_new_schedule($array)
{
	$link=start_nchsls();
	$sql='insert into schedule values(
			\'\',
			\''.$array['every'].'\',
			\''.$array['unit'].'\',
			\''.$array['starts'].'\',
			\''.$array['description'].'\',
			\''.$array['authority'].'\'
			)';
	
	//echo $sql;	
	if(!$result=mysql_query($sql,$link))
	{
		echo 'save_schedule()'.mysql_error();
	}
}


if(!login_varify())
{
exit();
}
main_menu();

echo '<form method=post>';
echo '<table><tr>';
echo '<td><input type=checkbox name=checkbox></td>';
echo '<td><button type=submit name=action value=hour>Hourly Reminders</button></td>';
echo '<td><button type=submit name=action value=day>Daily </button></td>';
echo '<td><button type=submit name=action value=week>Weekly Reminders</button></td>';
echo '<td><button type=submit name=action value=month>Monthly Reminders</button></td>';
echo '<td><button type=submit name=action value=year>Yearly Reminders</button></td>';
echo '<td><button type=submit name=action value=show_schedule>Show Schedule</button></td>';
echo '<td><button type=submit name=action value=new_schedule>New Schedule</button></td>';
echo '<td><button type=submit name=action value=update_schedule>Update Schedule</button></td>';
echo '</tr></table>';
echo '</form>';

if(isset($_POST['action']))
{
	if($_SESSION['login']!='root' && substr($_SESSION['login'],0,3)!='Dr.')
	{
		echo '<h2><font color=red>Please instruct appropriate person for pending work. Thank you</font></h2>'; 
	}
	else
	{
		if($_POST['action']=='delete_reminder')
		{
			delete_cron($_POST['id']);
			view_pending_cron();
		}
		
		if($_POST['action']=='show_schedule')
		{
			show_schedule();
		}

		if($_POST['action']=='delete_schedule')
		{
			delete_schedule($_POST['id']);
			update_schedule();
			show_schedule();
		}
		
		if($_POST['action']=='update_schedule')
		{
			update_schedule();
			show_schedule();
		}
		
		if($_POST['action']=='new_schedule')
		{
			new_schedule();
		}
		if($_POST['action']=='save_new_schedule')
		{
			save_new_schedule($_POST);
			update_schedule();
		}

		if($_POST['action']=='day'||$_POST['action']=='hour'||$_POST['action']=='week'||$_POST['action']=='month'||$_POST['action']=='year')
		{
			view_pending_cron_unitwise($_POST['action']);			
		}
		if($_POST['action']=='save_cron')
		{
			save_cron($_POST);			
			view_pending_cron_unitwise($_POST['unit']);	
		}		
		
	}
}




//update_schedule();
/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/

?>
