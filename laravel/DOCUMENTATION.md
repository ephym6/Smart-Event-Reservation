# Smart Event Reservation – DOCUMENTATION

This document explains the end‑to‑end flow of the Smart Event Reservation Laravel app: from authentication and data access through models, controllers, routes, and Tailwind-styled Blade views.

## Table of contents
- Overview
- Tech stack and setup
- Database schema and relationships
- Auth flows (User and Admin)
- Controllers and business logic
- Routes map
- Views and UI flow
- Availability and booking rules
- Home search flow
- Admin approval workflow
- Development commands
- Future improvements

---

## Overview
Smart Event Reservation lets users browse venues and events, reserve a venue for a time slot, and track their reservations. Admins/managers manage venues, review new reservations, and approve/decline them.

Key features:
- Tailwind + Vite UI with polished components
- Role-aware auth (user, manager, admin)
- Inline booking forms on Event and Venue pages
- Overlap prevention: a venue can’t be double-booked for the same time
- Admin dashboard with approval/decline actions
- Search venues by date, location, and guests

---

## Tech stack and setup
- Framework: Laravel 10+
- Styling: TailwindCSS via Vite (@vite in layout)
- Blade componentized layout
- Database: MySQL (default) via Eloquent ORM

Setup summary:
- Copy `.env.example` to `.env` and configure DB credentials
- Install deps: `composer install` and `npm install`
- Generate app key: `php artisan key:generate`
- Migrate + seed: `php artisan migrate --seed`
- Run dev servers:
  - Vite/Tailwind: `npm run dev`
  - Laravel: `php artisan serve`

---

## Database schema and relationships
Custom primary keys and tables:
- users (pk: user_id)
- venues (pk: venue_id)
- events (pk: event_id)
- reservations (pk: reservation_id)
- reservation_items (pk as per migration)
- inventory_items (pk)

Eloquent models:
- User (extends Authenticatable)
  - Fillables: name, email, password_hash, role, etc.
- Venue
  - hasMany Event (by venue_id)
  - hasMany Reservation (by venue_id)
- Event
  - belongsTo Venue
  - hasMany Reservation
- Reservation
  - belongsTo User, Venue, Event
  - hasMany ReservationItem

Important columns:
- users.password_hash stores hashed password.
- reservations.start_time, reservations.end_time used for overlap checks.
- reservations.status in [pending, approved, cancelled, completed]

Seeders/Factories:
- `DatabaseSeeder` calls `VenueSeeder`, `EventSeeder`, etc.
- `VenueSeeder` adds canonical Nairobi venues.
- Factories produce demo data for testing.

---

## Auth flows (User and Admin)
Two login paths:
- User login: `/login` (AuthController@showLoginForm / login)
- Admin login: `/admin/login` (AuthController@showAdminLoginForm / adminLogin)

Registration `/register`:
- Form includes Account type: user/manager/admin
- Saves role into users.role
- Post-register redirect:
  - user -> home
  - admin/manager -> dashboard

Login behavior:
- User login auto-creates a basic user if email doesn’t exist (optional bootstrap feature)
- Auth::login + session regeneration
- Role-based redirect: admin/manager -> `/dashboard`, user -> `/`

Logout: POST `/logout` -> invalidate session + token

---

## Controllers and business logic

### HomeController
- `index()`
  - Pulls 6 random venues for featured section
  - View: `resources/views/home.blade.php`

### VenueController
- `index()`
  - Reads filters: `date`, `location`, `guests`
  - Applies location/name partial match and capacity >= guests
  - Computes `active_reservations_count` via overlap for the selected `date`
  - View: `resources/views/venues/index.blade.php`
- `show($id)`
  - Loads venue with events
  - View includes inline booking form (`#reserve`) posting to `reservations.store`
- `store/update/destroy`
  - Admin/manager only; otherwise 403

### EventController
- `index()`
  - Shows only events considered "available" (no pending/approved reservations associated)
  - View: Tailwind table linking to the event’s venue
- `show($id)`
  - Event details + inline booking form targeting `reservations.store`

### ReservationController
- `index()`
  - Admin/manager: list all
  - User: list only current user’s reservations
  - View: Tailwind table with role-aware columns
- `store()`
  - Requires login
  - Validates input
  - Overlap prevention for the same venue/time window
  - Persists reservation with user_id and default `pending`
  - Redirects to `reservations.success`
- `success($id)`
  - Shows confirmation details and auto-redirects back to Venues for the reservation date
- `approve($id)` / `decline($id)`
  - Admin/manager only
  - Update `status` to `approved` or `cancelled`

