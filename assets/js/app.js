const loginForm = document.getElementById('login-form');
const errorMsg = document.getElementById('login-error');
const dashboard = document.getElementById('dashboard');
const loginContainer = document.getElementById('login-container');
const userName = document.getElementById('user-name');
const logoutBtn = document.getElementById("logout-btn");
const ticketForm = document.getElementById('create-ticket-form');
const formreset = document.getElementById('ticket-form');
const ticketsTableBody = document.getElementById('ticket_list');

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
                loginError.textContent = "";
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
    .catch(err => console.log(err));
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
                    <button type="button" class="btn btn-primary" onclick="viewTicket(${ticket.id})">View</button>
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

