function showHint(str) {
    // viene verificato che la stringa abbia una lunghezza effettiva
    if (str.length == 0) {
        document.getElementById("txtHint").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest(); //XMLHttpRequest() è l'oggetto che consente di effettuare richieste HTTP asincrone da parte del client
        xmlhttp.onreadystatechange = function() { // ogni volta che lo stato della richiesta cambia, viene eseguita questa funzione
            if (this.readyState == 4 && this.status == 200) { // il codice 4 indica che la richiesta è stata completata, mentre il codice 200 indica che questo è avvenuto con successo
                document.getElementById("txtHint").innerHTML = this.responseText; // qui viene stampata la responseText e quindi la risposta ricevuta dal server
            }
        }
        xmlhttp.open("GET", "backend.php?q="+str, true); // prepara la richiesta HTTP, definisce il metodo, l'url del server e i dati da inviare(la query)
        xmlhttp.send(); // invia efettivamente la richiesta al server
    }
}