const loginForm = document.getElementById('login-form');
const errorMsg = document.getElementById('login-error');
const dashboard = document.getElementById('dashboard');
const loginContainer = document.getElementById('login-container');
const userName = document.getElementById('user-name');
const logoutBtn = document.getElementById("logout-btn");
const ticketForm = document.getElementById('create-ticket-form');
const formreset = document.getElementById('ticket-form');
const ticketsTableBody = document.getElementById('ticket_list');
const updateform=document.getElementById('editform')


loginForm.addEventListener('submit', function(e){
    e.preventDefault();
    const data = {
        email: document.getElementById('email').value,
        password: document.getElementById('password').value
    };
  
    fetch("api/login.php", {
        method: "POST",
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(data => { 
if (data.success) {
    Swal.fire({
        icon: 'success',
        title: 'Login Successful!',
        text: `Welcome, ${data.user.name}!`, 
        timer: 1500,
        showConfirmButton: false
    }).then(() => {
 
        loginContainer.style.display = "none";
        dashboard.style.display = "block";
        userName.textContent = data.user.name; 
        loadDashboardCounts();
    
    });
}
else {
            errorMsg.textContent = data.message;
        }
    })
    .catch(err => console.log(err));
});

logoutBtn.addEventListener("click", function () {
    Swal.fire({
        title: 'Are you sure you want to logout?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, logout',
        cancelButtonText: 'No, stay',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'Logged Out',
                text: 'You have been logged out successfully.',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
     
                dashboard.style.display = "none";
                loginContainer.style.display = "flex";

  
                document.getElementById("email").value = "";
                document.getElementById("password").value = "";
            });
        } 
    });
});


function loadDashboardCounts() {
    loadTickets();
    fetch("api/dashboard_counts.php")
    .then(res => res.json())        
    .then(data => {

        document.getElementById("total-tickets").textContent = data.total_tickets;
        document.getElementById("new_tickets").textContent = data.new_tickets;
        document.getElementById("resolved_tickets").textContent = data.resolved_tickets;
    }   )
    .catch(err => console.log(err));
}


ticketForm.addEventListener('submit', function(e) {
        e.preventDefault();
    const ticketData = {
        title: document.getElementById('ticket_title').value,
        description: document.getElementById('ticket_description').value,
        tickprio: document.getElementById('ticket_priority').value
    };
    fetch("api/create_ticket.php", {
        method: "POST",
        headers: { 'Content-Type': 'application/json' },

        body: JSON.stringify(ticketData)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Ticket Submitted',
                text: 'Your ticket has been submitted successfully!'
            }).then(() => {
                document.getElementById('ticket_title').value = "";
                document.getElementById('ticket_description').value = "";
                document.getElementById('ticket_priority').value = "";
               ticketForm.reset();
                loadDashboardCounts();
            });

          
            
        }
        else {
            Swal.fire({
                icon: 'error',
                title: 'Submission Failed',
                text: data.message
            });
        }
    })
});

