/*profile*/
function toggleEdit(showEdit) {
    document.getElementById('profileView').style.display = showEdit ? 'none' : 'block';
    document.getElementById('profileEdit').style.display = showEdit ? 'block' : 'none';
}

function submitProfile(e) {
e.preventDefault();

// Get new values
const newName = document.getElementById('editName').value;
const newAddress = document.getElementById('editAddress').value;
const newContact = document.getElementById('editContact').value;

// Update text in view
document.querySelector('#profileView').innerHTML = `
    <h3>Profile Details</h3>
    <p><strong>Name:</strong> ${newName}</p>
    <p><strong>Address:</strong> ${newAddress}</p>
    <p><strong>Contact:</strong> ${newContact}</p>
    <button class="edit-btn" onclick="toggleEdit(true)">Edit Profile</button>
    `;

toggleEdit(false);

}

function showTab(tabId) {
    const contents = document.querySelectorAll('.tab-content');
    const buttons = document.querySelectorAll('.tab-button');

    contents.forEach(tab => tab.style.display = 'none');
    buttons.forEach(btn => btn.classList.remove('active-tab'));

    document.getElementById(tabId).style.display = 'block';
    event.target.classList.add('active-tab');
  }

  // Show first tab by default
  window.onload = function() {
    showTab('history');
};

/* */
