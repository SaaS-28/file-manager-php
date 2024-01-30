<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="Style/style-index.css">
        <title>INDEX</title>
    </head>
    <body>
        <h1>INVIO DEL CURRICULUM</h1>
        <?php
            $fname = $lname = $cf = $tn = $email = "";
            $fnameE = $lnameE = $cfE = $tnE = $emailE = $cvfileE = "";
            $maxSizeInBytes = 5000000;
            $allowedFormats = array("pdf", "doc", "docx", "rft");
            $show_results = False;

            function test_input($data) {
                $data = trim($data);
                $data = stripslashes($data);
                $data = htmlspecialchars($data);
                return $data;
            }

            if (isset($_POST['submit'])){
                // controllo del nome
                if (empty($_POST['fname'])){
                    $fnameE = "DEVI INSERIRE IL NOME PER CONTINUARE!!";
                }
                else {
                    $fname = test_input($_POST['fname']);
                    if (!preg_match("/^[a-zA-Z ]*$/", $fname)){
                        $fnameE = "SOLO LETTERE E SPAZI SONO ACCETTATI!!";
                    }
                }

                // controllo del cognome
                if (empty($_POST['lname'])){
                    $lnameE = "DEVI INSERIRE IL COGNOME PRIMA DI CONTINUARE!!";
                }
                else{
                    $lname = test_input($_POST['lname']);
                    if(!preg_match("/^[a-zA-Z ]*$/", $lname)){
                        $lnameE = "SOLO LETTERE E SPAZI SONO ACCETTATI!!";
                    }
                }

                // controllo del codice fiscale
                if (empty($_POST['cf'])){
                    $cfE = "DEVI INSERIRE IL CODICE FISCALE PRIMA DI CONTINUARE!!";
                }
                else{
                    $cf = test_input($_POST['cf']);
                    if(!preg_match("/^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$/", $cf)){
                        $cfE = "SOLO LETTERE, SPAZI E TRATTINI SONO ACCETTATI!!";
                    }
                }

                // controllo del numero di telefono
                if (empty($_POST['tn'])){
                    $tnE = "DEVI INSERIRE IL NUMERO DI TELEFONO PRIMA DI CONTINUARE!!";
                }
                else{
                    $tn = test_input($_POST['tn']);
                    if(!preg_match("/^3\d{9}$/", $tn)){
                        $tnE = "SOLO NUMERI SONO ACCETTATI!!";
                    }
                }

                // controllo dell'email
                $email = test_input($_POST['email']);
                if (!preg_match("/^[^@ ]+@[^@ ]+\.[^@ \.]+$/", $email)){
                    $emailE = "SOLO LETTERE, SPAZI E TRATTINI SONO ACCETTATI!!";
                }

                if (empty($_FILES['cvfile']['tmp_name']) || $_FILES['cvfile']['error'] != UPLOAD_ERR_OK) {
                    $cvfileE = "BISOGNA CARICARE UN FILE PRIMA DI PROSEGUIRE!!";
                }                

                if (empty($fnameE) && empty($lanmeE) && empty($cfE) && empty($tnE) && empty($emailE) && empty($cvfileE)){
                    //controllo sul file - il file deve essere pdf, doc, docx o rft e di dimensione max di 5MB
                    $fileName = $cf . "_" . date("YmdHis");
                    $fileType = strtolower(pathinfo($_FILES["cvfile"]["name"], PATHINFO_EXTENSION));
            
                    if ($_FILES["cvfile"]["size"] > $maxSizeInBytes) { // Controllo dimensione
                        $cvfileE = "IL FILE È TROPPO GRANDE. LA DIMENSIONE MASSIMA CONSENTITA È DI 5MB!!";
                    }else if (!in_array($fileType, $allowedFormats)) { // Controllo formato
                        $cvfileE = "FORMATO DEL FILE NON SUPPORTATO. I FORMATI SUPPORTATI SONO: " . implode(", ", $allowedFormats);
                    }else {
                        if (move_uploaded_file($_FILES["cvfile"]["tmp_name"], "UploadedCV/" . $fileName . "." . $fileType)){
                            //echo "Il file " . htmlspecialchars(basename($_FILES["cvfile"]["name"])) . " è stato caricato con successo.";
            
                            // Creazione del file CSV
                            $csvFileName = "AllCV/all_CV.csv";
                            $csvFile = fopen($csvFileName, "a");
            
                            $csvData = array($fname, $lname, $tn, $email, $cf, $fileName . "." . $fileType);
                            fputcsv($csvFile, $csvData);
            
                            fclose($csvFile);
                        }else {
                            echo "Errore durante il caricamento del file. Dettagli: " . print_r(error_get_last(), true);
                        }
                    }
                    $show_results = True;
                }
            }
        ?>

    <?php if ($show_results): ?>
            <!-- Mostra i risultati -->
            <h2>Riepilogo:</h2>
            <p>Gentile <?php echo $fname . " " . $lname?>, il suo curriculum è stato correttamente ricevuto. La contatteremo al più presto attraverso i recapiti da lei forniti".</p>
    <?php else: ?>
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post" enctype="multipart/form-data">
            <p class="mandatory">* CAMPI DA COMPILARE OBBLIGATORIAMENTE</p>
            <label for="fname">Nome: </label><span> * <?php echo $fnameE;?></span><br>
            <input type="text" name="fname"><br>
            <label for="lname">Cognome: </label><span> * <?php echo $lnameE;?></span><br>
            <input type="text" name="lname"><br>
            <label for="cf">Codice fiscale: </label><span> * <?php echo $cfE;?></span><br>
            <input type="text" name="cf"><br>
            <label for="tn">Numero di telefono: </label><span> * <?php echo $tnE;?></span><br>
            <input type="tel" name="tn"><br>
            <label for="email">Email: </label><span><?php echo $emailE;?></span><br>
            <input type="email" name="email"><br>
            <label for="cvfile">Curriculum: </label>
            <input type="file" name="cvfile"><span> * <?php echo $cvfileE;?></span><br><br>
            <input type="submit" name="submit" value="submit">
        </form>
    <?php endif; ?>
    </body>
</html>