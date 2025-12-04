<?php
/**
 * Library Manager REST API Class
 *
 * @package Library_Manager
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'LM_Library_Rest_API' ) ) {
    class LM_Library_Rest_API {

        public function __construct() {
            add_action( 'rest_api_init', [ $this, 'register_routes' ] );
        }

        /**
         * Register all REST API routes
         */
        public function register_routes() {

            register_rest_route( 'library/v1', '/books', [
                [
                    'methods'  => WP_REST_Server::READABLE,
                    'callback' => [ $this, 'get_books' ],
                    'permission_callback' => '__return_true'
                ],
                [
                    'methods'  => WP_REST_Server::CREATABLE,
                    'callback' => [ $this, 'create_book' ],
                    'permission_callback' => [ $this, 'verify_permissions' ]
                ]
            ] );

            register_rest_route( 'library/v1', '/books/(?P<id>\d+)', [
                [
                    'methods'  => WP_REST_Server::READABLE,
                    'callback' => [ $this, 'get_book' ],
                    'permission_callback' => '__return_true'
                ],
                [
                    'methods'  => WP_REST_Server::EDITABLE,
                    'callback' => [ $this, 'update_book' ],
                    'permission_callback' => [ $this, 'verify_permissions' ]
                ],
                [
                    'methods'  => WP_REST_Server::DELETABLE,
                    'callback' => [ $this, 'delete_book' ],
                    'permission_callback' => [ $this, 'verify_permissions' ]
                ],
            ] );
        }

        /**
         * Permission + Nonce Verification for POST/PUT/DELETE
         */
        public function verify_permissions( $request ) {

            // Nonce Check
            $nonce = $request->get_header( 'X-WP-Nonce' );

            if ( ! wp_verify_nonce( $nonce, 'wp_rest' ) ) {
                return new WP_Error(
                    'invalid_nonce',
                    'Invalid or missing nonce.',
                    [ 'status' => 403 ]
                );
            }

            // Capability Check
            if ( ! current_user_can( 'edit_posts' ) ) {
                return new WP_Error(
                    'forbidden',
                    'You do not have permission to perform this action.',
                    [ 'status' => 403 ]
                );
            }

            return true;
        }

        /**
         * GET /books — List with optional filters
         */
        public function get_books( WP_REST_Request $request ) {
            global $wpdb;

            $table = $wpdb->prefix . 'library_books';

            $status = sanitize_text_field( $request->get_param( 'status' ) );
            $author = sanitize_text_field( $request->get_param( 'author' ) );
            $year   = absint( $request->get_param( 'year' ) );

            $where  = "WHERE 1=1";
            $params = [];

            if ( ! empty( $status ) ) {
                $where .= " AND status = %s";
                $params[] = $status;
            }

            if ( ! empty( $author ) ) {
                $where .= " AND author = %s";
                $params[] = $author;
            }

            if ( ! empty( $year ) ) {
                $where .= " AND publication_year = %d";
                $params[] = $year;
            }

            $sql = "SELECT * FROM $table $where ORDER BY id DESC";

            if ( $params ) {
                $sql = $wpdb->prepare( $sql, $params );
            }

            $results = $wpdb->get_results( $sql );

            return rest_ensure_response( $results );
        }

        /**
         * GET /books/{id}
         */
        public function get_book( WP_REST_Request $request ) {
            global $wpdb;

            $table = $wpdb->prefix . 'library_books';
            $id    = absint( $request['id'] );

            $book = $wpdb->get_row(
                $wpdb->prepare( "SELECT * FROM $table WHERE id = %d", $id )
            );

            if ( ! $book ) {
                return new WP_Error( 'not_found', 'Book not found.', [ 'status' => 404 ] );
            }

            return rest_ensure_response( $book );
        }

        /**
         * POST /books
         */
        public function create_book( WP_REST_Request $request ) {
            global $wpdb;
            $table = $wpdb->prefix . 'library_books';

            $data = $this->sanitize_book_data( $request );

            // Validation
            $validation = $this->validate_book_data( $data );
            if ( is_wp_error( $validation ) ) {
                return $validation;
            }

            $insert = $wpdb->insert(
                $table,
                $data,
                [ '%s', '%s', '%s', '%d', '%s' ]
            );

            if ( ! $insert ) {
                return new WP_Error( 'db_error', 'Failed to create book.', [ 'status' => 500 ] );
            }

            $data['id'] = $wpdb->insert_id;

            return new WP_REST_Response( $data, 201 );
        }

        /**
         * PUT /books/{id}
         */
        public function update_book( WP_REST_Request $request ) {
            global $wpdb;
            $table = $wpdb->prefix . 'library_books';

            $id = absint( $request['id'] );

            $exists = $wpdb->get_var(
                $wpdb->prepare( "SELECT id FROM $table WHERE id = %d", $id )
            );

            if ( ! $exists ) {
                return new WP_Error( 'not_found', 'Book not found.', [ 'status' => 404 ] );
            }

            $data = $this->sanitize_book_data( $request );

            $validation = $this->validate_book_data( $data );
            if ( is_wp_error( $validation ) ) {
                return $validation;
            }

            $updated = $wpdb->update(
                $table,
                $data,
                [ 'id' => $id ],
                [ '%s', '%s', '%s', '%d', '%s' ],
                [ '%d' ]
            );

            if ( $updated === false ) {
                return new WP_Error( 'db_error', 'Failed to update book.', [ 'status' => 500 ] );
            }

            return rest_ensure_response( array_merge( [ 'id' => $id ], $data ) );
        }

        /**
         * DELETE /books/{id}
         */
        public function delete_book( WP_REST_Request $request ) {
            global $wpdb;
            $table = $wpdb->prefix . 'library_books';

            $id = absint( $request['id'] );

            $deleted = $wpdb->delete(
                $table,
                [ 'id' => $id ],
                [ '%d' ]
            );

            if ( ! $deleted ) {
                return new WP_Error( 'delete_failed', 'Failed to delete book.', [ 'status' => 500 ] );
            }

            return new WP_REST_Response(
                [ 'message' => 'Book deleted successfully.' ],
                200
            );
        }

        /**
         * Sanitize data helper
         */
        private function sanitize_book_data( $request ) {
            return [
                'title'            => sanitize_text_field( $request['title'] ),
                'description'      => sanitize_textarea_field( $request['description'] ),
                'author'           => sanitize_text_field( $request['author'] ),
                'publication_year' => absint( $request['publication_year'] ),
                'status'           => sanitize_text_field( $request['status'] ),
            ];
        }

        /**
         * Validate book data
         */
        private function validate_book_data( $data ) {

            if ( empty( $data['title'] ) ) {
                return new WP_Error( 'missing_title', 'Title is required.', [ 'status' => 400 ] );
            }

            if ( ! empty( $data['publication_year'] ) && ! is_numeric( $data['publication_year'] ) ) {
                return new WP_Error( 'invalid_year', 'Publication year must be a number.', [ 'status' => 400 ] );
            }

            if ( ! in_array( $data['status'], [ 'available', 'borrowed', 'unavailable' ], true ) ) {
                return new WP_Error( 'invalid_status', 'Invalid book status.', [ 'status' => 400 ] );
            }

            return true;
        }
    }
}
new LM_Library_Rest_API();