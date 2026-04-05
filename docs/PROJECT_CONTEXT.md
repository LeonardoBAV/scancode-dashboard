# ScanCode — project context

**Purpose:** Single source of narrative truth for humans and AI agents about domain, tenancy, and where code lives. **If this file disagrees with the code, the code wins** — then update this document in the same change when possible.

**Maintenance:** When you change tenancy, new tenant-scoped models, Filament resources, or major business flows, update the relevant sections below. Prefer small, dated notes in [Recent changes](#recent-changes).

---

## Product intent

Laravel + Filament dashboard for **distributors** to manage **catalog, clients, payment methods, sales representatives, and orders** (including line items). Access is **multi-tenant**: each distributor’s data is isolated by `distributor_id`.

---

## Tenancy model

| Concept | Implementation |
|--------|------------------|
| **Tenant** | `App\Models\Distributor` (`distributors`: `name`, `slug`). Route key for URLs: **`slug`**. |
| **Panel** | Filament **dashboard** panel: id `dashboard`, path `/dashboard`, **default** panel. |
| **Registration** | `App\Filament\Dashboard\Pages\Tenancy\RegisterDistributor` — creates distributor + slug (`Str::slug(name) + random suffix`). Shown in routing/onboarding only while `users.distributor_id` is null; menu item and `/new` page are hidden once the user has a distributor (one distributor per user). |
| **Tenant profile** | `App\Filament\Dashboard\Pages\Tenancy\EditDistributorProfile` — edit tenant `name` (slug unchanged). Registered via `tenantProfile()` on the dashboard panel. Authorization: `App\Policies\DistributorPolicy::update` + `User::canAccessTenant`. |
| **Tenant switcher** | Disabled (`tenantSwitcher(false)`): user is bound to one distributor. |
| **User ↔ tenant** | `users.distributor_id` (nullable). `User` implements `HasTenants`, `HasDefaultTenant`, `canAccessTenant`: user may only access their own distributor. Users with `distributor_id` null get an empty tenant list. |

**Skill / docs for tenancy work:** `.cursor/skills/filament-tenancy/SKILL.md` and Filament 5 multi-tenancy documentation.

---

## Data scoped by distributor

These tables include **`distributor_id`** (FK to `distributors`, `restrictOnDelete`), scoped per tenant:

- `product_categories`, `products`, `clients`, `payment_methods`, `sales_representatives`, `events`, `orders`, `order_items`

**Uniqueness** is **per distributor** where it matters (e.g. `product_categories`: `[distributor_id, name]`; `products`: SKU/barcode per distributor; `clients`: `cpf_cnpj` per distributor; etc.). See migration `2026_03_22_220054_add_distributor_tenancy_to_application_tables.php`.

---

## Domain outline

- **ProductCategory** → **Product** (catalog).
- **Client**, **PaymentMethod**, **SalesRepresentative**, **Event** — referenced by **Order** (each order belongs to one event).
- **Order** — `OrderStatusEnum`, observed by `App\Observers\OrderObserver`.
- **OrderItem** — observed by `App\Observers\OrderItemObserver`.

Use Eloquent relationships on models under `app/Models/` as the authoritative graph.

---

## Filament (dashboard)

- **Provider:** `App\Providers\Filament\DashboardPanelProvider`.
- **Resources (discovered):** `app/Filament/Dashboard/Resources/` — Clients, PaymentMethods, SalesRepresentatives, Products, Events, Orders, OrderItems (each with pages/schemas as per Filament v5 layout).
- **Tenancy pages:** `RegisterDistributor`, `EditDistributorProfile` under `app/Filament/Dashboard/Pages/Tenancy/`.

Forms and tables should respect tenant scoping (see existing resources and `filament-tenancy` skill).

---

## API

- **Authentication:** Laravel Sanctum token-based auth for `SalesRepresentative` (login via CPF + password).
- **Routes:** `routes/api.php`, versioned under `/api/v1/`.
- **Controller:** `App\Http\Controllers\Api\V1\AuthController` — `POST /api/v1/auth/login` issues a 7-day Bearer token.
- **Protected routes** use `auth:sanctum` middleware.
- `SalesRepresentative` extends `Authenticatable` and uses `HasApiTokens`.
- `sales_representatives.cpf` has a **global unique** constraint (in addition to the composite `[distributor_id, cpf]`).
- **Events listing:** `GET /api/v1/events` — returns events scoped to the authenticated `SalesRepresentative`'s `distributor_id`.
  - Controller: `App\Http\Controllers\Api\V1\EventController@index`.
  - Request validation: `App\Http\Requests\Api\V1\ListEventsRequest` — supports `filter[name|start_from|start_to|end_from|end_to]`, `fields` (CSV), `order` (column:asc|desc), `size` (null = no pagination).
  - Resource: `App\Http\Resources\Api\V1\EventResource`.
  - Model method: `Event::listBy(...)` centralizes query logic.
  - Global scope: `App\Models\Scopes\FilterDistributorByAuthSalesRepresentativeScope` — filters by `distributor_id` when the authenticated user is a `SalesRepresentative` (API token via Sanctum guard / request user; no-op for Filament `User`, CLI, and jobs).
- **Clients listing:** `GET /api/v1/clients` — same layering as events: `ClientController@index`, `ListClientsRequest` (`filter[corporate_name|fantasy_name|email|cpf_cnpj]`, `fields`, `order`, `size`), `ClientResource`, `Client::listBy(...)`, same global scope as `Event`.

---

## Tests

- **Pest** feature tests under `tests/Feature/Filament/Dashboard/...` for resources and pages.
- **API tests:** `tests/Feature/Api/V1/` for API endpoints.
- **Datasets:** `tests/Datasets/*` for shared examples.
- Run via Sail: `vendor/bin/sail artisan test --compact` (narrow with path or `--filter`).

Activate `.cursor/skills/pest-testing/SKILL.md` when writing or fixing tests.

---

## Recent changes

| Date | Note |
|------|------|
| 2026-03-24 | Initial `PROJECT_CONTEXT.md`: Distributor tenancy, dashboard resources, scoped tables. |
| 2026-03-24 | Tenant profile page `EditDistributorProfile` + `DistributorPolicy::update`. |
| 2026-03-24 | `RegisterDistributor::canView`: hide tenant registration menu/URL when user already has `distributor_id`. |
| 2026-03-25 | API layer: Sanctum installed, `SalesRepresentative` auth endpoint (`POST /api/v1/auth/login`), 7-day tokens, global unique CPF. |
| 2026-03-29 | `events` table + `Event` model; `orders.event_id` FK; Filament Events resource; Order form/table/infolist include event. |
| 2026-03-29 | `PaymentMethodSeeder` seeds methods per `Distributor`; `OrderSeeder` aligns reps/payment/event/products by `distributor_id`, creates items while pending then transitions status. |
| 2026-03-30 | API `GET /api/v1/events`: list events for authenticated seller, scoped by `distributor_id`, with filters/fields/order/pagination. Global scope `FilterDistributorByAuthSalesRepresentativeScope`. |
| 2026-04-04 | API `GET /api/v1/clients`: list clients for authenticated seller (same pattern as events). Scope resolves Sanctum / `request()->user()` so Bearer tokens apply `distributor_id` filtering. |

*(Append new rows when behavior or architecture shifts.)*
