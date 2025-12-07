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
**Insecure Version**
- The login and registration queries were built using string concatenation, which allowed attackers to inject SQL code.
- 
```php
' OR '1'='1' #
```
This logs the user in without knowing any password.

**Secure Fix**
-We fixed the SQL Injection vulnerability by using prepared statements, which safely separate user input from the SQL query structure.

```php
$stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE name = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
```


### 2. Weak Password Storage

**Insecure Version**
- Passwords were stored in plain text inside the database.
- This allows attackers to read all passwords if the database is compromised.

**Secure Fix**
Use hashing + verification:

```php
$hashed = password_hash($password, PASSWORD_DEFAULT);

if (password_verify($password, $user['password'])) {
    
}

```

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



### 5. Encryption 
//

---

