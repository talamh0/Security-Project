# Web Security 

## Overview

### Two Versions of the Website
The project contains **two versions** of the application:
1. **Insecure site** – intentionally left without protection to demonstrate common security weaknesses.
2. **Secure site** – includes fixes and protections against:
   - SQL Injection  
   - Weak Password Storage  
   - Cross-Site Scripting (XSS) 
   - Access Control
   - Encryption

---

## How to Run the Application

1. **Install a local server environment**
   - Windows → XAMPP  
   - macOS → MAMP or XAMPP  
   Make sure **Apache** and **MySQL** are running.

2. **Import the database**
   - Open `phpMyAdmin`
   - Create a new database named event_booking
   - Import the provided `.sql` file

3. **Configure the database connection**
   - Open `config.php` inside each version’s folder (secure / vulnerable)
   - Update database credentials according to your setup:
     ```
     php
     $db_user = "YOUR_USERNAME";    // XAMPP default: root
     $db_pass = "YOUR_PASSWORD";    // XAMPP default: empty
     ```

4. **Place the project in your server directory**
   - XAMPP/MAMP  `htdocs` folder

5. **Run the website**
   - **User page:**  
     `http://localhost/Security-Project/Secure%20Site/User/register.php`
   - **Admin page:**  
     `http://localhost/Security-Project/Secure%20Site/admin/admin.php`
   - Make sure to test **both versions**:
     - `Security-Project/Secure%20Site`
     - `Security-Project/insecure%20Site`

---

## Security Testing Instructions
//

### 1. SQL Injection  
//

### 2. Weak Password Storage  
//

### 3. Cross-Site Scripting (XSS)  
//

### 4. Access Control  
//

### 5. Encryption 
//

---

