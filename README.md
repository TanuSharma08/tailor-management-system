<div align="center">

<img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/php/php-original.svg" width="70" />
<img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/mysql/mysql-original.svg" width="70" />
<img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/html5/html5-original.svg" width="60" />
<img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/css3/css3-original.svg" width="60" />
<img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/javascript/javascript-original.svg" width="60" />

# ✂️ Tailor Management System

### A Web-Based Tailor Shop Management Solution

![PHP](https://img.shields.io/badge/PHP-8.0-blue)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-yellow)
![Status](https://img.shields.io/badge/Status-Completed-green)

</div>

---

# 📋 About The Project

The **Tailor Management System** is a web-based application developed to simplify customer measurement and order management for tailor shops.

The project replaces traditional paper-based tracking with a digital system that helps manage customer details, measurements, delivery records, and order history more efficiently.

It was developed as a freelance project focused on improving workflow, organization, and accessibility for daily tailor shop operations.

---

# ✨ Features

- Customer measurement management
- Multiple cloth type support
- Dynamic measurement fields
- Bilingual interface support
- Receive & delivery date tracking
- Search and filtering functionality
- WhatsApp sharing integration
- Print functionality
- Record editing & deletion
- Real-time table filtering
- Order history management

---

# 🌍 Bilingual Support

The system includes bilingual labels and content support for improved accessibility and local usability.

### Supported Languages

- English
- Gujarati

Example:
- Kurti / કુર્તિ
- Shirt / શર્ટ

---

# 🛠️ Tech Stack

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript (ES6)
- Flatpickr
- XAMPP / WAMP

---

# 📂 Project Structure

```text
tailor-management-system/
│
├── index.php
├── rec.php
├── delete.php
├── update.php
├── getPhone.php
├── header.php
├── footer.php
│
├── style.css
├── rec.css
│
├── flatpickr.min.css
├── flatpickr.min.js
│
├── measurements.sql
├── favicon.ico
├── header-logo.jpg
├── dashboard-img.png
│
└── README.md
```

---

# 🚀 Getting Started

## Clone the Repository

```bash
git clone https://github.com/TanuSharma08/tailor-management-system.git
```

```bash
cd tailor-management-system
```

---

# ⚙️ Setup Instructions

## 1️⃣ Move Project to Server Directory

### XAMPP

```text
C:\xampp\htdocs\
```

### WAMP

```text
C:\wamp\www\
```

---

## 2️⃣ Import Database

- Open `phpMyAdmin`
- Create a database named:

```text
tailor_db
```

- Import:

```text
tailor_db_schema.sql
```

---

## 3️⃣ Configure Database Connection

Update database credentials if needed:

```php
$conn = new mysqli("localhost", "root", "", "tailor_db");
```

---

## 4️⃣ Run the Application

Open in browser:

```text
http://localhost/tailor-management-system/index.php
```

---

# 📱 Key Functionalities

### 📌 Customer Management

- Add customer records
- Auto-suggest existing customers
- Save multiple measurement types
- Store remarks and pricing

### 📌 Records Management

- Search by customer name
- Filter records by cloth type
- Date range filtering
- Sort latest and oldest records
- Pagination support

### 📌 Actions

- Edit records
- Delete records
- Print records
- Share details through WhatsApp
- Clear filters instantly

---

# 🎯 Project Highlights

- Built as a real-world freelance project
- Focused on practical business workflow
- Improved record management digitally
- Combined frontend and backend integration
- Implemented bilingual usability support
- Designed for organized measurement tracking

---

# 🤝 Contributing

Suggestions and improvements are always welcome.

```bash
git checkout -b suggestion/topic-name
```

```bash
git commit -m "Add: suggestion"
```

---

# 📄 License

Distributed under the MIT License. See `LICENSE` for more information.

---

<div align="center">

Made with ❤️ by **Tanu Sharma**

</div>
=======
# tailor-management-system
 comprehensive web-based management system for tailor shops.

## Features
- Customer measurement entry
- Records management with filters
- WhatsApp sharing
- Print & backup functionality

## Tech Stack
- PHP, MySQL, JavaScript
- flatpickr for date picking

## Installation
1. Import tailor_db_schema.sql to MySQL
2. Configure database connection
3. Run on XAMPP/WAMP

## Developer
Tanu Sharma

