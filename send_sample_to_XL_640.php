<?php
session_start();

echo '<html>';
echo '<head>';

echo '</head>';
echo '<body>';


//echo '<pre>';
//print_r($GLOBALS);
//echo '</pre>';
include 'common.php';


function send_whole_sample_to_XL_640($sample_id)
{
$link=start_nchsls();

	$sql='select * from examination where sample_id=\''.$sample_id.'\'';
	$result=mysql_query($sql,$link);
	$sample_id_str='S|'.$sample_id;
	$examinations='E|';
	while($array_ex=mysql_fetch_assoc($result))
	{
		$examinations=$examinations.'^^^'.$array_ex['code'].'`';		
	}
	$examinations=substr($examinations,0,-1);
	
error_reporting(E_ALL);

/* Get the port for the WWW service. */
$service_port = 12377;

/* Get the IP address for the target host. */
$address = '127.0.0.1';

/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} else {
    echo "OK.\n";
}

echo "Attempting to connect to '$address' on port '$service_port'...";
$sock = socket_connect($socket, $address, $service_port);
if ($sock === false) {
    echo "socket_connect() failed.\nReason: ($socket) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    echo "OK Success!!!!!!!!!.\n";
}
/////////////////
socket_write($socket, chr(5), 1);
$bytee=socket_read($socket,1);

	if($bytee==chr(6))
		{
			//with fake chksum
			$to_send=chr(2).'1H||||||||||||NCHSLS'.chr(13).$sample_id_str.chr(13).$examinations.chr(13).chr(3)."XX".chr(13).chr(10);
			socket_write($socket,$to_send,strlen($to_send));
		}
$bytee=socket_read($socket,1);
	if($bytee==chr(6))
		{		
				socket_write($socket, chr(4), 1);	
		}
////////////////////////////
echo "Closing socket...";
socket_close($socket);
echo "OK.\n\n";

}


if(!login_varify())
{
exit();
}

main_menu();

if(isset($_POST['submit']) && isset($_POST['sample_id']))
{
		if($_POST['submit']=='next')
		{
			$sample_id=$_POST['sample_id']+1;
		}
		if($_POST['submit']=='prev')
		{
			$sample_id=$_POST['sample_id']-1;
		}
		if($_POST['submit']=='go_to')
		{
			$sample_id=$_POST['sample_id'];
		}
		if($_POST['submit']=='send_to_XL')
		{
			$sample_id=$_POST['sample_id'];
		}
}

else
{
	$sample_id=1;
}
echo '<table border=1>';
echo '<tr>';
echo '<td>';
echo '<table border=1>';
echo '<th colspan=4><font color=green>Send Sample to XL-640 worklist</font></th>';
echo '<form method=post action=\'send_sample_to_XL_640.php\'>';
echo '<td><input type=hidden name=sample_id value=\''.$sample_id.'\'></td>';	
echo '<tr>';
echo '<td><input type=submit value=prev name=submit ></td>';
echo '<td><input type=submit value=next name=submit ></td>';
echo '<td><input type=submit value=go_to name=submit ></td>';
echo '<td><input type=submit value=send_to_XL name=submit ></td></tr>';
echo '<tr><td colspan=4><input type=text name=sample_id value=\''.$sample_id.'\'></td></tr>';	
echo '</form>';
echo '</table>';
echo '</td>';
echo '</tr>';

echo '<tr>';
echo '<td valign=top>';
print_sample_worklist($sample_id);
echo '</td>';
echo '<td>';
//send_whole_sample_to_LIS($sample_id);

if(isset($_POST['submit']) && isset($_POST['sample_id']))
{
		if($_POST['submit']=='send_to_XL')
		{
			//$sample_id=$_POST['sample_id'];
			send_whole_sample_to_XL_640($sample_id);
		}
}
echo '</td>';
echo '</tr>';
echo '</table>';

?>
