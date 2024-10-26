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
cp .env.example .env
edit .env file database name and credential
composer install
php artisan key:generate
php artisan serve



Set Up Cron Job for Laravel Console
```bash
crontab -e
Add the following line to your crontab file to call the Laravel scheduler every minute:
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1

