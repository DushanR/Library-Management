// Function to set today's date as the default value
function setTodayDate() {
    const dateInput = document.getElementById('returnDateReturned');
    const today = new Date().toISOString().split('T')[0];
    dateInput.value = today;
}

// Set today's date when the page loads
window.onload = setTodayDate;

document.getElementById('searchmemberIDRTN').addEventListener('click', function () {
    const userID = document.getElementById('returnUserID').value;

    if (!userID) {
        alert("Please enter a Member ID");
        return;
    }

    // Fetch member details and borrowed books
    fetch(`returnbook.php?userID=${userID}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Populate the Member Name
                const memberName = data.data[0].MemberName;
                document.getElementById('returnUserName').value = memberName;

                // Populate the Borrowed Books Dropdown
                const bookDropdown = document.getElementById('returnbooks');
                bookDropdown.innerHTML = '<option value="">Select Book</option>'; // Clear previous options

                data.data.forEach(book => {
                    const option = document.createElement('option');
                    option.value = JSON.stringify({ BookID: book.BookID, DueDate: book.DueDate });
                    option.textContent = book.BookTitle;
                    bookDropdown.appendChild(option);
                });
            } else {
                alert(data.message);
                document.getElementById('returnUserName').value = ''; // Clear the Member Name field
                document.getElementById('returnbooks').innerHTML = '<option value="">Select Book</option>'; // Clear the dropdown
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
});

// Populate the Due Date and Calculate Fine
document.getElementById('returnbooks').addEventListener('change', function () {
    const selectedBook = this.value ? JSON.parse(this.value) : null;
    const dueDateInput = document.getElementById('returnDueDate');
    const fineInput = document.getElementById('returnFine');

    if (selectedBook && selectedBook.DueDate) {
        // Populate the Due Date
        dueDateInput.value = selectedBook.DueDate;

        // Calculate the Fine if Due Date is Missed
        const today = new Date();
        const dueDate = new Date(selectedBook.DueDate);
        const diffTime = today - dueDate;

        if (diffTime > 0) {
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); // Convert to days
            const fine = diffDays * 10; // Rs. 10 per day
            fineInput.value = fine;
        } else {
            fineInput.value = 0; // No fine
        }
    } else {
        dueDateInput.value = ''; // Clear Due Date if no book is selected
        fineInput.value = ''; // Clear Fine
    }
});

document.querySelector('form').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent default form submission

    const formData = new FormData(this);

    fetch('returnbook.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);

                // Clear form inputs
                document.getElementById('returnUserID').value = '';
                document.getElementById('returnUserName').value = '';
                document.getElementById('returnbooks').innerHTML = '<option value="">Select Book</option>';
                document.getElementById('returnDueDate').value = '';
                document.getElementById('returnFine').value = '';
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
});
