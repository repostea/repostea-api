# Repostea API – Public Core

Welcome to the core of the Repostea platform – a modern social link-sharing backend built with Laravel 12.

---

## 🚀 What is this?

This is a **complete Laravel 12 API** that powers the backend of the Repostea platform.  
It includes user registration, link submission, voting, commenting, tag management, multi-tenancy support, and more.

---

## 🔧 Technologies

- Laravel 12
- MySQL or SQLite
- Laravel Sanctum (auth)
- RESTful API structure
- Multi-tenant architecture

The frontend (Nuxt 3) has been decoupled and lives in a separate repo:  
🔗 [https://github.com/repostea/repostea-client](https://github.com/repostea/repostea-client)

---

## 📦 Getting Started

```bash
git clone https://github.com/repostea/repostea-api.git
cd repostea-api
composer install
cp .env.example .env
php artisan key:generate
```

Configure your `.env` file and run:

```bash
php artisan migrate --seed
```

### 🔄 Quick local development

To start the API server, queues, logs and frontend in parallel, use:

```bash
composer run dev
```

This will launch:

- `php artisan serve` (API server)
- `php artisan queue:listen` (queue worker)
- `php artisan pail` (log tailing)
- `npm run dev` (Vite/Frontend)

#### Windows Users

On Windows, you might encounter issues with `php artisan pail` as it requires the PHP `pcntl` extension which is not available natively on Windows. You have two options:

1. **Recommended: Use WSL (Windows Subsystem for Linux)**  
   WSL provides a Linux environment on Windows which supports the necessary extensions.  
   [Learn how to install WSL](https://learn.microsoft.com/en-us/windows/wsl/install)

2. **Alternative: Run components separately**  
   Instead of using `composer run dev`, you can run the components in separate terminals:

   ```bash
   # Terminal 1
   php artisan serve
   
   # Terminal 2
   php artisan queue:listen --tries=1
   
   # Terminal 3
   npm run dev
   ```

---

## 🔍 Code Quality Tools

Before committing code, you can run the following tools to check formatting and static analysis:

```bash
# Fix formatting
./vendor/bin/pint

# Run static analysis
vendor/bin/phpstan analyse
```

You can also combine both with:

```bash
composer quality
```

---

## 🏢 Multi-Tenant Architecture

Repostea implements a multi-tenant architecture, which means:

- A single installation can host multiple independent instances of the platform
- Each tenant has its own isolated data (users, links, comments, etc.)
- Tenants are identified by their UUID
- API requests require tenant authentication via API keys
- Data is automatically scoped to the current tenant

This architecture is ideal for:
- Running multiple communities with a single codebase
- White-labeling the platform for different clients
- Separating development, testing, and production environments

To create a new tenant, use:

```bash
php artisan tenant:create "My Tenant Name"
```

This will generate a UUID and API key for the new tenant.

---

## Live Demo

Check out the live version of Repostea at [repostea.com](https://repostea.com)

---

## Development Roadmap

You can view our planned features and current development status on our [GitHub Projects board](https://github.com/orgs/repostea/projects/2)

---

## 📚 Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for how to contribute and how to keep your private changes safe.

---

## 📄 License

This project is licensed under the **MIT License with Author Notification Clause**.  
See [LICENSE.md](LICENSE.md) for full terms.
