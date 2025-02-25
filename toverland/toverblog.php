<?php
// Functie om datum in 'dd-mm-yyyy' formaat om te zetten naar RFC 822 formaat
function formatPubDate($date) {
    $dateParts = explode("-", trim($date));
    if (count($dateParts) == 3) {
        $formattedDate = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];
        $timestamp = strtotime($formattedDate);
        return date("r", $timestamp);
    }
    return "Thu, 01 Jan 1970 01:00:00 +0100"; // Fallback
}

// URL van de Toverland-pagina
$url = "https://www.toverland.com/blog";

// Cookies instellen om redirect te voorkomen
$options = [
    "http" => [
        "header" => [
            "Cookie: GeoMateRedirectOverride=toverland;"
        ]
    ]
];

$context = stream_context_create($options);
$html = file_get_contents($url, false, $context);
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
$articles = $xpath->query("//article[contains(@class, 'card')]");

// Begin RSS-feed
header("Content-Type: application/rss+xml; charset=UTF-8");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
echo "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
echo "<channel>\n";
echo "<title>Toverland Blog</title>\n";
echo "<link>https://www.toverland.com/blog</link>\n";
echo "<description>Laatste updates en magie van Toverland</description>\n";
echo '<atom:link href="https://rss.nielskooyman.io/toverblog" rel="self" type="application/rss+xml" />';

// Artikelen doorlopen
foreach ($articles as $article) {
    // Titel ophalen
    $titleNode = $xpath->query(".//h3[contains(@class, 'card__title')]", $article);
    $title = $titleNode->length > 0 ? trim($titleNode->item(0)->textContent) : "Geen titel";

    // Link ophalen
    $linkNode = $xpath->query(".//a[contains(@class, 'btn--text')]", $article);
    $link = $linkNode->length > 0 ? $linkNode->item(0)->getAttribute("href") : "#";

    // Beschrijving ophalen
    $descNode = $xpath->query(".//div[contains(@class, 'card__text')]", $article);
    $description = $descNode->length > 0 ? trim($descNode->item(0)->textContent) : "Geen beschrijving";

    // Datum ophalen
    $dateNode = $xpath->query(".//span[contains(@class, 'card__date')]", $article);
    $dateText = $dateNode->length > 0 ? trim($dateNode->item(0)->textContent) : "01-01-1970";
    $pubDate = formatPubDate($dateText);

    // Afbeelding ophalen (beste resolutie)
    $imageNode = $xpath->query(".//picture/source[@media='(min-width: 1025px)']", $article);
    $imageUrl = $imageNode->length > 0 ? $imageNode->item(0)->getAttribute("data-srcset") : "";

    // Valideer en bouw een volledige URL voor de afbeelding
    if ($imageUrl && strpos($imageUrl, 'http') !== 0) {
        $imageUrl = "https://toverland.com" . $imageUrl;
    }

    // Item toevoegen aan RSS
    echo "<item>\n";
    echo "<title>" . htmlspecialchars($title) . "</title>\n";
    echo "<link>" . htmlspecialchars($link) . "</link>\n";
    echo "<description>" . htmlspecialchars($description) . "</description>\n";
    echo "<pubDate>" . $pubDate . "</pubDate>\n";
    echo "<guid>" . htmlspecialchars($link) . "</guid>\n";
    if ($imageUrl) {
        echo "<enclosure url=\"" . htmlspecialchars($imageUrl) . "\" type=\"image/jpeg\" length=\"\"/>\n";
    }
    echo "</item>\n";
}

// RSS-feed afsluiten
echo "</channel>\n";
echo "</rss>";
?>
