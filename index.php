<?php
declare(strict_types=1);
$titre = 'Agregateur RSS';
///http://www.bfmtv.com/rss/societe/
$html = <<<HTML
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>{$titre}</title>
    <style>
        body {
          background-color: #F6F6F6;
          padding:0px;
          margin:0px;
          font-family: 'pristina-regular', sans-serif;
        }
       .container {
         margin:30px;
         text-align: center;
         position: relative;
         top:100px;
         color: #07A515;
       }
        .container p {
        text-decoration:underline;
        }
        .ContenuF {
            color: #069112;
            position :absolute;
            bottom:0px;
            margin-left:10px;
        }
        button {
            background-color: #057a0f; 
            border: none;
            color: white;
            padding: 7px 17px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 10px;
            margin-left: 10px;
}
        }
    </style>
  </head>
  <body>
    <div class="container">
      <h1>{$titre}</h1>
      <p>Saisissez une URL :</p>
      <form method="Get" action="agregateurrss.php">
        <input name="url" type="search" id="site-search">
        <button type="submit">Rechercher</button>
      </form>
    </div>
    <footer>
      <div class="ContenuF">
        <p> Vous pouvez tester avec les Url : https://www.lemonde.fr/bresil/rss_full.xml ou https://www.lemonde.fr/planete/rss_full.xml
      </div>
    </footer>

HTML;
echo $html;
