# KabsuAtts Attendance System

A web-based attendance management system built with PHP that allows administrators to manage courses, subjects, departments, and track student attendance.

## Features

- Course Management (Add, Edit, Delete)
- Subject Management (Add, Edit, Delete)
- Department Management (Add, Edit, Delete)
- Student Tracking
- Instructor Assignment
- Interactive Dashboard

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- XAMPP (recommended for local development)

## Installation

1. Clone the repository:
```bash
git clone [repository-url]
```

2. Move the project to your web server directory (e.g., htdocs for XAMPP):
```bash
mv KabsuAtts /path/to/xampp/htdocs/
```

3. Import the database schema (provided in `database/schema.sql`)

4. Configure your database connection:
   - Copy `config.example.php` to `config.php`
   - Update the database credentials in `config.php`

5. Access the application through your web browser:
```
http://localhost/KabsuAtts
```

## Directory Structure

```
KabsuAtts/
├── Admin/              # Admin panel files
├── Includes/           # Common includes and utilities
├── css/               # Stylesheets
├── javascript/        # JavaScript files
├── database/          # Database schema and migrations
└── README.md          # This file
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Support

For support, please open an issue in the repository or contact the development team. 