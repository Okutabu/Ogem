<?php
$data_source_name = 'mysql:host=localhost;dbname=scgllydo_main;charset=utf8';

// class database{
    
//     public $data_source_name = 'mysql:host=localhost;dbname=scgllydo_main;charset=utf8';

//     public function users_connect(){
//         try {
//             $bdd = new PDO($this->data_source_name, 'scgllydo_enzo', 'EGcJAPF?baPO');
//         } catch(PDOException $e) {
//             die('Erreur : '.$e->getMessage());
//         }
//         echo "conenction successful";
//         return $bdd;

//     }
//     public function watches_connect(){
//         try {
//             $db_watches = new PDO($this->data_source_name, 'scgllydo_proplayer', '360noscope420');
//         } catch(PDOException $e) {
//             die('Erreur : '.$e->getMessage());
//         }
//         echo "conenction successful";
//         return $db_watches;

//     }
// }
try {
    $bdd = new PDO($data_source_name, 'root', '');
} catch(PDOException $e) {
    die('Erreur : '.$e->getMessage());
}
echo "conenction successful";


    
?>