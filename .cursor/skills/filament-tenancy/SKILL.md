---
name: filament-tenancy
description: >-
  Implements Filament v5 multi-tenancy (teams, organizations, SaaS tenants) safely.
  Use when the user mentions tenancy, multi-tenant, multi tenancy, tenant switcher,
  Filament::getTenant(), HasTenants, tenant registration, tenant profile, tenant
  middleware, scopedUnique, or isolating data per team/organization. Covers panel
  configuration, User contracts, security (canAccessTenant), validation
  (scopedUnique/scopedExists), resources (isScopedToTenant), and when to prefer
  simple global scopes instead. Primary reference: Filament 5.x Multi-tenancy docs.
license: MIT
metadata:
  author: project
---

# Filament 5 — Multi-tenancy

## When to use this skill

- **Filament panel tenancy** (`->tenant()`): user belongs to **many** tenants and may switch (teams, orgs). Use `HasTenants` + `canAccessTenant()` + tenant menu.
- **Simpler case** (one team per user, no switching UI): Filament docs recommend **not** using panel tenancy — use **Eloquent global scopes** + **observers** instead. See [Simple one-to-many tenancy](https://filamentphp.com/docs/5.x/users/tenancy#simple-one-to-many-tenancy).

Always read the official page before implementing: **[Multi-tenancy (Filament 5.x)](https://filamentphp.com/docs/5.x/users/tenancy)**.

Use `search-docs` (Boost) for Filament 5–specific APIs when writing code.

## Implementation workflow (checklist)

1. **Tenant model** — e.g. `Team` / `Organization`; add relationships and any `HasName`, `HasAvatar`, `HasCurrentTenantLabel` as needed.
2. **Panel** — `->tenant(TenantModel::class)`; optional: `slugAttribute`, `ownershipRelationship`, `tenantRoutePrefix`, `tenantDomain`, `tenantMiddleware()`, `tenantRegistration()`, `tenantProfile()`.
3. **User model** — implement `Filament\Models\Contracts\HasTenants`:
   - `getTenants(Panel $panel): Collection` — list tenants the user may access.
   - `canAccessTenant(Model $tenant): bool` — **required for security** (prevent ID guessing in URLs).
4. **Optional** — `HasDefaultTenant`, registration page extending `RegisterTenant`, profile page extending `EditTenantProfile`.
5. **Resources** — default: scoped to tenant. Shared data: `protected static bool $isScopedToTenant = false` or global `Resource::scopeToTenant(false)` with opt-in per resource.
6. **Validation** — Laravel `unique` / `exists` **ignore Eloquent global scopes**. For real isolation use Filament **`scopedUnique()`** / **`scopedExists()`** on form fields (see [validation](https://filamentphp.com/docs/5.x/users/tenancy#unique-and-exists-validation)).
7. **Models without a Filament resource** — tenancy global scope does **not** apply automatically; use **tenant middleware** + manual global scopes or explicit query scoping (see [tenant-aware middleware](https://filamentphp.com/docs/5.x/users/tenancy#using-tenant-aware-middleware-to-apply-additional-global-scopes)).
8. **Tests** — assert users cannot access another tenant’s records (404 / policy), and that `canAccessTenant` is enforced.

## Security (non-negotiable)

- **Never** ship tenancy without **`canAccessTenant()`** correctly implemented.
- Queries **outside** the Filament panel are **not** auto-scoped — audit APIs, jobs, CLI, and early middleware.
- **`withoutGlobalScopes()`** can leak data — prefer `withoutGlobalScope(filament()->getTenancyScopeName())` when you must bypass only the tenancy scope.
- Read Filament’s **[Tenancy security](https://filamentphp.com/docs/5.x/users/tenancy#tenancy-security)** section; Filament does not guarantee security — the app owner does.

## Quick reference — common APIs

| Need | Approach |
|------|----------|
| Current tenant | `Filament\Facades\Filament::getTenant()` |
| Tenant in URL | `tenant()`, `slugAttribute`, `tenantRoutePrefix`, `tenantDomain` |
| Custom ownership relation | `->tenant(..., ownershipRelationship: 'owner')` or `$tenantOwnershipRelationshipName` on resource |
| Custom resource relation name on tenant | `$tenantRelationshipName` on resource |
| Extra scopes for non-resource models | `->tenantMiddleware([ApplyTenantScopes::class], isPersistent: true)` |
| Billing (Spark) | `filament/spark-billing-provider`, `tenantBillingProvider()`, optional `requiresTenantSubscription()` |

## Related official docs

- [Multi-tenancy](https://filamentphp.com/docs/5.x/users/tenancy) — main guide
- [Panel configuration](https://filamentphp.com/docs/5.x/panels/configuration) — `tenant()`, menu, middleware
- Discover more: `https://filamentphp.com/docs/llms.txt`

## Out of scope for this skill

- Database-per-tenant vs shared schema strategy (business/infrastructure choice).
- Replacing Laravel authorization policies — tenancy complements policies, does not replace them.
