<?php
session_start();

echo '<html>';
echo '<head>';

echo '</head>';
echo '<body>';

include 'common.php';

//echo '<pre>';
//print_r($GLOBALS);
//echo '</pre>';

function receive_byte($fsockopen_handle)
{
//return when one byte is received
$rb=fread($fsockopen_handle,1);
echo "<tr><td>received:".$rb."</td><tr>";
return $rb;
}

function send_byte($byte_to_send,$fsockopen_handle)
{
	echo "<tr><td>Sent:".$byte_to_send."</td><tr>";
	return fwrite ( $fsockopen_handle, $byte_to_send ,1 );
}

function mk_chksum($str)
{
	$arr = str_split(trim($str));
	//print_r($arr);
	$cksum=0;
	//echo "<tr><td>...start=".$cksum;
	foreach ($arr  as $key=>$value)
	{
		if($key!='0')
		{
			$cksum_old=$cksum;
			$cksum=$cksum + ord($value);
			$cksum=0x00ff&$cksum;
			//echo "<br>[".dechex($cksum_old).'+'.dechex(ord($value)).'='.dechex($cksum).']....';
		}
		//echo $cksum."...";
	}
	//echo "<br>Final pre-checksum(desimal):-->".$cksum."<--";
	//	echo "<br>Final checksum (desimal):-->".(0x00ff&$cksum)."<--";
	//	echo "<br>Final checksum (desimal-hex):-->".dechex(0x00ff&$cksum)."<--<br>";
		
	//echo "<br>Final pre-checksum:-->".dechex($cksum)."<--";
	//echo "<br>Final checksum:-->".dechex(0x00ff&$cksum)."<--";

	$xx=str_pad(dechex(0x00ff&$cksum), 2, "00", STR_PAD_LEFT);
	//echo '<br>--'.hexdec($xx).'<br>';
	return strtoupper($xx);	//to convert 0xff to 0xFF... very important
	
}

function mk_data($str_nude)
{
$str_ready=chr(2).trim($str_nude).chr(3);
$cksum=mk_chksum($str_ready);
//if($cksum=='29'){$cksum="51";}
return $str_ready.$cksum.chr(13).chr(10);
}


function send_string_covered($data_to_send,$fsockopen_handle)
{
$covered=mk_data($data_to_send);
echo "<tr><td>sending:".$covered."</td><tr>";
fwrite($fsockopen_handle, $covered,strlen($covered));
}

function process_string($received_string)
{
	$link=start_nchsls();
	$sql='insert into output values(\''.$received_string.'\')';
	$result=mysql_query($sql,$link);
	echo '<tr><td>Received String:'.$received_string.'</td><tr>';
	mysql_close($link);
}

function elongate_string($received_string,$received_byte)
{
$final_string=$_POST['final_string'].$_POST['pc_array'];
}

function send_whole_sample_to_LIS($sample_id)
{
	$barcode_miura=array (
		'ALB'	=>	'AB',
		'ALP'	=>	'AP',
		'ALT'	=>	'AL',
		'AMY'	=>	'AM',
		'CAL'	=>	'CA',
		'CHO'	=>	'CH',
		'CR'	=>	'CR',
		'DBIL'=>	'DB',
		'TBIL'=>	'TB',
		'GLC'	=>	'GL',
		'TG'	=>	'TG',
		'TP'	=>	'TP',
		'UA'	=>	'UA',
		'URE'	=>	'UR',
		'CKMB'=>	'CM',
		'MPR' =>	'MP',
		'CHOH'=>	'HC');

		$link=start_nchsls();
		
		
		error_reporting(E_ALL);
$service_port = 4000;
$address = "10.207.3.242";

$socket=fsockopen($address, $service_port);

$received_byte=0;
$received_string="";

echo '<table border=1>';
		
////////////////
send_byte(chr(5),$socket);
$received_byte=receive_byte($socket);
if($received_byte==chr(6))	
	{
		send_string_covered("1P|1||".$sample_id."||^|20101212|M||||||||||||||||||||||||||A||",$socket);	
	}
elseif($received_byte==chr(21))		
	{
		send_byte(chr(4),$socket);
		fclose($socket);
		echo "<tr><td>Socket Closed</td></tr>";
		exit("<tr><td><h3><font color=green>(ENQ->NACK)</h3></td></tr>");
	}
///////////////////	
	
		  
			$sql='select * from examination where sample_id=\''.$sample_id.'\'';
			$result=mysql_query($sql,$link);
			$counter=1;
			while($post_array=mysql_fetch_assoc($result))  //take examinations one by on
			{
					if(isset($barcode_miura[$post_array['code']]))
					{
						$received_byte=receive_byte($socket);
						if($received_byte==chr(6))	
						{
							send_string_covered("2O|".$counter."||".$sample_id."|^".$barcode_miura[$post_array['code']]."^^1|R|18101212121212|||||N|||||||||||||O||||||",$socket);	
							$counter++;
						}
	
						elseif($received_byte==chr(21))		
						{
							send_byte(chr(4),$socket);
							fclose($socket);
							echo "<tr><td>Socket Closed</td></tr>";
							exit("<tr><td><h3><font color=green>failed Sample data(O)</h3></td></tr>");
						}
					}
			}
/////////////////////			
$received_byte=receive_byte($socket);
			if($received_byte==chr(6))	
			{
				send_string_covered("3L|1|N|",$socket);	
			}
			elseif($received_byte==chr(21))		
			{
				send_byte(chr(4),$socket);
				fclose($socket);
				echo "<tr><td>Socket Closed</td></tr>";
				exit("<tr><td><h3><font color=green>failed Sample data(O)</h3></td></tr>");
			}
////////////////////
$received_byte=receive_byte($socket);
			if($received_byte==chr(6))	
			{
				send_byte(chr(4),$socket);
				fclose($socket);
				echo "<tr><td><h3><font color=green>Socket Closed Success.. check with Miura</font></h3></td></tr>";
			}

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
		if($_POST['submit']=='send_to_miura')
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
echo '<th colspan=4><font color=green>Send Sample to miura worklist</font></th>';
echo '<form method=post action=\'send_sample_to_miura.php\'>';
echo '<td><input type=hidden name=sample_id value=\''.$sample_id.'\'></td>';	
echo '<tr>';
echo '<td><input type=submit value=prev name=submit ></td>';
echo '<td><input type=submit value=next name=submit ></td>';
echo '<td><input type=submit value=go_to name=submit ></td>';
echo '<td><input type=submit value=send_to_miura name=submit ></td></tr>';
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

if(isset($_POST['submit']) && isset($_POST['sample_id']))
{
		if($_POST['submit']=='send_to_miura')
		{
			send_whole_sample_to_LIS($sample_id);
		}
}
echo '</td>';
echo '</tr>';
echo '</table>';

?>
