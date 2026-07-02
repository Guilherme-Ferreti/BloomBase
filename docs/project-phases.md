# BloomBase — Project Phases

> Generated from grilling session and [`docs/project-description.md`](project-description.md).
>
> ✅ = Already completed (existing codebase)
> ⬜ = Not yet started

---

## ✅ Phase 0: Project Scaffolding (Already Done)

- [x] Laravel 13 + Livewire 4 installed (`composer.json`)
- [x] Tailwind CSS v4 configured with Vite (`vite.config.js`, `tailwindcss` NPM package)
- [x] Base layout — `resources/views/welcome.blade.php` (placeholder)
- [x] Route `GET /` → `welcome` view, named `home`
- [x] SQLite database configured (`.env`)
- [x] Users table, cache table, jobs table migrations exist
- [x] `User` model + factory exist
- [x] Example tests (Feature + Unit) exist
- [x] Pint, PHPStan, Rector configured
- [x] GitHub Actions CI (lint + tests)

---

## ⬜ Phase 1: Database Foundation — Models, Migrations, Factories, Seeders

> **UUID Convention:** All models in Phase 1 use UUID primary keys (`$table->uuid('id')->primary()`) instead of auto-increment integers. Models must use the `HasUuids` trait for automatic UUID generation. Foreign key columns use `foreignUuid()` with proper cascade rules.

### 1.1 Create Family Model + Migration

Table: `families`

| Column | Type | Constraints |
|---|---|---|
| `id` | `uuid()` | Primary key (auto-generated via `HasUuids`) |
| `name` | `string(255)` | Required, unique |
| `slug` | `string(255)` | Required, unique |
| `description` | `text` | Nullable |
| `timestamps` | — | `created_at`, `updated_at` |

Model: `App\Models\Family`
- Traits: `HasFactory`, `HasUuids`
- `#[Fillable]`: `name`, `slug`, `description`
- Relationship: `hasMany(Flower::class)`

**Acceptance tests:**
- `it('creates a family with valid data')` — assert family is persisted and retrievable
- `it('has many flowers')` — assert the `hasMany` relationship returns expected flowers
- `it('requires a unique name')` — assert duplicate name fails validation
- `it('requires a unique slug')` — assert duplicate slug fails validation

### 1.2 Create Climate Model + Migration

Table: `climates`

| Column | Type | Constraints |
|---|---|---|
| `id` | `uuid()` | Primary key (auto-generated via `HasUuids`) |
| `name` | `string(255)` | Required, unique |
| `slug` | `string(255)` | Required, unique |
| `timestamps` | — | `created_at`, `updated_at` |

Pivot table: `flower_climate`

| Column | Type | Constraints |
|---|---|---|
| `flower_id` | `foreignUuid()` | FK → `flowers.id`, cascade on delete |
| `climate_id` | `foreignUuid()` | FK → `climates.id`, cascade on delete |

→ Composite primary key on `(flower_id, climate_id)`

Model: `App\Models\Climate`
- Traits: `HasFactory`, `HasUuids`
- `#[Fillable]`: `name`, `slug`
- Relationship: `belongsToMany(Flower::class)`

**Acceptance tests:**
- `it('creates a climate with valid data')`
- `it('belongs to many flowers')` — assert the `belongsToMany` relationship works
- `it('can attach flowers via pivot table')` — assert flower_climate pivot row is persisted

### 1.3 Create FloweringSeason Model + Migration

Table: `flowering_seasons`

| Column | Type | Constraints |
|---|---|---|
| `id` | `uuid()` | Primary key (auto-generated via `HasUuids`) |
| `name` | `string(255)` | Required, unique |
| `slug` | `string(255)` | Required, unique |
| `timestamps` | — | `created_at`, `updated_at` |

Pivot table: `flower_flowering_season`

| Column | Type | Constraints |
|---|---|---|
| `flower_id` | `foreignUuid()` | FK → `flowers.id`, cascade on delete |
| `flowering_season_id` | `foreignUuid()` | FK → `flowering_seasons.id`, cascade on delete |

