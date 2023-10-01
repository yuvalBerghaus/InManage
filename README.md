# IM

```markdown
# User Data Management System

## Introduction

The User Data Management System is a PHP-based application designed to manage user data and posts. This system provides features for creating, updating, and retrieving user records and posts, as well as performing database operations efficiently.

## Features

- **User Management:**
  - Create new user records.
  - Retrieve user data including username, email, and active status.
  - Initialize the Auto Increment value for the users table.

- **Post Management:**
  - Create new posts.
  - Retrieve posts data, including user information (Join operation).
  - Retrieve the latest user post of the current month.
  - Create a new table to store posts per hour data.

## Prerequisites

Before using this application, ensure you have the following in place:

- PHP (>= 7.0)
- MySQL Database
- Apache or another web server
- Configuration settings (see `config.php`)

## Installation

1. Clone this repository to your local environment:
   ```bash
   git clone https://github.com/your-username/user-data-management.git
   ```

2. Configure the database settings:
   - Open `includes/config.php` and update the database host, username, password, and database name according to your MySQL setup.

3. Initialize the database:
   - Ensure your MySQL database is set up with the correct configuration.
   - Run the tasks in index.php
     ```

4. Start the application:
   - Launch a web server (e.g., Apache) and ensure PHP is enabled.
   - Navigate to the project directory in your web server's document root.

## Usage
1. **In order to run the tasks run Task_3() Task_4() .. Task_7() in index.php in a chronological order**
   - Run Task_3 in order to create users and posts tables and insert data from jsonPlaceHolder api
   - Run Task_4 in order to save image from the specified url to the server.
   - Run Task_5 in order to display all the posts of the users
   - Run Task_6 in order to display the last post of the user that was born in the current month
   - Run Task_7 in order to create table posts_per_hour
  
  
![inmanage](https://github.com/yuvalBerghaus/IM/assets/65304080/3449335e-74da-4c5e-b10b-4c5f32e2c58d)
