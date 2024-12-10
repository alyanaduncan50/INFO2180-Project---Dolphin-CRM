window.onload = function () {
  fetch("php/dashboard.php")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to fetch data");
      }
      return response.json();
    })
    .then((data) => {
      if (data.error) {
        alert(data.error);
        window.location.href = "../login.html";
        return;
      }
      const tableBody = document
        .getElementById("dashboard-table")
        .querySelector("tbody");

      data["data"].forEach((row) => {
        const tr = document.createElement("tr");
        
        let badgeClass = row.type == "Support"? "badge-support":"badge-sales-lead";

        tr.innerHTML = `
                <td style = "font-weight:bold;" >${row.title} ${row.firstname} ${row.lastname}</td>
                <td>${row.email}</td>
                <td>${row.company}</td>
                <td><span class="badge ${badgeClass}">${row.type}</span></td>
                <td class = 'view-btn'>View</td>
            `;
        tableBody.appendChild(tr);
      });
    });
};
