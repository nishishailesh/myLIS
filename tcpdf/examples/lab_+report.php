<?php

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
		
$pdf = new TCPDF('L', 'mm', 'A5', true, 'UTF-8', false);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

//set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

//set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

//set some language-dependent strings
$pdf->setLanguageArray($l);

// ---------------------------------------------------------

// set font
$pdf->SetFont('times', '', 20);

// add a page
$pdf->AddPage();

// set some text to print
$txt = <<<EOD
TCPDF Example 002

Default page header and footer are disabled using setPrintHeader() and setPrintFooter() methods.
EOD;

// print a block of text using Write()
$pdf->Write($h=0, $txt, $link='', $fill=0, $align='C', $ln=true, $stretch=0, $firstline=false, $firstblock=false, $maxh=0);

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('example_002.pdf', 'I');

//============================================================+
// END OF FILE                                                
//============================================================+
