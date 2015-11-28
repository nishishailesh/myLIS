<?php
session_start();

/*
echo '<html>';
echo '<head>';
echo '</head>';
echo '<body>';
*/
/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/

include 'common.php';

if(!login_varify())
{
	exit();
}

/*
function print_report_pdf_A4($sample_id_array,$doctor)
{
	foreach($sample_id_array as $key=>$value)
	{
		//echo $value;
		print_single_report_pdf_A4($value,$doctor);
	}
}

function print_single_report_pdf_A4($sample_id,$doctor)
{
	$pdf = new MYPDF_NABL('P', 'mm', 'A4', true, 'UTF-8', false);
	$pdf->sample_id=$sample_id;
	
	//$pdf->SetHeaderMargin(30);
	//$pdf->SetFooterMargin(30);
	//$pdf->SetMargins(10, 10);
	
	$pdf->AddPage();

	$pdf->SetFont('courier','',10);
	$pdf->SetXY(5,5);


 * @param $ln (int) Indicates where the current position should go after the call. 
Possible values are:<ul><li>
0: to the right (or left for RTL languages)</li><li>
1: to the beginning of the next line</li><li>
2: below</li></ul> Putting 1 is equivalent to putting 0 and calling Ln() just after. Default value: 0.

for($i=0;$i<=50;$i++)
{
				$pdf->Cell($w=10, $h=0, $pdf->GetX().','.$pdf->GetY(),$border=0, $ln=2, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
}


		
	
	$pdf->Output('report.pdf', 'I');
}
*/
function search_form($filename)
{
	$link=start_nchsls();
	$sql='desc sample';
	if(!$result=mysql_query($sql,$link)){echo mysql_error();}
	$tr=1;
	echo '<table border=1><form action=\''.$filename.'\' target=_blank method=post>';
	echo '	<tr>
				<td title=\'1) Tickmark to include the field for search. 2) Use % as wildcard. e.g. [%esh = Mahesh,Jignesh] [Mahesh%=Mahesh,Maheshbhai, Maheshkumar]\'><input type=submit name=submit value=print></td>';
	/*echo '			<td>Technician:</td><td>';
					mk_select_from_table('technician','','');
		  echo '</td>';
	*/
		echo   '<td>Authorized Signatory:</td><td>';
					mk_select_from_table('authorized_signatory','','');
		  echo '</td>';
		  echo '</tr>';
	while($ar=mysql_fetch_assoc($result))
	{
		if($tr%3==1){echo '<tr>';}
		
		if($ar['Field']=='sample_id')
		{
			echo '<td><input type=checkbox checked name=\'chk_from_'.$ar['Field'].'\' ></td><td>from_'.$ar['Field'].'</td>';
			echo '<td><input type=text name=\'from_'.$ar['Field'].'\' ></td>';
			echo '<td><input type=checkbox name=\'chk_to_'.$ar['Field'].'\' ></td><td>to_'.$ar['Field'].'</td>';
			echo '<td><input type=text name=\'to_'.$ar['Field'].'\' >';
			$tr++;
		}
		
		else
		{		
			echo '<td><input type=checkbox name=\'chk_'.$ar['Field'].'\' ></td><td>'.$ar['Field'].'</td><td>';
			if(!mk_select_from_table($ar['Field'],'',''))
			{
				  echo '<input type=text name=\''.$ar['Field'].'\' >';
			}
		}
		echo '</td>';
		if($tr%3==0){echo '</tr>';}
		$tr++;
	}
	echo '</form></table>';
}






if(isset($_POST['authorized_signatory']))
{
if(strlen($_POST['authorized_signatory'])==0)
 {
   echo '<h4>No authorized Signatory given</h4>';
 }
}


$search_str='select sample_id from sample '; 
$where=array();

if(isset($_POST['submit']))
{
	foreach($_POST as $key=>$value)
	{
		if(substr($key,0,4)=='chk_' && $value=='on')
		{
			//echo substr($key,4).'='.$_POST[substr($key,4)].'<br>';
			$where[substr($key,4)]=$_POST[substr($key,4)];
		}
	}
}

//print_r($where);

$sample_id_where='';
if(isset($where['from_sample_id']) && isset($where['to_sample_id']) )
{
$sample_id_where='sample_id between  \''.$where['from_sample_id'].'\' and \''.$where['to_sample_id'].'\' ';
}
elseif(isset($where['from_sample_id']))
{
$sample_id_where=' sample_id=\''.$where['from_sample_id'].'\' ';
}
elseif(isset($where['to_sample_id']))
{
$sample_id_where=' sample_id=\''.$where['to_sample_id'].'\' ';
}

$other_wheree='';
foreach($where as $key=>$value)
{
	if($key!='from_sample_id' && $key!='to_sample_id' )
	{
		$other_wheree=$other_wheree.' '.$key.' like \''.$value.'\' and';
	}
}
$other_where=substr($other_wheree,0,-3);


if(strlen($sample_id_where)>0 && strlen($other_where)>0)
{
$search_str=$search_str.' where '.$sample_id_where.' and '.$other_where;
}
elseif(strlen($sample_id_where)>0 && strlen($other_where)==0)
{
$search_str=$search_str.' where '.$sample_id_where;
}
elseif(strlen($sample_id_where)==0 && strlen($other_where)>0)
{
$search_str=$search_str.' where '.$other_where;
}

$printed=array();

if(isset($_POST['submit']) && substr($search_str,-7)!='sample ')
{
	$link=start_nchsls();
	if(!$search_result=mysql_query($search_str,$link)){echo mysql_error();}
	while($ar=mysql_fetch_assoc($search_result))
	{
		$printed[]=$ar['sample_id'];
	}
	foreach($printed as $value)
	{
		if(get_sample_status($value)!='verified')
		{
			echo $value.' is not verified. PDF report can not be printed<br>';
		}
	}
	print_report_pdf_A4($printed,$_POST['authorized_signatory']);
}
else
{
	main_menu();
	search_form('print_report_pdf_A4.php');
	echo '<h1>No coditions are given for selecting records</h1>';
}


?>
