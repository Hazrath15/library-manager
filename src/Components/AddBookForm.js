import { useEffect, useState } from "react";

const AddBookForm = ({ onSuccess, editingBook, setEditingBook, onClose }) => {
    const [form, setForm] = useState({
        title: '',
        author: '',
        description: '',
        publication_year: '',
        status: 'available'
    });

    useEffect(() => {
        if (editingBook) setForm(editingBook);
        else setForm({ title: '', author: '', description: '', publication_year: '', status: 'available' });
    }, [editingBook]);

    const handleChange = e => {
        const { name, value } = e.target;
        setForm({ ...form, [name]: value });
    };

    const handleSubmit = async e => {
        e.preventDefault();
        const method = editingBook ? 'PUT' : 'POST';
        const url = editingBook ? `${lmSettings.root}books/${editingBook.id}` : `${lmSettings.root}books`;

        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': lmSettings.nonce
            },
            body: JSON.stringify(form)
        });

        if (res.ok) {
            onSuccess();
            setEditingBook(null);
        } else {
            const err = await res.json();
            alert(err.message || 'Failed to save book.');
        }
    };

    return (
        <div className="lm-form-card">
            <h3>{editingBook ? 'Edit Book' : 'Add New Book'}</h3>
            <form onSubmit={handleSubmit}>
                <input name="title" placeholder="Title" value={form.title} onChange={handleChange} required />
                <input name="author" placeholder="Author" value={form.author} onChange={handleChange} required />
                <input name="publication_year" placeholder="Year" value={form.publication_year} onChange={handleChange} required />
                <textarea name="description" placeholder="Description" value={form.description} onChange={handleChange}></textarea>
                <select name="status" value={form.status} onChange={handleChange}>
                    <option value="available">Available</option>
                    <option value="borrowed">Borrowed</option>
                    <option value="unavailable">Unavailable</option>
                </select>
                <div className="lm-form-buttons">
                    <button type="submit" className="lm-btn lm-btn-primary">{editingBook ? 'Update Book' : 'Add Book'}</button>
                    {onClose && <button type="button" className="lm-btn lm-btn-secondary" onClick={onClose}>Cancel</button>}
                </div>
            </form>
        </div>
    );
};
export default AddBookForm;