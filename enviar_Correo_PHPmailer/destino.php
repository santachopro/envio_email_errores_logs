<?php
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;
 
 require 'PHPmailer/Exception.php';
 require 'PHPmailer/PHPMailer.php';
 require 'PHPmailer/SMTP.php';

try{
    //llamado de funciones:
      //ultimo_archivo("//loki\PRD\SAPaY2\TrasladosSAP\Log");
      ultimo_archivo("C:\cap");
       }catch(exception $ex){
  
      echo"excepcion ocurrida:".$ex->getMessage()."</br>"; 
  } finally{
     echo"<h2></br>se ha completado el proceso...</h2>";
  }

 function ultimo_archivo($path){
 
$latest_ctime = 0;
$latest_filename = '';    

$d = dir($path);
while (false !== ($entry = $d->read())) {
  $filepath = "{$path}/{$entry}";
   
  if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
    $latest_ctime = filectime($filepath);
    $latest_filename = $entry;
  }
}
 
lineasError($latest_filename,$path) ;
}
 
function lineasError($file,$path){

  $msj="";
  $mensaje="";
  $message="";

        $cod = detectFileEncoding($path."/".$file);
         //  echo $cod;
                  
         $archivo=fopen($path."/".$file,"r")or die ("error al abrir archivo :".$file);
     
        
         while (!feof($archivo)){
          //toma las lineas
           $linea = fgets($archivo);
           //función codifi... 
           $result = codifi($cod,$linea);    
               
           $saltoLinea=nl2br($result);
                
           $cadena_de_texto = $saltoLinea;
           
           $cadena_buscada = "Error";
          
           $posicion_coincidencia = strrpos($cadena_de_texto, $cadena_buscada,$offset = 0);
               
           if ($posicion_coincidencia === false){
   
         // echo "</br>NO se ha encontrado Error!!!!";
           $mensaje = Error($cadena_de_texto);
           $message = $message.$mensaje;
 
            }else if ($posicion_coincidencia === 0){
             
            //  echo " linea de 'Error' encontrada!!";           
           $msj= $msj.$cadena_de_texto;
                       
                }
             }
    
           $message = $message.$mensaje.$msj;
            
           if ($message != null){
            email($message,$file);   
          }
    fclose($archivo);
     
   }
   
   function Error($cadena_de_texto){
   $message = "";
    //palabra a buscar:
     $palabra_buscada ="error";
   
     $posicion_encontrada= strrpos($cadena_de_texto, $palabra_buscada,$offset = 0);
    
   
     if ($posicion_encontrada === false){
      // echo "</br>NO se ha encontrado error!!!!";
    
          }else if ($posicion_encontrada === 0){
     
          //echo " linea de 'error' encontrada!!";           
   
     $message = $message.$cadena_de_texto;
        
     }   
     //echo $message;
    return $message;
   
   }

function detectFileEncoding($filePath){ 

    $fopen=fopen($filePath,'r');

    $row = fgets($fopen);
    $encodings = mb_list_encodings();
    $encoding = mb_detect_encoding( $row, "UTF-8, ASCII");

    if($encoding !== false) {
        $key = array_search($encoding, $encodings) !== false;
        if ($key !== false)
            unset($encodings[$key]);
        $encodings = array_values($encodings);
    }

    $encKey = 0;
    while ($row = fgets($fopen)) {
        if($encoding == false){
            $encoding = $encodings[$encKey++];
        }

        if(!mb_check_encoding($row, $encoding)){
            $encoding =false;
            rewind($fopen);
        }

    }
    //echo $encoding;
    return $encoding;
} 

function codifi($enc,$linea){  
 
    $str = "";
     if ($enc === false){
         //no pudo detectar la codificación
    }
        else if ($enc !== "UTF-8"){
           
           $str = mb_convert_encoding($linea, "UTF-8","UTF-16");
           
            // echo $str;
        }
            else if ($enc === "UTF-8"){
            //UTF-8 detected
        
         $str = iconv("UTF-8", "UTF-8//IGNORE", $linea);
           
            } 
     // echo "<br>".$str;
     return $str;
}

