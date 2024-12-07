// Real-time validation functions
function validateBookCover() {
    const bookCover = document.getElementById('bookCover');
    const coverImageError = document.getElementById('coverImageError');
    if (!bookCover.files.length) {
        bookCover.classList.add('invalid');
        coverImageError.textContent = 'Please upload a book cover image';
    } else {
        bookCover.classList.add('valid');
        coverImageError.textContent = '';
    }
}

function validateBookTitle() {
    const bookTitle = document.getElementById('bookTitle');
    const bookTitleError = document.getElementById('bookTitleError');
    if (!bookTitle.value.trim()) {
        bookTitle.classList.add('invalid');
        bookTitleError.textContent = 'Book title cannot be empty';
    } else {
        bookTitle.classList.add('valid');
        bookTitleError.textContent = '';
    }
}

function validateAuthor() {
    const author = document.getElementById('author');
    const authorError = document.getElementById('authorError');
    if (!author.value.trim()) {
        author.classList.add('invalid');
        authorError.textContent = 'Author name cannot be empty';
    } else if (/[^a-zA-Z\s]/.test(author.value)) {
        author.classList.add('invalid');
        authorError.textContent = 'Author name cannot contain numbers or special characters';
    } else {
        author.classList.add('valid');
        authorError.textContent = '';
    }
}

function validateCategory() {
    const category = document.getElementById('category');
    const categoryError = document.getElementById('categoryError');
    if (!category.value) {
        category.classList.add('invalid');
        categoryError.textContent = 'Please select a category';
    } else {
        category.classList.add('valid');
        categoryError.textContent = '';
    }
}

function validateISBN() {
    const isbn = document.getElementById('isbn');
    const isbnError = document.getElementById('isbnError');
    if (!isbn.value.trim()) {
        isbn.classList.add('invalid');
        isbnError.textContent = 'ISBN cannot be empty';
    } else if (/[^0-9Xx-]/.test(isbn.value)) {
        isbn.classList.add('invalid');
        isbnError.textContent = 'Invalid ISBN format';
    } else {
        isbn.classList.add('valid');
        isbnError.textContent = '';
    }
}

function validateQuantity() {
    const quantity = document.getElementById('quantity');
    const quantityError = document.getElementById('quantityError');
    if (!quantity.value.trim()) {
        quantity.classList.add('invalid');
        quantityError.textContent = 'Total quantity cannot be empty';
    } else if (parseInt(quantity.value) <= 0 || isNaN(parseInt(quantity.value))) {
        quantity.classList.add('invalid');
        quantityError.textContent = 'Quantity must be a positive number';
    } else {
        quantity.classList.add('valid');
        quantityError.textContent = '';
    }
}

// Attach input event listeners for real-time validation
document.getElementById('bookCover').addEventListener('change', validateBookCover);
document.getElementById('bookTitle').addEventListener('input', validateBookTitle);
document.getElementById('author').addEventListener('input', validateAuthor);
document.getElementById('category').addEventListener('change', validateCategory);
document.getElementById('isbn').addEventListener('input', validateISBN);
document.getElementById('quantity').addEventListener('input', validateQuantity);
