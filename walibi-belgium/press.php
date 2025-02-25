<?php
// URL van Walibi Holland perspagina
$url = "https://walibibelgium.prezly.com/nl";

// HTML-pagina ophalen
$html = file_get_contents($url);
if ($html === false) {
    die("Kon de pagina niet laden.");
}

// Laad de HTML in een DOM-document
$dom = new DOMDocument();
libxml_use_internal_errors(true);
$dom->loadHTML($html);
libxml_clear_errors();

// XPath gebruiken om de gewenste elementen te selecteren
$xpath = new DOMXPath($dom);
$articles = $xpath->query("//div[contains(@class, 'StoryCard_container__KVQRO')]");

// Begin RSS-feed
header("Content-Type: application/rss+xml; charset=UTF-8");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
echo "<channel>\n";
echo "<title>Walibi Belgium Nieuws</title>\n";
echo "<link>$url</link>\n";
echo "<description>Laatste nieuws en updates van Walibi Belgium</description>\n";
echo '<atom:link href="https://rss.nielskooyman.io/walibi-belgium" rel="self" type="application/rss+xml" />';

// Artikelen doorlopen
foreach ($articles as $article) {
    // Titel ophalen
    $titleNode = $xpath->query(".//a[contains(@class, 'StoryCard_titleLink__El6wj')]", $article);
    $title = $titleNode->length > 0 ? trim($titleNode->item(0)->textContent) : "Geen titel";

    // Link ophalen
    $linkNode = $xpath->query(".//a[contains(@class, 'StoryCard_titleLink__El6wj')]", $article);
    $link = $linkNode->length > 0 ? "https://walibibelgium.prezly.com/nl" . $linkNode->item(0)->getAttribute("href") : "#";

    // Datum ophalen
    $dateNode = $xpath->query(".//time", $article);
    $date = $dateNode->length > 0 ? $dateNode->item(0)->getAttribute("datetime") : "1970-01-01T00:00:00Z";
    $pubDate = date("r", strtotime($date));

    // Afbeelding ophalen
    $imageNode = $xpath->query(".//img", $article);
    $imageUrl = $imageNode->length > 0 ? $imageNode->item(0)->getAttribute("src") : "";

    // Item toevoegen aan RSS
    echo "<item>\n";
    echo "<title>" . htmlspecialchars($title) . "</title>\n";
    echo "<link>" . htmlspecialchars($link) . "</link>\n";
    echo "<pubDate>" . htmlspecialchars($pubDate) . "</pubDate>\n";
    echo "<guid>" . htmlspecialchars($link) . "</guid>\n";
    if (!empty($imageUrl)) {
        echo "<enclosure url=\"" . htmlspecialchars($imageUrl) . "\" type=\"image/jpeg\" />\n";
    }
    echo "</item>\n";
}

// RSS-feed afsluiten
echo "</channel>\n";
echo "</rss>";
?>
