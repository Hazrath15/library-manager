import { useState } from 'react';
import BookViewModal from './BookViewModal';

const BookList = ({ books, fetchBooks, setEditingBook }) => {
    const [viewingBook, setViewingBook] = useState(null);

    const deleteBook = async (id) => {
        if (!confirm('Are you sure you want to delete this book?')) return;

        const res = await fetch(`${lmSettings.root}books/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': lmSettings.nonce,
            }
        });

        if (res.ok) fetchBooks();
        else alert('Failed to delete book.');
    };

    return (
        <div className="lm-table-card">
            <h3>Books List</h3>
            <table className="lm-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Year</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    {books.length ? books.map(book => (
                        <tr key={book.id}>
                            <td>{book.title}</td>
                            <td>{book.author}</td>
                            <td>{book.publication_year}</td>
                            <td>
                                <span className={`lm-status lm-status-${book.status}`}>
                                    {book.status.charAt(0).toUpperCase() + book.status.slice(1)}
                                </span>
                            </td>
                            <td>
                                <button className="lm-btn lm-btn-view" onClick={() => setViewingBook(book)}>View</button>
                                <button className="lm-btn lm-btn-edit" onClick={() => setEditingBook(book)}>Edit</button>
                                <button className="lm-btn lm-btn-delete" onClick={() => deleteBook(book.id)}>Delete</button>
                            </td>
                        </tr>
                    )) : (
                        <tr>
                            <td colSpan="5" style={{ textAlign: 'center', padding: '20px' }}>No books found.</td>
                        </tr>
                    )}
                </tbody>
            </table>

            {viewingBook && (
                <BookViewModal book={viewingBook} onClose={() => setViewingBook(null)} />
            )}
        </div>
    );
};

export default BookList;
