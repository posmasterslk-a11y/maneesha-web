# Maneesha Fashion - Project Knowledge & Rules

This document outlines the core architecture, rules, server details, and deployment processes for the Maneesha Fashion web project.

## 1. Project Architecture
The project is decoupled into three main repositories/folders:
- **Backend (`/backend`)**: A Laravel-based REST API. Handles all business logic, database operations, and PayHere payment integrations.
- **Frontend Admin (`/frontend-admin`)**: A Nuxt 4 (Vue 3) application for the business control panel.
- **Frontend Customer (`/frontend-customer`)**: A Nuxt 4 (Vue 3) application for the customer-facing online store.

## 2. Server & API Endpoints
- **Production API Server**: `https://api-maneesha.posmasters.lk/api` (The frontends default to this URL if `NUXT_PUBLIC_API_BASE` is not set).
- **Local API Server**: `http://localhost:8000` (When running `php artisan serve`).
- **Local Frontends**: Typically run on `localhost:3000` or `localhost:3003`/`3004` (using `npm run dev`).

## 3. Database Configurations
- **Current Local Setup**: The backend `.env` is currently pointing to a `sqlite` connection.
- **MySQL Setup**: There are scripts provided (`reset_mysql.ps1`) pointing to a MySQL 8.0 installation. The root credentials for local development are:
  - **Username**: `root`
  - **Password**: `ManeeshaDB@2024`
  
## 4. Key Integrations
- **Payments**: PayHere integration is configured with Merchant ID `1236053`.
- **Emails**: Uses SMTP via Gmail (`manishafashions.lk@gmail.com`).

## 5. Deployment Process
- **Backend Deployment**: Handled manually. The backend is uploaded to cPanel (e.g., using `api-cpanel.zip`).
- **Frontend Deployment**: Automated via Vercel. Both `frontend-admin` and `frontend-customer` are auto-deployed when changes are pushed.

## 6. Core Rules for Development
1. **API Communications**: Both frontends MUST use the Nuxt runtime config (`useRuntimeConfig().public.apiBase`) for all backend API calls. Avoid hardcoding URLs.
2. **Styling**: Both frontends utilize Tailwind CSS or plain CSS. The admin panel heavily relies on `@nuxt/ui`.
3. **Database Migrations**: Any changes to the database structure must be done through Laravel Migrations in the `backend` folder.
4. **Environment Variables**: Local `.env` variables should never be committed. Ensure that changes in `.env` requirements are documented.
