<?php



/*
//add_reminder shell script at /root
//it is called by crond

	mysql -uroot -pnishiiilu -e "insert into cron values('','$1',sysdate())" biochemistry
//end of file

//echo_cron_jobs shell script at /root
//it outputs crontab-list based on crontab table in database
//it add refresh_job to be done once a day

	echo "1	10	*	*	* 	/root/refresh_jobs>/root/cron.log"	
	mysql -uroot -pnishiiilu -N -B -e "select minute,hour,day_of_month,month,day_of_week, concat('/root/add_reminder \"',command,'\" 1>/root/cron.log 2>/root/cron.log')   from crontab " biochemistry
//end of file


//refresh_jobs makes crontab-list and feeds to crontab
//this is run once every day by cron to update itself
	/root/echo_cron_jobs|crontab
//end of file

//cron table structure
CREATE TABLE IF NOT EXISTS `cron` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
)

//crontab table structure
CREATE TABLE IF NOT EXISTS `crontab` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `minute` varchar(50) NOT NULL,
  `hour` varchar(50) NOT NULL,
  `day_of_month` varchar(50) NOT NULL,
  `month` varchar(50) NOT NULL,
  `day_of_week` varchar(50) NOT NULL,
  `command` varchar(500) NOT NULL,
  PRIMARY KEY (`id`)
)

*/

session_start();

echo '<html>';
echo '<head>';
echo '</head>';
echo '<body>';

include 'common.php';



function delete_cron($id)
{
	$link=start_nchsls();
	$sql='delete from cron where id=\''.$id.'\'';
	if(!$result=mysql_query($sql,$link)){echo mysql_error();return FALSE;}
}


function view_pending_cron()
{
	$link=start_nchsls();

	echo '<form method=post>';
	echo '<table bgcolor=lightblue>';

	$sql='select * from cron';
	echo '<tr><th colspan=5 bgcolor=yellow>NCHSLS Biochemistry Reminder System</th></tr>';
	echo '<tr><th bgcolor=yellow>Reminder</th><th bgcolor=yellow>Time</th></tr>';
		
	$result=mysql_query($sql,$link);
	while($ed=mysql_fetch_assoc($result))
	{
			$style='style="background-color:lightpink;"';
			echo '<tr>';
			echo '<td><button '.$style.' name=id value=\''.$ed['id'].'\'>'.$ed['description'].'</button></td><td>'.$ed['time'].'</td>';
			echo '</tr>';	
		}
	echo '</form></table>';
}



/////////////Script start
login_varify();
main_menu();

if(isset($_POST['id']))
{
	if($_SESSION['login']!='root' && substr($_SESSION['login'],0,3)!='Dr.')
	{
		echo '<h2><font color=red>Please instruct appropriate person for pending work. Thank you</font></h2>'; 
	}
	else
	{
		delete_cron($_POST['id']);
	}
}

view_pending_cron();

/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/

?>
