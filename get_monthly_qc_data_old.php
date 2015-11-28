<?php
session_start();

include 'common.php';

if(!login_varify())
{
exit();
}
main_menu();
/*
SELECT code,   	(
			(STDDEV_SAMP((result/target)*100))
			/
			(avg((result/target)*100))
		)*100 
		FROM `qc` group by code 						//CV with avg


SELECT code,   	(STDDEV_SAMP((result/target)*100))
		
		FROM `qc` group by code 						//CV with target



*/

function get_qc_data_yearly($equipment_name,$yymm)
{
$link=start_nchsls();

$yymm=round($yymm/100,0);
$yymm=$yymm*100;
//echo $yymm;
$QC_5=$yymm*10000 + 500000000;
$QC_5_last=($yymm+12)*10000 + 500000000+9999;

$QC_8=$yymm*10000 + 800000000;


$QC_8_last=($yymm+12)*10000 + 800000000+9999;


$sql_qc='
		SELECT code,round(STDDEV((result/target)*100),1)  SD_100, round(avg((result/target)*100) ,1) Target_100 ,	round(
							(
							(STDDEV((result/target)*100)) 
							/
							(avg((result/target)*100))
							)*100,
							1
							)  CV
		FROM `qc` where 
		equipment_name=\''.$equipment_name.'\' and 
		(
		sample_id between \''.$QC_5.'\' and \''.$QC_5_last.'\'  or
		sample_id between \''.$QC_8.'\' and \''.$QC_8_last.'\'
		)
		and comment=\'1\'
		group by code
		';
	
echo $sql_qc;

if(!$result_qc=mysql_query($sql_qc,$link)){echo mysql_error();}

	echo '<tr bgcolor=lightpink><th>Code</th><th>%Bias</th><th>CV%</th></tr>';

	while($array_qc=mysql_fetch_assoc($result_qc))
	{
		if($array_qc['SD_100']!=NULL || $array_qc['Target_100']!=NULL || $array_qc['CV']!=NULL)
		{
			echo '<tr><td>'.$array_qc['code'].'</td><td>'.round(($array_qc['Target_100']-100),1).'</td><td>'.$array_qc['CV'].'</td></tr>';
		}
	}
}



function get_qc_data($equipment_name,$yymm)
{
$link=start_nchsls();
$QC_5=$yymm*10000 + 500000000;
$QC_5_last=$QC_5+9999;

$QC_8=$yymm*10000 + 800000000;
$QC_8_last=$QC_8+9999;


$sql_qc='
		SELECT code,round(STDDEV((result/target)*100),1)  SD_100, round(avg((result/target)*100) ,1) Target_100 ,	round(
							(
							(STDDEV((result/target)*100)) 
							/
							(avg((result/target)*100))
							)*100,
							1
							)  CV
		FROM `qc` where 
		equipment_name=\''.$equipment_name.'\' and 
		(
		sample_id between \''.$QC_5.'\' and \''.$QC_5_last.'\'  or
		sample_id between \''.$QC_8.'\' and \''.$QC_8_last.'\'
		)
		and comment=\'1\'
		group by code 
		';
		

//echo $sql_qc;

if(!$result_qc=mysql_query($sql_qc,$link)){echo mysql_error();}

	echo '<tr bgcolor=lightpink><th>Code</th><th>%Bias</th><th>CV%</th></tr>';

	while($array_qc=mysql_fetch_assoc($result_qc))
	{
		if($array_qc['SD_100']!=NULL || $array_qc['Target_100']!=NULL || $array_qc['CV']!=NULL)
		{
			echo '<tr><td>'.$array_qc['code'].'</td><td>'.round(($array_qc['Target_100']-100),1).'</td><td>'.$array_qc['CV'].'</td></tr>';
		}
	}
	/*
	while($array_qc=mysql_fetch_assoc($result_qc))
	{
		echo '<tr><td>'.$array_qc['code'].'</td><td>'.$array_qc['SD_100'].'</td><td>'.$array_qc['Target_100'].'</td><td>'.$array_qc['CV'].'</td></tr>';
	}*/
	
}




echo '<table>';
echo '<th><h1>Monthly QC data Biochemistry, NCHSLS, NCH, Surat</h1></th>';
echo '<form method=post action=get_monthly_qc_data.php>';
echo '<tr><td>Write YYMM</td></tr>';
echo '<tr><td><input type=text name=YYMM_selected></td></tr>';
echo '<tr><td>';
mk_select_from_sql('select equipment_name from qc_equipment','equipment_name','','');
echo '</td></tr>';
echo '<tr><td><input type=submit value=OK name=submit></td></tr>';
echo '</form>';
echo '</table>';

echo '<h2 style="page-break-before: always;"></h2>';	



if (isset($_POST['YYMM_selected']))
{
echo '<table border="1" bgcolor=lightgreen>
<tr bgcolor=lightblue><td colspan=2>'.$_POST['equipment_name'].'</td><td colspan=2>YYMM='.$_POST['YYMM_selected'].'</td></tr>';
get_qc_data($_POST['equipment_name'],$_POST['YYMM_selected']);
echo '<tr><th colspan=7  bgcolor=lightblue>Yearly Data</th></tr>';
get_qc_data_yearly($_POST['equipment_name'],$_POST['YYMM_selected']);

echo '</table>';
}

//echo '<pre>';
//print_r($GLOBALS);
//echo '</pre>';
?>
