# Laravel Agent — Architectural Best Practices

> This document defines mandatory conventions for all code produced in this Laravel project.
> Every agent, developer, or AI assistant contributing must follow these instructions precisely and without exception.

---

## Table of Contents

1. [Core Philosophy](#1-core-philosophy)
2. [Directory & Namespace Structure](#2-directory--namespace-structure)
3. [Routes](#3-routes)
4. [Controllers — Thin by Design](#4-controllers--thin-by-design)
5. [Request Classes](#5-request-classes)
6. [Resource Classes](#6-resource-classes)
7. [Action Classes](#7-action-classes)
8. [Service Classes](#8-service-classes)
9. [Model Classes](#9-model-classes)
10. [States & Factories](#10-states--factories)
11. [Traits](#11-traits)
12. [Guards](#12-guards)
13. [Policies](#13-policies)
14. [Spatie Permissions — Roles & Permissions](#14-spatie-permissions--roles--permissions)
15. [Testing with Pest](#15-testing-with-pest)
16. [General Rules & Anti-Patterns](#16-general-rules--anti-patterns)

---

## 1. Core Philosophy

- **Thin controllers.** Controllers orchestrate only — they never hold business logic.
- **Namespace ownership.** When more than one controller, model, service, or action exists for the same concept, they must be grouped under a shared namespace.
- **Single Responsibility.** One class, one job. Actions do one thing. Services coordinate many things.
- **Explicit over implicit.** Avoid magic where clarity is achievable.
- **Test everything.** Every action, service, policy, and endpoint must have a corresponding Pest test.
- **Prefer invokable.** Single-purpose controllers and actions must use `__invoke`.

---

## 2. Directory & Namespace Structure

### When to introduce a sub-namespace

A sub-namespace (e.g. `App\Http\Controllers\Patient\`) is **required** the moment there is more than one controller, model, action, service, request, or resource related to the same concept. A single standalone class may live flat — but the moment a second related class is created, both must be moved into a grouped namespace immediately.

### Top-level layout

```
app/
├── Actions/
├── Http/
│   ├── Controllers/
│   ├── Requests/
│   └── Resources/
├── Models/
├── Policies/
├── Services/
├── States/
└── Support/
    ├── Enums/
    ├── Helpers/
    └── Traits/

database/
└── factories/

tests/
├── Feature/
└── Unit/
```

### Grouped namespace layout (when a concept has multiple classes)

```
app/
├── Actions/
│   ├── Patient/
│   │   ├── CreatePatientAction.php
│   │   ├── UpdatePatientAction.php
│   │   └── DischargePatientAction.php
│   └── Billing/
│       └── GenerateInvoiceAction.php
│
├── Http/
│   ├── Controllers/
│   │   ├── Patient/
│   │   │   ├── PatientController.php
│   │   │   └── PatientNotesController.php
│   │   └── Billing/
│   │       └── InvoiceController.php
│   │
│   ├── Requests/
│   │   ├── Patient/
│   │   │   ├── StorePatientRequest.php
│   │   │   └── UpdatePatientRequest.php
│   │   └── Billing/
│   │       └── StoreInvoiceRequest.php
│   │
│   └── Resources/
│       ├── Patient/
│       │   ├── PatientResource.php
│       │   └── PatientSummaryResource.php
│       └── Billing/
│           └── InvoiceResource.php
│
├── Models/
│   ├── Patient/
│   │   ├── Patient.php
│   │   └── PatientNote.php
│   └── Billing/
│       └── Invoice.php
│
├── Policies/
│   ├── PatientPolicy.php
│   └── InvoicePolicy.php
│
├── Services/
│   ├── Patient/
│   │   ├── PatientReportService.php
│   │   └── PatientNotificationService.php
│   └── Billing/
│       └── InvoiceService.php
│
└── States/
    └── Patient/
        ├── PatientState.php      ← abstract base
        ├── ActiveState.php
        ├── PendingState.php
        └── DischargedState.php

tests/
├── Feature/
│   ├── Patient/
│   └── Billing/
└── Unit/
    ├── Actions/
    ├── Services/
    └── States/
```

### Namespace naming rules

| Layer | Namespace Pattern |
|---|---|
| Controller | `App\Http\Controllers\{Concept}` |
| Request | `App\Http\Requests\{Concept}` |
| Resource | `App\Http\Resources\{Concept}` |
| Action | `App\Actions\{Concept}` |
| Service | `App\Services\{Concept}` |
| Model | `App\Models\{Concept}` |
| State | `App\States\{Concept}` |
| Policy | `App\Policies` (flat — one policy per model) |

---

## 3. Routes

This project uses **Inertia.js** with **web routes only**. There is no `api.php` in active use. All routes are defined in `routes/web.php` or concept-specific files included from it.

### Prefer resource routes

Always define routes using `Route::resource()` for standard CRUD controllers. Only fall back to manual route definitions when an endpoint does not map to a standard resource operation.

- Use `Route::resource()` for all resourceful controllers.
- Use `->only([...])` or `->except([...])` to restrict available methods — never define all five methods if only two or three are used.
- Never define individual `get`/`post` routes for operations that fit a resource convention.
- Use route model binding everywhere — never manually fetch a model inside a controller when it can be bound via the route.

### Prefer invokable controllers for non-resource actions

When an endpoint does not cleanly map to a standard resource operation (e.g. `approve`, `discharge`, `resend-invite`), use a single-action invokable controller rather than adding extra methods to an existing resource controller.

- Invokable controllers are registered as a plain class reference: `Route::post('/patients/{patient}/discharge', DischargePatientController::class)`.
- Invokable controllers follow the same thin-controller rules — one Action or Service call, then an Inertia redirect or response.
- Invokable controllers are named as `{Verb}{Model}Controller` (e.g. `DischargePatientController`, `ApproveInvoiceController`).

### Route file organisation

- `routes/web.php` acts as an index only — it includes concept-specific route files.
- Each concept has its own route file, e.g. `routes/patient.php`, `routes/billing.php`.
- Route files must not contain closures — only controller class references.
- Apply `auth` and `role`/`permission` middleware at the route group level, not on individual routes.
- Named routes must follow the Laravel resource convention (`patients.index`, `patients.store`, etc.) and be referenced by name everywhere — never hardcode URL strings in controllers or Inertia pages.

### Inertia-specific rules

- Controllers return `Inertia::render('PageComponent', [...props])` — never return JSON directly from a web controller unless it is an explicit JSON endpoint.
- Redirects after mutations (store, update, destroy) must use `to_route('route.name')` — never `redirect('/hardcoded-url')`.
- Shared Inertia props (auth user, flash messages, permissions) must be defined in `HandleInertiaRequests` middleware — never passed manually from every controller.
- Props passed to Inertia must be minimal — pass only what the page component needs. Heavy data must be shaped by a Resource or a dedicated Data class before being passed.
- Lazy props (`Inertia::lazy(...)`) must be used for data that is not needed on initial page load.

---

## 4. Controllers — Thin by Design

### A controller must NOT contain

- Business logic of any kind
- Direct database queries or Eloquent calls beyond passing to actions
- Validation logic — that belongs in Form Requests
- Data transformation — that belongs in Resources
- `try/catch` blocks — exceptions are handled in `Handler.php`

### A controller MUST only

- Inject Actions or Services via the constructor
- Call `$this->authorize()` before any operation
- Delegate to a single Action or Service method per endpoint
- Return an `Inertia::render()` response for read operations, or `to_route()` after mutations

### Controller types

**Resource controllers** handle standard CRUD operations and implement only the methods they need (`index`, `show`, `store`, `update`, `destroy`).

**Invokable controllers** handle single non-CRUD operations and implement only `__invoke`.

### Rules

- Every controller method must begin with an `authorize()` call.
- A controller must never call more than one Action directly — if coordination is needed, delegate to a Service.
- Constructor injection only — never use `app()` or `resolve()` inside a controller.
- Never return a raw model — always wrap in a Resource.
- Never use `$request->validate()` inside a controller.

---

## 5. Request Classes

All input validation lives exclusively in Form Request classes.

### Naming convention

| Operation | Class Name |
|---|---|
| Create | `Store{Model}Request` |
| Update | `Update{Model}Request` |
| Custom verb | `{Verb}{Model}Request` |

### Rules

- `authorize()` must perform a real permission check using the policy or Spatie helpers — never `return true` unconditionally.
- `rules()` must declare every expected input field explicitly.
- Use `prepareForValidation()` to normalise or cast input (trimming, lowercasing, etc.) before rules are evaluated.
- Use `after()` hooks for cross-field validation or database-backed uniqueness checks beyond what rules support.
- Complex or reusable rules must be extracted into dedicated Laravel `Rule` objects, not inlined as closures repeatedly.
- Custom error messages go in `messages()`, not hardcoded into rules.
- Requests must never perform database writes, fire events, or call services.

---

## 6. Resource Classes

Resources transform Eloquent models into structured, consistent API responses.

### Naming convention

| Type | Class Name |
|---|---|
| Full representation | `{Model}Resource` |
| Lightweight / list view | `{Model}SummaryResource` |
| Custom collection meta | `{Model}Collection` |

### Rules

- Never return a raw model or `->toArray()` from a controller — always use a Resource.
- Always use `$this->whenLoaded()` for relationships — never trigger additional queries inside a Resource.
- Use `$this->when()` for fields that are conditional on permissions, context, or load state.
- All dates must be explicitly formatted — never rely on default model serialisation.
- Resources must not contain business logic, conditionals based on model state that belong in the model, or database queries.
- Use `{Model}SummaryResource` for list endpoints to avoid over-fetching — reserve full `{Model}Resource` for single-record endpoints.

---

## 7. Action Classes

Actions are single-purpose invokable classes. Each action does **exactly one thing**.

### Naming convention

`{Verb}{Model}Action` — verbs must be imperative: `Create`, `Update`, `Delete`, `Send`, `Approve`, `Assign`, `Discharge`, `Activate`.

### Rules

- Every action must be invokable via `__invoke`.
- Actions must accept plain arrays, primitives, or DTOs — never Request objects.
- Wrap all side-effectful actions (writes, events, notifications) in `DB::transaction()`.
- Fire domain events from within the action — not from the controller.
- Never inject another Action into an Action — if coordination is needed, that is the responsibility of a Service.
- Actions must never call other actions directly or chain action calls internally.
- Actions must have a single return type, declared explicitly.

---

## 8. Service Classes

Services coordinate multiple Actions, external integrations, or complex read-side query logic.

### When to use a Service vs an Action

| Use an **Action** when | Use a **Service** when |
|---|---|
| Performing a single atomic write | Coordinating two or more actions |
| Transitioning a model state | Integrating with an external API |
| Sending a single notification | Generating reports or summaries |
| One database operation | Complex query pipelines |

### Rules

- Services are injected into controllers via the constructor — never called statically.
- Services may call multiple Actions but must not contain raw Eloquent queries unless it is a dedicated read/query service.
- A service method that exceeds roughly 20 lines of logic is a signal to extract further into Actions.
- Services must be focused — a service with more than 5–6 methods is likely doing too much and should be split.
- Services must not accept Request objects — only plain data.

---

## 9. Model Classes

Models are Eloquent entities. They define structure, relationships, casts, and scopes — not business logic.

### Rules

- Always declare `$fillable` explicitly — never use `$guarded = []`.
- All date and enum casts must be declared in `$casts`.
- No business logic inside models — no sending notifications, no calling services, no complex conditionals.
- Relationships must be declared as typed methods with explicit return types.
- Scopes must be named descriptively (`scopeActive`, `scopeAssignedTo`) and used in query builders, not controllers.
- Use `SoftDeletes` on any entity requiring audit trails or potential recovery.
- Use `HasFactory` on every model — there must always be a corresponding factory.
- State management must use the `spatie/laravel-model-states` package and be declared via `HasStates`.
- Model observers must be used for cross-cutting side effects (e.g. cache invalidation) — not for business logic.

---

## 10. States & Factories

### States

- Use `spatie/laravel-model-states` for all finite state machine behaviour on models.
- Every concept with a lifecycle must have an abstract base `{Model}State` class and one concrete class per state.
- All allowed transitions must be declared explicitly in the base state's `config()` method — no open-ended graphs.
- States must expose a `label(): string` method for human-readable display.
- State transitions must only happen inside Action classes — never in controllers, models, or observers.

### Factories

- Every model must have a corresponding factory — no exceptions.
- The `definition()` method must produce realistic, self-contained data using Faker.
- Factories must define named state methods for each model state (e.g. `->active()`, `->discharged()`, `->suspended()`).
- Never hardcode IDs in factories — use `for()` or `recycle()` for related model creation.
- Factories are the only place that seeds test data — tests must never create raw `Model::create()` calls directly.

---

## 11. Traits

Traits encapsulate reusable, cross-cutting behaviour shared by multiple classes.

### Location

- Model-specific traits → `app/Support/Traits/`
- General/utility traits → `app/Support/Traits/`
- Test helper traits → `tests/Traits/`

### Rules

- Traits must be cohesive — one clearly defined concern per trait.
- Boot methods on model traits must follow the `boot{TraitName}()` convention to integrate with Eloquent's boot lifecycle.
- Traits must not depend on concrete class implementations — use interfaces or injected parameters.
- Do not use traits as a way to avoid creating a proper class — if logic grows, extract it into a Service or Action.
- Every trait must have a docblock describing its purpose and any requirements for using it (e.g. expected properties or methods on the host class).
- Traits containing model methods must not fire events or perform writes — that remains the responsibility of Actions.

---

## 12. Guards

Guards define how users are authenticated for a given request context.

### Rules

- Every distinct user type (e.g. user, admin, staff) must have its own guard and Eloquent provider defined in `config/auth.php`.
- Never mix guards within a single route group.
- All web routes use the `auth` (session) guard — applied via the `auth` middleware on route groups.
- When checking the authenticated user inside an Action or Service, the user must be passed as a parameter — never call `Auth::user()` deep inside application logic.
- If an API layer is introduced in the future, it must use `auth:sanctum` — never reuse the `web` session guard for API endpoints.

---

## 13. Policies

Policies contain all model-level authorisation logic. No authorisation logic may live in controllers, actions, or services.

### Rules

- Every model must have a corresponding Policy registered in `AuthServiceProvider`.
- Policy method names must match Laravel's convention: `viewAny`, `view`, `create`, `update`, `delete`, `restore`, `forceDelete`.
- Custom policy methods (e.g. `viewSensitive`, `approve`) are permitted for non-standard operations.
- Policies must use Spatie permission helpers (`hasPermissionTo`, `hasRole`, `hasAnyPermission`) — never raw string checks against user attributes.
- A `before()` hook must be used to grant super-admins blanket access — defined once in `AuthServiceProvider`, not repeated across policies.
- Controllers must always call `$this->authorize()` — policies must never be called directly from a controller.
- Request classes may also call `$this->user()->can(...)` in `authorize()` to delegate to the policy.

---

## 14. Spatie Permissions — Roles & Permissions

### Setup requirements

- Install `spatie/laravel-permission` and run migrations before defining any roles or permissions.
- Add `HasRoles` trait to the `User` model (and any other authenticatable model that requires role/permission support).
- Cache must be cleared on every deployment: run `php artisan permission:cache-reset` in the deploy pipeline.

### Naming conventions

- **Permissions** must be kebab-case and follow the pattern `{verb}-{resource}`, e.g. `create-patient`, `approve-invoice`, `view-any-report`.
- **Roles** must be lowercase and descriptive, e.g. `admin`, `clinician`, `receptionist`, `billing-manager`.
- Permission and role strings must never be hardcoded across the codebase. Define them as constants in a dedicated `Enums/Permission.php` and `Enums/Role.php` backed enum.

### Seeding

- All roles and permissions must be seeded via a dedicated `RolesAndPermissionsSeeder`.
- Always call `forgetCachedPermissions()` at the start of the seeder.
- Use `firstOrCreate()` for idempotent seeding — the seeder must be safely re-runnable.
- Use `syncPermissions()` to assign permissions to roles — never `givePermissionTo()` in seeders as it does not remove stale permissions.

### Middleware

- Apply `role:` or `permission:` middleware at the route group level.
- Never check roles or permissions inline inside controller methods — that is the policy's job.

### Super-admin

- Super-admin bypass must be implemented once as a `Gate::before()` hook in `AuthServiceProvider`.
- Never replicate super-admin logic across individual policies.

---

## 15. Testing with Pest

### Setup

- Install `pestphp/pest` and `pestphp/pest-plugin-laravel` as dev dependencies.
- Run `php artisan pest:install` to scaffold `tests/Pest.php`.
- Apply `RefreshDatabase` globally for all Feature tests via `uses(RefreshDatabase::class)->in('Feature')` in `Pest.php`.

### File structure

Tests must mirror the application structure. Each class under test has a corresponding test file.

```
tests/
├── Feature/
│   ├── Patient/
│   │   ├── PatientControllerTest.php
│   │   └── PatientPolicyTest.php
│   └── Billing/
│       └── InvoiceControllerTest.php
└── Unit/
    ├── Actions/
    │   └── CreatePatientActionTest.php
    ├── Services/
    │   └── PatientOnboardingServiceTest.php
    └── States/
        └── PatientStateTest.php
```

### What must be tested

| Layer | Test Type | Must cover |
|---|---|---|
| Controller endpoints | Feature | Correct Inertia component rendered, forbidden, validation errors, redirect after mutation |
| Action classes | Unit | Happy path, edge cases, exception cases |
| Service classes | Unit | Coordination logic, correct action delegation |
| Policies | Unit | Each method, for each role that should pass and fail |
| State transitions | Unit | Valid transitions succeed, invalid transitions throw |
| Request classes | Feature | Required fields, format rules, authorization |
| Factories | Unit | State methods produce expected attributes |

### Conventions

- Use `it('...')` with plain-English, lowercase descriptions — describe behaviour, not implementation.
- Use `beforeEach()` for shared setup — not PHPUnit's `setUp()`.
- Use the `expect()` API over PHPUnit assertion methods wherever possible.
- Use `$this->actingAs($user)` for authentication in Feature tests (web session guard).
- Use `assertInertia()` to assert the correct Inertia component and props are returned.
- After mutations, assert a redirect using `->assertRedirect(route('route.name'))` — not a JSON response.
- Use `describe()` blocks to group related scenarios within a single test file.
- Unit tests must not hit the database unless absolutely necessary — mock or fake dependencies.
- Feature tests must use factories exclusively — never `Model::create()` directly in test bodies.
- Every test must assert both the Inertia component/props (or redirect) and the database state where a write occurred.
- Tests for forbidden actions must assert `403` — not just that a record was not created.
- Tests must not depend on execution order — each test must be fully self-contained.

---

## 16. General Rules & Anti-Patterns

### Always

- Declare `strict_types=1` at the top of every PHP file.
- Declare explicit return types on every method.
- Use constructor property promotion for injected dependencies.
- Use `readonly` properties on DTOs and value objects.
- Run `php artisan ide-helper:generate` to keep IDE support accurate.
- Declare `@throws` docblocks on any method that can throw a domain exception.

### Never

| Anti-Pattern | Correct Approach |
|---|---|
| `$request->validate()` in a controller | Use a Form Request class |
| Returning a raw model from a controller | Shape into a Resource or Data class, pass as Inertia props |
| `User::find($id)` inside a controller | Use route model binding |
| Business logic in a model | Move to an Action or Service |
| `DB::table(...)` for application queries | Use Eloquent with scopes |
| `$guarded = []` on a model | Declare explicit `$fillable` |
| Role/permission strings scattered inline | Define in backed Enums |
| Logic or closures in route files | Move to controllers |
| Fat service class with 10+ methods | Split into focused services |
| `Auth::user()` inside an Action | Pass the user as a parameter |
| Calling one Action from inside another Action | Use a Service to coordinate |
| Skipping `$this->authorize()` in a controller | Always call it first |
| Multiple operations in one controller method | Delegate to a Service |
| Resource controller with custom non-CRUD methods | Use an invokable controller |
| Hardcoding URLs in redirects or links | Always use named routes via `route()` or `to_route()` |
| Passing full Eloquent models as Inertia props | Shape data with a Resource or Data class first |

---

> **Maintained by:** Engineering Lead
> Update this document whenever a new pattern is adopted or an existing one is revised.
> All agents must re-read this file at the start of every session before producing any code.