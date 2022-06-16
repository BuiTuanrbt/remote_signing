
<?php
class Call_Jar{
    
    public $jar_path;
    public $json_path;
    public $original_pdf_path;
    public $signed_pdf_path;
    public $ca_url;

    function setJarPath($jar_path){

        $this->jar_path = $jar_path;
    }
    function setJsonPath($json_path){

        $this->json_path = $json_path;
    }
    function setOriginalPdfPath($original_pdf_path){

        $this->original_pdf_path = $original_pdf_path;
    }
    function setSignedPdfpath($signed_pdf_path){

        $this->signed_pdf_path = $signed_pdf_path;
    }

    function setCaUrl($ca_url){
        $this->ca_url = $ca_url;
    }

    function runJar(){
        try{
            $command = "java -jar ".$this->jar_path." ".$this->json_path." ".$this->original_pdf_path." ".$this->signed_pdf_path." ".$this->ca_url;
            echo $command;
            exec($command);
            return true;
        }catch(Exception $e){
            //echo $e;
            return false;
        }
    }
}
?>