<?php
session_start();

require_once "config/database.php";

include "includes/header.php";
include "includes/navbar.php";

$query = "
SELECT
    c.id,
    c.category_name,
    COUNT(p.id) AS total_products
FROM categories c
LEFT JOIN products p
ON c.id = p.category_id
GROUP BY c.id
ORDER BY c.category_name ASC
";

$categories = mysqli_query($conn, $query);
?>

<div class="container py-5">

    <div class="text-center mb-5">

        <h1 class="fw-bold">
            Shop by Category
        </h1>

        <p class="text-muted">
            Browse products by category.
        </p>

    </div>

    <div class="row g-4">

        <?php while($category = mysqli_fetch_assoc($categories)){ ?>

        <div class="col-md-6 col-lg-3">

            <div class="card shadow-sm border-0 h-100 text-center">

                <div class="card-body p-4">

                    <?php

                    $icon = "bi-grid";

                    switch($category['category_name']){

                        case "Laptops":
                            $icon = "bi-laptop";
                            break;

                        case "Phones":
                            $icon = "bi-phone";
                            break;

                        case "Audio":
                            $icon = "bi-headphones";
                            break;

                        case "Wearables":
                            $icon = "bi-smartwatch";
                            break;

                    }

                    ?>

                    <i class="bi <?php echo $icon; ?> display-3 text-primary"></i>

                    <h4 class="mt-3">

                        <?php echo htmlspecialchars($category['category_name']); ?>

                    </h4>

                    <p class="text-muted">

                        <?php echo $category['total_products']; ?>

                        Products

                    </p>

                    <a
                    href="shop.php?category=<?php echo $category['id']; ?>"
                    class="btn btn-primary rounded-pill">

                        Browse

                    </a>

                </div>

            </div>

        </div>

        <?php } ?>

    </div>

</div>

<?php include "includes/footer.php"; ?>