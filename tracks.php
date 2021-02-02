<?php

require(__DIR__ . '/vendor/autoload.php');


if (file_exists(__DIR__ . '/.env')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

//pdo = php data object
// pgsql:host={host};port={port};dbname={dbname};user={user};password={password}
// $pdo = new PDO('pgsql:host=ec2-54-208-233-243.compute-1.amazonaws.com;port=5432;dbname=d8r6q401olu72r;user=pgakyjdgcbfxsp;password=ff01d7daf16a23813cbd04e8a14c987334ca9012a1b613639cff1913d5a8143f');

// php super globals ( that contain a bunch of info: $_GET, $_POST, $_ENV
$pdo = new PDO($_ENV['PDO_CONNECTION_STRING']);

if ( !isset($_GET["playlist"]) || empty($_GET["playlist"]) ) {
	header("Location: playlists.php");
}

$sql = "
SELECT tracks.name, albums.title AS album, artists.name AS artist, unit_price AS price, genres.name AS genre
FROM playlist_track
INNER JOIN tracks
ON tracks.id = playlist_track.track_id
INNER JOIN albums
ON tracks.album_id = albums.id
INNER JOIN artists
ON albums.artist_id = artists.id
INNER JOIN genres
ON tracks.genre_id = genres.id  
WHERE playlist_id = " . $_GET["playlist"] . ";";

$statement = $pdo->prepare($sql); //creating a prepared statement 
$statement->execute();

// $invoices = $statement->fetchAll();
// $invoices = $statement->fetchAll(PDO::FETCH_OBJ);

$tracks = $statement->fetchAll(PDO::FETCH_OBJ);

if ($statement->rowCount() == 0) {
    
    $playlist_sql = "
    SELECT name
    FROM playlists
    WHERE id = " . $_GET["playlist"] . ";";
    
    $statement = $pdo->prepare($playlist_sql);
    $statement->execute();

    $playlists = $statement->fetchAll(PDO::FETCH_OBJ);
    foreach ($playlists as $playlist) {
        echo "No tracks found for " . $playlist->name;
    }
}
// var_dump($invoices);
// die();

?>

<table>
    <thead>
        <tr>
            <th>Track</th>
            <th>Album</th>
            <th>Artist</th>
            <th>Price</th>
            <th>Genre</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tracks as $track) : ?>
            <tr>
                <td>
                    <?php 
                    echo $track->name
                    ?>
                </td>
                <td>
                    <?php 
                    echo $track->album
                    ?>
                </td>
                <td>
                    <?php 
                    echo $track->artist
                    ?>
                </td>
                <td>
                    <?php 
                    echo $track->price 
                    ?>
                </td>
                <td>
                    <?php 
                    echo $track->genre 
                    ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>