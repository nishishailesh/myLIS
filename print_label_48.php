<?php 
session_start();

include 'common.php';
require_once('tcpdf/config/lang/eng.php');
require_once('tcpdf/tcpdf.php');

/*
echo '<pre>';
print_r($GLOBALS);
echo '</pre>';
*/


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

if(!isset($_POST['submit']))
{
main_menu();
echo '<form method=post target=_blank><table>
<th colspan=2>Print 48 Lables in 4x12 array on A4 page</th>
<tr><td>Text of first line</td><td><input type=text name=line1></td></tr>
<tr><td>Text of second line</td><td><input type=text name=line2></td></tr>
<tr><td>Text of third line</td><td><input type=text name=line3></td></tr>
<tr><td><input type=submit name=submit value=print></td></tr>
</table></form>';
}

else
{
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
		'fontsize' => 6,
		'stretchtext' => 4
	);

		//Page header W=210 H=148
		//210 × 297 A4
		//180,x270 (30,27)
		//3 x 9
		//$this->SetXY(18,$y_counter);
		
	$pdf = new MYPDF_BARCODE('P', 'mm', 'A4', true, 'UTF-8', false);
	$pdf->AddPage();
	$pdf->SetMargins(0,0, $right=-1, $keepmargins=true);
	$pdf->setPrintFooter(false);
	$pdf->setPrintHeader(false);
	$pdf->SetAutoPageBreak(TRUE, 0);


	$pdf->setCellPaddings(0,0,0,0);

	/*
	 * planning
	 * 210 × 297 A4
	 * 4 columns, 12 raws, 5 mm margin  so lable width=50
	 * 12 raws, 10 mm margin, so 23 mm height
	 * */
	 
	 $um=5; //upper margin
	 $lm=5; //left margin
	 $cs=3;  //space between columns
	 $lw=48;//width 
	 $lh=24;//height 



	$text1=$_POST['line1'];
	$text2=$_POST['line2'];
	$text3=$_POST['line3'];


	//////////////////with text printed twice to take care of lable misalignment
	for($i=0;$i<4;$i++)
	{
		for($j=0;$j<12;$j++)
		{
			$pdf->SetFont('times', '', 8);

			$pdf->SetXY(($i*48)+($i*3)+5+2 ,($j*24)+5 );
			$pdf->Cell($w=48-4, $h=4,$text1 ,$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');		
		
			$pdf->SetXY(($i*48)+($i*3)+5+2 ,($j*24)+10 );
			$pdf->Cell($w=48-4, $h=4,$text2 ,$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');		
			
			$pdf->SetXY(($i*48)+($i*3)+5+2 ,($j*24)+15 );
			$pdf->Cell($w=48-4, $h=4,$text3 ,$border=0, $ln=0, $align='', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');		

		}
	}

	$filename='lable.pdf';
	$pdf->Output($filename, 'I');
}
?>
