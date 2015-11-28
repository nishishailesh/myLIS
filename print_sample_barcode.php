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

function prepare_container_string($section,$sample_type,$preservative)
{
	$link=start_nchsls();
	$sql='select * from sample_type where sample_type=\''.$sample_type.'\'';
	$result=mysql_query($sql,$link);if($result===FALSE){echo mysql_error(); return FALSE;}
	$return_array=mysql_fetch_assoc($result);	//only one is returned

	$sql_pr='select * from preservative where preservative=\''.$preservative.'\'';
	$result_pr=mysql_query($sql_pr,$link);if($result_pr===FALSE){echo mysql_error(); return FALSE;}
	$return_array_pr=mysql_fetch_assoc($result_pr);	//only one is returned
	
	return '<'.$section.'>'.$return_array['str'].'-'. $return_array_pr['str'];
}

function print_duplicate_lable($pdf, $style, $sample_id)
{
	$link=start_nchsls();
	$sql='select * from sample where sample_id=\''.$sample_id.'\'';
	$result=mysql_query($sql,$link);if($result===FALSE){echo mysql_error(); return FALSE;}
	$return_array=mysql_fetch_assoc($result);	//only one is returned

	$tube=prepare_container_string($return_array['section'],$return_array['sample_type'],$return_array['preservative']);

	for($i=0;$i<1;$i++)
	{
		$pdf->AddPage();
		//$pdf->SetXY(02,02);
		//$pdf->SetFont('times', '', 10);
		//$pdf->Cell (45,05,$return_array['patient_name'].' '.$return_array['patient_id'] ,$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');		

		//now cursor is at 2,7									  x  y   w    h
		if($return_array['sample_id']<100)
		{
			$si=str_pad($return_array['sample_id'],3,'0',STR_PAD_LEFT);
		}
		else
		{
			$si=$return_array['sample_id'];
		}
		
		$pdf->write1DBarcode($si, 'C128', 02, 5 , 30, 13, 0.4, $style, 'N');
		
		// Start Transformation
		$pdf->SetFont('helveticaB', '', 13);
		
		$pdf->StartTransform();
		// Rotate 90 degrees counter-clockwise centered by (43,18) which is the lower left corner of the rectangle
		$pdf->Rotate(90, 43, 18);
		//$pdf->Text(39, 18, $tube.'-'.$return_array['sample_id'].'-'.$return_array['patient_name']);
		$pdf->SetXY(40,18);
		$tt1=substr($return_array['patient_name'],0,8);
		$tt2=$tube.'-'.$return_array['sample_id'];
		$pdf->Cell(18,5,$tt1,$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=2, $ignore_min_height=false, $calign='T', $valign='M');		
		$pdf->SetXY(40,14);
		$pdf->Cell(18,5,$tt2,$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=2, $ignore_min_height=false, $calign='T', $valign='M');		

		// Stop Transformation
		$pdf->StopTransform();

//		$pdf->write1DBarcode($return_array['sample_id'], 'C128', 30, 5 , 20, 13, 0.4, $style, 'N');

		//now cursor is at 2,17
		//$pdf->SetFont('times', '', 10);
		//$pdf->SetXY(02,17);
		//$pdf->Cell (38,5,$return_array['clinician'].'/'.$return_array['unit'].'/'.$return_array['location'],$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');		

		//now cursor is at 40,17
		
		$tt_below_barcode=$tube.'-'.substr($return_array['patient_name'],0,13);
		$pdf->SetFont('helveticaB', '', 13);
		
		$pdf->SetXY(5,18);
		$pdf->Cell (25,5,$tt_below_barcode,$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=2, $ignore_min_height=false, $calign='T', $valign='M');		
		
		//$pdf->SetXY(42,18);
		//$pdf->Cell (7,5,$tube,$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=2, $ignore_min_height=false, $calign='T', $valign='M');		
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

$sample_id_list=explode("|",$_POST['list_of_samples']);
//print_r($sample_id_list);

foreach($sample_id_list as $key=>$value)
{
	if(strlen($value)>0)
	{
		print_duplicate_lable($pdf,$style,$value);	
	}
}

$filename=str_replace("|","-",$_POST['list_of_samples']).'.pdf';
$pdf->Output($filename, 'I');


?>
