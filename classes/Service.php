<?php

namespace App\Classes;

use PDO;

class Service
{
    private Database $db;


    public function __construct(Database $database, Validator $validator)
    {
        $this->db = $database;
    }

    public function randomizeMovies(): array
    {
        $query = "SELECT f.title, f.description, f.release_year,
                     GROUP_CONCAT(DISTINCT c.name) AS categories,
                     GROUP_CONCAT(CONCAT(a.first_name, ' ', a.last_name)) AS actors
              FROM film f
              LEFT JOIN film_category fc ON f.film_id = fc.film_id
              LEFT JOIN category c ON fc.category_id = c.category_id
              LEFT JOIN film_actor fa ON f.film_id = fa.film_id
              LEFT JOIN actor a ON fa.actor_id = a.actor_id
              GROUP BY f.film_id
              ORDER BY RAND() LIMIT 10";

        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function searchMovies($searchParam, $searchValue): array
    {
        if ($searchParam == 'categories') {
            $query = "SELECT f.title, f.description, f.release_year,
                     GROUP_CONCAT(DISTINCT c.name) AS categories,
                     GROUP_CONCAT(CONCAT(a.first_name, ' ', a.last_name)) AS actors
              FROM film f
              LEFT JOIN film_category fc ON f.film_id = fc.film_id
              LEFT JOIN category c ON fc.category_id = c.category_id
              LEFT JOIN film_actor fa ON f.film_id = fa.film_id
              LEFT JOIN actor a ON fa.actor_id = a.actor_id
              WHERE c.name LIKE :searchValue
              GROUP BY f.film_id";
        } elseif ($searchParam == 'actors') {
            $query = "SELECT f.title, f.description, f.release_year,
                     GROUP_CONCAT(DISTINCT c.name) AS categories,
                     GROUP_CONCAT(CONCAT(a.first_name, ' ', a.last_name)) AS actors
              FROM film f
              LEFT JOIN film_category fc ON f.film_id = fc.film_id
              LEFT JOIN category c ON fc.category_id = c.category_id
              LEFT JOIN film_actor fa ON f.film_id = fa.film_id
              LEFT JOIN actor a ON fa.actor_id = a.actor_id
              WHERE CONCAT(a.first_name, ' ', a.last_name) LIKE :searchValue
              GROUP BY f.film_id";
        } else {
            $query = "SELECT f.title, f.description, f.release_year,
                     GROUP_CONCAT(DISTINCT c.name) AS categories,
                     GROUP_CONCAT(CONCAT(a.first_name, ' ', a.last_name)) AS actors
              FROM film f
              LEFT JOIN film_category fc ON f.film_id = fc.film_id
              LEFT JOIN category c ON fc.category_id = c.category_id
              LEFT JOIN film_actor fa ON f.film_id = fa.film_id
              LEFT JOIN actor a ON fa.actor_id = a.actor_id
              WHERE f.$searchParam LIKE :searchValue
              GROUP BY f.film_id";
        }

        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindValue(':searchValue', "%$searchValue%", PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}