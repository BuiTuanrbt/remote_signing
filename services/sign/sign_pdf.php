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
                $get_cert->setUserId("0101243150-996");
                $rand_trans = "SP_CA_".random_int(10000,19999);
                $get_cert->setTransactionId("SP_CA_".$rand_trans);
                $get_cert->setSerialNumber("54010C24214B4106504997B6583AC921");

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

                $sta = $call_jar->runJar();
                unlink($json_path_file);
                foreach (glob($OUTPUT_PATH."*.pdf") as $file) {

                
                    if(time() - filectime($file) > 180){
                        unlink($file);
                        }
                    }

                if ($sta){
                                       
                    echo "<h1>Signed Succesfully </h1>
                    <a href='".$signed_pdf_file."' download>Download</a>";
                   
                   
                    
                }else{

                    echo "<h1>Signed Fail </h1>";
                }


               
            }
    
        }
        else{
        }
    }
}else{
}
    

?>