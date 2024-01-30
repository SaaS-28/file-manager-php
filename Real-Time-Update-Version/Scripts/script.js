function showHint(str) {
    // viene verificato che la stringa abbia una lunghezza effettiva

    var xmlhttp = new XMLHttpRequest(); //XMLHttpRequest() è l'oggetto che consente di effettuare richieste HTTP asincrone da parte del client
    xmlhttp.open('POST', 'index.php', true);
    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            // Aggiorna il contenuto di ".show-page" con la risposta ricevuta
            document.querySelector('.show-table').innerHTML = xmlhttp.responseText;
        }
    };
    if (str.length != 0) {
        xmlhttp.send('q=' + str);
        return;
    } else {
        xmlhttp.send('q=');
    }
}