<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="assets/css/style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="icon" href="https://iconduck.com/icons/135271/ticket.png" type="image/png">

<title>Ticket System</title>
</head>
<body>

    <div class="align-items-center justify-content-center vh-100" id="login-container"  >
            <div class="card">
                <div class="card-body">
                    <h2>Login</h2>
                    <form id="login-form">
                        <input type="email" id="email" placeholder="Email" required><br><br>
                        <input type="password" id="password" placeholder="Password" required><br><br>
                        <button type="submit">Login</button>
                    </form>
                    <p id="login-error" style="color:red;"></p>
                </div>
            </div>
        </div>  

        <div  id="dashboard" style="display:none; " class="container py-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Welcome, <span id="user-name"></span>!</h2>
                <div class="display-flex gap-2">
                    <button onclick="document.getElementById('myDialog').showModal()" class="btn btn-success">Add Ticket</button>
                    <button id="logout-btn" class="btn btn-danger">Logout</button>
                </div>
            </div>

            <div class="row mt-4 g-4">
                <div class="col-md-4">
                    <div class="card summary-card text-center p-4">
                        <h5>Total Tickets</h5>
                        <p id="total-tickets" class="fs-3">0</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card summary-card text-center p-4">
                        <h5>New Tickets</h5>
                        <p id="new_tickets" class="fs-3">0</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card summary-card text-center p-4">
                        <h5>Resolved Tickets</h5>
                        <p id="resolved_tickets" class="fs-3">0</p>
                    </div>
                </div>
            </div>
            <div class="mt-5">
             <table class="table table-bordered table-hover bg-white shadow-sm table-striped text-center">
                    <thead class="table-dark">
						<tr>
							<th style="padding: 10px;">ID</th>
							<th style="padding: 10px;">Titile</th>
							<th style="padding: 10px;">Status</th>
                            <th style="padding: 10px;">Priority</th>
                            <th style="padding: 10px;">Created_at</th>
                            <th style="padding: 10px;">Action</th>
						</tr>
					</thead>
					<tbody id="ticket_list"></tbody>
			</table>
            </div>            
        </div>

        <dialog id="myDialog" class="p-4 rounded" style="border:none;">
        <h3 class="mb-3">Create Ticket</h3>
        <form id="create-ticket-form" class="d-flex flex-column gap-3">
            <div>
                <label class="form-label">Title</label>
                <input type="text" id="ticket_title" class="form-control" placeholder="Enter ticket title" required>
            </div>

            <div>
                <label class="form-label">Description</label>
                <textarea id="ticket_description" class="form-control" rows="3" placeholder="Describe the issue" required></textarea>
            </div>

            <div>
            <label class="form-label">Priority</label>
            <select id="ticket_priority" class="form-select" required>
                <option value="" disabled selected>Choose...</option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
            </div>
            <div class="d-flex justify-content-end gap-2 mt-3">
            <button type="button" class="btn btn-secondary"
                onclick="document.getElementById('myDialog').close()">Cancel</button>
            <button type="submit" id="submitbutton" class="btn btn-primary" onclick="document.getElementById('myDialog').close()">Submit</button>
            </div>
        </form>
        </dialog>


        <dialog id="viewdialog" class="p-4 rounded shadow-lg" style="border:none; max-width:500px; width:90%; animation: fadeIn 0.3s ease-in-out;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0 text-primary">View Ticket</h3>
                <button type="button" class="btn-close" aria-label="Close" onclick="document.getElementById('viewdialog').close()"></button>
            </div>

            <div id="view-ticket-content" class="border rounded p-3 mb-3" style="background-color: #f8f9fa; min-height: 100px;">
            </div>
            

            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('viewdialog').close()">Close</button>
            </div>
        </dialog>
        

        
        <dialog id="editdialog" class="p-4 rounded shadow-lg" style="border:none; max-width:500px; width:90%; animation: fadeIn 0.3s ease-in-out;">
            <form id="editform">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0 text-primary">Edit Ticket</h3>
                <button type="button" class="btn-close" aria-label="Close" onclick="document.getElementById('editdialog').close()"></button>
            </div>

            <div id="edit_ticket" class="container-fluid border rounded p-3 mb-3" style="background-color: #f8f9fa; min-height: 100px;">
            
            </div>
        
            <div class="d-flex justify-content-end gap-2 mt-3">
                  <button type="submit" class="btn btn-secondary" >Update</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('editdialog').close()">Close</button>
            </div>

            </form>
            
        </dialog>

           <dialog id="commentdialog" class="p-4 rounded shadow-lg" style="border:none; max-width:500px; width:90%; animation: fadeIn 0.3s ease-in-out;">
             <form id="commentform">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0 text-primary">Add Comment</h3>
            <button type="button" class="btn-close" aria-label="Close" onclick="document.getElementById('commentdialog').close()"></button>
        </div>

        <div id="addcomments" class="container-fluid border rounded p-3 mb-3" style="background-color: #f8f9fa; min-height: 100px;">
            <div id="comments-list"></div>
            <textarea id="edit_comments" class="form-control mb-2" placeholder="Type your comment"></textarea>
        </div>
    
        <div class="d-flex justify-content-end gap-2 mt-3">
<button type="submit" id="comment-submit" class="btn btn-secondary">Add Comment</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('commentdialog').close()">Close</button>
        </div>
    </form>
</dialog>




<script src="assets/js/app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>
