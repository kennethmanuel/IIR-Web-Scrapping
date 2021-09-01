<?php
require 'vendor/autoload.php';

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

// 3. scrap multiple html pages
$client = new Client();
$data = array();
for ($i = 1; $i <= 10; $i++) {
    // 1. scrap html // 

    $res = $client->request('GET', "https://indeks.kompas.com/?page={$i}");
    $html = $res->html();
    // echo $html;

    // 2. scrap html with node filtering // 
    $crawler = new Crawler($html);

    $items = $crawler
        ->filter('.latest--indeks > .article__list')
        ->each(function (Crawler $node, $i) {
            // echo $node->html();
            $title = $node->filter('.article__list__title')->text();
            $url = $node->filter('.article__list__asset > .article__asset > a')->attr('href');
            $img = $node->filter('img')->attr('src');
            $item = [
                'title' => $title,
                'url' => $url,
                'img' => $img
            ];
            return $item;
        });

    $data = array_merge($data, $items);
}
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
    $file = fopen('data.csv', 'w');
    foreach ($data as $item) {
        fputcsv($file, $item);
    }
    fclose($file);
    ?>
</body>

</html>