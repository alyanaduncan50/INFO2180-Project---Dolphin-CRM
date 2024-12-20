document.addEventListener('DOMContentLoaded', () => {
    var btn = document.querySelector("#add-user-btn");

    btn.addEventListener('click', function (e) {
        e.preventDefault();
        window.location.href = "add_user.html";
    });

    fetch('php/user_list.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch data');
            }
            return response.json();
        })
        .then(users => {
            const tbody = document.querySelector('.user-table tbody');
            tbody.innerHTML = ''; 

           
            users.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${user.firstname} ${user.lastname}</td>
                    <td class = "user-data">${user.email}</td>
                    <td class = "user-data">${user.role}</td>
                    <td class = "user-data">${user.created_at}</td>
                `;
                tbody.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load users. Please try again.');
        });
});
