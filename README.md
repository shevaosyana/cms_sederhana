# CMS Sederhana

A simple Content Management System built with PHP and AdminLTE.

## Features

- User Authentication
- Post Management
- User Management
- Responsive Admin Dashboard
- Modern UI with AdminLTE

## Requirements

- PHP >= 7.4
- MySQL >= 5.7
- Composer
- Web Server (Apache/Nginx)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/cms_sederhana.git
cd cms_sederhana
```

2. Install dependencies:
```bash
composer install
```

3. Create a copy of the environment file:
```bash
cp .env.example .env
```

4. Configure your database settings in the `.env` file:
```bash
DB_HOST=localhost
DB_NAME=cms_sederhana
DB_USER=your_username
DB_PASS=your_password
```

5. Create the database:
```sql
CREATE DATABASE cms_sederhana;
```

6. Configure your web server to point to the project's root directory.

7. Access the application through your web browser:
```
http://localhost/cms_sederhana
```

## Default Login

- Email: admin@example.com
- Password: admin123

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).