# Simple Task Manager in Laravel

A simple task manager application built with Laravel. This application allows users to create, update, delete, and manage their tasks effectively.

## Features

- User authentication
- Create, read, update, and delete tasks
- Soft delete for tasks
- Status Update 'todo,'in-progress','Done'
- File uploads for tasks
- Create Subtasks
- Restore deleted tasks
- auto delete tasks

## Requirements

- PHP >= 8.x
- Laravel >= 10.x
- MySQL or another supported database

## Installation

Follow these steps to set up the application on your local machine.

### 1. Clone the Repository

```bash
git clone https://github.com/ryanroydev/simple-task-manager.git
cd simple-task-manager
```

### 2. Create the .env File

Copy the example environment file and configure your database settings:
```bash
cp .env.example .env
```

Edit the .env file to set your database name and credentials:

```bash
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 3. Install Dependencies
```bash
composer install
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. run laravel with port 8000 in local
```bash
php artisan serve
```



## Set Up Cron Job for Laravel Console in ubuntu linux

### 1. Open crontab file
```bash
crontab -e
```
### 2. Add the following line to your crontab file to call the Laravel scheduler every minute:

```bash
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```

### 3. Restart Cron:

```bash
sudo service cron reload
```