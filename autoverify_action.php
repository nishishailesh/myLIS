<?php
session_start();

echo '<html>';
echo '<head>';
echo '</head>';
echo '<body>';


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

main_menu();

if(isset($_POST['sample_id']) && isset($_POST['action']))
{
	if($_POST['action']=='1')
	{
		edit_sample($_POST['sample_id'],'autoverify_action.php	','disabled','No');
		edit_examination($_POST['sample_id'],'autoverify_action.php	','');
	}
	elseif($_POST['action']=='save_examination')
	{
		save_examination($_POST);	
		edit_sample($_POST['sample_id'],'autoverify_action.php	','disabled','No');
		edit_examination($_POST['sample_id'],'autoverify_action.php	','disabled');
	}
	
	elseif($_POST['action']=='2')
	{
		echo '<a href="import_results_XL_640.php">Import results from XL-640</a><br>';
		echo '<a href="import_results_miura.php">Import results from Miura-300</a>';
	}

	elseif($_POST['action']=='3')
	{
		save_single_examination_code($_POST['sample_id'],$_POST['code'],'Result awaited');
		edit_sample($_POST['sample_id'],'autoverify_action.php	','disabled','No');
		edit_examination($_POST['sample_id'],'autoverify_action.php	','');
	}

	
	elseif($_POST['action']=='5')
	{
		save_single_examination_code($_POST['sample_id'],$_POST['code'],'not done');
		insert_single_examination($_POST['sample_id'],1002);
		append_single_examination($_POST['sample_id'],1002,$_POST['code'].' NOT DONE done because sample inadequate.');

		edit_sample($_POST['sample_id'],'autoverify_action.php	','disabled','No');
		edit_examination($_POST['sample_id'],'autoverify_action.php	','');
	}

	elseif($_POST['action']=='6')
	{
		save_single_examination_code($_POST['sample_id'],$_POST['code'],'not done');
		insert_single_examination($_POST['sample_id'],1002);
		append_single_examination($_POST['sample_id'],1002,$_POST['code'].' NOT DONE done because lab. resources inadequate.');

		edit_sample($_POST['sample_id'],'autoverify_action.php	','disabled','No');
		edit_examination($_POST['sample_id'],'autoverify_action.php	','');
	}
	
	elseif($_POST['action']=='7')				//same as action=1(edit manually)
	{
		edit_sample($_POST['sample_id'],'autoverify_action.php	','disabled','No');
		edit_examination($_POST['sample_id'],'autoverify_action.php	','');
	}

/*
	elseif($_POST['action']=='delete_examination')
	{
		if(isset($_POST['code']))
		{
			$link=start_nchsls();
			$sql='delete from examination where sample_id=\''.$_POST['sample_id'].'\' and code=\''.$_POST['code'].'\'';
			echo $sql;
			$result=mysql_query($sql,$link);
			echo 'deleted '.mysql_affected_rows($link).' examination<br>';	
		}
		edit_sample($_POST['sample_id'],'autoverify_action.php	','disabled','No');
		edit_examination($_POST['sample_id'],'autoverify_action.php	','disabled');
	}
*/

	
}

?>
