<?php declare(strict_types=1);

/**
 *  Outils XML
 */
class XmlTools {
    /**
     * Rechercher la valeur texte contenue dans une balise descendant d'un nœud DOM
     * @param DOMElement $node le nœud DOM à partir duquel la recherche doit être lancée
     * @param string $nodeName le nom de la balise cherchée
     * @return string la valeur texte contenu dans la balise <$nodeName>
     * @throws Exception quand la balise n'a pu être trouvée
     */
    public static function getDescendantNodeValue(DOMElement $node, string $nodeName) : string
    {
        $list = $node->getElementsByTagName($nodeName);
        // La recherche donne un seul nœud résultat et ce nœud résultat possède au moins un fils
        if ($list->length == 1 && $list->item(0)->hasChildNodes()) {
            // Si le nœud résultat possède plus d'un fils, il contient vraisemblablement une section <![CDATA[ ... ]]>
            if (count($list ->item(0)->childNodes) > 1) {
                // Parcours de ses fils à la recherche d'un nœud <![CDATA[ ... ]]>
                foreach ($list ->item(0)->childNodes as $child) {
                    if ($child->nodeType == XML_CDATA_SECTION_NODE) {
                        // Trouvé ! Retourner sa valeur
                        return $child->nodeValue;
                    }
                }
                // Pas trouvé... Nous avons un problème
                throw new Exception("La balise '$nodeName' n'a pu être trouvée dans les descendants de '{$node->tagName}'");
            }
            else {
                return $list
                    ->item(0)
                    ->firstChild
                    ->nodeValue;
                // return $list->item(0)->nodeValue ; // Fonctionne par "abus de langage" mais ne devrait pas !
            }
        }
        else {
            if ($list->length > 1) {
                throw new Exception("La balise '$nodeName' a été trouvée plusieurs fois dans les descendants de '{$node->tagName}'");
            }
            else {
                throw new Exception("La balise '$nodeName' n'a pu être trouvée dans les descendants de '{$node->tagName}'");
            }
        }
    }
}
