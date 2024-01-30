<?php
    session_start();

    $file_path = "../AllCV/all_CV.csv";
    $file_content = file_get_contents($file_path);
    $csv_data = array_map('str_getcsv', explode("\n", $file_content));
    $csv_header = array_shift($csv_data);
    $All_lname = array();
    $hint = null;

    // aggiungo il nome all'array contenente tutti i nomi, ma solo se non c'è 
    foreach ($csv_data as $row) {
        if (isset($row[1])) {
            if (!in_array($row[1], $All_lname)) {
                array_push($All_lname, $row[1]);
            }
        }
    }

    // prendo la richiesta
    if (isset($_REQUEST['q'])) {
        $q = $_REQUEST["q"] ?? null;

        // la elaboro
        if ($q !== "") {
            $q = strtolower($q);
            $len = strlen($q);
            foreach ($All_lname as $name) {
                if (stristr($q, substr($name, 0, $len))) {
                    if ($hint === null) {
                        $hint = $name;
                    } else {
                        $hint .= ", $name";
                    }
                }
            }
        }
        echo generateTableHTML($csv_data, $csv_header, $hint);
        exit();
    }

    // gestione del login
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($username === 'admin' && $password === 'admin') {
            $_SESSION['logged_in'] = true;
        } else {
            $errorMessage = 'Username o Password non corretti, riprovare';
        }
    }

    // funzione per generare la tabella
    function generateTableHTML($csv_data, $csv_header, $hint) {
        $htmlTable = '<table>';
    
        // se hint è null mostro l'intera tabella, altrimenti mostro solo la parte di tabella che mi interessa
        if($hint === null){
            $htmlTable .= '<tr>';
            foreach($csv_header as $cell) {
                $htmlTable .= '<th>' . htmlspecialchars($cell) . '</th>';
            }
            $htmlTable .= '</tr>';

            foreach($csv_data as $row) {
                $htmlTable .= '<tr>';
                foreach ($row as $cell) {
                    if (strlen($cell) == 35){
                        $htmlTable .= '<td><a href="../UploadedCV/' . $cell . '" target="_blank">' . htmlspecialchars($cell) . '</a></td>';
                    }else {
                        $htmlTable .= '<td>' . htmlspecialchars($cell) . '</td>';
                    }
                }
                $htmlTable .= '</tr>';
            }
        } else {
            foreach ($csv_data as $row) {
                if ((isset($row[1]) && in_array(strtolower($row[1]), array_map('strtolower', explode(", ", $hint))))) {
                    $htmlTable .= '<tr>';
                    foreach ($row as $cell) {
                        if (strlen($cell) == 35) {
                            $htmlTable .= '<td><a href="../UploadedCV/' . $cell . '" target="_blank">' . htmlspecialchars($cell) . '</a></td>';
                        } else {
                            $htmlTable .= '<td>' . htmlspecialchars($cell) . '</td>';
                        }
                    }
                    $htmlTable .= '</tr>';
                }
            }
        }
    
        $htmlTable .= '</table>';
    
        return $htmlTable;
    }

?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../Style/style-management.css">
        <title>Login</title>
    </head>
    <body>
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
            <div class="show-page">
                <h1>RICERCA:</h1>
                <div class="div-search">
                    <form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post">
                        <label for="lname">Cognome: </label><br>
                        <input type="text" name="lname" onkeyup="showHint(this.value)"><br>
                    </form>
                </div>
            </div>
            <div class="show-table"><?php echo generateTableHTML($csv_data, $csv_header, $hint); ?></div>
        <?php else: ?>
            <div class="login-container">
                <h2>Login</h2>
                <form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <button type="submit" name="submit">Login</button>
                </form>
            </div>
        <?php endif;?>

        <script src="../Scripts/script.js"></script>
    </body>
    ?>
</html>