<?php
require_once './../classes/Database.php';
require_once './../classes/Service.php';
require_once './../classes/Validator.php';

use App\Classes\Database;
use App\Classes\Service;
use App\Classes\Validator;

$database = new Database();
$validator = new Validator();
$service = new Service($database, $validator);

$searchParam = isset($_GET['search-param']) ? $_GET['search-param'] : null;
$searchValue = isset($_GET['search-value']) ? $_GET['search-value'] : null;

if ($searchParam && $searchValue) {
  $movies = $service->searchMovies($searchParam, $searchValue);
} else {
  $movies = $service->randomizeMovies();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Movie Tiles</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="./../styles/main.css" />
</head>

<body>
  <div class="container mt-5">
    <h1 class="text-center mb-4">🎬 <strong>MOVIE TILES</strong></h1>

    <form method="GET" action="./../logic/search.php" class="row gx-2 gy-2 align-items-center justify-content-center">
      <div class="col-md-4">
        <select id="search-option" name="search-param" class="form-select" required>
          <option value="" disabled <?= !$searchParam ? 'selected' : ''; ?>>-- Choose a parameter --</option>
          <option value="title" <?= $searchParam === 'title' ? 'selected' : ''; ?>>Title</option>
          <option value="description" <?= $searchParam === 'description' ? 'selected' : ''; ?>>Description</option>
          <option value="categories" <?= $searchParam === 'categories' ? 'selected' : ''; ?>>Categories</option>
          <option value="actors" <?= $searchParam === 'actors' ? 'selected' : ''; ?>>Actors</option>
          <option value="release_year" <?= $searchParam === 'release_year' ? 'selected' : ''; ?>>Release Year</option>
        </select>
      </div>
      <div class="col-md-4">
        <input type="text" name="search-value" value="<?= htmlspecialchars($searchValue ?? '', ENT_QUOTES); ?>"
          class="form-control" placeholder="Search..." />
      </div>
      <div class="col-md-2 text-center">
        <button type="submit" class="btn w-100">Search</button>
      </div>
    </form>

    <div class="row mt-4">
      <?php if ($movies): ?>
        <?php foreach ($movies as $movie): ?>
          <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
              <div class="card-body">
                <h5 class="card-title text-primary"><?= htmlspecialchars($movie['title']); ?></h5>
                <p class="card-text"><strong>Description:</strong> <?= htmlspecialchars($movie['description']); ?></p>
                <p class="card-text"><strong>Categories:</strong> <?= htmlspecialchars($movie['categories']); ?></p>
                <p class="card-text"><strong>Actors:</strong> <?= htmlspecialchars($movie['actors']); ?></p>
                <p class="card-text"><strong>Release Year:</strong> <?= htmlspecialchars($movie['release_year']); ?></p>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12 text-center">
          <p class="text-muted">No results found. Please adjust your search parameters.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>