function email($error,$file){
   
    $mail = new PHPMailer(true);
    
    try {
        //Server settings
        $mail->SMTPDebug = 0;                     
        $mail->isSMTP();                                            
        $mail->Host = 'smtp.gmail.com';  
        //servers                 
        $mail->SMTPAuth   = true;                                   
        $mail->Username   = 'pruebaerrorcv@gmail.com';                     
        $mail->Password   = 'juliocesar22';                              
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            
        $mail->Port       = 465;                                    
    
        //Recipients
        $mail->setFrom('pruebaerrorcv@gmail.com', 'errores logs');
        $mail->addAddress('schaverra@cuerosvelez.com', 'santiago');    
        $mail->addAddress('santacho2014@gmail.com');
                      
         
        //Attachments: imagenes, archivos adjuntos
        /*$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        */
    
        //Content
        $mail->isHTML(true);                                   
        $mail->Subject = 'Errores Logs';
        $mail->Body    = ' 
        <!DOCTYPE html>
 
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="x-apple-disable-message-reformatting">
  <title></title>
  
  <style>
    table, td, div, h1, p {
      font-family: Arial, sans-serif;
    }
    @media screen and (max-width: 530px) {
      .unsub {
        display: block;
        padding: 8px;
        margin-top: 14px;
        border-radius: 6px;
        background-color: #555555;
        text-decoration: none !important;
        font-weight: bold;
      }
      .col-lge {
        max-width: 100% !important;
      }
    }
    @media screen and (min-width: 531px) {
      .col-sml {
        max-width: 27% !important;
      }
      .col-lge {
        max-width: 73% !important;
      }
    }
  </style>
</head>
<body style="margin:0;padding:0;word-spacing:normal;background-color:#939297;">
  <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#ffffff;">
    <table role="presentation" style="width:100%;border:none;border-spacing:0;">
      <tr>
        <td align="center" style="padding:0;">
         
          <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
            <tr>
              <td style="padding:40px 30px 30px 30px;text-align:center;font-size:24px;font-weight:bold;">
                <a href="" style="text-decoration:none;"><img src="https://tse4.mm.bing.net/th?id=OIP.WNW5girEiCQh3EI4AxjGggHaCi&pid=Api&P=0&w=453&h=156"  width="165" alt="Logo" style="width:80%;max-width:165px;height:auto;border:none;text-decoration:none;color:#ffffff;"></a>
              </td>
            </tr>
            <tr>
              <td style="padding:30px;background-color:#ffffff;">
                <h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">ARCHIVO:'.$file.'</h1>
                <h2 style="margin:0;">ERROR:</h2>
              </td>
            </tr>
             
            <tr>
              <td style="padding:35px 30px 11px 30px;font-size:0;background-color:#ffffff;border-bottom:1px solid #f0f0f5;border-color:rgba(201,201,207,.35);">
              
                <div class="col-sml" style="display:inline-block;width:100%;max-width:145px;vertical-align:top;text-align:left;font-family:Arial,sans-serif;font-size:14px;color:#363636;">
                  <img src="https://tse2.mm.bing.net/th?id=OIP.tEgKeG4NDh60xDSg9Mlz1QHaGJ&pid=Api&P=0&w=300&h=300" width="115" alt="" style="width:80%;max-width:115px;margin-bottom:20px;">
                </div>
                 
                <div class="col-lge" style="display:inline-block;width:100%;max-width:395px;vertical-align:top;padding-bottom:20px;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
                  <p style="margin-top:0;margin-bottom:12px;">'.$error.'</p>
                  
                </div>
               
              </td>
            </tr>
         
          </table>
           
        </td>
      </tr>
    </table>
  </div>
</body>
</html>
    '; 
    
        $mail->send();
        echo 'Email enviado correctamente';
    } catch (Exception $e) {
        echo "error al enviar el Email {$mail->ErrorInfo}";
    }
}

?>