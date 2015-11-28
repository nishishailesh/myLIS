<?php 
session_start();

/*
echo '<html>';
echo '<head>';
echo '<title></title>';
echo '<style>';
echo 'td.field {background-color:lightblue;}';
echo '</style>';
echo '</head>';
echo '<body>';
*/
/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/



include 'common.php';
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

class MYPDF_NABL extends TCPDF {
	public $letter;
	public function Header() 
	{
		$border=0;
		//A4=210x297

		$this->SetXY(10,10);		
		$this->SetFont('helvetica', 'B', 20);
		$this->Cell(190, $h=0, $txt='Page:'.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), $border, $ln=0, $align='R', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

		$this->SetFont('helvetica', 'B', 10);
		$this->SetXY(105,20);
		$this->Cell(95, $h=0,'From', $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
		$this->SetFont('helvetica', '', 10);
		$this->SetXY(105,25);
		$this->MultiCell($w=95, $h=0, $txt=$this->letter['from'], $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=10, $valign='T', $fitcell=true);
		$this->SetFont('helvetica', 'B', 10);
		$this->SetXY(105,35);
		$this->MultiCell($w=95, $h=0, $txt='No: '.$this->letter['id'].' / '.$this->letter['type'].' / '.$this->letter['date'], $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=5, $valign='T', $fitcell=true);

		$this->SetFont('helvetica', 'B', 10);
		$this->SetXY(10,20);
		$this->Cell(95, $h=0,'To', $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
		$this->SetFont('helvetica', '', 10);
		$this->SetXY(10,25);
		$this->MultiCell($w=95, $h=0, $txt=$this->letter['to'], $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=15, $valign='T', $fitcell=true);
	}

	// Page footer
	public function Footer() 
	{

	}
}

function print_letter($id)
{
	$sql='select * from letter where id=\''.$id.'\'';
			
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link)){echo mysql_error();}	
		$letter=mysql_fetch_assoc($result);		
	$pdf = new MYPDF_NABL('P', 'mm', 'A4', true, 'UTF-8', false);
	$pdf->letter=$letter;
	$pdf->SetMargins(10, 50);
	$pdf->SetAutoPageBreak(TRUE, 10);
	$pdf->SetFont('helvetica', '', 8);
	$pdf->AddPage();

		$border=0;

		$pdf->SetFont('helvetica', 'B', 10);
		$pdf->SetXY(10,50);
		$pdf->MultiCell($w=20, $h=0, $txt='Subject:', $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=5, $valign='T', $fitcell=true);
		$pdf->SetFont('helvetica', '', 10);
		$pdf->SetXY(30,50);
		$pdf->MultiCell($w=210-10-30, $h=0, $txt=$pdf->letter['subject'], $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=5, $valign='T', $fitcell=true);


		$pdf->SetFont('helvetica', 'B', 10);
		$pdf->SetXY(10,55);
		$pdf->MultiCell($w=20, $h=0, $txt='Sub-subject:', $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=2, $ishtml=false, $autopadding=true, $maxh=5, $valign='T', $fitcell=true);
		$pdf->SetFont('helvetica', '', 10);
		$pdf->SetXY(30,55);
		$pdf->MultiCell($w=210-10-30, $h=0, $txt=$pdf->letter['sub_subject'], $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=5, $valign='T', $fitcell=true);


		$border=0;
		$max_height=0;
		if(strlen($pdf->letter['reference'])>0)
		{
		$c_str=count_chars($pdf->letter['reference'],0);
		$max_height=$c_str[13]*5+5;
		
		$pdf->SetFont('helvetica', 'B', 10);
		$pdf->SetXY(10,60);
		$pdf->MultiCell($w=20, $h=0, $txt='Reference:', $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=5, $valign='T', $fitcell=true);
		$pdf->SetFont('helvetica', '', 10);
		$pdf->SetXY(30,60);
		$pdf->MultiCell($w=210-10-30, $h=0, $txt=$pdf->letter['reference'], $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=$max_height, $valign='T', $fitcell=true);
		}

		$my_y=55+5+$max_height;
		$pdf->SetFont('helvetica', 'B', 10);
		$pdf->SetXY(10,$my_y);
		$pdf->MultiCell($w=190, $h=0, $txt=$pdf->letter['greeting'].',', $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=5, $valign='T', $fitcell=true);
	
		$pdf->SetFont('helvetica', '', 10);
		$pdf->SetXY(10,$my_y+5);
		$pdf->MultiCell($w=190, $h=0, $txt=$pdf->letter['body'], $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=true);
		
		$x=$pdf->getX();
		$y=$pdf->getY();
		
