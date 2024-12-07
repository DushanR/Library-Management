// Function to set today's date as the default value
function setTodayDate() {
    const dateInput = document.getElementById('issueDateBorrowed');
    const today = new Date().toISOString().split('T')[0];
    dateInput.value = today;
}

// Set today's date when the page loads
window.onload = setTodayDate;


document.addEventListener('DOMContentLoaded', function () 
{
    // Search for Member Name using Member ID
    document.getElementById('searchUserID').addEventListener('click', function() {
        var userID = document.getElementById('issueUserID').value;
        var userIDError = document.getElementById('userIDError');

        // Clear any previous error message
        userIDError.textContent = '';

        if (userID) {
            // Make an AJAX request to search.php
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'searchUser.php?userID=' + userID, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Parse the response from PHP (assumes JSON format)
                    var response = JSON.parse(xhr.responseText);

                    if (response.success) {
                        // Populate the Member Name field with the fullname
                        document.getElementById('issueUserName').value = response.fullname;
                    } else {
                        // Handle the case where the userID is not found
                        userIDError.textContent = 'Member not found!';
                        document.getElementById('issueUserName').value = '';  // Clear Member Name field
                    }
                }
            };
            xhr.send();
        } else {
            userIDError.textContent = 'Please enter a valid Member ID';
        }
    });

    // Search for Book Name using Book ID
    document.getElementById('searchBookID').addEventListener('click', function() {
        var bookID = document.getElementById('issueBookID').value;
        var bookNameError = document.getElementById('bookIDError');

        // Clear any previous error message
        bookNameError.textContent = '';

        if (bookID) {
            // Make an AJAX request to searchBook.php
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'searchBook.php?bookID=' + bookID, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);

                    if (response.success) {
                        document.getElementById('issueBookName').value = response.title;
                    } else {
                        bookNameError.textContent = 'Book not found!';
                        document.getElementById('issueBookName').value = '';  // Clear Book Name
                    }
                }
            };
            xhr.send();
        } else {
            bookNameError.textContent = 'Please enter a valid Book ID';
        }
    });
});

