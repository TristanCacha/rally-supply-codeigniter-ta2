# TA2 assessment requirements

Source: the instructor outline supplied by the student in this conversation.

PO C: Design, implement and evaluate computer-based systems or applications
to meet desired needs and requirements.

CLO 2: Apply advanced web development principles in creating, debugging,
validating and securing database-backed web applications.

The exercise extends TFA1 with a local MySQL connection, customers and users
tables using the supplied schema, at least five sample records per table,
CustomerModel and UserModel, controller queries instead of account arrays,
and views that display the retrieved records in the existing roster layout.

Module 2 reference: IT0049 CodeIgniter Data Layer, pages 7-13 (tables and
connection settings), 19-27 (models and reads), and 28-31 (controller and view).

The authoritative column definitions are reproduced in database/schema.sql.
The users table does not include passwords or roles. The customers table does
not include loyalty points or a status. TFA1's decorative statuses and role
column therefore become creation dates and account IDs in the TA2 views.

Technical note: literal PHP sample arrays in source files do not disappear
when the server restarts; they are recreated on each request. The benefit of
TA2 is independently stored records that can be changed without editing PHP.
