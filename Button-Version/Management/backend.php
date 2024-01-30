<?php
  // array
  $All_lname = array();
  $file_content = file_get_contents("../AllCV/all_CV.csv");
  $csv_data = array_map('str_getcsv', explode("\n", $file_content));

  foreach ($csv_data as $row) {
    if(isset($row[1])){
      if(!in_array($row[1], $All_lname)){
        array_push($All_lname, $row[1]);
      }
    }
  }

  // prende la query e quindi i suoi dati
  $q = $_REQUEST["q"] ?? null;

  $hint = ""; // variabile utilizzata per raccogliere i suggerimenti che corrispondono alla query dell'utente

  // se q è diverso da "" allora si cercano i suggerimenti
  if ($q !== "") {
    $q = strtolower($q);
    $len = strlen($q);
    foreach($All_lname as $name) {
      if (stristr($q, substr($name, 0, $len))) { //stristr esegue una ricerca case-insensitive e controlla che la stringa q sia uguale a quella dell'array
        if ($hint === "") { // se hint è vuoto vuol dire che questa è la prima corrispondenza
          $hint = $name;
        } else { // altrimenti vuol dire che ce ne sono altre e quindi le mette in coda
          $hint .= ", $name";
        }
      }
    }
  }

  // Se non ci sono corrispondenze viene stampato il seguente messaggio
  echo $hint === "" ? "no suggestion" : $hint;
?>