<?php

        mysqli_report(MYSQLI_REPORT_STRICT);

        try {
                $connection = new mysqli("localhost","root","", "db_venetoinmostra") ;
                } catch (Exception $e ) {
                    echo "<h2> Database momentaneamente non disponibile :( <h2>";
                    exit;
                }

        include("cambiamentiNav.php");

        $dataOdierna = date ("Y-m-d");

        $dataBottone = $_GET["data"];
        $citta = $_GET["citta"];
        $articolo = file_get_contents("../HTML/boxArticolo.html");

        $conn = mysqli_connect("localhost", "root", "");

        mysqli_select_db($conn, "db_venetoinmostra");

        if($dataBottone == "oggi"){
            $result = mysqli_query($conn, "select * from ". $citta ." where data_inizio <='" . $dataOdierna."' and '".$dataOdierna."' <= data_fine");
            $stampa = "di oggi";
        }

        if($dataBottone == "domani"){
            $result = mysqli_query($conn, "select * from ". $citta ." where data_inizio <= '".$dataOdierna."' + interval 1 day and '".$dataOdierna."' + interval 1 day <= data_fine");
            $stampa = "di domani";
        }

        if($dataBottone == "settimana"){
             $result = mysqli_query($conn, "select * from ". $citta ." where data_inizio <= '".$dataOdierna."' + interval 7 day and '".$dataOdierna."' + interval 7 day <= data_fine");
            $stampa = "per i prossimi 7 giorni";
        }

         if($dataBottone == "mese"){
             $result = mysqli_query($conn, "select * from ". $citta ." where data_inizio <= '".$dataOdierna."' + interval 30 day and '".$dataOdierna."' + interval 30 day <= data_fine");
             $stampa = "per i prossimi 30 giorni";
        }

        echo "<h1>Eventi ".$stampa." a ".$citta."</h1>";

         while ($riga = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $titolo = $riga['titolo'];
                $testo =  $riga['testo'];
                $img = $riga['img'];
                $dataI = $riga['data_inizio'];

                if($dataI != ""){
                    $dataI = implode("/", array_reverse(explode("-", $dataI)));
                    $dataInizio = "DAL ".$dataI;    }
                else $dataInizio = "";

                $dataF = $riga['data_fine'];

                if($dataF != ""){
                $dataF = implode("/", array_reverse(explode("-", $dataF)));
                $dataFine = " AL ".$dataF;    }
                else $dataFine = "";

                if (isset($_SESSION['username']) && $_SESSION['username'] == "admin"){
                    $articolo = str_replace('$ELIMINA$', "elimina", $articolo);
                } else $articolo = str_replace('$ELIMINA$', "", $articolo);

                $alt = $riga['alt'];
                $id = $riga['id'];
                $articolo = str_replace('$TITOLO$', $titolo, $articolo);
                $articolo = str_replace('$DATAI$', $dataInizio, $articolo);
                $articolo = str_replace('$DATAF$', $dataFine, $articolo);
                $articolo = str_replace('$TESTO$', $testo, $articolo);
                $articolo = str_replace('$URL$', $img, $articolo);
                $articolo = str_replace('$ALT$', $alt, $articolo);


                echo $articolo;
                $articolo = file_get_contents("../HTML/boxArticolo.html");
            }
?>