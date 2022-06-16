<?php
$uploadDirectory = "../../uploads/";
require "../../common/common.php";
require "../../model/get_cert.php";
require "../task.php";
if(isset($_POST["submit"])) {

    if ($_FILES['fileToUpload']['error'] ==  UPLOAD_ERR_OK  ){
        
        $filename = $_FILES['fileToUpload']['name'];

        $filedata = $_FILES['fileToUpload']['tmp_name'];

        $filesize = $_FILES['fileToUpload']['size'];
        
        if($filedata !='')
        {
            $ext = pathinfo($_FILES['fileToUpload']['name'],PATHINFO_EXTENSION);

            if(strtolower($ext)=="pdf"){

                $config_sp = file_get_contents($CONFIG_SP_PATH);

                //chuyen thanh json object
                $sp_obj =  json_decode($config_sp);
                //Lua chon ca
                try{
                    for($i = 0; $i< count($sp_obj->credentials_list); $i++){
                    // kiem tra ca_id
                    if ($_POST["category"] == $sp_obj->credentials_list[$i]->ca_id){
                        break;
                    }
                }
                }catch(Exception $e){
                    echo "error";
                }
                // config req get cert
                $get_cert = new Get_cert();
                $get_cert->setSpId($sp_obj->credentials_list[$i]->sp_id);
                $get_cert->setSpPassword($sp_obj->credentials_list[$i]->sp_password);
                $get_cert->setUserId("User_01");
                $get_cert->setTransactionId("1as3f1ads");
                $get_cert->setSerialNumber("4d274078f45b55336c74f48f8025e1047775e4e5");

                //tao json_getcert file
                $json_path_file = $JSON_FOLDER_PATH.uniqid().".json";

                $ca_id = $sp_obj->credentials_list[$i]->ca_id;
                $url =  $sp_obj->credentials_list[$i]->url;

                // file json request
                file_put_contents($json_path_file,$get_cert->createJson());

                //file da ki
                $signed_pdf_file = $OUTPUT_PATH.uniqid().".pdf";

                // config plugin java
                $call_jar = new Call_Jar();
                $call_jar->setJarPath($JAR_PATH);
                $call_jar->setJsonPath($json_path_file);
                $call_jar->setOriginalPdfPath($filedata);
                $call_jar->setSignedPdfpath($signed_pdf_file);
                $call_jar->setCaUrl($url);

                
                if ($call_jar->runJar()){
                    
                    // header('Content-type: application/pdf');
                    // header('Content-Disposition: attachment; filename="' . $signed_pdf_file . '"');
                    // header('Content-Length: ' . filesize($signed_pdf_file));

                    // readfile($signed_pdf_file);

                    //test
                    //Read the filename
                    //$filename = $_GET['path'];
                    //Check the file exists or not
                    //if(file_exists($filename)) {

                    //Define header information
                    //header('Content-Description: File Transfer');
                    header('Content-Type: application/pdf');
                    //header("Cache-Control: no-cache");
                    //header("Expires: 0");
                    header('Content-Disposition: inline; filename="'.basename($signed_pdf_file).'"');
                    //header('Content-Length: ' . filesize($signed_pdf_file));
                    header('Pragma: public');

                    //Clear system output buffer
                    flush();

                    //Read the size of the file
                    readfile($signed_pdf_file);

                    //Terminate from the script
                    die();

                    //end test


                    exit;
                 
                    unlink($json_path_file);
                    // unlink($signed_pdf_file);

                }else{

                    ob_start();
                    $file = "C:\\xampp\\htdocs\\remote_sign\\output\\62a9a225bb365.pdf";
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/pdf');
                    header('Content-Disposition: attachment; filename="'.basename($file).'"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($file));
                    ob_clean();
                    flush();
                    readfile($file);
                }


               
            }
    
        }
        else{

        }
    }
}else{
}
    

?>