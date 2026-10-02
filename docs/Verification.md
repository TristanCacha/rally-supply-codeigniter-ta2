# TA2 expanded verification record

Checked on October 2, 2026 using CodeIgniter 4.7.4, PHP 8.5.11 and the local
XAMPP MariaDB 10.4.32 instance through MySQLi.

- All 87 project PHP files passed `php -l` after the main feature build.
- The original five customers and five users remained in tables with the exact
  required TA2 columns.
- Nine products, 29 stocked variants and one private staff credential were
  installed without resetting the existing accounts.
- 34 direct PHP/database upgrade checks passed: customer and user search,
  sorting, category filtering, staff password verification, protected access,
  cart stock limits, inventory updates, completed sale records, sale item
  snapshots, stock deduction, insufficient stock rollback and preserved data.
- The checkout verification used an outer database transaction and rolled it
  back. The original stock quantity and sale count were unchanged afterward.
- The separate PDF guide was rendered and checked page by page. Word layout
  could not be rendered on this machine, so visual Word layout remains unverified.
- Later customer checkout and website order management were added after these
  checks; those earlier 34 checks do not cover the new flow.

## Manual browser review

Automated browser access was denied during preparation. No browser screenshots
or visual browser pass are claimed. Run `Start-TA2.cmd`, open
`http://localhost:8082/`, and check these flows:

1. Browse Shop, category filters, product images and option stock.
2. Add a product, change its Cart quantity and remove it.
3. Sign in using `.local/staff-login.txt`; verify Customers and Users can be
   searched and sorted, and Inventory can save a stock quantity.
4. Sign out, add an item, and place a website order without staff sign-in.
   Check the private confirmation and reduced stock. No payment is taken.
5. Sign in, open Orders, then mark that order fulfilled or cancel a separate
   sample order and confirm its stock is released.
6. Record an in-person sale while signed in and check the Sales list and receipt.
7. Sign out and confirm only staff pages redirect to Staff sign in.
8. Narrow the window and use the keyboard to check navigation, tables and forms.
