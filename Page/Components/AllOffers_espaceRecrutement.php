<?php
session_start();
include('../../src/Database/Database.php');
include('../../src/Classe/Espace_Recruteur.php');
use \Class\Espace_Recruteur;
use \Database\Database;

$database = new Database;
$db = $database->getConnection();

$getJobs = new Espace_Recruteur($db);
$jobs = $getJobs->getAllJobs();

if(isset($_GET['id'])){
    $getJobs->deleteJobs();
}
?>
<?php while($row = $jobs->fetch(PDO::FETCH_ASSOC)):?>
    <form class="jobs__container" data-id="<?=$row['id']?>" method="POST" action="">
        <img class="jobs__container__logo" alt="entreprise" src='https://www.apiecf.colas.cefim.o2switch.site<?= $row['logo']?>' style="background-color: <?= $row['logo_background'] ?>">
        <div class="jobs__container__items">
            <div class="time">
                <p><?= explode(' ', $row['postedAt'])[0]?></p>
                <span class="dot"></span>
                <p><?=$row['contract']?></p>
            </div>
            <div class="jobs__container__info">
                <h3 class="jobs__container__info__name"><?=$row['position']?></h3>
                <p><?=$row['company']?></p>
            </div>
            <p class="city"><?=$row['location']?></p>
            <div class="buttons_container">
                <a class="btnJobs"><i class="fa-solid fa-pen-to-square"></i></a>
                <a class="btnJobs"><i class="fa-solid fa-trash"></i></a>
            </div>
        </div>
    </form>
<?php endwhile;?>
