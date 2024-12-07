let users = [];

// Fetch user data from PHP script
fetch('fetchUsers.php')  // Replace with your actual backend script path
    .then(response => response.json())
    .then(data => {
        users = data;
        displayUsers(users);
    })
    .catch(error => console.error('Error fetching user data:', error));

function displayUsers(usersToDisplay) {
    const tableBody = document.querySelector("#userTable tbody");
    const noResultsMessage = document.getElementById('noResultsMessage');
    tableBody.innerHTML = '';

    if (usersToDisplay.length === 0) {
        noResultsMessage.textContent = `No member available with the name or NIC '${document.getElementById('searchMemberField').value}'`;
        noResultsMessage.style.display = 'block';
    } else {
        noResultsMessage.style.display = 'none';
        usersToDisplay.forEach(user => {
            const row = document.createElement("tr");

            // Member ID
            const idCell = document.createElement("td");
            idCell.textContent = user.id;
            row.appendChild(idCell);

            // Full Name
            const nameCell = document.createElement("td");
            nameCell.textContent = user.fullName;
            row.appendChild(nameCell);

            // NIC Number
            const nicCell = document.createElement("td");
            nicCell.textContent = user.nic;
            row.appendChild(nicCell);

            // Mobile
            const mobileCell = document.createElement("td");
            mobileCell.textContent = user.mobile;
            row.appendChild(mobileCell);

            // Email
            const emailCell = document.createElement("td");
            emailCell.textContent = user.email;
            row.appendChild(emailCell);

            // Actions (Edit and Deactivate buttons)
            const actionsCell = document.createElement("td");

            const editButton = document.createElement("button");
            editButton.textContent = "Edit";
            editButton.classList.add("btn", "btn-primary", "btn-sm", "me-2");
            editButton.addEventListener('click', () => openEditModal(user));
            actionsCell.appendChild(editButton);

            const deactivateButton = document.createElement("button");
            deactivateButton.textContent = "Deactivate";
            deactivateButton.classList.add("btn", "btn-danger", "btn-sm");
            deactivateButton.addEventListener('click', () => deactivateUser(user.id, row));  // Implement deactivateUser function as needed
            actionsCell.appendChild(deactivateButton);

            row.appendChild(actionsCell);

            // Append row to table body
            tableBody.appendChild(row);
        });
    }
}

function filterMembers() {
    const query = document.getElementById('searchMemberField').value.toLowerCase();
    const filteredUsers = users.filter(user => 
        user.fullName.toLowerCase().includes(query) || 
        user.nic.toLowerCase().includes(query)
    );
    displayUsers(filteredUsers);
}


// Function to open the edit modal and populate it with the user's data
function openEditModal(user) {
    document.getElementById('editMemberId').value = user.id;
    document.getElementById('editFullName').value = user.fullName;
    document.getElementById('editNIC').value = user.nic;
    document.getElementById('editMobile').value = user.mobile;
    document.getElementById('editEmail').value = user.email;
    
    const editModal = new bootstrap.Modal(document.getElementById('editMemberModal'));
    editModal.show();
}

// Function to save changes to the user's data
document.getElementById('editMemberForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const id = document.getElementById('editMemberId').value;
    const fullName = document.getElementById('editFullName').value;
    const nic = document.getElementById('editNIC').value;
    const mobile = document.getElementById('editMobile').value;
    const email = document.getElementById('editEmail').value;
    
    const updatedUser = { id, fullName, nic, mobile, email };
    
    fetch('updateUser.php', {  // Replace with the actual PHP script for updating user details
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(updatedUser),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Member details updated successfully');
            
            // Update the user in the local array
            const userIndex = users.findIndex(user => user.id === id);
            if (userIndex !== -1) {
                users[userIndex] = updatedUser;
                displayUsers(users); // Ensure displayUsers is defined and used to update the UI
            }
            
            // Close the modal
            const editModal = bootstrap.Modal.getInstance(document.getElementById('editMemberModal'));
            editModal.hide();
        } else {
            alert('Failed to update member details');
        }
    })
    .catch(error => console.error('Error updating member details:', error));
});

// Function to deactivate a user (delete from database)
function deactivateUser(userId, row) {
    if (confirm("Are you sure you want to deactivate this member?")) {
        // Send an AJAX request to deactivate the user
        fetch('deactivateUser.php', {  // Replace with the actual PHP script for deactivating a user
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: userId }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('User deactivated successfully');
                row.remove();  // Remove the row from the table
            } else {
                alert('Failed to deactivate user');
            }
        })
        .catch(error => console.error('Error deactivating user:', error));
    }
}