# Smart Event Reservation System

A web-based event reservation system built with PHP, Apache, and MySQL. Users can browse venues, reserve event slots, manage bookings, and admins can track usage.

## Prerequisites

- **Apache** (version 2.4 or higher)
- **PHP** (version 7.4 or higher)
- **MySQL** (version 5.7 or higher) or **MariaDB**
- **PDO MySQL extension** enabled in PHP

## Installation Steps

### 1. Clone or Download the Project

Place the project in your Apache `htdocs` directory:
```
C:\Apache24\htdocs\Smart-Event-Reservation
```

### 2. Configure Apache

Ensure Apache is installed and running. The project should be accessible at:
```
http://localhost/Smart-Event-Reservation/public/
```

If you want a cleaner URL, configure a virtual host in your Apache `httpd.conf` or `httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    ServerName smartevent.local
    DocumentRoot "C:/Apache24/htdocs/Smart-Event-Reservation/public"
    <Directory "C:/Apache24/htdocs/Smart-Event-Reservation/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Don't forget to add to your `hosts` file (`C:\Windows\System32\drivers\etc\hosts`):
```
127.0.0.1 smartevent.local
```

### 3. Create the MySQL Database

Connect to MySQL using a client (MySQL Workbench, phpMyAdmin, or command line):

```bash
mysql -u root -p
```

Create the database:

```sql
CREATE DATABASE smart_event_reservation_s CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE smart_event_reservation_s;
```

### 4. Import the Database

The project includes a complete SQL dump file. Import it using one of these methods:

**Method 1: Using MySQL Command Line**
```bash
mysql -u root -p < sql/smart_event_reservation_s.sql
```

**Method 2: Using phpMyAdmin**
1. Open phpMyAdmin in your browser
2. Create a new database called `smart_event_reservation_s`
3. Select the database
4. Click "Import" tab
5. Choose the file `sql/smart_event_reservation_s.sql`
6. Click "Go"

**Method 3: Using MySQL Workbench**
1. Open MySQL Workbench
2. Connect to your MySQL server
3. Go to Server > Data Import
4. Select "Import from Self-Contained File"
5. Browse to `sql/smart_event_reservation_s.sql`
6. Click "Start Import"

The SQL file includes:
- Complete database schema with all tables
- Sample venues (Grand Ballroom, Garden Terrace, Executive Hall, Sunset Lounge)
- Sample users with test data
- Foreign key constraints and indexes
- Database trigger to prevent overlapping reservations

### 5. Configure Database Connection

Edit the database configuration in `config/Database.php`:

```php
private $host = '127.0.0.1';
private $db_name = 'smart_event_reservation_s';
private $username = 'root';
private $password = 'student';  // Change this to your MySQL password
```

Update the credentials to match your MySQL setup.

### 6. Start Using the Application

Navigate to:
```
http://localhost/Smart-Event-Reservation/public/index.php
```

## Project Structure

```
Smart-Event-Reservation/
├── classes/
│   ├── Auth.php          # Authentication logic
│   ├── User.php          # User management
│   ├── Venue.php         # Venue management
│   └── Reservation.php   # Reservation management
├── config/
│   ├── Database.php      # Database connection
│   └── email.php         # Email configuration
├── public/
│   ├── index.php         # Homepage
│   ├── login.php         # Login page
│   ├── register.php      # Registration page
│   ├── dashboard.php     # User dashboard
│   ├── venues.php        # Browse venues
│   ├── reserve.php       # Make reservation
│   ├── users.php         # View all users
│   └── logout.php        # Logout
├── sql/
│   └── smart_event_reservation_s.sql  # Complete database dump
└── README.md
```

## Features

- User registration and login
- Email verification
- Browse available venues
- Make venue reservations
- View reservation history
- Admin role support
- View all registered users

## Troubleshooting

### Database Connection Error
- Verify MySQL is running
- Check database credentials in `config/Database.php`
- Ensure the database exists

### Apache Not Finding Files
- Check that the project is in the correct `htdocs` directory
- Verify Apache is running
- Check file permissions

### PHP PDO Extension Missing
Enable PDO in `php.ini`:
```ini
extension=pdo_mysql
```

Restart Apache after making changes.

## License

This project is for educational purposes.
