<?php

namespace Controller;

use PDO;

class Espace_Recruteur
{

    private $connexion;
    private $sql;

    public function __construct($db)
    {
        $this->connexion = $db;
    }

    public function getAllJobs()
    {
        $this->sql = "SELECT a.company, contract, position, logo, logo_background, position, location, postedAt, a.id
                      FROM job a
                      INNER JOIN entreprise b
                      WHERE b.company = :company AND a.company = :company;";

        $query =  $this->connexion->prepare($this->sql);
        $query->bindValue(':company', $_SESSION['company']);
        $query->execute();
        return $query;
    }


    public function getJobById($id)
    {
        $this->sql = "SELECT a.position, a.id, a.contract, a.postedAt, b.description, c.content as req_content, d.content as role_content
                      FROM job a
                      INNER JOIN job_detail b
                      INNER JOIN requirement_content c
                      INNER JOIN role_content d
                          ON a.id = b.job_id AND a.id = c.job_id AND a.id = d.job_id
                            WHERE a.id = :id;";

        $query =  $this->connexion->prepare($this->sql);
        $query->bindValue(':id', $id);
        $query->execute();
        return $query;
    }

    public function getReqList($id)
    {
        $this->sql = "SELECT id, item FROM requirement_items WHERE job_id = :id";

        $query =  $this->connexion->prepare($this->sql);
        $query->bindValue(':id', $id);
        $query->execute();
        return $query;
    }

    public function getRoleList(string $id)
    {
        $this->sql = "SELECT id, item FROM role_item WHERE job_id = :id";

        $query =  $this->connexion->prepare($this->sql);
        $query->bindValue(':id', $id);
        $query->execute();
        return $query;
    }

    public function deleteJobs($id): bool
    {
        $this->sql = 'DELETE FROM job WHERE id = :id';
        $query = $this->connexion->prepare($this->sql);
        $query->bindValue(':id', $id);
        $query->execute();
        return true;
    }

    public function editJobs($id): bool
    {
        if(isset($_POST['id']))
        {
            $this->sql = 'UPDATE job a SET
                          a.position = :position,
                          a.contract = :contract
                          WHERE a.id = :id';
            $query = $this->connexion->prepare($this->sql);
            $query->bindValue(':position', $_POST['position']);
            $query->bindValue(':contract', $_POST['contract']);
            $query->bindValue(':id', $id);
            $query->execute();


            $this->sql = 'UPDATE job_detail a SET
                          a.description = :description
                          WHERE a.job_id = :id';
            $query = $this->connexion->prepare($this->sql);
            $query->bindValue(':description', $_POST['description']);
            $query->bindValue(':id', $id);
            $query->execute();

            $this->sql = 'UPDATE requirement_content a SET
                          a.content = :req_content
                          WHERE a.job_id = :id';
            $query = $this->connexion->prepare($this->sql);
            $query->bindValue(':req_content', $_POST['req_content']);
            $query->bindValue(':id', $id);
            $query->execute();

            $this->sql = 'UPDATE role_content a SET
                          a.content = :role_content
                          WHERE a.job_id = :id';
            $query = $this->connexion->prepare($this->sql);
            $query->bindValue(':role_content', $_POST['role_content']);
            $query->bindValue(':id', $id);
            $query->execute();


            $reqList = $this->getReqList($id);
            while($row = $reqList->fetch(PDO::FETCH_ASSOC))
            {
                $this->sql = 'UPDATE requirement_items a SET
                              a.item = :item
                              WHERE a.id = :id AND a.job_id = :id_jobs';
                $query = $this->connexion->prepare($this->sql);
                $query->bindValue(':item', $_POST['req_item_'.$row['id']]);
                $query->bindValue(':id', $row['id']);
                $query->bindValue(':id_jobs', $id);
                $query->execute();
            }

            $RoleList = $this->getRoleList($id);
            while($row = $RoleList->fetch(PDO::FETCH_ASSOC))
            {
                $this->sql = 'UPDATE role_item a SET
                              a.item = :item
                              WHERE a.id = :id AND a.job_id = :id_jobs';
                $query = $this->connexion->prepare($this->sql);
                $query->bindValue(':item', $_POST['role_item_'.$row['id']]);
                $query->bindValue(':id', $row['id']);
                $query->bindValue(':id_jobs', $id);
                $query->execute();
            }

            $this->sql = "SELECT id, item FROM role_item WHERE job_id = :id";

            $query =  $this->connexion->prepare($this->sql);
            $query->bindValue(':id', $id);
            $query->execute();

            return true;
        }
        return false;
    }
}