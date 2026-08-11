# Employee Attendance Management System

## Project Description

The Employee Attendance Management System is a web-based application developed to manage employee records and daily attendance.

The system allows an administrator to register employees, manage employee information, record employee check-in and check-out times, manage work schedules, and generate attendance reports.

## Features

- Admin login and logout
- Employee registration
- View employee records
- Edit employee information
- Delete employee records
- Employee check-in
- Employee check-out
- Daily attendance status
- Work schedule management
- Attendance reports
- Dashboard with attendance summary
- Present and absent employee counts

## Technologies Used

- HTML
- CSS
- PHP
- MySQL / MariaDB
- XAMPP
- phpMyAdmin
- Visual Studio Code

## System Modules

### 1. Dashboard

The dashboard provides a summary of the attendance system, including:

- Total employees
- Employees present today
- Employees absent today

### 2. Employee Management

The employee management module allows the administrator to:

- Add employees
- View employees
- Edit employee information
- Delete employees

### 3. Attendance Management

The attendance module allows the administrator to:

- Check employees in
- Check employees out
- View daily attendance status
- Record check-in and check-out times

### 4. Work Schedule

The work schedule module allows the administrator to set:

- Check-in time
- Check-out time

### 5. Attendance Reports

The attendance report module allows the administrator to select a date and view attendance records for that date.

## Database

The system uses a MySQL/MariaDB database named:

`attendance_system`

The database contains the tables required to manage employees, attendance records, and work schedules.

## How to Run the Project

1. Install XAMPP.

2. Start **Apache** and **MySQL** from the XAMPP Control Panel.

3. Copy the project folder into:

   `C:\xampp\htdocs\`

4. Import the `attendance_system` database using phpMyAdmin.

5. Make sure the database connection settings in `includes/connection.php` match your local XAMPP configuration.

6. Open a web browser.

7. Visit:

   `http://localhost/employee-attendance-system/login.php`

8. Log in using the administrator account.

## Project Structure

```text
employee-attendance-system/
│
├── admin/
│   ├── add_employee.php
│   ├── attendance.php
│   ├── attendance_report.php
│   ├── check_in.php
│   ├── check_out.php
│   ├── dashboard.php
│   ├── delete_employee.php
│   ├── edit_employee.php
│   ├── view_employees.php
│   └── work_schedule.php
│
├── css/
│   └── style.css
│
├── includes/
│   ├── connection.php
│   └── sidebar.php
│
├── login.php
├── logout.php
├── .gitignore
└── README.md

## Author

**Faith Olesi — Front-End Developer**

## Project Status

Completed and available on GitHub.