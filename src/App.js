import { useState, useEffect } from 'react';
import BookList from './Components/BookList';
import AddBookForm from './Components/AddBookForm';
import Pagination from './Components/Pagination';
import './index.scss';

const App = () => {
    const [books, setBooks] = useState([]);
    const [editingBook, setEditingBook] = useState(null);
    const [showForm, setShowForm] = useState(false);

    // Filters
    const [filters, setFilters] = useState({
        search: '',
        author: '',
        year: '',
        status: ''
    });

    // Pagination
    const [page, setPage] = useState(1);
    const [perPage] = useState(5);
    const [totalPages, setTotalPages] = useState(1);

    /**
     * Fetch Books (with pagination + filters)
     */
    const fetchBooks = async () => {
        try {
            const params = new URLSearchParams({
                ...filters,
                page: page,
                per_page: perPage
            });

            const res = await fetch(`${lmSettings.root}books?${params}`);
            const data = await res.json();

            setBooks(data);

            // Pagination headers
            const totalPagesHeader = res.headers.get('X-WP-TotalPages');
            if (totalPagesHeader) {
                setTotalPages(parseInt(totalPagesHeader, 10));
            }

        } catch (error) {
            console.error('Failed to fetch books', error);
        }
    };

    useEffect(() => {
        fetchBooks();
    }, [filters, page]);

    /**
     * Add Book Button
     */
    const handleAddBookClick = () => {
        setEditingBook(null);
        setShowForm(true);
    };

    /**
     * Close Form
     */
    const handleFormClose = () => {
        setShowForm(false);
        setEditingBook(null);
    };

    /**
     * Filter Change Handler
     */
    const handleFilterChange = (e) => {
        const { name, value } = e.target;

        setFilters(prev => ({
            ...prev,
            [name]: value
        }));

        setPage(1); // reset page on filter change
    };

    return (
        <div className="lm-dashboard-container">
            <header className="lm-dashboard-header">
                <h1>📚 Library Manager</h1>
                <p className="lm-dashboard-subtitle">Manage your library with ease.</p>

                {!showForm && (
                    <button
                        className="lm-btn lm-btn-primary lm-add-book-btn"
                        onClick={handleAddBookClick}
                    >
                        + Add Book
                    </button>
                )}
            </header>

            {/* Add / Edit Form */}
            {showForm && (
                <AddBookForm
                    onSuccess={() => {
                        fetchBooks();
                        setShowForm(false);
                    }}
                    editingBook={editingBook}
                    setEditingBook={setEditingBook}
                    onClose={handleFormClose}
                />
            )}

            {/* Filters */}
            {!showForm && (
                <div className="lm-filters">
                    <input
                        type="text"
                        name="search"
                        placeholder="Search by title..."
                        value={filters.search}
                        onChange={handleFilterChange}
                    />

                    <input
                        type="text"
                        name="author"
                        placeholder="Filter by author..."
                        value={filters.author}
                        onChange={handleFilterChange}
                    />

                    <input
                        type="number"
                        name="year"
                        placeholder="Filter by year..."
                        value={filters.year}
                        onChange={handleFilterChange}
                    />

                    <select
                        name="status"
                        value={filters.status}
                        onChange={handleFilterChange}
                    >
                        <option value="">All Status</option>
                        <option value="available">Available</option>
                        <option value="borrowed">Borrowed</option>
                        <option value="unavailable">Unavailable</option>
                    </select>
                </div>
            )}

            {/* Book List */}
            <BookList
                books={books}
                fetchBooks={fetchBooks}
                setEditingBook={(book) => {
                    setEditingBook(book);
                    setShowForm(true);
                }}
            />

            {/* Pagination */}
            {!showForm && (
                <Pagination
                    currentPage={page}
                    totalPages={totalPages}
                    onPageChange={(p) => setPage(p)}
                />
            )}
        </div>
    );
};

export default App;
