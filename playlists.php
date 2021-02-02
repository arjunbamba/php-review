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

$sql = "
SELECT * FROM playlists
";

$statement = $pdo->prepare($sql); //creating a prepared statement 
$statement->execute();

// $invoices = $statement->fetchAll();
// $invoices = $statement->fetchAll(PDO::FETCH_OBJ);

$playlists = $statement->fetchAll(PDO::FETCH_OBJ);


// var_dump($invoices);
// die();

?>

<table>
    <thead>
        <tr>
            <th>Playlists</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($playlists as $playlist) : ?>
            <tr>
                <td>
                    <?php 
                        echo '<a href="tracks.php?playlist='.$playlist->id.'">'.$playlist->name.'</a>';
                    ?>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>