<?php
    $showLoginForm = true;

    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if ($username === 'admin' && $password === 'admin') {
            $showLoginForm = false;
        } else {
            $errorMessage = 'Username o Password non corretti, riprovare';
        }
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
        <div class="login-container">
            <?php if (isset($_POST["submitsearch"])): $showLoginForm = false; ?>
            <?php endif; ?>
            <?php if ($showLoginForm): ?>
                <h2>Login</h2>
                <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>

                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <button type="submit" name="submit">Login</button>
                </form>
                <?php if (isset($errorMessage)): ?>
                    <p><?php echo $errorMessage; ?></p>
                <?php endif; ?>
                <?php else: ?>
                    <h1>RICERCA:</h1>
                    <div class="div-search">
                        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']);?>" method="post">
                            <label for="lname">Cognome: </label><br>
                            <input type="text" name="lname" onkeyup="showHint(this.value)"><br>
                            <p class="hint">Suggestions: <span id="txtHint"><?php include("backend.php"); ?></span></p><br>
                            <input type="submit" name="submitsearch" value="submitsearch">
                        </form>
                    </div>

                    <script>
                        document.querySelector('.login-container').style.cssText = "width: 100%; margin: auto; background-color: none; border-radius: 0; box-shadow: none;";
                    </script>
                    <?php
                        $lname = $_POST["lname"] ?? null;
                        $file_path = "../AllCV/all_CV.csv";

                        if (file_exists($file_path)) {
                            $file_content = file_get_contents($file_path); // prende il contenuto del file e lo mette in una stringa
                            $csv_data = array_map('str_getcsv', explode("\n", $file_content)); // metto in un array tutte le stringhe che terminano con \n
                            $csv_header = array_shift($csv_data); // separo l'header del csv così da poter intestare bene

                            if (isset($_POST["submitsearch"])){
                                //tabella
                                echo '<table>';
                                foreach($csv_data as $row) {
                                    $matchingElements = array_filter($row, function($cell) use ($lname) {
                                        return stripos($cell, $lname) !== false;
                                    });

                                    // Verifica se ci sono elementi corrispondenti
                                    if (!empty($matchingElements)) {
                                        echo '<tr>';
                                        foreach ($row as $cell) {
                                            if(strlen($cell) == 35){
                                                echo '<td><a href="../UploadedCV/' . $cell . '" target="_blank">' . htmlspecialchars($cell) . '</a></td>';
                                            } else {
                                                echo '<td>' . htmlspecialchars($cell) . '</td>';
                                            }
                                        }
                                        echo '</tr>';
                                    }
                                }
                                echo '</table>';
                            }
                            else{
                                // tabella
                                echo '<table>';
                                echo '<tr>';
                                foreach($csv_header as $cell) {
                                    echo '<th>' . htmlspecialchars($cell) . '</th>';
                                }
                                echo '</tr>';

                                foreach($csv_data as $row) {
                                    echo '<tr>';
                                    foreach ($row as $cell) {
                                        if (strlen($cell) == 35){
                                            echo '<td><a href="../UploadedCV/' . $cell . '" target="_blank">' . htmlspecialchars($cell) . '</a></td>';
                                        }else {
                                            echo '<td>' . htmlspecialchars($cell) . '</td>';
                                        }
                                    }
                                    echo '</tr>';
                                }
                                echo '</table>';
                            }
                        }else{
                            echo "Il file non esiste.";
                        }
                    ?>
            <?php endif; ?>
        </div>

        <script src="../Scripts/script.js"></script>
    </body>
</html>
