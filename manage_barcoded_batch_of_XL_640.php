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
    //echo "OK.\n";
}

//echo "Attempting to connect to '$address' on port '$service_port'...";
$sock = socket_connect($socket, $address, $service_port);
if ($sock === false) {
    echo "socket_connect() failed.\nReason: ($socket) " . socket_strerror(socket_last_error($socket)) . "\n";
} else {
    //echo "OK Success!!!!!!!!!.\n";
}
/////////////////
socket_write($socket, chr(5), 1);
$bytee=socket_read($socket,1);

	if($bytee==chr(6))
		{
			//with fake chksum
			$to_send=chr(2).'1H||||||||||||NCHSLS'.chr(13).$sample_id_str.chr(13).$examinations.chr(13).chr(3)."XX".chr(13).chr(10);
			socket_write($socket,$to_send,strlen($to_send));
			/*
			socket_write($socket, chr(2), 1);
			$header_nch='1H||||||||||||NCHSLS';
			socket_write($socket, $header_nch, strlen($header_nch));
			socket_write($socket, chr(13), 1);
			
			socket_write($socket,$sample_id_str , strlen($sample_id_str));
			socket_write($socket, chr(13), 1);
			
			socket_write($socket,$examinations , strlen($examinations));
			socket_write($socket, chr(13), 1);
			socket_write($socket, chr(3), 1);	
			socket_write($socket, chr(10), 1);*/
		}
$bytee=socket_read($socket,1);
	if($bytee==chr(6))
		{		
				socket_write($socket, chr(4), 1);	
		}
////////////////////////////
//echo "Closing socket...";
socket_close($socket);
echo $sample_id.',';
//echo "OK.\n\n";
}


if(!login_varify())
{
exit();
}
main_menu();

echo '
<table>
<th colspan=3>Manage Barcoded Batch for XL-640</th>
<form method=post action=manage_barcoded_batch_of_XL_640.php>
<tr>
	<td><input type=submit value=clear_batch name=submit ></td>
	<td><input type=submit name=submit value=refresh></td>
	<td><input type=submit name=submit value=send_batch></td></tr>
</form>
</table>	
';	
	


$link=start_nchsls();
if(!isset($_POST['submit']))
{
		echo '<br>current batch:(total=';
		$sql='select * from inquiry';
		$result=mysql_query($sql,$link);
		echo mysql_num_rows($result).')';
		while($array_res=mysql_fetch_assoc($result))
		{
			echo $array_res['sample_id'].',';	
		}		


exit(0);
}


switch ($_POST['submit']) 
{
	case 'clear_batch':	
		$sql='delete from inquiry';
		$result=mysql_query($sql,$link);
		echo '<br>'.$sql.'::  Query Result: number of records deleted:'.mysql_affected_rows($link);
		break;	
	case 'refresh':
		break;
	case 'send_batch':
		$counter=0;
		echo '<br>batch sent:';
		$sql='select * from inquiry';
		$result=mysql_query($sql,$link);
		while($array_inq=mysql_fetch_assoc($result))
		{
			send_whole_sample_to_XL_640($array_inq['sample_id']);
			$counter++;
			sleep(1);
		}
		echo '(total '.$counter.')';
		break;
}


		echo '<br>current batch:(total=';
		$sql='select * from inquiry';
		$result=mysql_query($sql,$link);
		echo mysql_num_rows($result).')';
		while($array_res=mysql_fetch_assoc($result))
		{
			echo $array_res['sample_id'].',';	
		}	
?>
