# 🚀 Laravel + Vue3 + Tailwind + Pusher --- Realtime Trading Engine

## 📌 Overview

This project is a **full-stack realtime trading engine** built using:

-   **Laravel 10 (Backend API + Matching Engine)**
-   **Vue 3 + Vite (Frontend UI)**
-   **TailwindCSS**
-   **Sanctum (Token Auth)**
-   **Pusher + Laravel Echo (Realtime Events)**
-   **MySQL**
-   **Queues (Order Matching + Broadcasting)**

Both backend and frontend work together to process trades, broadcast
live updates, update user balances, and allow order cancellation.

------------------------------------------------------------------------

# 🛠 Tech Stack

## Backend

-   Laravel 12\
-   Sanctum Token Authentication\
-   Pusher Broadcasting\
-   Event Queueing\
-   MySQL\
-   PHP 8.2

## Frontend

-   Vue 3\
-   Pinia\
-   Vite\
-   TailwindCSS\
-   Axios\
-   Pusher JS\
-   Laravel Echo

------------------------------------------------------------------------

# 📦 Backend (Laravel)

## Installation

``` bash
cd limit-order-exchange-mini-engine
composer install
cp .env.example .env
php artisan key:generate
```

### Configure `.env`

    DB_DATABASE=mini-exchange-engine
    DB_USERNAME=root
    DB_PASSWORD=

    BROADCAST_DRIVER=pusher
    PUSHER_APP_ID=xxxx
    PUSHER_APP_KEY=xxxx
    PUSHER_APP_SECRET=xxxx
    PUSHER_APP_CLUSTER=ap2
    PUSHER_PORT=443
    PUSHER_SCHEME=https

    BROADCAST_CONNECTION=pusher

    VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
    VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
    VITE_PUSHER_HOST="${PUSHER_HOST}"
    VITE_PUSHER_PORT="${PUSHER_PORT}"
    VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"

Run migrations:

``` bash
php artisan migrate
```

Start server:

``` bash
php artisan serve
```

### Queue Worker (Required)

``` bash
php artisan queue:work
    or
php artisan queue:listen
```

------------------------------------------------------------------------

# 📡 Broadcasting

Private channel per user:

    user.{id}

Event example:

``` php
class OrderMatched implements ShouldBroadcast { ... }
```

------------------------------------------------------------------------

# 🔐 API Endpoints

  Method   Endpoint                 Description
  -------- ------------------------ ---------------
  POST     /api/register            Register User
  POST     /api/login               Login User
  GET      /api/profile             Fetch Profile
  POST     /api/order/place         Place Order
  GET      /api/orders              Get Orders
  POST     /api/order/cancel/{id}   Cancel Order

------------------------------------------------------------------------

# 🌐 Frontend (Vue 3)

## Install

``` bash
cd limit-order-exchange-frontend
npm install
```

### Add `.env`

    VITE_API_BASE_URL=http://localhost:8000/api
    VITE_PUSHER_KEY=xxxx
    VITE_PUSHER_CLUSTER=ap2

Run frontend:

``` bash
npm run dev
```



# ✔ Features

-   Token-based API authentication\
-   Realtime order updates (Pusher)\
-   Private user channels\
-   Order placement\
-   Order cancellation\
-   Automatic profile refresh\
-   Dynamic symbol filtering\
-   Fully responsive Tailwind UI

------------------------------------------------------------------------

# 📁 Folder Structure

    limit-order-exchange-mini-engine/
     ├── app/
     │    ├── Events/OrderMatched.php
     │    ├── Jobs/MatchOrderJob.php
     │    ├── Http/Controllers/
     │    │      ├── AuthController.php
     │    │      ├── OrderController.php

    limit-order-exchange-frontend/
     ├── src/
     │    ├── plugins/echo.js
     │    ├── api/
     │    ├── components/
     │    ├── store/
     │    └── views/

------------------------------------------------------------------------

# 🟢 Project Ready

This README covers **backend + frontend** setup, broadcasting, API,
queues, and integration.

Enjoy building your realtime trading engine! 🚀
