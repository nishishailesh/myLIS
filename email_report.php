<?php 
session_start();

include 'common.php';


//echo '<pre>';
//print_r($GLOBALS);
//echo '</pre>';

login_varify();
main_menu();
$headers = 'From: Dr S M Patel <nishishailesh@gmail.com>' . PHP_EOL .
        'X-Mailer: PHP/' . phpversion();


mail('biochemistrygmcs@gmail.com', 'Urine report','
    Urinary immunoglobulin losses
    Edema fluid acting as a culture medium
    Protein deficiency
    Decreased bactericidal activity of the leukocytes
    Immunosuppressive therapy
    Decreased perfusion of the spleen caused by hypovolemia
    Urinary loss of a complement factor (properdin factor B) that opsonizes certain bacteria
',$headers);

//mail('root@localhost.localdomain', 'From PHP', 'Tryingto sendpatient reports...');


?>
