<html>
<head>
  <title>Hello Intranet!</title>
  <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <P> Intranet :: It works! </p>
    <P>Login gegevens:</P>
    <?
      echo "<P>Gebruiker '" . $_SERVER["AUTHENTICATE_UID"] . "' ingelogd met wachtwoord '" . $_SERVER['PHP_AUTH_PW'] . "'</P>";
    ?>
    <form action="createNewUser.php" method="post">
      <label for="idUserName">Gebruikersnaam</label><input type="text" name="username"   id="idUserName"><br/>
      <label for="idVoornaam">Voornaam</label>      <input type="text" name="voornaam"   id="idVoornaam"><br/>
      <label for="idAchternaam">Achternaam</label>  <input type="text" name="achternaam" id="idAchternaam"><br/>
      <button type="submit">Opslaan</button>
    </form>
</body>
</html>
