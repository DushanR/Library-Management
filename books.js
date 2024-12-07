let books = [];

// Fetch book data from PHP script
fetch('fetchBooks.php')  // Replace with your actual backend script path
    .then(response => response.json())
    .then(data => {
        books = data;
        displayBooks(books);
    })
    .catch(error => console.error('Error fetching book data:', error));

function displayBooks(booksToDisplay) {
    const tableBody = document.querySelector("#booktable tbody");
    const noResultsMessage = document.getElementById('noResultsMessage');
    tableBody.innerHTML = '';

    if (booksToDisplay.length === 0) {
        noResultsMessage.textContent = `No book or author available with the name '${document.getElementById('searchBookField').value}'`;
        noResultsMessage.style.display = 'block';
    } else {
        noResultsMessage.style.display = 'none';
        booksToDisplay.forEach(book => {
            const row = document.createElement("tr");

            // Book ID
            const bookidCell = document.createElement("td");
            bookidCell.textContent = book.book_id;
            row.appendChild(bookidCell);

            // Book Cover Image
            const imageCell = document.createElement("td");
            const img = document.createElement("img");
            img.src = `${book.coverImage}`;
            img.alt = book.title;
            img.style.width = "60px";  // Adjust as needed
            imageCell.appendChild(img);
            row.appendChild(imageCell);

            // Book Title
            const titleCell = document.createElement("td");
            titleCell.textContent = book.title;
            row.appendChild(titleCell);

            // Author
            const authorCell = document.createElement("td");
            authorCell.textContent = book.author;
            row.appendChild(authorCell);

            // Category
            const categoryCell = document.createElement("td");
            categoryCell.textContent = book.category;
            row.appendChild(categoryCell);

            // ISBN
            const isbnCell = document.createElement("td");
            isbnCell.textContent = book.isbn;
            row.appendChild(isbnCell);

            // Total Quantity
            const totalQtyCell = document.createElement("td");
            totalQtyCell.textContent = book.total_quantity;
            row.appendChild(totalQtyCell);

            // Total Quantity
            const availableQtyCell = document.createElement("td");
            availableQtyCell.textContent = book.available_quantity;
            row.appendChild(availableQtyCell);

            // Status
            const statusCell = document.createElement("td");
            if (book.available_quantity > 0) {
                statusCell.textContent = 'Available';
                statusCell.classList.add('status-available');
            } else {
                statusCell.textContent = 'Unavailable';
                statusCell.classList.add('status-unavailable');
            }
            row.appendChild(statusCell);

            // Append row to table body
            tableBody.appendChild(row);
        });
    }
}

function filterBooks() {
    const query = document.getElementById('searchBookField').value.toLowerCase();
    const filteredBooks = books.filter(book => 
        book.title.toLowerCase().includes(query) || 
        book.author.toLowerCase().includes(query)
    );
    displayBooks(filteredBooks);
}
