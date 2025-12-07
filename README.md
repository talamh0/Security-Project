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

### 1. SQL Injection  

- Open the login page in the insecure version.

- In the username field, enter:

```php
' OR '1'='1' #
```
This allows the user to log in.


### 2. Weak Password Storage

Passwords in the insecure version were stored using MD5, which is weak and easily cracked.
```html
$md5_pass = md5($password);
```
1- Register a new user.

2- Open phpMyAdmin → users table.

3- Observe that the stored password is an MD5 hash (e.g., 5f4dcc3b5aa765d61d8327deb882cf99).

4- Use an online MD5 cracker to verify that the password can be recovered easily.

### 3. Cross-Site Scripting (XSS)

- When registering, try using this username:

```html
<script>alert('xss')</script>
```

Then open the Dashboard and observe.

- Try adding a comment with the following text:
```html
  <img src=x onerror=alert('xss')>
```

### 4. Access Control  
- In the insecure version , try accessing admin pages using a normal user:
  
First , log in as a regular user.

Then manually open this URL in the browser:

  `http://localhost/Admin/manageEvents.php`


### 5. Encryption & Secure Session

all in `security_config.php`

- HTTPS Redirection
http://localhost/... → redirects to https://...
Triggered by: `if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off")`

- Secure Session Cookies
Cookie flags must be: `'Secure=true'', ''HttpOnly=true'', ''SameSite=Strict'`
Set via: `session_set_cookie_params([...])`

- Session Start
All protected pages must run: `session_start()`






---

