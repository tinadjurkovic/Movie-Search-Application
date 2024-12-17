<?php
require_once './../classes/Database.php';
require_once './../classes/Service.php';
require_once './../classes/Validator.php';

use App\Classes\Database;
use App\Classes\Validator;
use App\Classes\Service;

$database = new Database();
$validator = new Validator();

$searchParam = isset($_GET['search-param']) ? $_GET['search-param'] : null;
$searchValue = isset($_GET['search-value']) ? $_GET['search-value'] : null;

$error = null;

if ($searchParam && $searchValue) {
    if ($validator->isValidSearchParam($searchParam) && $validator->isValidSearchValue($searchValue)) {
        $service = new Service($database, $validator);
        $movies = $service->searchMovies($searchParam, $searchValue);

        if (empty($movies)) {
            $error = "No results found. Please adjust your search parameters.";
        }
    } else {
        $error = "Invalid search parameters.";
    }
}

$queryString = http_build_query([
    'search-param' => $searchParam,
    'search-value' => $searchValue,
    'error' => $error
]);

header("Location: ./../public/index.php?$queryString");
exit;
