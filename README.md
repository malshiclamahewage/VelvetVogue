# Velvet Vogue (E-Commerce Platform)

Velvet Vogue is a comprehensive PHP and MySQL-based e-commerce web application. It features a complete shopping experience for customers alongside a robust administrative dashboard for store management.

## 🚀 Features

### Customer Features
*   **User Authentication:** Secure registration and login system with password hashing.
*   **Product Catalog:** Browse products, view detailed descriptions, and select specific sizes (Small, Medium, Large).
*   **Search Functionality:** Quickly find products using the built-in search engine.
*   **Shopping Cart:** Add items to the basket, adjust quantities, and manage size variations dynamically.
*   **Checkout & Mock Payment:** Advanced mock payment gateway featuring CVV validation, card type detection, and algorithmic validation using the **Luhn Algorithm**.
*   **Order History:** Customers can track their past orders and monitor live shipping statuses (Pending, Shipped, Delivered).
*   **Profile Management:** Customers can easily update their shipping address and contact details.
*   **Product Reviews:** Logged-in users can leave 1-5 star ratings and written reviews for products they love.

### Administrator Features
*   **Admin Dashboard:** A centralized hub for store managers.
*   **Product Management:** Add new products, edit existing listings, or delete items securely. Features strict form validations to prevent negative pricing and stock.
*   **Order Management:** A state-machine tracking system to process orders. Admins can progress orders from `Pending` -> `Shipped` -> `Delivered` or `Cancelled`.
*   **User Management:** View registered users and their account types.
*   **Admin Security:** Strict access controls prevent Administrators from accidentally making purchases or accessing customer checkout pipelines.

## 🛠️ Tech Stack
*   **Frontend:** HTML5, CSS3
*   **Backend:** PHP (Session Management, Prepared Statements)
*   **Database:** MySQL

## ⚙️ Installation & Setup

1.  **Clone the Repository:**
    Place the project files into your local server's web directory (e.g., `C:\xampp\htdocs\VelvetVogue` or `C:\xampp\htdocs\hometeq`).

2.  **Database Configuration:**
    *   Open phpMyAdmin and create a new database.
    *   Import the SQL tables for `Users`, `Product`, `Orders`, `Order_Line`, and `Reviews`.
    *   Ensure your database connection settings in `db.php` match your local setup (default: `localhost`, `root`, no password).

3.  **Run the Application:**
    *   Start the Apache and MySQL modules in your XAMPP/WAMP control panel.
    *   Navigate to `http://localhost/hometeq/index.php` in your web browser.

## 🔑 Admin Access
By default, the system requires you to manually assign an administrator. To do this, open your database, locate a user in the `Users` table, and change their `userType` from `C` (Customer) to `A` (Admin).

*For demonstration purposes, a default admin account may be set up as:*
*   **Email:** admin@hometeq.com
*   **Password:** admin123
