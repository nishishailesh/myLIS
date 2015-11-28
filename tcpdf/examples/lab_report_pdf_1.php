<?php
require_once('../../common.php');
require_once('../config/lang/eng.php');
require_once('../tcpdf.php');

// create new PDF document
	/**
	 * This is the class constructor.
	 * It allows to set up the page format, the orientation and the measure unit used in all the methods (except for the font sizes).
	 * @param $orientation (string) page orientation. Possible values are (case insensitive):<ul><li>P or Portrait (default)</li><li>L or Landscape</li><li>'' (empty string) for automatic orientation</li></ul>
	 * @param $unit (string) User measure unit. Possible values are:<ul><li>pt: point</li><li>mm: millimeter (default)</li><li>cm: centimeter</li><li>in: inch</li></ul><br />A point equals 1/72 of inch, that is to say about 0.35 mm (an inch being 2.54 cm). This is a very common unit in typography; font sizes are expressed in that unit.
	 * @param $format (mixed) The format used for pages. It can be either: one of the string values specified at getPageSizeFromFormat() or an array of parameters specified at setPageFormat().
	 * @param $unicode (boolean) TRUE means that the input text is unicode (default = true)
	 * @param $encoding (string) Charset encoding; default is UTF-8.
	 * @param $diskcache (boolean) If TRUE reduce the RAM memory usage by caching temporary data on filesystem (slower).
	 * @param $pdfa (boolean) If TRUE set the document to PDF/A mode.
	 * @public
	 * @see getPageSizeFromFormat(), setPageFormat()
	 
	public function __construct($orientation='P', $unit='mm', $format='A4', $unicode=true, $encoding='UTF-8', $diskcache=false, $pdfa=false) {
	*/
session_start();
$lab_name='New Civil Hospital Surat Laboratory Services';
$section_name='Biochemistry Section';
$address_phone='2nd Floor, Near Blood Bank, NCH Surat(Guj) Ph: 2224445 Ext:317,366';
$nabl_symbol='nabl.jpg';
$blank_symbol='blank.jpg';
$nabl_cert_no='Cert. No:X-1234';
$blank_cert_no='';
$bypass_autoverification='no';		//if 'yes'=>it will bypass autoverification


