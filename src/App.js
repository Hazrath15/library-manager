import React, { useState, useEffect } from 'react';
import BookList from './Components/BookList';
import AddBookForm from './Components/AddBookForm';
import './index.scss';

const App = () => {
    const [books, setBooks] = useState([]);
    const [editingBook, setEditingBook] = useState(null);
    const [showForm, setShowForm] = useState(false); // control form visibility

    const fetchBooks = async () => {
        try {
            const res = await fetch(`${lmSettings.root}books`);
            const data = await res.json();
            setBooks(data);
        } catch (error) {
            console.error('Failed to fetch books', error);
        }
    };

    useEffect(() => {
        fetchBooks();
    }, []);

    const handleAddBookClick = () => {
        setEditingBook(null); // ensure it's not editing
        setShowForm(true);    // show form
    };

    const handleFormClose = () => {
        setShowForm(false);   // hide form
    };

    return (
        <div className="lm-dashboard-container">
            <header className="lm-dashboard-header">
                <h1>📚 Library Manager</h1>
                <p className="lm-dashboard-subtitle">Manage your library with ease.</p>
            </header>

            {/* Add Book Button */}
            {!showForm && (
                <button className="lm-btn lm-btn-primary lm-add-book-btn" onClick={handleAddBookClick}>
                    + Add Book
                </button>
            )}

            {/* Add/Edit Book Form */}
            {showForm && (
                <AddBookForm
                    onSuccess={() => { fetchBooks(); setShowForm(false); }}
                    editingBook={editingBook}
                    setEditingBook={setEditingBook}
                    onClose={handleFormClose} // pass close handler
                />
            )}

            {/* Book List */}
            <BookList books={books} fetchBooks={fetchBooks} setEditingBook={(book) => {
                setEditingBook(book);
                setShowForm(true); // open form in edit mode
            }} />
        </div>
    );
};

export default App;
