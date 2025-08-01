🍽️ Smart Restaurant Management System
A fully responsive web-based restaurant management system designed to streamline dine-in and online ordering experiences for customers, waiters, chefs, and admin. Built with PHP, HTML, CSS, JavaScript, and MySQL.

🚀 Key Features

🧑‍🍳 Customer Features

🛒 Online food ordering (home or dine-in)

📱 Table QR Scan to order

⏳ Countdown Timer: Estimated serve time display

🌐 Multi-language Support: English, বাংলা, Italiano, हिन्दी

💳 Pay Now or Pay on Serve options


🧾 Admin Panel
📊 Sales Reports (Daily/Monthly)

🧾 Invoice Download (PDF)

🍔 Food & Category Management

📅 Table-wise Order History


👤 User & Role Management

👨‍🍳 Kitchen Dashboard

🕐 Real-time order status & preparation timer

🧑‍🍳 Chef can set serve time that syncs to customers

✅ Order complete notification


📦 Tech Stack
Frontend	Backend	Database	Tools
HTML5, CSS3	PHP	MySQL	Bootstrap
JavaScript	AJAX		Font Awesome
QR Code Library	Session/Auth		Google Translate API


🗃️ Database Structure
Tables:

users (admin, customer, waiter, chef)
orders
tables
menu_items
categories
invoices
order_status

📝 SQL script included in database/restaurant_db.sql

📸 UI Preview

🌐 Multi-Language Support
Users can toggle between:

🇬🇧 English

🇧🇩 বাংলা

🇮🇹 Italiano

🇮🇳 हिन्दी

Implemented via Google Translate script + custom static translations.

📁 Folder Structure
pgsql
Copy
Edit
Project-1-Smart_-Restureant_Management/
│
├── index.html
├── style.css
├── app.js
├── server.php
├── admin/
├── customer/
├── kitchen/
├── assets/
│   ├── images/
│   ├── css/
│   └── js/
├── database/
│   └── restaurant_db.sql
├── README.md
└── LICENSE
🔧 Setup Instructions
Clone the repo:
git clone https://github.com/yourusername/Project-1-Smart_-Restureant_Management.git

Import SQL file to your local phpMyAdmin.

Configure DB credentials in server.php or config.php.

Run on localhost (XAMPP/LAMP) or deploy online.

📄 License
This project is open-source under the MIT License.

✉️ For queries or contributions, contact: biswasashikuzzaman@gmail.com
or
                                           https://www.linkedin.com/in/ashikuzzaman-biswas/
