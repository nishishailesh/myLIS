<?php
require_once('../../common.php');
require_once('../config/lang/eng.php');
require_once('../tcpdf.php');


session_start();

class MYPDF_NABL extends TCPDF {
	public $lab_name='New Civil Hospital Surat Laboratory Services';
	public $section_name='Biochemistry Section';
	public $address_phone='2nd Floor, Near Blood Bank, NCH Surat(Guj) Ph: 2224445 Ext:317,366';
	public $nabl_symbol='nabl.jpg';
	public $blank_symbol='blank.jpg';
	public $nabl_cert_no='Cert. No:X-1234';
	public $blank_cert_no='';
	public $bypass_autoverification='no';		//if 'yes'=>it will bypass autoverification
	public $sample_id_array;
	public $sample_id;
	public $doctor;
	public $login;
	//Page header W=210 H=148
	//$this->Write(0,$this->getPageWidth());
	public function Header() 
	{
		$this->Rect(10, 10,190,30, $style='', $border_style=array(), $fill_color=array());
		//write six lines in header
		//all header things XY between 10,10 and 200,40
		//public function Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $stretch=0, 
		//						$ignore_min_height=false, $calign='T', $valign='M')
		$this->SetFont('helvetica', 'B', 10);
		$this->SetXY(10,10);
		$this->Cell(190, $h=0, $txt=$this->lab_name.' ('.$this->section_name.')', $border=0, $ln=0, $align='C', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

		$this->SetFont('helvetica', '', 10);
		$this->SetXY(10,15);
		$this->Cell(190, $h=0, $txt=$this->address_phone, $border=0, $ln=0, $align='C', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

///////////Print NABL symbol if any one is accredited
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
			$this->Image($image_file, 10, 10,275/12, 320/12, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
	
			$this->SetXY(10,36);//(Y=10+(320/12)=36)
			$this->Cell(275/12, $h=0, $txt=$this->nabl_cert_no, $border=0, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
		}

//////////////////Print Sample data
		if(mysql_num_rows($result_sample_data=mysql_query($sql_sample_data,$linkk))>0)
		{
			$border=0;
			$sample_array=mysql_fetch_assoc($result_sample_data);
			////line 1
			$this->SetXY(35,20); //275/12=22 22+10=32
			//210-10-35=165 remaining//55 name,mrd,sampleif=165 
			$this->Cell(54, $h=0, $txt='Patient Name: '.$sample_array['patient_name'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			//35+55=90
			$this->SetXY(90,20); //275/12=22 22+10=32
			$this->Cell(54, $h=0, $txt='MRD: '.$sample_array['patient_id'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			//90+55=145
			$this->SetXY(145,20); //275/12=22 22+10=32
			//210-35=175 remaining//55 name,mrd,sampleif=165+ 5,5 space 
			$this->SetFont('helvetica', 'B', 12);
			$this->Cell(54, $h=0, $txt='Sample ID: '.$sample_array['sample_id'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
			
			$this->SetFont('helvetica', '', 10);
			////line 2
			$this->SetXY(35,25); //275/12=22 22+10=32
			//210-10-35=165 remaining//55 name,mrd,sampleif=165 
			$this->Cell(54, $h=0, $txt='Received: '.$sample_array['sample_receipt_time'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			//35+55=90
			$this->SetXY(90,25); //275/12=22 22+10=32
			$this->Cell(54, $h=0, $txt='Reported: '.strftime('%Y-%m-%d %H:%M:%S'), $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			//90+55=145
			$this->SetXY(145,25); //275/12=22 22+10=32
			//210-35=175 remaining//55 name,mrd,sampleif=165+ 5,5 space 
			$this->Cell(54, $h=0, $txt=$sample_array['clinician'].' Unit:'.$sample_array['unit'].' '.$sample_array['location'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');


			////line 3
			$this->SetXY(35,30); //275/12=22 22+10=32
			//210-10-35=165 remaining//55 name,mrd,sampleif=165 
			$this->Cell(54, $h=0, $txt='Sample Type: '.$sample_array['sample_type'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			//35+55=90
			$this->SetXY(90,30); //275/12=22 22+10=32
			$this->Cell(54, $h=0, $txt='Preservative: '.$sample_array['preservative'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			//90+55=145
			$this->SetXY(145,30); //275/12=22 22+10=32
			//210-35=175 remaining//55 name,mrd,sampleif=165+ 5,5 space 
			$this->Cell(54, $h=0, $txt=$sample_array['sample_details'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');


			////line 4
			$this->SetXY(35,35); //275/12=22 22+10=32
			//210-10-35=165 remaining//55 name,mrd,sampleif=165 
			$this->Cell(109, $h=0, $txt='Collection Time/Age/Sex/Dx: '.$sample_array['details'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

			//90+55=145
			$this->SetFont('helvetica', 'B', 10);
			$this->SetXY(145,35); //275/12=22 22+10=32
			//210-35=175 remaining//55 name,mrd,sampleif=165+ 5,5 space 
			$this->Cell(54, $h=0, $txt='Status: '.$sample_array['status'], $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
			$this->SetFont('helvetica', '', 10);
		}
				$border=1;
				$counter=45;
				$this->SetXY(10,$counter);
				$this->Cell($w=10, $h=0, 'NABL Accr.',$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
				
				$this->SetXY(20,$counter);
				$this->Cell($w=40, $h=0, 'Examination',$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

				$this->SetXY(60,$counter);
				$this->Cell($w=40, $h=0, 'Result',$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

				$this->SetXY(100,$counter);
				$this->Cell($w=40, $h=0, 'Referance range',$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

				$this->SetXY(140,$counter);
				$this->Cell($w=20, $h=0,'Alert',$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
				
				$this->SetXY(160,$counter);					
				$this->Cell($w=40, $h=0,'Method',$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');		
		
	}

	// Page footer
	public function Footer() 
	{
		$border=1;
		$this->SetFont('helvetica', 'B', 10);
		$this->SetXY(10,-10);
		$this->Cell(95, $h=0, $txt='Examinations marked \'No\' are not NABL Accredited.', $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
		$this->SetFont('helvetica', '', 10);
		$this->SetXY(105,-10);
		$this->Cell(95, $h=0, $txt='Page:'.$this->getPageNumGroupAlias().'/'.$this->getPageGroupAlias(), $border, $ln=0, $align='R', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

		//this start at Y=127
		//210/4=52 50-20=30,70   130,170
		$border=1;
		$this->SetFont('helvetica', '', 10);
		$this->SetXY(30,-20);
		$this->Cell(40, $h=10, $txt=$this->login, $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
		$this->SetXY(130,-20);
		$this->Cell(40, $h=10, $txt=$this->doctor, $border, $ln=0, $align='L', $fill=false, $link='', $stretch=1, $ignore_min_height=false, $calign='T', $valign='M');


	}
}


function print_report_pdf_A5($sample_id_array,$doctor)
{
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
	//A5=210,148
	$pdf = new MYPDF_NABL('L', 'mm', 'A5', true, 'UTF-8', false);
	$pdf->sample_id_array=$sample_id_array;
	$pdf->doctor=$doctor;
	$pdf->login=$_SESSION['login'];
	
	// set default monospaced font
	//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
	//set margins
	$pdf->SetMargins(10, 50);
	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, 10);

	//set image scale factor
	//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	//$pdf->SetFont('times', '', 10);
	$pdf->SetFont('helvetica', '', 8);
	
	
	foreach($sample_id_array as $value)
	{
		$pdf->sample_id=$value;
		$pdf->startPageGroup();
		$pdf->AddPage();
		
		$linkk=start_nchsls();
		$sql_examination_data='select * from examination where sample_id=\''.$pdf->sample_id.'\' order by name_of_examination';
		$result_examination_data=mysql_query($sql_examination_data,$linkk);
		$counter=45;
		$pdf->SetFont('helvetica','',10);
		$border=0;
		while($examination_array=mysql_fetch_assoc($result_examination_data))
		{
			$counter=$counter+5;
			if($examination_array['id']<1000)
			{	//available 190 mm
				//10+40+40+40+20+40
				
				$pdf->SetXY(10,$counter);
				$pdf->Cell($w=10, $h=0, $examination_array['NABL_Accredited'],$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
				
				$pdf->SetXY(20,$counter);
				$pdf->Cell($w=40, $h=0, $examination_array['name_of_examination'],$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

				$pdf->SetXY(60,$counter);
				$pdf->Cell($w=40, $h=0, $examination_array['result'],$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

				$pdf->SetXY(100,$counter);
				$pdf->Cell($w=40, $h=0, $examination_array['referance_range'].' '.$examination_array['unit'],$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');

				$pdf->SetXY(140,$counter);
				$acr=$acr_check_code[check_critical_abnormal_reportable($examination_array['sample_id'],$examination_array['code'])];
				$pdf->Cell($w=20, $h=0,$acr,$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
				
				$pdf->SetXY(160,$counter);					
				$pdf->Cell($w=40, $h=0,$examination_array['method_of_analysis'],$border, $ln=0, $align='', $fill=false, $link='', 
					$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
				if($counter>=120){$counter=45;$pdf->AddPage();}
			}
			else
			{
				$pdf->SetXY(10,$counter);
				$pdf->Cell($w=50, $h=0, trim($examination_array['name_of_examination'],'Z_'),$border, $ln=0, $align='', $fill=false, $link='', 
				$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
				
				$pdf->SetXY(60,$counter);
				$pdf->Cell($w=140, $h=0, $examination_array['result'],$border, $ln=0, $align='', $fill=false, $link='', 
				$stretch=1, $ignore_min_height=false, $calign='T', $valign='M');
				if($counter>=120){$counter=45;$pdf->AddPage();}
			}
		}
	
	}
	
	$pdf->Output('report.pdf', 'I');
}

print_report_pdf_A5(array(120909102930,102907,102908,102909,102910),'Dr XYZ');
