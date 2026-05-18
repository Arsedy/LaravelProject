# School Homework Project

This is a web application project built for school homework, utilizing the powerful PHP framework Laravel.

## 🚀 Tech Stack

- **Backend:** PHP 8.x, Laravel
- **Frontend:** HTML, CSS, JavaScript (TailwindCSS / Vite)
- **Database:** SQLite / MySQL / PostgreSQL (Configure in `.env`)
- **Testing:** Pest / PHPUnit

## 📋 Prerequisites

Before you begin, ensure you have the following installed on your machine:
- [PHP](https://www.php.net/downloads) (v8.2 or higher recommended)
- [Composer](https://getcomposer.org/download/)
- [Node.js & npm](https://nodejs.org/) (for frontend asset compilation)
- Database server (MySQL, PostgreSQL, or you can use the default SQLite)

## 🛠️ Installation & Setup

Follow these steps to get the project running on your local machine:

1. **Clone the repository** (if you haven't already):
   ```bash
   git clone <repository-url>
   cd LaravelProject
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install frontend dependencies:**
   ```bash
   npm install
   ```

4. **Environment Configuration:**
   Copy the example environment file and configure it if necessary (the defaults usually work fine for local development with SQLite).
   ```bash
   cp .env.example .env
   ```

5. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

6. **Run Migrations:**
   Create the necessary database tables. If you are using SQLite, this will create the database file for you.
   ```bash
   php artisan migrate
   ```

7. **(Optional) Seed the Database:**
   If you want to populate the database with initial/dummy data:
   ```bash
   php artisan db:seed
   ```

## 💻 Running the Application

To run the application locally, you need to start both the Laravel development server and the Vite development server (for compiling frontend assets).

1. **Start the Laravel development server:**
   ```bash
   php artisan serve
   ```
   The application will be accessible at `http://localhost:8000`.

2. **Start the Vite development server** (in a new terminal tab/window):
   ```bash
   npm run dev
   ```

## 🧪 Testing

To run the test suite and ensure everything is working correctly, you can use Pest (or PHPUnit):

```bash
php artisan test
```

## 📖 About Laravel & PHP

**PHP** is a popular general-purpose scripting language that is especially suited to web development.
**Laravel** is a web application framework with expressive, elegant syntax built on top of PHP. It provides a structure and starting point for creating your application, allowing you to focus on creating something amazing while it sweats the details.

Laravel takes the pain out of development by easing common tasks used in many web projects, such as:
- Simple, fast routing.
- Powerful dependency injection container.
- Expressive, intuitive database ORM (Eloquent).
- Database agnostic schema migrations.
- Robust background job processing.

## 🎓 Academic Information

This project is submitted as part of a school assignment.
