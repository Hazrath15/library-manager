const Pagination = ({ currentPage, totalPages, onPageChange }) => {

    if (totalPages <= 1) return null;

    const pages = [];
    for (let i = 1; i <= totalPages; i++) {
        pages.push(i);
    }

    return (
        <div className="lm-pagination">
            <button
                disabled={currentPage === 1}
                onClick={() => onPageChange(currentPage - 1)}
            >
                Prev
            </button>

            {pages.map((p) => (
                <button
                    key={p}
                    className={p === currentPage ? 'active' : ''}
                    onClick={() => onPageChange(p)}
                >
                    {p}
                </button>
            ))}

            <button
                disabled={currentPage === totalPages}
                onClick={() => onPageChange(currentPage + 1)}
            >
                Next
            </button>
        </div>
    );
};

export default Pagination;
