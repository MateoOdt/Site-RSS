<?php declare(strict_types=1);

require_once('autoload.php');

$p = new WebPage("Agrégation de flux RSS");
$p->appendCSS(<<<CSS
          .rss a:link, .rss a:visited {
              text-decoration : none;
          }

          .rss a:link:hover {
              text-decoration : underline;
          }

          .rss .flux {
              font-weight : bold;
              padding: 2px;
          }
          
          body {
               background-color: #F6F6F6;
          }
          h1 {
          color :#07A515;
          }
CSS
);
$p->appendContent(<<<HTML
    <h1>Vos flux RSS :</h1>
HTML);
    try {
    // Tableau contenant les noms des fichiers source
    $url = [$_GET['url']];

    // Construction de l'agrégateur
    $a = new RssAgregator();
    // Parcours des noms des fichiers RSS
    foreach ($url as $u) {
        // Ajout à l'agrégateur
        $a->addFeed($u);
    }
    // Mise en forme HTML des éléments
    $p->appendContent($a->toHTML());
} catch (Exception $e) {
    $p->appendContent(<<<HTML
    <h1>Exception rencontrée</h1>
    <em>{$e->getMessage()}</em>
    <div id='trace'>Trace :
    <pre>
{$e->getTraceAsString()}
    </pre>
    </div>
HTML
    );
}
if(empty($url)) {
    $p->appendContent(<<<HTML
    <h1>Veuillez entrer une URL</h1>
HTML);
}

// Envoi de la réponse HTTP au client
echo $p->toHTML();