<?php declare(strict_types=1);

/**
 * Agrégateur de flux RSS
 */
class RssAgregator
{
    /**
     * Tableau des éléments de flux RSS = tableau d'instances de Item
     *
     * @var Item[] $items
     */
    protected array $items = [];

    /**
     * Ajouter un flux RSS à l'agrégateur
     * Ceci correspond à chercher toutes les balises 'item'
     * dans le DOM et en faire des objets Item qui seront
     * ajoutés au tableau $items de l'instance courante
     * @param string $url l'URL du flux à ajouter
     * @param string|null $feedTitle string le titre du flux.
     *                  S'il est null, utiliser le titre du flux trouvé dans $url
     *
     * @return void
     * @throws Exception quand le flux ne peut pas être chargé
     */
    public function addFeed(string $url, string $feedTitle = null): void
    {
        // Explorer tous les éléments <item> afin de construire un Item pour chacun d'eux
        $DOMDoc = new DOMDocument();
        // Le fichier peut-il être chargé ?
        if (@$DOMDoc->load($url)) {
            // Titre du flux
            $title = $feedTitle;
            if (is_null($feedTitle)) {
                /* Fonctionne mais suppose que le titre du flux est en début de fichier XML
                $titre_flux = $DOMDoc->getElementsByTagName('channel')
                                     ->item(0)
                                     ->getElementsByTagName('title')
                                     ->item(0)
                                     ->firstChild
                                     ->nodeValue;
                */
                // Recherche des nœuds 'title' du document DOM
                $titleNodes = $DOMDoc->getElementsByTagName('title');
                // Parcours des nœuds de la liste jusqu'à trouver celui dont le parent est 'channel'
                foreach ($titleNodes as $node) {
                    if ($node->parentNode->nodeName == 'channel') {
                        $title = $node->firstChild->nodeValue;
                        break;
                    }
                }
                if (is_null($title)) {
                    throw new Exception("Le flux '$url' ne contient pas de titre");
                }
            }
            // Recherche des items
            $items = $DOMDoc->getElementsByTagName('item');
            // Parcours des items
            foreach ($items as $i) {
                $this->items[] = new Item($title, $i);
            }
        } else {
            throw new Exception("Le flux '$url' n'a pu être chargé");
        }
    }

    /**
     * Production de la liste des éléments en HTML
     *
     * @return string le code HTML
     */
    public function toHTML(): string
    {
        $html = '<div class="feed">';
        foreach ($this->items as $n) {
            $html .= <<<HTML
    <div class='rss'>
        <span class='date'>{$n->getPubDateAsString()}</span>
        <span class='flux'>{$n->getFeedTitle()}</span> :
        <a class='lien' href='{$n->getLink()}'>{$n->getTitle()}</a>
    </div>\n
HTML;
        }
        return $html . '</div>';
    }

    /**
     * Tri des éléments par titre de flux source
     *
     * @see https://www.php.net/manual/fr/function.uasort.php
     *
     * @return void
     */
    public function sortByFeed(): void
    {
        // uasort($this->items, 'Item::compareFeed');
        // ou
        uasort($this->items, [Item::class, 'compareFeed']);
    }

    /**
     * Tri des éléments par titre
     *
     * @see https://www.php.net/manual/fr/function.uasort.php
     *
     * @return void
     */
    public function sortByTitle(): void
    {
        // uasort($this->items, 'Item::compareTitle');
        // ou
        uasort($this->items, [Item::class, 'compareTitle']);
    }

    /**
     * Tri des éléments par date
     *
     * @see https://www.php.net/manual/fr/function.uasort.php
     *
     * @return void
     */
    public function sortByPubDate(): void
    {
        // uasort($this->items, 'Item::comparePubDate');
        // ou
        uasort($this->items, [Item::class, 'comparePubDate']);
    }

    /**
     * Production d'un flux RSS
     * @param $title string le titre du flux
     * @param $description string la description du flux
     * @param $max int le nombre maximal d'éléments dans le flux (0 = tous)
     *
     * @return string le code XML du nouveau flux RSS
     */
    public function getRssFeed(string $title, string $description, int $max = 0): string
    {
        // Réduction du nombre d'éléments
        $elements = $max ? array_slice($this->items, 0, $max) : $this->items;
        // Début du fichier RSS
        $rss = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
    <channel>
        <title>{$title}</title>
        <description>{$description}</description>
        <link>http://{$_SERVER['SERVER_NAME']}/{$_SERVER['PHP_SELF']}</link>

XML;
        // Parcours des éléments
        foreach ($elements as $n) {
            $date = gmdate('D, d M Y H:i:s \G\M\T', $n->getPubDate());
            $guid = sha1($n->getLink());
            $rss .= <<<XML
            <item>
                <title><![CDATA[ {$n->getTitle()} ]]></title>
                <guid><![CDATA[ {$guid} ]]></guid>
                <pubDate>{$date}</pubDate>
                <link><![CDATA[ {$n->getLink()} ]]></link>
            </item>

XML;
        }
        // Fin du fichier RSS
        $rss .= <<<XML
    </channel>
</rss>
XML;
        return $rss;
    }
}