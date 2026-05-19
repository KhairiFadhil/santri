<?php
    namespace App\Controllers; 
    
    use App\Core\View;
    use App\Model\Dokter;
       
    class DokterController
    {
        public function detail($id)
        {
            $dokter = Dokter::findById((int)$id);
            if(!$dokter){
                http_response_code(400);
                echo "<h1> Dokter tidak ada </h1>";
                return;
            }

            View::render('dokter/detail', ['dokter' => $dokter], 'main');
        }
    }

?>