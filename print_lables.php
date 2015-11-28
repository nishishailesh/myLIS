<?php 
session_start();

include 'common.php';
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');


//echo '<pre>';
//print_r($GLOBALS);
//echo '</pre>';


/*
2015-01-02: added str_pad to tackle problem of barcode reading when sample_id<100)

*/

function print_lable_general($pdf, $style, $line1,$line2,$line3,$line4,$number)
{

	$pdf->SetFont('helveticaB', '', 9);
	for($i=0;$i<$number;$i++)
	{
		$pdf->AddPage();
		
		//3 mm all side
		//25-6=19(20)
		//4 lines of 5 mm
		//50-6=44 available width
		$pdf->SetXY(3,3);
		$pdf->Cell (44,5,$line1,$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');		
		$pdf->SetXY(3,8);
		$pdf->Cell (44,5,$line2,$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');		
		$pdf->SetXY(3,13);
		$pdf->Cell (44,5,$line3,$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');		
		$pdf->SetXY(3,18);
		$pdf->Cell (44,5,$line4,$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');		
	}
}


function print_lable_incremental($pdf, $style, $line1,$line2,$line3,$line4,$number)
{

	$pdf->SetFont('helveticaB', '', 9);
	for($i=0;$i<$number;$i++)
	{
		$pdf->AddPage();
		
		//3 mm allside
		//25-6=19/4=5
		//50-6=44
		$pdf->SetXY(3,3);
		$pdf->Cell (44,5,$line1,$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');		
		$pdf->SetXY(3,8);
		$pdf->Cell (44,5,$line2." ".($i+1),$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');		
		$pdf->SetXY(3,13);
		$pdf->Cell (44,5,$line3,$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');		
		$pdf->SetXY(3,18);
		$pdf->Cell (44,5,$line4,$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');		
	}
}


class MYPDF_BARCODE extends TCPDF 
{
	public function Header() 
	{

	}

	// Page footer
	public function Footer() 
	{

	}
}

login_varify();
	// for barcode
	$style = array(
		'position' => '',
		'align' => 'C',
		'stretch' => false,
		'fitwidth' => true,
		'cellfitalign' => '',
		'border' => false,
		'hpadding' => 'auto',
		'vpadding' => '0',
		'fgcolor' => array(0,0,0),
		'bgcolor' => false, //array(255,255,255),
		'text' => true,
		'font' => 'helvetica',
		'fontsize' => 10,
		'stretchtext' => 4
	);


	$pdf = new MYPDF_BARCODE('', 'mm', array("50","25"), true, 'UTF-8', false);
	
	$pdf->SetMargins(0,0, $right=-1, $keepmargins=true);
	$pdf->setPrintFooter(false);
	$pdf->setPrintHeader(false);
	$pdf->SetAutoPageBreak(TRUE, 0);
	$pdf->setCellPaddings(0,0,0,0);



	//minimum 2 mm margin
	//25-4=21 available Y
	//50-5=46 available X
	//5 line 1, 10 barcode, 5 line 3
function general_form()
{
	echo '<form method=post target=_blank><table border=1>
	<tr><th colspan=10 style="background-color:lightpink;">50mmx25mm barcode lables</th></tr>
	<tr><th colspan=10 style="background-color:lightblue;">Print multiple copies of lables with 4 lines</th></tr>
	<tr><td>line1</td><td><input type=text name=line1></td></tr>
	<tr><td>line1</td><td><input type=text name=line2></td></tr>
	<tr><td>line1</td><td><input type=text name=line3></td></tr>
	<tr><td>line1</td><td><input type=text name=line4></td></tr>
	<tr><td>copies</td><td><input type=text name=number></td></tr>
	<tr><th style="background-color:lightblue;" colspan=10 ><input type=submit name=action value=general></th></tr>
	</table></form>';
	
}

function incremental_form()
{
	echo '<form method=post target=_blank><table border=1>
	<tr><th colspan=10 style="background-color:lightpink;">50mmx25mm barcode lables</th></tr>
	<tr><th colspan=10 style="background-color:lightblue;">Print incremental copies of lables with 4 lines</th></tr>
	<tr><td>line1</td><td><input type=text name=line1></td></tr>
	<tr><td>line1</td><td><input type=text name=line2><td>1 to </td><td><input type=text name=number></td></tr>
	<tr><td>line1</td><td><input type=text name=line3></td></tr>
	<tr><td>line1</td><td><input type=text name=line4></td></tr>
	<tr><th style="background-color:lightblue;" colspan=10 ><input type=submit name=action value=incremental></th></tr>
	</table></form>';
	
}

if(isset($_POST['action']))
{
	if($_POST['action']=='general')
	{
		print_lable_general($pdf,$style,$_POST['line1'],$_POST['line2'],$_POST['line3'],$_POST['line4'],$_POST['number']);	
	}

	elseif($_POST['action']=='incremental')
	{
		print_lable_incremental($pdf,$style,$_POST['line1'],$_POST['line2'],$_POST['line3'],$_POST['line4'],$_POST['number']);	
	}
	$pdf->Output('labels.php', 'I');	

}

else
{
	main_menu();
	general_form();
	incremental_form();
}



?>