→ Composite primary key on `(flower_id, flowering_season_id)`

Model: `App\Models\FloweringSeason`
- Traits: `HasFactory`, `HasUuids`
- `#[Fillable]`: `name`, `slug`
- Relationship: `belongsToMany(Flower::class)`

**Acceptance tests:**
- `it('creates a flowering season with valid data')`
- `it('belongs to many flowers')`

### 1.4 Create Flower Model + Migration

Table: `flowers`

| Column | Type | Constraints |
|---|---|---|
| `id` | `uuid()` | Primary key (auto-generated via `HasUuids`) |
| `common_name` | `string(255)` | Required |
| `scientific_name` | `string(255)` | Required |
| `slug` | `string(255)` | Required, unique |
| `description` | `text` | Required |
| `interesting_facts` | `text` | Nullable |
| `care_instructions` | `text` | Nullable |
| `lifespan` | `string(255)` | Nullable |
| `hero_image` | `string(255)` | Required — URL string |
| `images` | `json` | Nullable — array of `{url, alt, attribution}` |
| `family_id` | `foreignUuid()` | FK → `families.id`, cascade on delete |
| `timestamps` | — | `created_at`, `updated_at` |

Indexes:
- `common_name` — regular index (for search queries)
- `scientific_name` — regular index (for search queries)

Model: `App\Models\Flower`
- Traits: `HasFactory`, `HasUuids`
- `#[Fillable]`: `common_name`, `scientific_name`, `slug`, `description`, `interesting_facts`, `care_instructions`, `lifespan`, `hero_image`, `images`, `family_id`
- `#[Cast]` / `casts()`: `images → array`
- Relationships:
  - `belongsTo(Family::class)`
  - `belongsToMany(Climate::class)`
  - `belongsToMany(FloweringSeason::class)`

**Acceptance tests:**
- `it('creates a flower with all attributes')` — assert all columns are persisted correctly
- `it('belongs to a family')` — assert the `belongsTo` relationship
- `it('belongs to many climates')` — assert attaching/detaching climates
- `it('belongs to many flowering seasons')` — assert attaching/detaching seasons
- `it('casts images as an array')` — assert images column is decoded as PHP array
- `it('requires a unique slug')`
- `it('can find flowers by common name')` — scope query
- `it('can find flowers by scientific name')` — scope query
- `it('can search with partial matches')` — e.g., searching "rose" finds "Rose", "Rosemary"

### 1.5 Create Factories

- **FamilyFactory** — generates name + slug using `fake()->unique()->word()`
- **ClimateFactory** — generates name + slug from curated pool `['Tropical', 'Temperate', 'Arid', 'Mediterranean', 'Continental', 'Alpine']`
- **FloweringSeasonFactory** — generates name + slug from curated pool `['Spring', 'Summer', 'Autumn', 'Winter']`
- **FlowerFactory** — generates all flower fields + attaches to a random family + optionally attaches climates/seasons with `$this->faker->randomElements()`

**Acceptance tests:**
- `it('creates a valid flower using factory')` — factory creates a persisted flower with all relationships

### 1.6 Create DatabaseSeeder

**Curated flowers** (8 entries):

| Common Name | Scientific Name | Family | Climates | Seasons |
|---|---|---|---|---|
| Rose | Rosa × hybrida | Rosaceae | Temperate | Spring, Summer |
| Sunflower | Helianthus annuus | Asteraceae | Temperate | Summer, Autumn |
| Lavender | Lavandula angustifolia | Lamiaceae | Mediterranean | Summer |
| Tulip | Tulipa × hybrida | Liliaceae | Temperate, Continental | Spring |
| Daisy | Bellis perennis | Asteraceae | Temperate, Alpine | Spring, Summer |
| Orchid | Orchidaceae | Orchidaceae | Tropical | All-year |
| Cherry Blossom | Prunus serrulata | Rosaceae | Temperate | Spring |
| Hydrangea | Hydrangea macrophylla | Hydrangeaceae | Temperate | Summer, Autumn |

