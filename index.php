<?php
   ini_set('display_errors', 0);
   ini_set('display_startup_errors', 1);
   error_reporting(E_ALL);
   
   include('lib/main.php');
   
   
   if( formatPOST('submit') != 'NULL' ){
      $folder = formatPOST('teryt').'/';
       $countfiles = count($_FILES['file']['name']);
       for($i=0;$i<$countfiles;$i++){
           $filename = $_FILES['file']['name'][$i];
           move_uploaded_file($_FILES['file']['tmp_name'][$i],'upload/'.$folder.$filename);
       $file = "PLIKI WGRANE";
       }
   } 
   ?>
<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>SIDUSIS</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
      <script>
         function show()
         {
         document.getElementById("komunikat").style.display = "block";
         document.getElementById('welcomeDiv').style.display = "block";
         }
      </script>
   </head>
   <body>
      <div class="alert alert-danger" role="alert">
         OPROGRAMOWANIE DEMO - pamiętaj że wgrane dane będą widoczne dla innych. W razie pytań zachęcamy do kontaktu biuro@aniatel.pl.
      </div>
      <?php
         echo '<div class="alert alert-success" role="alert" style="display:none;" id="komunikat">Trwa generowanie raportu. Raport będzie dostępny do pobrania gdy zniknie ten komunikat.</div>';
         
         ?>
      <div class="container" style="margin-top: 20px;">
         <a href="index.php"><img src="https://internet.gov.pl/static/images/logo_sidusis.png"></a> <a href="https://internet.gov.pl/help/" target="_blank" style="float: right;" > POMOC </a>
         <hr>
         <center>
            <?php 
               if (isset($file)) {
               $folder = formatPOST('teryt').'/'; 
               echo '<div class="alert alert-success" role="alert">  Plik/Pliki wgrane na serwer <br /> <pre>';
               print_r(glob('upload/'.$folder.'*'));
               echo' </pre></div> ';
               
               }
               
               ?>
            <div class="alert alert-primary" role="alert">
               <span style="float: left;"><b>1.</b></span> 
               <h4>Nazwy plików do importu</h4>
               <ul>
                  <li>  
                     Dane zasięgów adresówych: <code>baza.csv</code>
                  </li>
                  <li>  
                     Dodatkowo (nie obowiązkowo): <code>recznie.csv</code> - plik z danymi nie zawartymi w bazie klientów. 
                  </li>
               </ul>

               <p>
               <ul>
                  <li>    
                     Słownik : <code>slownik.csv</code>  </p> 
                  </li>
                  <li>    
                     Dane Instytucji : <code>di.csv</code> </p> 
                  </li>
                  <li>    
                     Przedstawiciel Operatora : <code>po.csv</code>  </p> 
                  </li>
               </ul>
               <div class="alert alert-danger" role="alert">
                  Opis i przykłądy do pobrania znajdziesz tu : <a href="https://github.com/sell24cz/sidusis" target="_blank">sidusis</a>
               </div>
            </div>
            <div class="alert alert-secondary" role="alert">
               <span style="float: left;"><b>2.</b></span>  Pobierz ostatnią bazę teryt  <a href="https://internet.gov.pl/static/docs/address_points.zip" action="_blank" class="alert-link">TERYT</a>
               <hr>
               <h6>Można wgrywać kilka plików, zaznacz je przy wyborze!</h6>
               
            <form method='post' action='' enctype='multipart/form-data'>
               <input type="file" name="file[]" id="file" multiple>
               <input type="hidden" name="teryt" value="teryt" >
               <input type='submit' name='submit' value='Upload'>
            </form>

            </div>
            <hr>
            <div class="alert alert-secondary" role="alert">
            <span style="float: left;margin-left: 15px;"><b>3.</b></span> Wgraj pliki: baza.cvs, recznie.csv, di.csv, po.csv, slownik.csv
            <hr>
            <h6>Można wgrywać kilka plików, zaznacz je przy wyborze!</h6>
       
            <form method='post' action='' enctype='multipart/form-data'>
               <input type="file" name="file[]" id="file" multiple>
               <input type='submit' name='submit' value='Upload'>
            </form>
              </div>
            <hr>
            <br />
            <span style="float: left; margin-left: 15px;"><b>4.</b></span>
            <form action="" method="POST">
               <input type="hidden" name="generuj" value="1"/>
               <input type="submit" class="btn btn-warning btn-lg" value="GENERUJ DANE" onclick="show()">
               <br /><br />
               <label for="formFileSm" class="form-label">Generowanie pliku trwa około 5 minut.</label>
            </form>
      
            <hr>
            <div class="alert alert-primary d-flex align-items-center" role="alert"  >
              
               <a href="24-full.zip" style="padding: 5px;"> OSTATNI EXPORT </a>  
               <a href="24-przyrostowo.zip"  style="padding: 5px;"> PRZYROSTOWO</a>
               <a target="_blank" href="error.txt"  style="padding: 5px;"> ERROR LOG</a>
              <a target="_blank" href="brak.txt"  style="padding: 5px; "> BRAK W TERYCIE</a>
              <div>
      </div>
      <?php
         if (formatPOST('generuj') == '1' ) 
         {
             exec("bin/generuj.sh");
             header ("Location: index.php");
         }
         ?>
      </center>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
   </body>
</html>
