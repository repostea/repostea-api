# 🤝 Contributing to Repostea API

Thank you for your interest in contributing to **Repostea**!  
We welcome community involvement and appreciate your help in improving our API and ecosystem.

---

## 🗂️ Project Overview

This repository contains the core of the **Repostea API**, which powers our news aggregation and fediverse integration platform.

- ✅ Built with **Laravel 12**
- ✅ Follows **RESTful** principles
- ✅ Separated backend/frontend (see [`repostea-client`](https://github.com/repostea/repostea-client))
- ✅ Uses custom `Request` / `Response` objects per resource (no generic controllers)

---

## 🧠 Before You Start

1. **Check the Kanban board**  
   We organize work using a public GitHub project board:  
   👉 [Repostea Kanban](https://github.com/orgs/repostea/projects/2)

2. **Look for existing issues or discussions**  
   Your idea might already be in progress or under review.

3. **Follow our conventions**  
   We're strict with code quality:
    - Larastan Level 9
    - SOLID principles
    - PSR-12 formatting
    - Tests are required for any business logic

4. **Translations**  
   All user-facing messages must support **English** and **Spanish** via Laravel's native i18n system.

---

## 🚀 Getting Started

```bash
git clone git@github.com:repostea/repostea-api.git
cd repostea-api
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
```

> Requires PHP 8.3+, Laravel 12, and a local database configured in `.env`.

---

## 🧪 Running Tests

We use Pest for testing.

```bash
./vendor/bin/pest
```

To check static analysis:

```bash
./vendor/bin/phpstan analyse
```

---

## 🔀 Pull Request Guidelines

- Use **feature branches**
- Write meaningful **commit messages**
- Include **tests** for new features or bug fixes
- Make sure your PR passes **CI** and static analysis

---

## 📦 Composer Package? Maybe Later.

This project is **not yet a Composer package**.  
We're sticking with full Laravel for clarity and velocity during early development.

If the community shows interest, we'll modularize it accordingly.

---

## 🫶 Code of Conduct

We follow the [Contributor Covenant](https://www.contributor-covenant.org/).  
Please be respectful and constructive in all interactions.

---

Thanks again for contributing to Repostea!  
Together we’re building something useful, open, and connected to the fediverse. 🚀
