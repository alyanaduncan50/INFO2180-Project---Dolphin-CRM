window.onload = function () {
  fetchAllRecords();
  setupFilters();
};

function fetchAllRecords() {
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
      populateTable(data["data"]);
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

function fetchFilteredRecords(filterType) {
  fetch(`php/dashboard.php?filter=${encodeURIComponent(filterType)}`)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Failed to fetch data");
      }
      return response.json();
    })
    .then((data) => {
      if (data.error) {
        alert(data.error);
        return;
      }

      populateTable(data["data"]);
    })
    .catch((error) => {
      console.error("Error:", error);
    });
}

function populateTable(rows) {
  const tableBody = document
    .getElementById("dashboard-table")
    .querySelector("tbody");

  tableBody.innerHTML = ""; 

  rows.forEach((row) => {
    const tr = document.createElement("tr");
    let badgeClass = row.type === "Support" ? "badge-support" : "badge-sales-lead";

    tr.innerHTML = `
      <td style="font-weight: bold;">${row.title} ${row.firstname} ${row.lastname}</td>
      <td>${row.email}</td>
      <td>${row.company}</td>
      <td><span class="badge ${badgeClass}">${row.type}</span></td>
      <td class="view-btn">View</td>
    `;
    tableBody.appendChild(tr);
  });
}

// Filter Button Setup
function setupFilters() {
  const filterLinks = document.querySelectorAll(".filter-btn");

  filterLinks.forEach((link) => {
    link.addEventListener("click", (event) => {
      event.preventDefault();

      filterLinks.forEach((link) => link.classList.remove("active"));
      event.target.classList.add("active");

      const filterType = event.target.dataset.filter;

      if (filterType === "all") {
        fetchAllRecords(); // Fetch all records
      } else {
        fetchFilteredRecords(filterType); // Fetch filtered records
      }
    });
  });
}
