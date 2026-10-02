# Rally Supply TA2 expanded feature map

## Core assessment flow

`GET /customers` and `GET /users` → staff filter → account controller →
CustomerModel or UserModel → required TA2 table → escaped roster view.
Search and sort are GET parameters. Controllers accept only known sort columns;
Query Builder escapes the search value. Empty tables and unmatched searches
have different messages.

## Product and order flow

`GET /shop` → Catalog → ProductModel and ProductVariantModel → product cards.
The product option select uses a variant ID, which has its own stock quantity.
`POST /cart/add` stores that variant ID and quantity in the session. Updating
the cart rechecks current stock.

`GET /checkout` is open to a shopper with a nonempty cart. `POST /checkout`
validates the shopper's name and email, then runs one database transaction.
Each variant stock reduction has a `stock_qty >= quantity` condition. The
same transaction writes a pending web order and item snapshots. A failed stock
update rolls everything back; a successful one clears the cart and redirects
to a confirmation URL with a random, unguessable token. No payment is taken.

`GET /orders` is staff-only. Staff can mark a pending order fulfilled or cancel
it. Cancellation restores the reserved variant quantities in the same
transaction as the status change. In-person sales remain separate at
`GET /pos/checkout` and use the existing sales and sale_items tables.

## Staff access

The required `users` table remains exactly as specified by TA2. A separate
`staff_credentials` table stores password hashes against `users.id`. The local
launcher creates one random first password and writes it to a private
`.local/staff-login.txt` file. The `staff:password` Spark command can create or
reset credentials for any existing user.

The StaffOnly route filter protects account rosters, inventory, website order
management, in-person sale checkout and sales history. Shopper checkout does
not use the staff filter. POST forms have CSRF fields. Sign-in rotates the session ID. This is a
local teaching login and not a public deployment review.

## User interface

The existing Rally Supply logo, palette, illustrations and WebP product photos
are retained. Body text and touch targets are larger, products use a three
column desktop grid, and responsive pages adjust to smaller screens. Product
specifications help players compare gear. Focus indicators and reduced-motion
rules help keyboard and motion-sensitive users. Customer and user dates display
in a readable format while the database keeps exact DATETIME values.
