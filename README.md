# 🛒 TechCart

<p align="center">

<img src="https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php">

<img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white">

<img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap">

<img src="https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white">

<img src="https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white">

<img src="https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black">

<img src="https://img.shields.io/badge/License-MIT-success?style=for-the-badge">

</p>

---

## 📖 Overview

TechCart is a full-stack e-commerce web application built using **PHP**, **MySQL**, **Bootstrap 5**, **HTML5**, **CSS3**, and **JavaScript**.

The application enables customers to browse electronic products, manage shopping carts, create accounts, place orders, and track their purchases. It also includes a dedicated administrator panel for managing products, customer accounts, and orders.

The project was built as a portfolio application to demonstrate practical full-stack web development concepts including authentication, CRUD operations, relational databases, session management, responsive design, and e-commerce workflows.

---

# ✨ Features

## 👤 Customer Features

- User Registration
- Secure Login & Logout
- Password Hashing
- Profile Management
- Change Password
- Browse Products
- Search Products
- Filter by Categories
- Product Details Page
- Shopping Cart
- Update Cart Quantity
- Remove Items from Cart
- Checkout Process
- Order Confirmation
- Order History
- Order Details
- Responsive Design

---

## 🔐 Admin Features

- Secure Admin Login
- Dashboard
- Product Management
- Add Products
- Edit Products
- Delete Products
- User Management
- Order Management
- View Customer Orders

---

# 🛠 Tech Stack

## Frontend

- HTML5
- CSS3
- Bootstrap 5
- Bootstrap Icons
- JavaScript

## Backend

- PHP

## Database

- MySQL

## Development Environment

- XAMPP
- phpMyAdmin
- Visual Studio Code

---

# 📂 Project Structure

```text
TechCart/
│
├── admin/
│   ├── add_product.php
│   ├── delete_product.php
│   ├── edit_product.php
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   ├── orders.php
│   ├── products.php
│   ├── users.php
│   └── view_order.php
│
├── assets/
│   ├── css/
│   └── images/
│ 
├── config/
│   └── database.php
│
├── database/
│   └── techcart.sql
│
├── includes/
│   ├── header.php
│   ├── navbar.php
│   └── footer.php
│
├── about.php
├── add_to_cart.php
├── cart.php
├── categories.php
├── change_password.php
├── checkout.php
├── clear_cart.php
├── contact.php
├── deals.php
├── faqs.php
├── index.php
├── login.php
├── logout.php
├── my_orders.php
├── order_details.php
├── order_success.php
├── place_order.php
├── privacy.php
├── product.php
├── profile.php
├── register.php
├── remove_from_cart.php
├── returns.php
├── shipping.php
├── shop.php
├── test.php
├── update_cart.php
├── update_profile.php
│
├── README.md
└── LICENSE
```

---

# 📸 Screenshots

Screenshots will be added soon.

Recommended screenshots:

- Home Page
- Shop
- Product Details
- Shopping Cart
- Checkout
- Profile
- Order History
- Order Details
- Admin Dashboard
- Product Management
- User Management

---

# 🔄 Application Workflow

```
Register
      ↓
Login
      ↓
Browse Products
      ↓
View Product Details
      ↓
Add to Cart
      ↓
Checkout
      ↓
Place Order
      ↓
View Order History
```

---

# ⚙ Installation

## 1. Clone the repository

```bash
git clone https://github.com/BenjaminAyivoh1/TechCart.git
```

---

## 2. Move the project

Copy the project folder into:

```
xampp/htdocs/
```

---

## 3. Start XAMPP

Launch:

- Apache
- MySQL

---

## 4. Create Database

Create a database named:

```
techcart
```

---

## 5. Import Database

Import

```
database/techcart.sql
```

using phpMyAdmin.

---

## 6. Configure Database Connection

Edit:

```
config/database.php
```

Example:

```php
<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "techcart"
);

if(!$conn){
    die("Connection Failed.");
}

?>
```

---

## 7. Run the Project

Open:

```
http://localhost/TechCart
```

---

# 🔑 Admin Access

Administrator Login:

```
http://localhost/TechCart/admin/login.php
```

---

# 📚 Concepts Demonstrated

This project demonstrates practical knowledge of:

- CRUD Operations
- Authentication
- Session Management
- Password Hashing
- Relational Databases
- Shopping Cart Logic
- Checkout Workflow
- Responsive Design
- PHP & MySQL Integration
- Bootstrap UI Development

---

# 🚀 Future Improvements

- Wishlist
- Product Reviews
- Product Ratings
- Product Image Gallery
- Email Verification
- Password Reset
- Paystack Integration
- Stripe Integration
- Order Tracking
- Sales Analytics
- Coupons & Discounts
- Inventory Notifications
- Product Recommendations
- Dark Mode

---

# 📈 Lessons Learned

While building TechCart, I gained practical experience in:

- Designing relational databases
- Building secure authentication systems
- Managing PHP sessions
- Implementing CRUD functionality
- Structuring larger PHP applications
- Building responsive interfaces with Bootstrap
- Debugging database-driven applications
- Organizing reusable code using includes

---

# 👨‍💻 Author

## Benjamin Ayivoh

**B.Ed Information & Communication Technology**

University of Ghana

---

# 🤝 Contributing

Contributions, suggestions, and feedback are welcome.

Feel free to fork the repository and submit a pull request.

---

# 📄 License

This project is licensed under the MIT License.

See the LICENSE file for more details.

---

# ⭐ Support

If you enjoyed this project or found it useful, please consider giving it a ⭐ on GitHub.

It helps others discover the project and supports my work.
