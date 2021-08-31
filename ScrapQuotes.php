<?php
require 'vendor/autoload.php';

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

// 3. scrap multiple html pages
$client = new Client();
$data = array();
for ($i = 1; $i <= 10; $i++) {
    // 1. scrap html // 

    $res = $client->request('GET', "https://quotes.toscrape.com/page/{$i}/");
    $html = $res->html();
    // echo $html;

    // 2. scrap html with node filtering // 
    $crawler = new Crawler($html);

    $items = $crawler
        ->filter('.col-md-8 > .quote')
        ->each(function (Crawler $node, $i) {
            // echo $node->html();
            $quote = $node->filter('span.text')->text();
            $author = $node->filter('span > small')->text();
            $item = [
                'quote' => $quote,
                'author' => $author
            ];
            return $item;
        });

    $data = array_merge($data, $items);
}
// print_r($items);


// $file = fopen('images.csv', 'w');

// foreach ($items as $item) {
//     fputcsv($file, $item);
// }

// fclose($file);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    foreach ($data as $item) {
        foreach ($item as $key => $value) {
            echo "{$key}: $value";
            echo "<br>";
        }
    }
    ?>

</body>

</html>