		$pdf->SetFont('helvetica', '', 10);
		$pdf->SetXY($x,$y+10);
		$pdf->MultiCell($w=190, $h=0, $txt=$pdf->letter['thanks'].',', $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=true);


		$x=$pdf->getX();
		$y=$pdf->getY();
		
		$pdf->SetFont('helvetica', '', 10);
		$pdf->SetXY($x,$y+5);
		$pdf->MultiCell($w=190, $h=0, $txt=$pdf->letter['closing'].',', $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=true);

		$x=$pdf->getX();
		$y=$pdf->getY();
		
		$pdf->SetFont('helvetica', 'B', 10);
		$pdf->SetXY($x,$y+10);
		$pdf->MultiCell($w=190, $h=0, $txt=$pdf->letter['signature'], $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=true);

		if(strlen($pdf->letter['attachment_list'])>0)
		{
		$x=$pdf->getX();
		$y=$pdf->getY();
		$pdf->SetFont('helvetica', 'B', 10);
		$pdf->SetXY(10,$y+5);
		$pdf->MultiCell($w=20, $h=0, $txt='Attachments:', $border, $align='L', $fill=false, $ln=0, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=5, $valign='T', $fitcell=true);
		$pdf->SetFont('helvetica', '', 10);
		$pdf->MultiCell($w=210-10-30, $h=0, $txt=$pdf->letter['attachment_list'], $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=true);
		}

		if(strlen($pdf->letter['copy_to'])>0)
		{
		$x=$pdf->getX();
		$y=$pdf->getY();
		$pdf->SetFont('helvetica', 'B', 10);
		$pdf->SetXY(10,$y+5);
		$pdf->MultiCell($w=20, $h=0, $txt='Copy to:', $border, $align='L', $fill=false, $ln=0, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=5, $valign='T', $fitcell=true);
		$pdf->SetFont('helvetica', '', 10);
		$pdf->MultiCell($w=210-10-30, $h=0, $txt=$pdf->letter['copy_to'], $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=$max_height, $valign='T', $fitcell=true);
		}

	$filename=$pdf->letter['id'].'-'.$pdf->letter['type'].'-'.$pdf->letter['date'].'.pdf';
	$pdf->Output($filename, 'I');
}

function new_letter($id)
{
	if(is_str_digit($id))
	{
	$sql='insert into letter (id,type,`from`,`to`,`date`,greeting,subject,sub_subject,`reference`,`body`,closing,thanks,signature,attachment_list,copy_to)
			values
			(\''.$id.'\'
			\'\',
			\'\',
			\'\',
			\'\',
			\''.	mysql_real_escape_string(strftime("%Y-%m-%d")).'\',
			\'\',
			\'\',
			\'\',
			\'\',
			\'\',			
			\'\',	
			\'\',
			\'\',
			\'\',
			\'\')';
		//echo $sql;
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link)){echo mysql_error();}
		return mysql_insert_id($link);	
	}
	else
	{
	echo 'id not numeric<br>';
	return FALSE;
	}
}


function save_letter($letter)
{
	$sql='update letter set 
			`type`=\''.	mysql_real_escape_string($letter['type']).'\',
			`from`=\''.	mysql_real_escape_string($letter['from']).'\',
			`to`=\''.	mysql_real_escape_string($letter['to']).'\',
			`date`=\''.	mysql_real_escape_string($letter['year'].'-'.$letter['month'].'-'.$letter['day']).'\',
			`greeting`=\''.	mysql_real_escape_string($letter['greeting']).'\',
			`subject`=\''.	mysql_real_escape_string($letter['subject']).'\',
			`sub_subject`=\''.	mysql_real_escape_string($letter['sub_subject']).'\',
			`reference`=\''.	mysql_real_escape_string($letter['reference']).'\',
			`body`=\''.	mysql_real_escape_string($letter['body']).'\',			
			`closing`=\''.	mysql_real_escape_string($letter['closing']).'\',	
			`thanks`=\''.	mysql_real_escape_string($letter['thanks']).'\',
			`signature`=\''.	mysql_real_escape_string($letter['signature']).'\',
			`attachment_list`=\''.	mysql_real_escape_string($letter['attachment_list']).'\',
			`copy_to`=\''.	mysql_real_escape_string($letter['copy_to']).'\'   
			where id=\''.$letter['id'].'\'
			';
			
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link)){echo mysql_error();}
		return mysql_insert_id($link);	
}

function max_id()
{
	$sql='select max(id) max_id from letter';
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link)){echo mysql_error();}
		$letter=mysql_fetch_assoc($result);	
		return $letter['max_id'];
}

if(!login_varify())
{
	exit();
}