Each flower includes realistic `hero_image`, `images` (2–3 Wikimedia URLs per flower), `description`, `interesting_facts`, `care_instructions`, and `lifespan`.

Seed in order: families → climates → flowering_seasons → flowers (and attach relationships).

**Acceptance tests:**
- `it('seeds the database with expected flowers')` — assert `Flower::count()` returns 8
- `it('seeds families correctly')` — assert expected families exist
- `it('seeds climates correctly')`
- `it('seeds flowering seasons correctly')`
- `it('attaches correct relationships via seeder')` — assert a rose belongs to Rosaceae family, temperate climate, spring + summer seasons

---

## ⬜ Phase 2: Layout & Design System

### 2.1 Configure Tailwind Color Palette

In `resources/css/app.css`, define the custom palette using Tailwind v4's `@theme` directive:

```css
@theme {
  --color-forest-900: #1B4332;
  --color-forest-700: #2D6A4F;
  --color-forest-500: #40916C;
  --color-amber-400: #D4A373;
  --color-cream: #FAF9F6;
  --color-text-primary: #1A1A2E;
  --color-text-muted: #6B7280;
  --color-border: #E5E7EB;
  --color-success: #40916C;
  --color-error: #D62828;
  --color-warning: #E9C46A;
}
```

### 2.2 Create Master Layout

File: `resources/views/layouts/app.blade.php`

Include:
- `<!DOCTYPE html>` with `lang="en"`
- Meta viewport, charset, CSRF token
- `@vite(['resources/css/app.css', 'resources/js/app.js'])`
- `@fonts` (Instrument Sans from Bunny CDN — already configured)
- Skip-to-content link (`#main-content`)
- Navigation bar:
  - Logo/site name ("BloomBase") linking to `route('home')`
  - "Browse Flowers" linking to `route('flowers.index')`
  - Accessible hamburger menu on mobile
- `main#main-content` slot with `{{ $slot }}`
- Footer with copyright
- Tailwind classes: Forest Green (`bg-forest-700`) nav, Cream (`bg-cream`) page background, Dark text, responsive

### 2.3 Create Welcome → Home Page

Replace `resources/views/welcome.blade.php` with a proper home page extending `layouts.app`.

**Acceptance tests:**
- `it('loads the homepage')` — `get(route('home'))` → assertOk
- `it('shows the site name in the nav')` — assertSee('BloomBase')
- `it('has a skip-to-content link')` — assertSee('Skip to content')
- `it('has navigation links')` — assertSee('Browse Flowers')

---

## ⬜ Phase 3: SearchFlowers Livewire Component

### 3.1 Create SearchFlowers Component

A Livewire component that provides:

**Properties:**
- `public string $search = ''` — bound to search input, syncs with `?q=` query param via `protected $queryString = ['search' => ['except' => '']]`
- `public ?int $familyId = null` — scope to a specific family (nullable, passed from category pages)
- `public ?int $climateId = null` — scope to a specific climate
- `public ?int $seasonId = null` — scope to a specific season

**Query logic:**
```php
Flower::query()
    ->when($this->familyId, fn($q) => $q->where('family_id', $this->familyId))
    ->when($this->climateId, fn($q) => $q->whereRelation('climates', 'id', $this->climateId))
    ->when($this->seasonId, fn($q) => $q->whereRelation('floweringSeasons', 'id', $this->seasonId))
    ->where(function ($q) {
        $q->where('common_name', 'like', '%'.$this->search.'%')
          ->orWhere('scientific_name', 'like', '%'.$this->search.'%');
    })
    ->paginate(12)
```

**Reactivity:**
- `wire:model.live.debounce.300ms` on search input
- `withQueryString()` on pagination links

**View:**
- Search input at top
- Card grid (3 columns desktop, 2 tablet, 1 mobile)
- Each card: `hero_image`, `common_name`, `scientific_name` (italic), family badge
- Pagination links
- Empty state: "No flowers found matching your search."