### AdminController
- `dashboard()`
  - Role-gated
  - Supplies `$venuesCount`, `$reservationsCount`, `$reservedVenues` (today), `$recentReservations`
  - View: `resources/views/dashboard.blade.php` with Approve/Decline actions

---

## Routes map (selected)

Home and general:
- GET `/` -> HomeController@index (name: home)
- GET `/dashboard` -> AdminController@dashboard (name: dashboard)

Auth (users):
- GET `/login` -> login form (name: login)
- POST `/login` -> login.post
- GET `/register` -> register form (name: register)
- POST `/register` -> register.post
- POST `/logout` -> logout

Auth (admin):
- GET `/admin/login` -> admin login (name: admin.login)
- POST `/admin/login` -> admin login.post

Admin reservation actions:
- POST `/admin/reservations/{id}/approve` -> ReservationController@approve (name: admin.reservations.approve)
- POST `/admin/reservations/{id}/decline` -> ReservationController@decline (name: admin.reservations.decline)

Venues:
- GET `/venues` -> VenueController@index (name: venues.index)
- GET `/venues/{id}` -> VenueController@show (name: venues.show)
- POST `/venues` -> store (admin)
- PUT `/venues/{id}` -> update (admin)
- DELETE `/venues/{id}` -> destroy (admin)

Events:
- GET `/events` -> EventController@index (name: events.index)
- GET `/events/{id}` -> EventController@show (name: events.show)

Reservations:
- GET `/reservations` -> ReservationController@index (name: reservations.index)
- POST `/reservations` -> ReservationController@store (name: reservations.store)
- GET `/reservations/{id}` -> ReservationController@show (name: reservations.show)
- GET `/reservations/{id}/success` -> ReservationController@success (name: reservations.success)

---

## Views and UI flow

Layout:
- `resources/views/layouts/app.blade.php`
  - Tailwind + Vite: `@vite(['resources/css/app.css', 'resources/js/app.js'])`
  - Navbar with role-aware Dashboard link and Login/Sign Up
  - Footer with contact/social links

Components:
- `components/venue-card.blade.php`, `components/event-card.blade.php`
- `components/navbar.blade.php`, `components/footer.blade.php`

Pages:
- Home (`home.blade.php`)
  - Hero banner, search form (location, date, guests), featured venues, stats
  - Search submits to `/venues?location=..&date=..&guests=..`
- Venues
  - Index (`venues/index.blade.php`): grid of cards, availability badge, date filter, "Reserve" -> venue show `#reserve`
  - Show (`venues/show.blade.php`): details, events list, inline booking form posting to `reservations.store`
- Events
  - Index (`events/index.blade.php`): only available events; action "View Venue" -> venue show
  - Show (`events/show.blade.php`): details and inline booking form
- Reservations
  - Index (`reservations/index.blade.php`): “My Reservations” for users; full list for admins
  - Success (`reservations/success.blade.php`): confirmation + auto-redirect back to day-filtered venues
- Auth
  - Login (`auth/login.blade.php`) with Admin login link
  - Admin Login (`auth/admin-login.blade.php`) with link to user login
  - Register (`auth/register.blade.php`) with Account type
- Dashboard (`dashboard.blade.php`)
  - KPIs, reserved venues today, recent reservations with Approve/Decline actions

---

## Availability and booking rules
A venue is considered Reserved for a given date if there is any reservation with status in [pending, approved] such that:

```
reservation.start_time <= dayEnd
AND reservation.end_time >= dayStart
```

Booking a reservation:
- User must be logged in
- Server validates no overlap for the same venue across the requested time
- Status defaults to `pending`
- Admin/manager can later approve/decline

---

## Home search flow
- User selects location (free text), date (optional), guests (optional)
- Submits GET to `/venues`
- Controller filters by:
  - Location: matches `venues.location` OR `venues.venue_name` (LIKE)
  - Guests: `capacity >= guests`
  - Date: used only to compute availability badge (`Reserved` / `Available`)
- UI shows matching venues; a venue with overlap on the day shows `Reserved` and the Reserve button is disabled.

---

## Admin approval workflow
- On Dashboard, recent reservations show Approve / Decline buttons for `pending` items
- Approve -> sets status `approved`
- Decline -> sets status `cancelled`
- User’s Reservations page reflects updated status with badges

---

## Development commands
- Migrate: `php artisan migrate`
- Seed: `php artisan db:seed` (or `php artisan migrate --seed`)
- Run app: `php artisan serve`
- Run Vite/Tailwind: `npm run dev`

---

## Future improvements
- Replace inline role checks with dedicated middleware (e.g., `EnsureAdmin`)
- Add time-slot filters (start/end) to Venues index
- Email notifications on approval/decline
- Paginate long lists (venues, events, reservations)
- Add tests for reservation overlap logic
