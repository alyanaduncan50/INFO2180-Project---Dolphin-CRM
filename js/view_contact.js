document.addEventListener('DOMContentLoaded', () => {
    function getQueryParam(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    function fetchContactDetails(contactId) {
        fetch(`php/view_contact.php?id=${contactId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error fetching contact: ${response.status}`);
                }
                return response.json();
            })
            .then(contact => {
                document.getElementById('contact-fullname').textContent = `${contact.title} ${contact.firstname} ${contact.lastname}`;
                document.getElementById('contact-email').textContent = contact.email;
                document.getElementById('contact-telephone').textContent = contact.telephone;
                document.getElementById('contact-company').textContent = contact.company;
                document.getElementById('contact-created-at').textContent = contact.created_at;
                document.getElementById('contact-updated-at').textContent = contact.updated_at;
                document.getElementById('contact-assigned-to').textContent = contact.assigned_to;

                renderNotes(contact.notes);
                initializeAddNoteButton(contactId);
                initializeAssignToMeButton(contactId);
                initializeSwitchRoleButton(contactId, contact.type);
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Failed to fetch contact details.");
            });
    }

    function renderNotes(notes) {
        const notesList = document.getElementById('notes-list');
        notesList.innerHTML = '';
        notes.forEach(note => {
            const noteElement = document.createElement('p');
            noteElement.innerHTML = `
                <strong>${note.addedBy || "Unknown"}</strong><br>
                ${note.comment}<br>
                <small>${new Date(note.date).toLocaleString()}</small>
            `;
            notesList.appendChild(noteElement);
        });
    }

    function initializeAddNoteButton(contactId) {
        const addNoteButton = document.getElementById('add-note');
        addNoteButton.onclick = () => {
            const noteContent = document.getElementById('new-note').value.trim();
            if (!noteContent) {
                alert("Note cannot be empty.");
                return;
            }
            addNote(contactId, noteContent);
        };
    }

    function addNote(contactId, noteContent) {
        fetch('php/add_note.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `contact_id=${contactId}&comment=${encodeURIComponent(noteContent)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("✅ " + data.success);
                document.getElementById('new-note').value = '';
                fetchContactDetails(contactId);
            } else {
                alert("❌ " + (data.error || "Failed to add note."));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("❌ An unexpected error occurred.");
        });
    }

    function initializeAssignToMeButton(contactId) {
        const assignBtn = document.getElementById('assign-btn');
        assignBtn.onclick = () => {
            fetch('php/assign_to_me.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `contact_id=${contactId}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("✅ " + data.success);
                    document.getElementById('contact-assigned-to').textContent = "You";
                } else {
                    alert("❌ " + (data.error || "Failed to assign contact."));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("❌ An unexpected error occurred.");
            });
        };
    }

    function initializeSwitchRoleButton(contactId, currentType) {
        const switchRoleBtn = document.getElementById('switch-type');
        updateSwitchRoleButtonText(switchRoleBtn, currentType);

        switchRoleBtn.onclick = () => {
            const newType = currentType === "Sales Lead" ? "Support" : "Sales Lead";
            fetch('php/switch_role.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `contact_id=${contactId}&type=${newType}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(`✅ Role switched to ${newType}`);
                    fetchContactDetails(contactId);
                } else {
                    alert(`❌ ${data.error || "Failed to switch role."}`);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("❌ An unexpected error occurred.");
            });
        };
    }

    function updateSwitchRoleButtonText(button, currentType) {
        const newText = currentType === "Sales Lead" ? "Switch to Support" : "Switch to Sales Lead";
        button.innerHTML = `<img src="img/switch.png" alt="Switch button" style="height: 16px; width: 16px; margin-right: 5px;"> ${newText}`;
    }

    const contactId = getQueryParam('id');
    if (contactId) {
        fetchContactDetails(contactId);
    } else {
        alert("No contact ID provided.");
    }
});
