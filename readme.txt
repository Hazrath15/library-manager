=== Library Manager ===
Contributors: hazrathali
Tags: library, books, admin, rest api, react
Requires at least: 6.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Library Manager is a custom WordPress plugin that provides an admin dashboard for managing books using a custom database table, secure REST API endpoints, and a React single-page application. Developed for the Junior WordPress Plugin Developer Assignment (Dec 2025).

== Description ==

Library Manager creates a dedicated book management system inside the WordPress admin area. It uses a custom database table to store book records and exposes a structured REST API for CRUD operations. The admin interface is powered by a bundled React application and provides features such as pagination, filtering, searching, and inline editing.

=== Key Features ===

* Custom database table for storing books  
* React-based admin dashboard (no CDN scripts)  
* REST API with secure nonce-based authentication  
* Add, edit, and delete books  
* Book listing with pagination  
* Filters: author, year, status  
* Search by title  
* Data sanitization and validation  
* Capability checks and secure REST permissions  
* Clean uninstall process  

=== Admin Dashboard ===

Access the React dashboard at:

**WP Admin → Library Manager → Dashboard**

Includes:

* Book list  
* Add book form  
* Edit book form  
* Delete action (with confirmation)  
* Search and filtering  
* Pagination support  
* React SPA bundled via `@wordpress/scripts`  
* Nonce + REST base URL provided using `wp_localize_script`  

=== Installation ===

1. Upload the plugin folder to: `/wp-content/plugins/library-manager`
2. Activate the plugin through **Plugins → Installed Plugins**
3. The plugin automatically creates the required database table
4. Access the dashboard via **Library Manager → Dashboard**
5. To rebuild the React assets, run the build commands listed below

=== Database Table ===

On activation, the plugin creates:

`wp_library_books`

With columns:

* `id` (int, primary key)  
* `title`  
* `author`  
* `description`  
* `publication_year`  
* `status`  
* `created_at`
* `updated_at`

=== REST API Documentation ===

All endpoints are available under: `/wp-json/library-manager/v1/`

=== Get All Books ===

Method: GET  
Endpoint: `/wp-json/library-manager/v1/books`

Response example:

[
  {
    "id": 1,
    "title": "Book Title",
    "author": "Author Name",
    "description": "Book description",
    "publication_year": 2025,
    "status": "available",
    "created_at": "2025-12-05 10:00:00",
    "updated_at": "2025-12-05 10:00:00"
  }
]

---

=== Get Single Book ===

Method: GET  
Endpoint: `/wp-json/library-manager/v1/books/{id}`  

Parameters:

* id (required): ID of the book

Response example:

{
  "id": 1,
  "title": "Book Title",
  "author": "Author Name",
  "description": "Book description",
  "publication_year": 2025,
  "status": "available",
  "created_at": "2025-12-05 10:00:00",
  "updated_at": "2025-12-05 10:00:00"
}

---

=== Create Book ===

Method: POST  
Endpoint: `/wp-json/library-manager/v1/books`  

Request Body (JSON):

{
  "title": "New Book",
  "author": "Author Name",
  "description": "Book description",
  "publication_year": 2025,
  "status": "available"
}

Response example:

{
  "success": true,
  "id": 2
}

---

=== Update Book ===

Method: PUT  
Endpoint: `/wp-json/library-manager/v1/books/{id}`  

Parameters:

* id (required): ID of the book

Request Body (JSON):

{
  "title": "Updated Book Title",
  "author": "Updated Author",
  "description": "Updated description",
  "publication_year": 2025,
  "status": "checked_out"
}

Response example:

{
  "success": true
}

---

=== Delete Book ===

Method: DELETE  
Endpoint: `/wp-json/library-manager/v1/books/{id}`  

Parameters:

* id (required): ID of the book

Response example:

{
  "success": true
}

**Note:** All write operations (POST, PUT, DELETE) require a valid WordPress nonce for authentication.

=== Uninstall Behavior ===

When the plugin is deleted:

* The `wp_library_books` table is removed  
* Optional plugin options are deleted  

Handled via `uninstall.php`.

== Source Code ==

The full uncompressed development source code for this plugin is publicly available here:

**https://github.com/Hazrath15/library-manager**

This repository includes all development files:

* `src` directory (React components)
* SCSS files
* JS files
* Build configuration
* Uncompiled development assets
* Instructions for generating the production build contained in the plugin

== Build Instructions ==

Developers may rebuild the compiled React assets using:

1. `npm install`  
2. `npm run build`

**npm run build** generates the production-ready assets inside the `/admin/build` directory.  
**npm run start** runs the development environment with auto-reloading.

== Changelog ==

= 1.0.0 =
* Initial release  
* Custom DB table  
* CRUD REST API  
* React admin dashboard  
* Pagination + filters  
* Secure nonce-based requests  
* Uninstall cleanup

== Upgrade Notice ==

= 1.0.0 =
First stable release.
