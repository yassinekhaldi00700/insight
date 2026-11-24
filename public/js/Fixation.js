// Add row button event listeners
document.getElementById("add-row-qualitatif").addEventListener("click", () => {
    addRow("table-qualitatif", false);
});

document.getElementById("add-row-quantitatif").addEventListener("click", () => {
    addRow("table-quantitatif", true);
});

// Function to add a new row to a table
function addRow(tableId, isQuantitative) {
    const table = document.getElementById(tableId).getElementsByTagName("tbody")[0];
    const rows = table.getElementsByTagName("tr").length;

    // Limit to 3 rows per table
    if (rows >= 3) {
        alert("Vous ne pouvez pas ajouter plus de 3 lignes.");
        return;
    }

    const newRow = table.insertRow();
    newRow.innerHTML = `
        <td><input type="text" placeholder="Objectif" required></td>
        <td><input type="text" placeholder="Description" required></td>
        ${isQuantitative ? `<td><input type="number" min="1" placeholder="Quantité" required></td>` : ""}
        <td><input type="number" min="1" max="100" placeholder="1-100" class="pond-input" required></td>
        <td style="min-width: 110px;">
            <button class="add-btn icon" style="background-image: url('${confirm}');"></button>
            <button class="delete-btn icon" style="background-image: url('${deleteIconUrl}');"></button>
        </td>
    `;

    // Add event listeners for confirm and delete buttons
    newRow.querySelector(".add-btn").addEventListener("click", function() {
        finalizeRow(this);
    });
    newRow.querySelector(".delete-btn").addEventListener("click", function() {
        newRow.remove();
        updatePonderation();
    });
}

// Function to finalize a row after confirmation
function finalizeRow(button) {
    const row = button.parentElement.parentElement;
    const inputs = row.querySelectorAll("input");

    // Validate that all inputs are filled
    for (const input of inputs) {
        if (!input.value) {
            alert("Veuillez remplir tous les champs ou supprimer la ligne.");
            return;
        }
    }

    // Disable inputs after confirmation
    inputs.forEach(input => input.setAttribute("disabled", true));

    // Replace buttons with edit and delete options
    button.parentElement.innerHTML = `
        <button class="edit-btn icon" style="background-image: url('${editIconUrl}');"></button>
        <button class="delete-btn icon" style="background-image: url('${deleteIconUrl}');"></button>
    `;

    // Add event listeners for new buttons
    row.querySelector(".delete-btn").addEventListener("click", function() {
        row.remove();
        updatePonderation();
    });
    row.querySelector(".edit-btn").addEventListener("click", function() {
        enableEditMode(row);
    });

    updatePonderation();
}

// Function to enable edit mode for a row
function enableEditMode(row) {
    const inputs = row.querySelectorAll("input");
    inputs.forEach(input => input.removeAttribute("disabled"));

    row.querySelector("td:last-child").innerHTML = `
        <button class="add-btn icon" style="background-image: url('${confirm}');"></button>
    `;
    row.querySelector(".add-btn").addEventListener("click", function() {
        finalizeRow(this);
    });
}

// Function to update ponderation totals
function updatePonderation() {
    const pondInputs = document.querySelectorAll(".pond-input");
    let totalPonderation = 0;

    pondInputs.forEach(input => {
        if (!input.disabled) return; // Ignore incomplete rows
        totalPonderation += parseInt(input.value || 0);
    });

    const warningSpan = document.querySelector(".warn");
    const remainingPonderation = 100 - totalPonderation;

    if (remainingPonderation < 0) {
        warningSpan.textContent = `La pondération totale est ${totalPonderation}. Réduisez à 100.`;
    } else if (remainingPonderation > 0) {
        warningSpan.textContent = `La pondération restante est ${remainingPonderation}.`;
    } else {
        warningSpan.textContent = "";
    }
}

// Function to collect table data
function collectTableData(tableId) {
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    const data = [];

    for (const row of rows) {
        const inputs = row.querySelectorAll('input');
        if (tableId === 'table-qualitatif') {
            let id = "";
            if (inputs[0].type === "hidden") {
                id = inputs[0].value; // Existing row from DB
            }
            data.push({
                id: id,
                objectif: inputs[inputs[0].type === "hidden" ? 1 : 0].value,
                description: inputs[inputs[0].type === "hidden" ? 2 : 1].value,
                ponderation: inputs[inputs[0].type === "hidden" ? 3 : 2].value
            });
        } else {
            let id = "";
            if (inputs[0].type === "hidden") {
                id = inputs[0].value; // Existing row from DB
            }
            data.push({
                id: id,
                objectif: inputs[inputs[0].type === "hidden" ? 1 : 0].value,
                description: inputs[inputs[0].type === "hidden" ? 2 : 1].value,
                quantite: inputs[inputs[0].type === "hidden" ? 3 : 2].value,
                ponderation: inputs[inputs[0].type === "hidden" ? 4 : 3].value
            });
        }
    }

    return data;
}
// Form submission event listener
document.getElementById('objectives-form').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent default form submission
    
    const tables = ['table-qualitatif', 'table-quantitatif'];
    const warningSpan = document.querySelector('.warn');
    let totalPonderation = 0;
    let isValid = true;

    // Validation
    for (const tableId of tables) {
        const rows = document.querySelectorAll(`#${tableId} tbody tr`);
        if (rows.length === 0) {
            warningSpan.textContent = 'Chaque table doit contenir au moins une ligne.';
            isValid = false;
            break;
        }
        
        for (const row of rows) {
            const inputs = row.querySelectorAll('input');
            for (const input of inputs) {
                if (!input.value) {
                    warningSpan.textContent = 'Veuillez remplir toutes les lignes ou supprimer celles qui sont incomplètes.';
                    isValid = false;
                    break;
                }
            }
            if (!isValid) break;
            
            const pondInput = row.querySelector('.pond-input');
            totalPonderation += parseInt(pondInput.value || 0);
        }
        if (!isValid) break;
    }

    if (!isValid) {
        e.preventDefault();
        return;
    }

    if (totalPonderation !== 100) {
        warningSpan.textContent = `La pondération totale doit être exactement 100. Actuellement : ${totalPonderation}.`;
        e.preventDefault();
        return;
    }

    // Collect and set form data
    const qualitatifData = collectTableData('table-qualitatif');
    const quantitatifData = collectTableData('table-quantitatif');

    // Set hidden field values
    document.getElementById('qualitatif-data').value = JSON.stringify(qualitatifData);
    document.getElementById('quantitatif-data').value = JSON.stringify(quantitatifData);

    // Log data for debugging
    console.log('Qualitative Data:', qualitatifData);
    console.log('Quantitative Data:', quantitatifData);

    // Submit the form
    this.submit();
});

// Next step button handler
document.getElementById("next-stepp").addEventListener("click", function(e) {
    // The form submit handler will handle everything
    // This button is just a submit button, so we don't need additional logic here
});

