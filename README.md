# Aspiring Entrepreneurs Portal - Pakistan

Aspiring-Entrepreneur-Noreen is an entrepreneur portal with business resources and community features.

![PHP](https://img.shields.io/badge/PHP-2563EB?style=flat-square) ![MySQL](https://img.shields.io/badge/MySQL-0F172A?style=flat-square)

## Overview

The data model covers `admin`, `agents`, `business_ideas`, `business_templates`, `categories`, `events`, `feedback`, `forum_replies`, `forum_topics`, `mentorship_requests`, `messages`, `opportunities`, and related records.

## Application areas

| Area | Pages in the repository |
| --- | --- |
| Admin | [`admin/add_category.php`](admin/add_category.php), [`admin/add_event.php`](admin/add_event.php), [`admin/add_tutorial.php`](admin/add_tutorial.php), [`admin/admin_dashboard.php`](admin/admin_dashboard.php), [`admin/edit_category.php`](admin/edit_category.php) |
| Root Pages | [`adminlogin.php`](adminlogin.php), [`agent_register.php`](agent_register.php), [`dashboard.php`](dashboard.php), [`forum.php`](forum.php), [`login.php`](login.php) |
| Agent | [`agent/add_idea.php`](agent/add_idea.php), [`agent/add_opportunity.php`](agent/add_opportunity.php), [`agent/add_template.php`](agent/add_template.php), [`agent/dashboard.php`](agent/dashboard.php), [`agent/edit_idea.php`](agent/edit_idea.php) |

## Getting started

### 1. Get the project

```bash
git clone https://github.com/nasirabbas827/Aspiring-Entrepreneur-Noreen.git
cd Aspiring-Entrepreneur-Noreen
```

### 2. Configure PHP and the database

1. Use a local PHP server with MySQL or MariaDB, such as XAMPP.
2. Import [`Database/interactive-noreen-db.sql`](Database/interactive-noreen-db.sql) into your local database server.
3. Set your local database connection details in [`admin/config.php`](admin/config.php), [`agent/config.php`](agent/config.php), [`config.php`](config.php).
4. Keep the existing asset and upload directory paths intact.

### 3. Start the application

With the database running, start a local development server from the repository root:

```bash
php -S 127.0.0.1:8000
```

Open http://127.0.0.1:8000/index.php. If using Apache/XAMPP instead, place the project in its document root and open the matching localhost folder.

## Repository guide

| Path | Purpose |
| --- | --- |
| [`CSS`](CSS) | Stylesheets |
| [`Database`](Database) | Database scripts |
| [`config.php`](config.php) | Application/database configuration |
| [`images`](images) | Image assets |
| [`index.php`](index.php) | Web entry page |
| [`uploads`](uploads) | Upload storage |

## Development notes

These setup instructions are based on the repository source. The application has not been executed as part of this documentation update; verify dependencies and configuration in your local environment.
