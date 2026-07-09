<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm py-3">

    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand fw-bold fs-2" href="index.php">
            <i class="bi bi-bag-fill text-primary"></i> TechCart
        </a>

        <!-- Mobile Toggle -->
        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

            <!-- Search Bar -->
            <form
                class="d-flex mx-lg-4 flex-grow-1"
                action="shop.php"
                method="GET">

                <input
                    class="form-control rounded-pill me-2"
                    type="search"
                    name="search"
                    placeholder="Search by product, description or category..."
                    value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                    autocomplete="off">

                <button
                    class="btn btn-primary rounded-pill px-4"
                    type="submit">

                    <i class="bi bi-search"></i>

                </button>

            </form>

            <!-- Navigation -->
            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="shop.php">Shop</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="categories.php">Categories</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="deals.php">Deals</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="about.php">About</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>

                <?php if(isset($_SESSION['user_id'])){ ?>

                <li class="nav-item dropdown ms-lg-3">

                    <a class="nav-link dropdown-toggle"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">

                            <i class="bi bi-person-circle fs-5"></i>

                            <?php echo htmlspecialchars($_SESSION['user_name']); ?>

                        </a>

                    <ul class="dropdown-menu dropdown-menu-end">

                        <li>

                            <a class="dropdown-item" href="profile.php">

                                <i class="bi bi-person"></i>

                                My Profile

                            </a>

                        </li>

                        <li>

                            <a class="dropdown-item" href="my_orders.php">

                                <i class="bi bi-bag-check"></i>

                                My Orders

                            </a>

                        </li>

                        <li><hr class="dropdown-divider"></li>

                        <li>

                            <a class="dropdown-item text-danger" href="logout.php">

                                <i class="bi bi-box-arrow-right"></i>

                                Logout

                            </a>

                        </li>

                    </ul>

                </li>

                <?php } else { ?>

                <li class="nav-item ms-lg-3">

                    <a class="nav-link" href="login.php">

                        <i class="bi bi-person-circle fs-5"></i>

                    </a>

                </li>

                <?php } ?>

                <li class="nav-item ms-lg-2">

                    <a class="nav-link position-relative" href="cart.php">

                        <i class="bi bi-cart3 fs-5"></i>

                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

                            <?php
                            if (isset($_SESSION['cart'])) {
                                echo array_sum($_SESSION['cart']);
                            } else {
                                echo 0;
                            }
                            ?>

                        </span>

                    </a>

                </li>

            </ul>

        </div>

    </div>

</nav>