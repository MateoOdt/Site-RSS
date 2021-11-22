<?php declare(strict_types=1);

/**
 *  Élément de flux RSS
 */
class Item
{
    /**
     * Titre du flux source
     * @var string
     */
    private string $feedTitle;
    /**
     * Titre de l'élément
     * @var string
     */
    private string $title;
    /**
     * URL associée à l'élément
     * @var string
     */
    private string $link;
    /**
     * Date de publication sous forme de timestamp
     * @var int
     */
    private int $pubDate;

    /**
     * Constructeur
     *
     * @param string $feedTitle le titre (title) du flux source
     * @param DOMelement $node le nœud DOM source
     *
     * @throws Exception si les balises 'title' ou 'link' sont absentes
     *
     * @see https://www.php.net/manual/fr/function.strtotime.php
     * @see https://www.php.net/manual/fr/function.time.php
     */
    public function __construct(string $feedTitle, DOMelement $node)
    {
        $this->feedTitle = $feedTitle;
        $this->title = XmlTools::getDescendantNodeValue($node, 'title');
        $this->link = XmlTools::getDescendantNodeValue($node, 'link');
        // Certains flux ne comportent pas de date de publication...
        try {
            $this->pubDate = strToTime(XmlTools::getDescendantNodeValue($node, 'pubDate'));
        } catch (Exception $e) {
            $this->pubDate = time();
        }
    }

    /**
     * Accès au nom du flux source
     *
     * @return string le nom du flux
     */
    public function getFeedTitle(): string
    {
        return $this->feedTitle;
    }

    /**
     * Accès au titre de l'élément
     *
     * @return string le titre de l'élément
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Accès à l'URL de l'élément
     *
     * @return string l'URL de l'élément
     */
    public function getLink(): string
    {
        return $this->link;
    }

    /**
     * Accès à la date de publication de l'élément sous forme de timestamp
     *
     * @return int le timestamp de l'élément
     */
    public function getPubDate(): int
    {
        return $this->pubDate;
    }

    /**
     * Accès à la date de publication de l'élément sous forme de texte
     *
     * @see https://www.php.net/manual/fr/function.strftime.php
     *
     * @return string la date de l'élément sous forme de texte
     */
    public function getPubDateAsString(): string
    {
        return strftime('%d/%m/%Y %H:%M', $this->pubDate);
    }

    /**
     * Comparaison alphabétique des noms des flux de deux éléments
     * @param Item $item1 le premier opérande de la comparaison
     * @param Item $item2 le second opérande de la comparaison
     *
     * @return int 0, -1 ou 1
     */
    public static function compareFeed(self $item1, self $item2): int
    {
        // return $item1->feedTitle <=> $item2->feedTitle;
        if ($item1->feedTitle == $item2->feedTitle)
            return 0;
        return $item1->feedTitle < $item2->feedTitle ? -1 : 1;
    }

    /**
     * Comparaison alphabétique des titres de deux éléments
     * @param Item $item1 le premier opérande de la comparaison
     * @param Item $item2 le second opérande de la comparaison
     *
     * @return int 0, -1 ou 1
     */
    public static function compareTitle(self $item1, self $item2): int
    {
        // return $item1->title <=> $item2->title;
        if ($item1->title == $item2->title)
            return 0;
        return $item1->title < $item2->title ? -1 : 1;
    }

    /**
     * Comparaison chronologique inverse des dates de deux éléments
     * @param Item $item1 le premier opérande de la comparaison
     * @param Item $item2 le second opérande de la comparaison
     *
     * @return int 0, -1 ou 1
     */
    public static function comparePubDate(self $item1, self $item2): int
    {
        // return $item1->pubDate <=> $item2->pubDate;
        if ($item1->pubDate == $item2->pubDate)
            return 0;
        return $item1->pubDate > $item2->pubDate ? -1 : 1;
    }
}