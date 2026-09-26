# NURA Authentication System

A simple full-stack authentication and profile management system built using PHP, MySQL, MongoDB, Redis, jQuery, AJAX and Bootstrap.

## Features

- User registration
- User login
- Password hashing
- Email and username validation
- Redis-based login sessions
- Protected profile page
- Profile information stored in MongoDB
- Account information stored in MySQL
- AJAX form submission without page reload
- Logout and session removal
- Responsive Bootstrap UI

## Technologies Used

- PHP
- MySQL
- MongoDB
- Redis
- jQuery
- AJAX
- Bootstrap
- HTML
- CSS
- JavaScript

## Project Structure

```text
nura-auth/
│
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       ├── login.js
│       ├── register.js
│       └── profile.js
│
├── php/
│   ├── config.php
│   ├── register.php
│   ├── login.php
│   ├── profile.php
│   └── logout.php
│
├── index.html
├── login.html
├── register.html
├── profile.html
├── .env
├── .gitignore
├── composer.json
└── README.md