**Acceptance tests:**
- `it('filters flowers by common name')` — search "rose" → sees Rose, not Sunflower
- `it('filters flowers by scientific name')` — search "Helianthus" → sees Sunflower
- `it('supports partial matches')` — search "ros" → sees Rose and Cherry Blossom
- `it('shows all flowers when search is empty')` — count matches total flowers
- `it('paginates results')` — with 12 per page and more than 12 flowers, shows pagination
- `it('scopes by family')` — passing `familyId` returns only flowers in that family
- `it('scopes by climate')` — passing `climateId` returns only flowers in that climate
- `it('scopes by season')` — passing `seasonId` returns only flowers in that season
- `it('shows empty state when no results')` — search "zzzznotfound" → sees "No flowers found"
- `it('syncs search with query string')` — visit `/flowers?q=rose` → search input shows "rose"

---

## ⬜ Phase 4: Public Pages

### 4.1 Home Page — Controller + View

**Controller:** `App\Http\Controllers\HomeController`
- Single `__invoke()` method
- Returns `view('home')`

**View:** `resources/views/home.blade.php`
- Hero section with tagline: "Discover the world of flowers"
- Embedded search bar (a form redirecting to `/flowers?q=...`)
- "Featured flowers" section — show 4 random flowers as cards
- "Browse by category" section — links to family/climate/season pages

**Route:** `GET /` — named `home` (already exists, update view reference)

**Acceptance tests:**
- `it('loads the homepage successfully')`
- `it('displays featured flowers')` — assertSee flower common names
- `it('displays category links')` — assertSee family/climate/season names
- `it('has a working search bar')` — assert the search input exists

### 4.2 Browse Flowers Page — Controller + View

**Controller:** `App\Http\Controllers\FlowerController`
- Single `__invoke()` method
- Returns `view('flowers.index')` with embedded `SearchFlowers` Livewire component

**View:** `resources/views/flowers/index.blade.php`
- Page title: "All Flowers"
- Embeds `<livewire:search-flowers />`

**Route:** `GET /flowers` — named `flowers.index`

**Acceptance tests:**
- `it('loads the browse page')`
- `it('shows the search component')` — assertSee search input
- `it('lists flowers in a grid')` — assertSee flower names
- `it('search filters results')` — go to `/flowers?q=rose` → sees Rose, not Sunflower

### 4.3 Flower Detail Page — Controller + View

**Controller:** `App\Http\Controllers\FlowerDetailController`
- Implicit route model binding: `public function __invoke(Flower $flower)`
- Returns `view('flowers.show', compact('flower'))`

**View:** `resources/views/flowers/show.blade.php`
Structure:
```
Hero section:       hero_image (large, responsive)
Header section:     common_name (h1), scientific_name (italic, below), family badge
Attributes bar:     lifespan, climate tags, season tags — horizontal row
Description:        prose block
Gallery:            grid of remaining images from `images` JSON column
Interesting facts:  bulleted list or highlight cards
Care instructions:  formatted prose section
```

**Route:** `GET /flowers/{flower:slug}` — named `flowers.show`

**Acceptance tests:**
- `it('loads a flower detail page')` — get route with flower slug → assertOk
- `it('shows flower common name')`
- `it('shows flower scientific name')`
- `it('shows hero image')` — assertSee the hero_image URL
- `it('shows description')`
- `it('shows interesting facts')`
- `it('shows care instructions')`
- `it('shows family name')`
- `it('shows gallery images')` — assertSee gallery image URLs
- `it('shows climate tags')`
- `it('shows season tags')`
- `it('shows lifespan')`
- `it('returns 404 for unknown flower')` — get `/flowers/nonexistent` → assertNotFound

### 4.4 Category Pages — Controllers + Views

**Family Page — `App\Http\Controllers\FamilyController`**
- `__invoke(Family $family)` — returns view with `SearchFlowers` scoped to `familyId`
- Route: `GET /families/{family:slug}` — named `families.show`
- View: `resources/views/families/show.blade.php` — header with family name + description, embedded `<livewire:search-flowers :family-id="$family->id" />`
- Empty state: "No flowers in this family yet."

