# Rally Supply POS · TA2 expanded edition

A CodeIgniter 4 application with the two exact TA2 account tables and an optional
working point of sale extension. Customer and user rosters read MySQL records
through Models. The shop also reads database products and stock for every option.
Shoppers can place an order without signing in. Staff can sign in to manage
pending orders, update inventory, record in-person sales, and inspect receipts.

## Start on this Windows machine

1. Open the `pickleball-pos-ta2` folder in VS Code, or extract the complete ZIP.
2. Double-click `Start-TA2.cmd`. Leave that terminal open.
3. Visit `http://localhost:8082/`. Use Shop, Cart and Checkout to place a
   pending order with your name and email; no staff sign-in is needed.
4. For staff pages, open `.local/staff-login.txt` in this project folder. Its
   randomly generated username and password are created on first launch.
5. Sign in from the website. Visit Customers, Users, Inventory, Orders and Sales.
6. To stop, press Ctrl+C in the launcher terminal. Run `Stop-Database.cmd` to
   stop the local database. Its records remain saved for the next launch.

The launcher uses the installed PHP and `C:\xampp\mysql` programs on this
machine. It enables the installed MySQLi extension for its own process, starts a
separate MariaDB instance on `127.0.0.1:3307`, and serves the site on port 8082.
MariaDB is MySQL-compatible. The launcher creates `.env` only if absent and
checks an existing `.env` before starting the website. An occupied database
port is checked against this project's data directory before anything is
imported. Use one copy on those ports at a time.

The ZIP includes Composer dependencies. A future Git clone will need
`composer install` with PHP 8.2+, intl, mbstring and mysqli.

## What to try

1. Browse the nine products. Category links filter the database catalog.
2. Pick a stocked size or color, enter a quantity, and select **Add to cart**.
3. Update or remove cart items. Cart quantities cannot exceed current stock.
4. Choose **Continue to checkout** as a shopper. Enter a name and email, then
   place a pending order. This reserves stock, clears the cart and opens a
   private confirmation link. No payment or shipping is processed.
5. Sign in as staff to review Orders. Mark an order fulfilled after handling it,
   or cancel it to release its reserved stock. Staff can also record a separate
   in-person sale from Cart.
6. Use Inventory to adjust stock. Search and sort the Customers and Users pages.

If stock changes before order submission, the entire order transaction is rolled
back and the cart remains available. Review the stock in Cart and try again.
All prices are stored as integer centavos to avoid rounding errors.

## Database design

The assessment tables are defined in `database/schema.sql` exactly as supplied:

| Table | Fields |
| --- | --- |
| `customers` | `id`, `full_name`, `email`, optional `phone`, `created_at` |
| `users` | `id`, unique `username`, `full_name`, `created_at` |

`database/sample-data.sql` provides five fictional rows in each. The launcher
imports these only when the database does not yet exist. It never resets them.

The optional extension lives in separate files:

| Table | Purpose |
| --- | --- |
| `products` | Product details and base price |
| `product_variants` | Sizes or colors and stock quantity |
| `staff_credentials` | Password hashes linked to `users.id` |
| `sales` | Staff member, optional customer, total and time |
| `sale_items` | Item name, option, price and quantity captured at sale time |
| `web_orders` | Shopper contact, private confirmation token, status and total |
| `web_order_items` | Product and price snapshots for website orders |

`database/commerce-schema.sql` creates these tables without altering the two
TA2 tables. `database/commerce-seed.sql` supplies nine fictional products and
29 variants. Repeated launcher runs preserve stock changes, website orders,
completed sales, account records and credentials. The first login is created only when no staff
credential exists; its password is never stored in the repository or ZIP.

## Manual database setup

Use these steps if you prefer an existing XAMPP or MySQL service on port 3306.
Do not use `Start-TA2.cmd` with that manual `.env`; it manages the separate
port-3307 database.

1. Start MySQL in XAMPP. Start Apache if using phpMyAdmin.
2. Import `database/schema.sql`, then `database/sample-data.sql` once.
3. Import `database/commerce-schema.sql`, then `database/commerce-seed.sql`.
4. Copy `.env.example` to `.env`, set the real MySQL hostname, account,
   password and port, and set the database name to `rally_supply_ta2`.
5. Run `composer install` if `vendor` is absent.
6. Run `php spark staff:password` to create a first staff login. Its credentials
   are saved in `.local/staff-login.txt`. To create or reset another staff
   login, run `php spark staff:password USERNAME` from the project folder. A
   second user's credentials get their own file in `.local`.
7. Run `php spark serve --host localhost --port 8082` and open the website.

On the current machine, use the launcher if PHP reports that MySQLi is
installed but disabled. For a manual PHP process, enable mysqli in its php.ini
or launch PHP with `-d extension=mysqli`.

## Where the code lives

- `app/Models/CustomerModel.php` and `UserModel.php` implement the TA2 mappings.
- `app/Controllers/Customers.php` and `Users.php` use Query Builder with
  `findAll()` for a name search and an allowed sort order.
- `app/Controllers/Catalog.php` reads products and variants from database Models.
- `app/Libraries/CartService.php` manages the session cart and stock checks.
- `app/Controllers/Sales.php` records a sale in one database transaction.
- `app/Controllers/Orders.php` records website orders and staff status changes
  with transactions; cancellation restores reserved stock.
- `app/Controllers/Inventory.php` updates the stock of one variant.
- `app/Controllers/Auth.php` and `app/Filters/StaffOnly.php` protect staff pages.
- `app/Views/` contains the page markup; `public/assets/css/store.css` controls
  the visual system and responsive layouts.
- `docs/Feature-Map.md` gives the page flows and `docs/Verification.md` records
  the observed checks and remaining manual visual review.

## Boundaries and future work

This is a teaching POS, with fictional records and no payment processor. A
website order is a pending request, not proof of payment or shipping. Staff
authentication is a local demonstration; review deployment, HTTPS, session
settings, permissions, backups and access logging before using real customer
data. Order contact details appear on protected staff routes and at the order
confirmation URL containing a random token. All write forms use
CodeIgniter CSRF protection, passwords are hashed, and session IDs rotate on
sign-in. The visual browser review still needs to be completed on the user's
machine because automated browser access was denied during preparation.

Keep `.env`, `.local`, `vendor` and runtime files out of a later GitHub
repository. The complete ZIP contains `vendor` for this local machine. A
GitHub repository stores code; running this PHP application publicly requires
a PHP-capable host. Preserve the original TA2 schema if submitting only the
instructor's database exercise; the POS extension is clearly separated in its
own SQL files and app components.
