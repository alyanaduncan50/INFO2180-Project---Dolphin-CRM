document.addEventListener('DOMContentLoaded', () => {

    let contacts = [];

    
    function getContacts() {
        const fetchContact = new XMLHttpRequest();
        fetchContact.open('GET', './view.php', true);

        fetchContact.onreadystatechange = () => {
            if (fetchContact.readyState === 4) {
                if (fetchContact.status === 200) {
                    try {
                        const data = JSON.parse(fetchContact.responseText);
                        contacts = data.map(contact => ({
                            id: parseInt(contact.Id),
                            title: contact.title,
                            firstname: contact.firstname,
                            lastname: contact.lastname,
                            email: contact.email,
                            telephone: contact.telephone,
                            company: contact.company,
                            type: contact.type,
                            assigned_to: parseInt(contact.assigned_to),
                            created_by: parseInt(contact.created_by),
                            created_at: contact.created_at,
                            updated_at: contact.updated_at,
                            notes: []
                        }));
                        console.log("Loaded Contacts: Success:", contacts);
                        viewContacts();
                    } catch (error) {
                        console.error("Parsing Error:", error);
                    }
                } else {
                    console.error(`Error fetching contacts: Status ${fetchContact.status}`);
                }
            }
        };

        fetchContact.send();
    }

    
    function viewContacts() {
        document.querySelectorAll('.view-btn').forEach((button, index) => {
            button.addEventListener('click', () => showContacts(index + 1));
        });
    }

    
    function getNotes(contactId) {
        const fetchNotes = new XMLHttpRequest();
        fetchNotes.open('GET', `./view.php?contact_id=${contactId}`, true);

        fetchNotes.onreadystatechange = () => {
            if (fetchNotes.readyState === 4) {
                if (fetchNotes.status === 200) {
                    try {
                        const data = JSON.parse(fetchNotes.responseText);
                        const contact = contacts.find(c => c.id === contactId);
                        if (contact) {
                            contact.notes = data.map(note => ({
                                addedBy: note.created_by,
                                date: note.ceated_at,
                                comment: note.comment
                            }));
                            console.log(`Notes for contact ${contactId}:`, contact.notes);
                            showContacts(contactId); // Show details with notes
                        }
                    } catch (error) {
                        console.error("Error fetching notes:", error);
                    }
                } else {
                    console.error(`Error fetching notes: Status ${fetchNotes.status}`);
                }
            }
        };

        fetchNotes.send();
    }

    
    function showContacts(contactId) {
        const contact = contacts.find(c => c.id === contactId);

        if (!contact) {
            alert("Contact not found.");
            return;
        }

        if (contact.notes.length === 0) {
            getNotes(contactId);
            return;
        }

        const modal = document.getElementById('contact-modal');
        modal.style.display = 'block';

        document.getElementById('contact-title').textContent = contact.title;
        document.getElementById('contact-fullname').textContent = `${contact.title} ${contact.firstname} ${contact.lastname}`;
        document.getElementById('contact-email').textContent = contact.email;
        document.getElementById('contact-telephone').textContent = contact.telephone;
        document.getElementById('contact-company').textContent = contact.company;
        document.getElementById('contact-created-at').textContent = contact.created_at;
        document.getElementById('contact-assigned-to').textContent = contact.assigned_to;
        document.getElementById('contact-updated-at').textContent = contact.updated_at;

        const notesList = document.getElementById('notes-list');
        notesList.innerHTML = '';
        contact.notes.forEach(note => {
            const li = document.createElement('li');
            li.textContent = `${note.addedBy} - ${note.comment} (${note.date})`;
            notesList.appendChild(li);
        });

        document.getElementById('assign-btn').addEventListener('click', () => assignToMe(contactId));
        document.getElementById('switch-type').addEventListener('click', () => switchType(contactId));
        document.getElementById('add-note-btn').addEventListener('click', () => addNoteToContact(contactId));
    }

    function assignToMe(contactId) {
        const contact = contacts.find(c => c.id === contactId);
        if (contact) {
            contact.assigned_to = `${contact.title} ${contact.firstname} ${contact.lastname}`; 
            contact.updated_at = new Date().toISOString().split('T')[0];
            alert(`${contact.title} ${contact.firstname} ${contact.lastname} is now assigned to you.`);
            closeModal();
        }
    }

    function switchType(contactId) {
        const contact = contacts.find(c => c.id === contactId);
        if (contact) {
            contact.type = (contact.type === 'Sales Lead') ? 'AssignToMe' : 'Sales Lead';
            contact.updated_at = new Date().toISOString().split('T')[0];
            alert(`Contact type switched to ${contact.type}.`);
            closeModal();
        }
    }

    function addNoteToContact(contactId) {
        const noteContent = document.getElementById('new-note').value;
        if (noteContent.trim() === '') {
            alert("Note cannot be empty.");
            return;
        }

        const contact = contacts.find(c => c.id === contactId);
        if (contact) {
            contact.notes.push({
                addedBy: `${contact.title} ${contact.firstname} ${contact.lastname}`,
                date: new Date().toISOString().split('T')[0],
                comment: noteContent
            });
            alert('Note added.');
            showContacts(contactId); 
        }
    }

    function closeModal() {
        document.getElementById('contact-modal').style.display = 'none';
    }

    getContacts();
});
