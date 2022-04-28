<?php 
$err = "";
$obraz = ""; $login = ""; $wiek = ""; $hobby = ""; $przyjaciol = "";
$wyswietl = false;
$db = mysqli_connect('localhost', 'root', '', 'portal');
if(!$db){
    die("Błąd połączenia z bazą danych");
}
$sql1 = "SELECT COUNT(id) FROM dane";
$res1 = mysqli_query($db, $sql1);

if(!$res1){
    die("Błąd zapytania: " + mysqli_error($db));
}
$row1 = mysqli_fetch_row($res1)[0];
if(isset($_POST['login']) && isset($_POST['haslo'])){
if($_POST['login'] != "" && $_POST['haslo'] != ""){
$sql2 = "SELECT haslo FROM uzytkownicy WHERE login = '".$_POST['login']."'";
$res2 = mysqli_query($db, $sql2);
$rows2 = mysqli_fetch_row($res2);
if($rows2 == null){
    $err .= "Login nie istnieje <br>";
}else{
if(sha1($_POST['haslo']) == $rows2[0]){
    $sql3 = "SELECT login, rok_urodz, przyjaciol, hobby, zdjecie FROM uzytkownicy INNER JOIN dane ON uzytkownicy.id = dane.id WHERE login = '".$_POST['login']."'";
    
    $res3 = mysqli_query($db, $sql3);
    if(!$res3){
        die("Błąd zapytania: " + mysqli_error($db));
    }else{
        $wyswietl = true;
    }
    $rows3 = mysqli_fetch_row($res3);
    $login = $rows3[0];
    $wiek = date("Y") - $rows3[1];
    $hobby = $rows3[3];
    $przyjaciol = $rows3[2];
    $obraz = $rows3[4];

}else{
    $err .= "Hasło nieprawidłowe <br>";
}
}

}else{
    $err .= "Wypełnij wszystkie pola <br>";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal społecznościowy</title>
    <link rel="stylesheet" href="styl5.css">
</head>
<body>
    <header id="headerLewy">
    <h2>Nasze osiedle</h2>
    </header>
    <header id="headerPrawy">
<p>Liczba użytkowników portalu: <?php echo $row1 ?></p>
    </header>
    <div id="lewy">
        <h3>Logowanie</h3>
        <form action="uzytkownicy.php" method="POST">
            <p>Login</p>
            <input type="text" name="login" id="loginIn">
            <p>Hasło</p>
            <input type="password" name="haslo" id="hasloIn"><br>
            <input type="submit" value="Zaloguj" id="przyciskLogowania">
        </form>
        <?php echo $err; ?>
    </div>
    <div id="prawy">
    <h3>Wizytówka</h3>
    <div id="wizytowka">
        <?php 
        if($wyswietl == true){
            echo "<img src='".$obraz."' alt='osoba'><br>";
            echo "<h4>$login ($wiek)</h4>";
            echo "<p>hobby: $hobby</p>";
            echo "<h1><img src='icon-on.png'></img> $przyjaciol</h1>";
            echo "<a href='./dane.html'><button>Więcej informacji</button></a>";
        }
        
        
        ?>
    </div>
    </div>
    <footer id="footer1">
        <p>Stronę wykonał: PESEL</p>
    </footer>
</body>
</html>
<?php 
mysqli_close($db);
?>