**Climate Page — `App\Http\Controllers\ClimateController`**
- Same pattern, scoped to `climateId`
- Route: `GET /climates/{climate:slug}` — named `climates.show`
- View: `resources/views/climates/show.blade.php`

**Season Page — `App\Http\Controllers\FloweringSeasonController`**
- Same pattern, scoped to `seasonId`
- Route: `GET /seasons/{floweringSeason:slug}` — named `seasons.show`
- View: `resources/views/seasons/show.blade.php`

**Acceptance tests (repeat for family, climate, season):**
- `it('loads the category page and shows filtered flowers')`
- `it('shows the category name in the header')`
- `it('further filters via search within category')` — add `?q=...` on category page
- `it('shows empty state when category has no flowers')`

### 4.5 Register All Routes

In `routes/web.php`:

```php
use App\Http\Controllers\ClimateController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\FlowerController;
use App\Http\Controllers\FlowerDetailController;
use App\Http\Controllers\FloweringSeasonController;
use App\Http\Controllers\HomeController;

Route::get('/', HomeController::class)->name('home');
Route::get('/flowers', FlowerController::class)->name('flowers.index');
Route::get('/flowers/{flower:slug}', FlowerDetailController::class)->name('flowers.show');
Route::get('/families/{family:slug}', FamilyController::class)->name('families.show');
Route::get('/climates/{climate:slug}', ClimateController::class)->name('climates.show');
Route::get('/seasons/{floweringSeason:slug}', FloweringSeasonController::class)->name('flowering-seasons.show');
```

**Acceptance tests:**
- `it('registers all public routes')` — assert each named route resolves correctly

---

## ⬜ Phase 5: Design Polish & Responsiveness

### 5.1 Responsive Review
- Test browse grid at mobile (1 col), tablet (2 col), desktop (3–4 col)
- Test nav at mobile (hamburger toggle)
- Test flower detail hero image scales properly
- Test touch targets are at least 44×44px
- Test no horizontal overflow on any page

### 5.2 Accessibility Review
- All images have `alt` text (hero_image's alt derived from flower name; gallery images use the `alt` field from JSON)
- Semantic HTML: `nav`, `main`, `article`, `section`, `h1`–`h6` headings
- Color contrast ratios meet WCAG AA (4.5:1 normal text, 3:1 large text)
- Focus styles visible on all interactive elements
- Skip-to-content link is first focusable element
- Search input has associated `<label>` or `aria-label`
- Card grid uses `role="list"` and `role="listitem"` where appropriate

### 5.3 Performance
- Pagination prevents loading all flowers at once (already handled by Livewire `paginate()`)
- Images load with `loading="lazy"` on gallery images
- Debounced search prevents excessive queries (300ms)

---

## ⬜ Phase 6: Admin Panel (Future)

*Deferred — install Filament PHP and build admin resources for Flowers, Families, Climates, and Flowering Seasons.*

---

## Dependency Graph

```
Phase 0 (scaffolding — done)
    │
    ▼
Phase 1 (DB foundation — models, migrations, factories, seeders)
    │
    ▼
Phase 2 (layout & design system)
    │
    ▼
Phase 3 (SearchFlowers Livewire component)
    │
    ▼
Phase 4 (public pages — home, browse, detail, categories)
    │
    ├──► Phase 5 (polish, responsive, accessibility)
    │
    └──► Phase 6 (admin panel — future, deferred)
```

---

## Formatting Requirement for AI Agents

After implementing any step in this plan, run the following command and fix any issues it flags before moving to the next step:

```bash
composer format
```

This runs `vendor/bin/pint --format agent` (via the `format` script defined in `composer.json`), which automatically fixes code style according to the project's Pint configuration. If it reports any issues it cannot fix, address them manually before proceeding.

---

## Running Tests

```bash
php artisan test --compact
```

Run a specific test file:

```bash
php artisan test --compact --filter=FlowerTest
```
