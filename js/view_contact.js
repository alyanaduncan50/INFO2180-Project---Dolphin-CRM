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

                const notesList = document.getElementById('notes-list');
                notesList.innerHTML = '';
                contact.notes.forEach(note => {
                    const noteElement = document.createElement('p');
                    noteElement.innerHTML = `<strong>${note.addedBy}</strong><br>${note.comment}<br><small>${note.date}</small>`;
                    notesList.appendChild(noteElement);
                });

                initializeAddNoteButton(contactId);
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Failed to fetch contact details.");
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
                alert(data.success);
                document.getElementById('new-note').value = '';
                fetchContactDetails(contactId); // Reload notes
            } else {
                alert(data.error || "Failed to add note.");
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert("An error occurred while adding the note.");
        });
    }

    const contactId = getQueryParam('id');
    if (contactId) {
        fetchContactDetails(contactId);
    } else {
        alert("No contact ID provided.");
    }
});