class MYPDF_NABL extends TCPDF {
	public $lab_name='New Civil Hospital Surat Laboratory Services';
	public $section_name='Biochemistry Section';
	public $address_phone='2nd Floor, Near Blood Bank, NCH Surat(Guj) Ph: 2224445 Ext:317,366';
	public $nabl_symbol='nabl.jpg';
	public $blank_symbol='blank.jpg';
	public $nabl_cert_no='Cert. No:X-1234';
	public $blank_cert_no='';
	public $bypass_autoverification='no';		//if 'yes'=>it will bypass autoverification
	public $sample_id;
	
	
	//Page header
	public function Header() {
		$linkk=start_nchsls();
		$sql_sample_data='select * from sample where sample_id='.$this->sample_id;
		$sql_examination_data='select * from examination where sample_id=\''.$this->sample_id.'\' order by name_of_examination';

		$NABL_acc_counter=0;
		$result_examination_data_for_accr=mysql_query($sql_examination_data,$linkk);
		while($acc_array=mysql_fetch_assoc($result_examination_data_for_accr))
		{
			if($acc_array['NABL_Accredited']=='Yes')
			{
				$NABL_acc_counter++;
			}
		}
		if($NABL_acc_counter>0)
		{
		$image_file = '../../'.$this->nabl_symbol;
		$this->Image($image_file, 5, 5,20, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		}
		$this->SetY(5);
		$this->Write($h=0, $this->lab_name, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
		$this->Write($h=0, $this->section_name, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
		$this->Write($h=0, $this->address_phone, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
		$this->SetY(30);
		$this->SetFont('helvetica', 'B', 8);
		$this->Write($h=0, $this->nabl_cert_no, $link='', $fill=0, $align='L', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);
		
		if(mysql_num_rows($result_sample_data=mysql_query($sql_sample_data,$linkk))>0)
		{
			$sample_array=mysql_fetch_assoc($result_sample_data);
		
				/**
	 * Prints a cell (rectangular area) with optional borders, background color and character string. The upper-left corner of the cell corresponds to the current position. The text can be aligned or centered. After the call, the current position moves to the right or to the next line. It is possible to put a link on the text.<br />
	 * If automatic page breaking is enabled and the cell goes beyond the limit, a page break is done before outputting.
	 * @param $w (float) Cell width. If 0, the cell extends up to the right margin.
	 * @param $h (float) Cell height. Default value: 0.
	 * @param $txt (string) String to print. Default value: empty string.
	 * @param $border (mixed) Indicates if borders must be drawn around the cell. The value can be a number:<ul><li>0: no border (default)</li><li>1: frame</li></ul> or a string containing some or all of the following characters (in any order):<ul><li>L: left</li><li>T: top</li><li>R: right</li><li>B: bottom</li></ul> or an array of line styles for each border group - for example: array('LTRB' => array('width' => 2, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)))
	 * @param $ln (int) Indicates where the current position should go after the call. Possible values are:<ul><li>0: to the right (or left for RTL languages)</li><li>1: to the beginning of the next line</li><li>2: below</li></ul> Putting 1 is equivalent to putting 0 and calling Ln() just after. Default value: 0.
	 * @param $align (string) Allows to center or align the text. Possible values are:<ul><li>L or empty string: left align (default value)</li><li>C: center</li><li>R: right align</li><li>J: justify</li></ul>
	 * @param $fill (boolean) Indicates if the cell background must be painted (true) or transparent (false).
	 * @param $link (mixed) URL or identifier returned by AddLink().
	 * @param $stretch (int) font stretch mode: <ul><li>0 = disabled</li><li>1 = horizontal scaling only if text is larger than cell width</li><li>2 = forced horizontal scaling to fit cell width</li><li>3 = character spacing only if text is larger than cell width</li><li>4 = forced character spacing to fit cell width</li></ul> General font stretching and scaling values will be preserved when possible.
	 * @param $ignore_min_height (boolean) if true ignore automatic minimum height value.
	 * @param $calign (string) cell vertical alignment relative to the specified Y value. Possible values are:<ul><li>T : cell top</li><li>C : center</li><li>B : cell bottom</li><li>A : font top</li><li>L : font baseline</li><li>D : font bottom</li></ul>
	 * @param $valign (string) text vertical alignment inside the cell. Possible values are:<ul><li>T : top</li><li>C : center</li><li>B : bottom</li></ul>
	 * @public
	 * @since 1.0
	 * @see SetFont(), SetDrawColor(), SetFillColor(), SetTextColor(), SetLineWidth(), AddLink(), Ln(), MultiCell(), Write(), SetAutoPageBreak()
	 
	public function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $stretch=0, 
	* $ignore_min_height=false, $calign='T', $valign='M') */
			$wth=$this->getPageWidth();
			$from_x=$wth/5;
			$to_x=$wth-($wth/5);
			$this->Line($from_x,22,$to_x,22);
			
			$this->SetY(25);
			$this->Cell($w=30, $h=0, '', $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=0, $ignore_min_height=false, $calign='T', $valign='M');
			$aw=($wth-30-5);
			$this->Cell($w=$aw*0.25, $h=0, 'Name:'.$sample_array['patient_name'], $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
			$this->Cell($w=$aw*0.25, $h=0, 'MRD:'.$sample_array['patient_id'], $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
			$this->Cell($w=$aw*0.25, $h=0, 'Department:'.$sample_array['clinician'], $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');					
			$this->Cell($w=$aw*0.10, $h=0, 'Unit:'.$sample_array['unit'], $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
			$this->Cell($w=$aw*0.15, $h=0, 'Location:'.$sample_array['location'], $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');	

			$this->SetY(30);
			$this->Cell($w=$aw-25, $h=0, '', $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=0, $ignobre_min_height=false, $calign='T', $valign='M');
			$this->SetFont('helvetica', '', 18);	
			$this->Cell($w=50, $h=0, 'Sample ID:'.$sample_array['sample_id'], $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=2, $ignore_min_height=false, $calign='T', $valign='M');

			$this->SetY(30);
			$this->SetFont('helvetica','B',8);
			$this->Cell($w=30, $h=0, '', $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=0, $ignore_min_height=false, $calign='T', $valign='M');				

			$this->Cell($w=$aw*0.25, $h=0, 'Sample Type:'.$sample_array['sample_type'], $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');					
			$this->Cell($w=$aw*0.25, $h=0, 'Preservative:'.$sample_array['preservative'], $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
						
						$this->SetFont('helvetica','B',18);
			$this->Cell($w=$aw*0.15, $h=0, $sample_array['status'], $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=2, $ignore_min_height=false, $calign='T', $valign='M');
						$this->SetFont('helvetica','B',8);
						
			$this->SetY(35);
			$this->Cell($w=30, $h=0, '', $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=0, $ignore_min_height=false, $calign='T', $valign='M');						
			$this->Cell($w=$aw/3, $h=0, 'Collection Time/A/S/Dx:'.$sample_array['details'], $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');							

			$this->SetY(40);
			$this->Cell($w=30, $h=0, '', $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=0, $ignore_min_height=false, $calign='T', $valign='M');						
			$this->Cell($w=$aw*0.35, $h=0, 'Receipt time:'.$sample_array['sample_receipt_time'], $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');					
			$this->Cell($w=$aw*0.35, $h=0, 'Report time:'.strftime('%Y-%m-%d %H:%M:%S'), $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');						
			$this->Cell($w=$aw*0.30, $h=0, $sample_array['sample_details'], $border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');			
			$this->Line($from_x,45,$to_x,45);			
			
			
					$this->SetY(50);
					$this->SetFont('helvetica','B',8);
					$this->Cell($w=$wth*0.1, $h=0, 'NABL_Accredited',$border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
					$this->Cell($w=$wth*0.2, $h=0, 'name_of_examination',$border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
					$this->Cell($w=$wth*0.2, $h=0,'result',$border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
					$this->Cell($w=$wth*0.2, $h=0, 'referance_range',$border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
					$this->Cell($w=$wth*0.1, $h=0,'Alert',$border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
					$this->Cell($w=$wth*0.2, $h=0,'method_of_analysis',$border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');			
		}

	}

	// Page footer
	public function Footer() {
		// Position at 15 mm from bottom
		$this->SetY(-10);
		// Set font
		//$this->SetFont('helvetica', 'I', 8);
		// Page number
		$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
	}
}


// create new PDF document
$pdf = new MYPDF_NABL('L', 'mm', 'A5', true, 'UTF-8', false);
$pdf->sample_id=102907;
$sample_id=102907;
//$pdf->setPrintHeader(false);
//$pdf->setPrintFooter(false);



// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(5, 55);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 5);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', '', 10);
$acr_check_code=array(	'-1'=>'',
					'-2'=>'',
					'-3'=>'',
					'0'=>'',
					'1'=>'low absurd',
					'2'=>'high absurd',					
					'3'=>'low critical',
					'4'=>'high critical',
					'5'=>'',
					'6'=>'');
// add a page
$pdf->AddPage();
				$wth=$pdf->getPageWidth()-10;
		$linkk=start_nchsls();
		$sql_examination_data='select * from examination where sample_id=\''.$sample_id.'\' order by name_of_examination';
		$result_examination_data=mysql_query($sql_examination_data,$linkk);
		
		

							
		$counter=55;
		$pdf->SetFont('helvetica','',8);
			while($examination_array=mysql_fetch_assoc($result_examination_data))
			{
				//if($examination_array['id']<1000)
				//{
					$pdf->SetY($counter);$counter=$counter+5;
					$pdf->Cell($w=$wth*0.1, $h=0, $examination_array['NABL_Accredited'],$border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
					$pdf->Cell($w=$wth*0.2, $h=0, $examination_array['name_of_examination'],$border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
					$pdf->Cell($w=$wth*0.2, $h=0, $examination_array['result'],$border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
					$pdf->Cell($w=$wth*0.2, $h=0, $examination_array['referance_range'].' '.$examination_array['unit'],$border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
					$acr=$acr_check_code[check_critical_abnormal_reportable($examination_array['sample_id'],$examination_array['code'])];
					$pdf->Cell($w=$wth*0.1, $h=0,$acr,$border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
					$pdf->Cell($w=$wth*0.2, $h=0,$examination_array['method_of_analysis'],$border=0, $ln=0, $align='', $fill=false, $link='', 
						$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');						
				//}
				//else
				//{
				//		$pdf->SetY($counter);$counter=$counter+5;
				//		$pdf->Cell($w=$wth*0.2, $h=0, $examination_array['name_of_examination'],$border=0, $ln=0, $align='', $fill=false, $link='', 
				//		$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
				//		$pdf->Cell($w=$wth*0.8, $h=0, $examination_array['result'],$border=0, $ln=0, $align='', $fill=false, $link='', 
				//		$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');				
				//}
			}

// print a block of text using Write()
	/**
	 * This method prints text from the current position.<br />
	 * @param $h (float) Line height
	 * @param $txt (string) String to print
	 * @param $link (mixed) URL or identifier returned by AddLink()
	 * @param $fill (boolean) Indicates if the cell background must be painted (true) or transparent (false).
	 * @param $align (string) Allows to center or align the text. Possible values are:<ul><li>L or empty string: left align (default value)</li><li>C: center</li><li>R: right align</li><li>J: justify</li></ul>
	 * @param $ln (boolean) if true set cursor at the bottom of the line, otherwise set cursor at the top of the line.
	 * @param $stretch (int) font stretch mode: <ul><li>0 = disabled</li><li>1 = horizontal scaling only if text is larger than cell width</li><li>2 = forced horizontal scaling to fit cell width</li><li>3 = character spacing only if text is larger than cell width</li><li>4 = forced character spacing to fit cell width</li></ul> General font stretching and scaling values will be preserved when possible.
	 * @param $firstline (boolean) if true prints only the first line and return the remaining string.
	 * @param $firstblock (boolean) if true the string is the starting of a line.
	 * @param $maxh (float) maximum height. The remaining unprinted text will be returned. It should be >= $h and less then remaining space to the bottom of the page, or 0 for disable this feature.
	 * @param $wadj (float) first line width will be reduced by this amount (used in HTML mode).
	 * @param $margin (array) margin array of the parent container
	 * @return mixed Return the number of cells or the remaining string if $firstline = true.
	 * @public
	 * @since 1.5
	 
	public function Write($h, $txt, $link='', $fill=false, $align='', $ln=false, $stretch=0, $firstline=false, $firstblock=false, $maxh=0, $wadj=0, $margin='') {
		// check page for no-write regions and adapt page margins if necessary
		*/
		
// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('report.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
