<?php
require_once('common.php');
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');


session_start();

/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/

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


function save_letter($letter)
{
	$sql='insert into letter (id,type,`from`,`to`,`date`,greeting,subject,sub_subject,`reference`,`body`,closing,thanks,signature,attachment_list,copy_to)
			values
			(
			\'\',
			\''.	mysql_real_escape_string($letter['type']).'\',
			\''.	mysql_real_escape_string($letter['from']).'\',
			\''.	mysql_real_escape_string($letter['to']).'\',
			\''.	mysql_real_escape_string($letter['year'].'-'.$letter['month'].'-'.$letter['day']).'\',
			\''.	mysql_real_escape_string($letter['greeting']).'\',
			\''.	mysql_real_escape_string($letter['subject']).'\',
			\''.	mysql_real_escape_string($letter['sub_subject']).'\',
			\''.	mysql_real_escape_string($letter['reference']).'\',
			\''.	mysql_real_escape_string($letter['body']).'\',			
			\''.	mysql_real_escape_string($letter['closing']).'\',	
			\''.	mysql_real_escape_string($letter['thanks']).'\',
			\''.	mysql_real_escape_string($letter['signature']).'\',
			\''.	mysql_real_escape_string($letter['attachment_list']).'\',
			\''.	mysql_real_escape_string($letter['copy_to']).'\')';
			
		$link=start_nchsls();
		if(!$result=mysql_query($sql,$link)){echo mysql_error();}
		return mysql_insert_id($link);	
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
		$pdf->MultiCell($w=190, $h=0, $txt=$pdf->letter['body'].',', $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=true);

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
		$pdf->SetXY($x,$y+5);
		$pdf->MultiCell($w=190, $h=0, $txt=$pdf->letter['signature'].',', $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=0, $valign='T', $fitcell=true);

		if(strlen($pdf->letter['attachment_list'])>0)
		{
		$x=$pdf->getX();
		$y=$pdf->getY();
		$pdf->SetFont('helvetica', 'B', 10);
		$pdf->SetXY(10,$y+10);
		$pdf->MultiCell($w=20, $h=0, $txt='Attachments:', $border, $align='L', $fill=false, $ln=0, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=5, $valign='T', $fitcell=true);
		$pdf->SetFont('helvetica', '', 10);
		$pdf->MultiCell($w=210-10-30, $h=0, $txt=$pdf->letter['attachment_list'], $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=$max_height, $valign='T', $fitcell=true);
		}

		if(strlen($pdf->letter['copy_to'])>0)
		{
		$x=$pdf->getX();
		$y=$pdf->getY();
		$pdf->SetFont('helvetica', 'B', 10);
		$pdf->SetXY(10,$y+10);
		$pdf->MultiCell($w=20, $h=0, $txt='Copy to:', $border, $align='L', $fill=false, $ln=0, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=5, $valign='T', $fitcell=true);
		$pdf->SetFont('helvetica', '', 10);
		$pdf->MultiCell($w=210-10-30, $h=0, $txt=$pdf->letter['copy_to'], $border, $align='L', $fill=false, $ln=1, $x='', $y='', $reseth=true, $stretch=1, $ishtml=false, $autopadding=true, $maxh=$max_height, $valign='T', $fitcell=true);
		}

	$filename=$pdf->letter['id'].'-'.$pdf->letter['type'].'-'.$pdf->letter['date'].'.pdf';
	$pdf->Output($filename, 'I');
}


if(!login_varify())
{
exit();
}

if($_POST['action'])
{
	if($_POST['action']=='save')
	{
		save_letter($_POST);
	}
}