if($_SESSION['login']!='root' && substr($_SESSION['login'],0,3)!='Dr.')
{
echo 'This user is not authorized to use this menu'; 
exit();
}

function view_letter($id)
{
	$sql='select * from letter where id=\''.$id.'\'';
		
	$link=start_nchsls();
	if(!$result=mysql_query($sql,$link)){echo mysql_error(); return;}	
	if(mysql_num_rows($result)<1){return;}
	$letter=mysql_fetch_assoc($result);	
	
	echo '<table>';
	foreach($letter as $key=>$value)
	{
		echo '<tr><td bgcolor=lightblue>'.$key.'</td><td bgcolor=lightpink><pre>'.$value.'</pre></td></tr>';
	}
	echo '</table>';
}

function view_register($from_id,$to_id)
{
	$sql='select * from letter where id between \''.$from_id.'\' and \''.$to_id.'\'';
	$link=start_nchsls();
	if(!$result=mysql_query($sql,$link)){echo mysql_error(); return;}	
	echo '<table border=1 bgcolor=lightpink>';	
	echo '<tr>
				<th bgcolor=lightgreen>id</th>
				<th bgcolor=lightgreen>type</th>
				<th bgcolor=lightgreen>from</th>
				<th bgcolor=lightgreen>to</th>		
				<th bgcolor=lightgreen>subject</th>
				<th bgcolor=lightgreen>sub_subject</th>		
		</tr>';
	while($letter=mysql_fetch_assoc($result))
	{
		echo '<tr>
				<td>'.$letter['id'].'</td>
				<td>'.$letter['type'].'</td>
				<td>'.$letter['from'].'</td>
				<td>'.$letter['to'].'</td>		
				<td>'.$letter['subject'].'</td>
				<td>'.$letter['sub_subject'].'</td>		
		</tr>';
	}
	echo '</table>';		
}

function edit_letter($id)
{
	$sql='select * from letter where id=\''.$id.'\'';
		
	$link=start_nchsls();
	if(!$result=mysql_query($sql,$link)){echo mysql_error(); return;}	
	if(mysql_num_rows($result)<1){return;}
	$letter=mysql_fetch_assoc($result);	
		
	$type=array('outword','inword');
	$from=array(
	'Department of Biochemistry/Clinical Chemistry
	New civil Hpspital and Government Medical College Surat'
	,
	'Medical Superintendent
	New Civil Hospital Surat'
	,
	'Medical Superintendent
	New Civil Hospital Surat
	(Attn: Store Section)'
	);

	$greeting=array('Respected Sir','Respected Madam','Respected Sir/Madam');

	$thanks=array('Thanking you','Hoping for quick action','Urgent action in the matter is requested');

	$closing=array('Yours Sincerely','Yours','Yours faithfully');

	$signature=array(
	'Professor and Head
	Biochemistry
	NCH and GMC Surat',
	'Laboratory Incharge,
	Biochemistry
	NCH and GMC Surat'
	);

	$subject=array(	'Human Resources',
					'Material Resources:Request for vendor evaluation/tendering/quotation',
					'Material Resources:Request for supply-Equipment',
					'Material Resources:Request for supply-Consumables',
					'Material Resources:Request for supply-DeadStock',
					'Material Resources:Request for supply-Linen',
					'Material Resources:Request for condemnation',
					'Material Resources:Request for Purchase',
					'Acknowledgement for material received'		
				);

	echo'<table><form method=post  enctype=\'multipart/form-data\'>';
		echo '<tr><td class="field">ID</td>';
			echo '<td><input type=text name=id readonly value=\''.$letter['id'].'\'></td>';
		echo '</tr>';
		echo '<tr><td class="field">Type</td>';
			echo '<td>';mk_select_from_array_return_value('type',$type,'',$letter['type']);echo '</td>';
		echo '</tr>';

		echo '<tr><td  class="field">Date</td>';
			$exp=explode('-',$letter['date']);
			//print_r($exp);
			echo '<td>';read_date_time(3,$exp[0],$exp[1],$exp[2]);echo '</td>';
		echo '</tr>';

		echo '<tr><td  class="field">From</td>';
			echo '<td>';mk_select_from_array_return_value('from',$from,'',$letter['from']);echo '</td>';
		echo '</tr>';
		
		echo '<tr><td  class="field">To</td>';
			echo '<td>';mk_select_from_array_return_value('to',$from,'',$letter['to']);echo '</td>';
		echo '</tr>';

		echo '<tr><td  class="field">Greeting</td>';
			echo '<td>';mk_select_from_array_return_value('greeting',$greeting,'',$letter['greeting']);echo '</td>';
		echo '</tr>';

		echo '<tr><td  class="field">Subject</td>';
			echo '<td>';mk_select_from_array_return_value('subject',$subject,'',$letter['subject']);echo '</td>';
		echo '</tr>';

		echo '<tr><td  class="field">Sub-subject</td>';
			echo '<td><input type=text size=100 name=sub_subject value=\''.$letter['sub_subject'].'\'></td>';
		echo '</tr>';
		
		echo '<tr><td  class="field">Reference</td>';
			echo '<td><textarea  cols=100 rows=2 name=reference >'.$letter['reference'].'</textarea></td>';
		echo '</tr>';

		echo '<tr><td  class="field">Body</td>';
			echo '<td><textarea  cols=100 rows=25 name=body>'.$letter['body'].'</textarea></td>';
		echo '</tr>';

		echo '<tr><td  class="field">Thanks</td>';
			echo '<td>';mk_select_from_array_return_value('thanks',$thanks,'',$letter['thanks']);echo '</td>';

		echo '<tr><td  class="field">Closing</td>';
			echo '<td>';mk_select_from_array_return_value('closing',$closing,'',$letter['closing']);echo '</td>';
		echo '</tr>';


		echo '</tr>';

		echo '<tr><td  class="field">Signature</td>';
			echo '<td>';mk_select_from_array_return_value('signature',$signature,'',$letter['signature']);echo '</td>';
		echo '</tr>';

		echo '<tr><td  class="field">Copy To:</td>';
			echo '<td><textarea  cols=100 rows=2 name=copy_to></textarea>'.$letter['copy_to'].'</td>';
		echo '</tr>';


		echo '<tr><td  class="field">attachment_List</td>';
			echo '<td><textarea  cols=100 rows=2 name=attachment_list>'.$letter['attachment_list'].'</textarea></td>';
		echo '</tr>';
			
		echo '<tr><td>Attachment</td><td><input type=file name=attachment></td></tr>';
		echo '<tr><td class="field" colspan=2><input type=submit name=action value=save></td></tr>';
	echo '</form></table>';
}

