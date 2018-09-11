<?php

        include("database.php");

        include("connDatabase.php");

        $ricercata = $_SESSION['ricerca'];
        $articolo = file_get_contents("html/boxArticolo.html");
        $elimina = file_get_contents("html/bottoneElimina.html");


        if (isset($_SESSION['PAGINA']))
                $pag = $_SESSION['PAGINA']; //per pagina intendo le citta qui, quindi vicenza, padova ecc
        else $pag = "";


 if(!isset($_SESSION['pag'])){  //sessione['pag'] e' l'url
     $pageHome  = str_replace('$TITOLO$', "Home | Cerca", $pageHome );  //ricera nella Home, quindi cerco in tutte le citta
     $pageHome = str_replace('$SEZIONE$', "HOME RICERCA", $pageHome);

    $tabelle = array("padova", "vicenza", "verona", "venezia");
    $x = 0;
    $numRisultati = 0;

    while($x < 4){

           $result = mysqli_query($conn, "select * from ".$tabelle[$x]." where sezione <> 'biglietti' AND (testo LIKE '%".$ricercata."%' OR titolo LIKE '%".$ricercata."%')");

        while ($riga = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                $titolo = $riga['titolo'];
                $testo =  $riga['testo'];
                $img = $riga['img'];
                $dataI = $riga['data_inizio'];
                $biglietti = $riga['biglietti'];
                $alt = $riga['alt'];
                $id = $riga['id'];
                $citta = $tabelle[$x];

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
                    $elimina = str_replace('$ID$', $id , $elimina);
                    $elimina = str_replace('$CITTA$', $citta, $elimina);
                    $articolo = str_replace('$ELIMINA$', $elimina, $articolo);
                } else $articolo = str_replace('$ELIMINA$', "", $articolo);

                $articolo = str_replace('$TITOLO$', $titolo, $articolo);
                $articolo = str_replace('$DATAI$', $dataInizio, $articolo);
                $articolo = str_replace('$DATAF$', $dataFine, $articolo);
                $articolo = str_replace('$TESTO$', $testo, $articolo);
                $articolo = str_replace('$URL$', $img, $articolo);
                $articolo = str_replace('$ALT$', $alt, $articolo);

                if($biglietti!='null'){
                    $articolo = str_replace('$BIGLIETTO$', $biglietti, $articolo);
                } else $articolo = str_replace('$BIGLIETTO$', "", $articolo);

                echo $articolo;
                $numRisultati = $numRisultati + 1;
                $articolo = file_get_contents("html/boxArticolo.html");
            }

            $x = $x + 1;
    }

     if ($numRisultati==0) echo "<div class=\"messaggioSpeciale2\">Nessun risultato per: \"".$ricercata."\"</div>";

 }
    else  {
             $NomeCitta = ucfirst($pag);
             $pageHome = str_replace('$TITOLO$', $NomeCitta." | Cerca", $pageHome);
             $pageHome = str_replace('$SEZIONE$', "RICERCA IN ".strtoupper($NomeCitta), $pageHome);

             $result1 = mysqli_query($conn, "select * from ".$pag." where sezione <> 'biglietti' AND (testo LIKE '%".$ricercata."%' OR titolo LIKE '%".$ricercata."%')");

            if(!($riga = mysqli_fetch_array($result1, MYSQLI_ASSOC))) echo "<div class=\"messaggioSpeciale2\">Nessun risultato per: \"".$ricercata."\"</div>";

            $result2 = mysqli_query($conn, "select * from ".$pag." where sezione <> 'biglietti' AND (testo LIKE '%".$ricercata."%' OR titolo LIKE '%".$ricercata."%')");

            while ($riga = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {

                $titolo = $riga['titolo'];
                $testo =  $riga['testo'];
                $img = $riga['img'];
                $dataI = $riga['data_inizio'];
                $biglietti = $riga['biglietti'];
                $alt = $riga['alt'];
                $id = $riga['id'];
                $citta = $titolo;

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
                    $elimina = str_replace('$ID$', $id, $elimina);
                    $elimina = str_replace('$CITTA$', $citta, $elimina);
                    $articolo = str_replace('$ELIMINA$', $elimina, $articolo);
                } else $articolo = str_replace('$ELIMINA$', "", $articolo);

                $articolo = str_replace('$TITOLO$', $titolo, $articolo);
                $articolo = str_replace('$DATAI$', $dataInizio, $articolo);
                $articolo = str_replace('$DATAF$', $dataFine, $articolo);
                $articolo = str_replace('$TESTO$', $testo, $articolo);
                $articolo = str_replace('$URL$', $img, $articolo);
                $articolo = str_replace('$ALT$', $alt, $articolo);

                if($biglietti!='null'){
                    $articolo = str_replace('$BIGLIETTO$', $biglietti, $articolo);
                } else $articolo = str_replace('$BIGLIETTO$', "", $articolo);

                echo $articolo;
                $articolo = file_get_contents("html/boxArticolo.html");
            }
        }

?>
