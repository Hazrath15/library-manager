const BookViewModal = ({ book, onClose }) => {
    return (
        <div className="lm-modal-overlay" onClick={onClose}>
            <div className="lm-modal-card" onClick={e => e.stopPropagation()}>
                <header className="lm-modal-header">
                    <h3>{book.title}</h3>
                    <button className="lm-modal-close" onClick={onClose}>×</button>
                </header>
                <div className="lm-modal-content">
                    <p><strong>Author:</strong> {book.author}</p>
                    <p><strong>Year:</strong> {book.publication_year}</p>
                    <p><strong>Status:</strong> {book.status.charAt(0).toUpperCase() + book.status.slice(1)}</p>
                    <p><strong>Description:</strong></p>
                    <p>{book.description || 'No description available.'}</p>
                </div>
            </div>
        </div>
    );
};

export default BookViewModal;