if(isset($_POST['action']))
{
	if($_POST['action']!='print')
	{
		main_menu();
		echo '
	<table>
	<form method=post>
	<tr>
	<td></td><td><input type=submit name=action value=new></td>
	<td  bgcolor=lightgray><input type=submit name=action value=edit></td>
	<td bgcolor=lightgray><input type=submit name=action value=delete></td>
	<td bgcolor=lightgray><input type=submit name=action value=view></td>
	<td bgcolor=lightgray><input type=submit name=action value=print></td>
	</tr>
	<tr><td>Letter ID:</td><td colspan=5 bgcolor=lightgray><input type=text name=id></td>
	<td bgcolor=lightgreen>last_id='.max_id().'</td></tr>
	</table>
	<table>
	<tr>
	<td bgcolor=lightpink><input type=submit name=action value=register></td>
	<td>From ID:</td><td bgcolor=lightpink><input type=text name=from_id></td>
	<td>To ID:</td><td bgcolor=lightpink><input type=text name=to_id></td>

	</tr>	
	</form>
	</table>
		';
	}
}
else
{
	main_menu();
	echo '
	<table>
	<form method=post>
	<tr>
	<td></td><td><input type=submit name=action value=new></td>
	<td  bgcolor=lightgray><input type=submit name=action value=edit></td>
	<td bgcolor=lightgray><input type=submit name=action value=delete></td>
	<td bgcolor=lightgray><input type=submit name=action value=view></td>
	<td bgcolor=lightgray><input type=submit name=action value=print></td>
	</tr>
	<tr><td>Letter ID:</td><td colspan=5 bgcolor=lightgray><input type=text name=id></td>
	<td bgcolor=lightgreen>last_id='.max_id().'</td></tr>
	</table>
	<table>
	<tr>
	<td bgcolor=lightpink><input type=submit name=action value=register></td>
	<td>From ID:</td><td bgcolor=lightpink><input type=text name=from_id></td>
	<td>To ID:</td><td bgcolor=lightpink><input type=text name=to_id></td>

	</tr>	
	</form>
	</table>
	';
	
}
if(isset($_POST['action']))
{
	if($_POST['action']=='save')
	{
		save_letter($_POST);
	}
	
	if($_POST['action']=='edit')
	{
		edit_letter($_POST['id']);
	}
	
	if($_POST['action']=='new')
	{
		edit_letter(new_letter($_POST['id']));
	}
	
	if($_POST['action']=='print')
	{
		print_letter($_POST['id']);
	}
	
	if($_POST['action']=='view')
	{
		view_letter($_POST['id']);
	}

	if($_POST['action']=='register')
	{
		view_register($_POST['from_id'],$_POST['to_id']);
	}
}

?>