let ticketsArray = []; 
function loadTickets() {
    fetch("api/get_tickets.php")
    .then(res => res.json())
    .then(data => {
        ticketsArray = data.data; 
        ticketsTableBody.innerHTML = "";

        data.data.forEach((ticket) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${ticket.id}</td>   
                <td>${ticket.title}</td>
                <td>${ticket.status}</td>
                <td>${ticket.priority}</td>
                <td>${ticket.created_at}</td>
                <td>
                <div class=" gap: 5px;">
                    <button type="button" class="btn btn-primary" onclick="viewTicket(${ticket.id})">View</button>
                     <button type="button" class="btn btn-success" onclick="editTicket(${ticket.id})">Update</button>
                    <button type="button" class="btn btn-secondary" onclick="addcomment(${ticket.id})">Add Comment</button>
                      <button type="button" class="btn btn-danger" onclick="delete_ticket(${ticket.id})">Delete</button>
                     <div/>
                </td>
            `;
            ticketsTableBody.appendChild(row);
        });
    })
    .catch(err => console.log(err));
}

function viewTicket(ticketId) {
    const ticket = ticketsArray.find(t => t.id === ticketId);
    if (!ticket) return;
    const viewContent = document.getElementById('view-ticket-content');
    viewContent.innerHTML = `
        <h5><strong>ID:</strong> ${ticket.id}</h5>
        <h5><strong>Title:</strong> ${ticket.title}</h5>
        <h5><strong>Description:</strong> ${ticket.description}</h5>
        <h5><strong>Priority:</strong> ${ticket.priority}</h5>
        <h5><strong>Status:</strong> ${ticket.status}</h5>
        <h5 id="comments-section"><strong>Comments:</strong> </h5>`;

        fetch(`api/get_ticket_details.php?ticket_id=${ticketId}`)
        .then(res => res.json())
        .then(data => {

            const commentsSection = document.getElementById('comments-section');
            commentsSection.innerHTML = '<h5>Comments:</h5>';

            const comments = data.comments || [];

            comments.forEach(comment => {
                const commentDiv = document.createElement('div');
                commentDiv.classList.add('mb-2', 'p-2', 'border', 'rounded');
                commentDiv.innerHTML = `
                    <p><small class="text-muted">${comment.comment}</small></p>
                `;
                commentsSection.appendChild(commentDiv);
            });
        })
        .catch(error => console.error(error));
        document.getElementById('viewdialog').showModal();
}

function editTicket(ticketId) {
    const ticket = ticketsArray.find(t => t.id === ticketId);
    if (!ticket) return;

    const viewContent = document.getElementById('edit_ticket');

    viewContent.innerHTML = `
        <input type="hidden" id="edit_id" class="form-control" value="${ticket.id}" ><br>

        <h5><strong>Title:</strong></h5>
        <input type="text" id="edit_title" class="form-control" value="${ticket.title}"><br>

        <h5><strong>Description:</strong></h5>
        <textarea id="edit_description" class="form-control">${ticket.description}</textarea><br>

        <h5><strong>Priority:</strong></h5>
        <select id="ticket_priority1" class="form-select" required>
            <option value="" disabled>Choose...</option>
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
        </select><br>

        <h5><strong>Status:</strong></h5>
            <select id="status" class="form-select" required>
            <option value="New">New</option>
            <option value="Resolved">Resolved</option>
        </select>
   

    `;
    document.getElementById("status").value = ticket.status;
    document.getElementById("ticket_priority1").value = ticket.priority;
    document.getElementById('editdialog').showModal();
}

updateform.addEventListener('submit', function(e) {
        e.preventDefault();
    const ticketId = document.getElementById('edit_id').value;
    const title = document.getElementById('edit_title').value;
    const description = document.getElementById('edit_description').value;
    const priority = document.getElementById('ticket_priority1').value;
    const status = document.getElementById('status').value;

    fetch('api/update_ticket.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            ticket_id: ticketId,
            title: title,
            description: description,
            priority: priority,
            status: status
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
                   document.getElementById('editdialog').close();
            Swal.fire({
                
                icon: 'success',
                title: 'Updated!',
                text: 'Ticket updated successfully!',
                timer: 2000,
                showConfirmButton: false
                
            }).then(() => {
         
                loadDashboardCounts();
                loadTickets();
                
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Update failed!',
            });
        }
    })
    .catch(error => console.error("Error updating:", error));
});

function addcomment(ticketId) {
    const dialog = document.getElementById('commentdialog');
    const commentsList = document.getElementById('comments-list');
    const textarea = document.getElementById('edit_comments');
    commentsList.innerHTML = '';
    textarea.value = '';
    dialog.dataset.ticketId = ticketId;

    dialog.showModal();
}

const commentform = document.getElementById('commentform');
commentform.addEventListener('submit', function(e) {
    e.preventDefault();

    const dialog = document.getElementById('commentdialog');
    const ticketId = dialog.dataset.ticketId; 
    const textarea = document.getElementById('edit_comments');


    const commentText = textarea.value.trim();
    if (!commentText) {
        Swal.fire({
            icon: 'warning',
            title: 'Oops...',
            text: 'Comment cannot be empty!'
        });
        return;
    }

    fetch('api/add_comment.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            ticket_id: ticketId,
            comment: commentText
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
                dialog.close();
            Swal.fire({
                icon: 'success',
                title: 'Comment added!',
                showConfirmButton: false,
                timer: 1500
            });

        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to add comment.'
            });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong while adding comment.'
        });
    });
});

function delete_ticket(ticketId) {
    Swal.fire({
        title: 'Are you sure you want to delete this ticket?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, keep it',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('api/delete_ticket.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ ticket_id: ticketId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: 'The ticket has been deleted.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        loadDashboardCounts();
                        loadTickets();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'Failed to delete the ticket.'
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Something went wrong while deleting the ticket.'
                });
            });
        }
    });
}
