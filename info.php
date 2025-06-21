<!DOCTYPE html>
<html>
<head>
    <title>Create Item Entry</title>
  <link rel="stylesheet" href="store.css">
</head>
<body>

<div id="header">
    <img src="lg.png" alt="Logo" class="logo">
    <div class="shop-info">
        <strong>Shree Plywood & Hardware</strong><br>
        123 Market Street,<br>
        Pune, Maharashtra - 411001<br>
        📞 +91-9876543210
    </div>
</div>
<h2>Create Item Entry</h2>

<div class="customer-info" id="customerInfoPrint">
    <div>
        <label><strong>Customer Name:</strong></label>
        <input type="text" id="customer_name" name="customer_name">
    </div>
    <div>
        <label><strong>Mobile:</strong></label>
        <input type="text" id="customer_mobile" name="customer_mobile">
    </div>
    <div>
        <label><strong>City:</strong></label>
        <input type="text" id="customer_city" name="customer_city">
    </div>
</div>

<div class="top-controls">
    <label>Select Date: </label>
    <input type="date" id="global_date" style="width: 150px;">
</div>

<table id="itemTable">
    <thead>
        <tr>
            <th>Sr. No</th>
            <th>Date</th>
            <th>Item Name</th>
            <th>Size</th>
            <th>Unit</th>
            <th>Price</th>
            <th>Discount %</th>
            <th>Discount Rs</th>
            <th>Total Price</th>
        </tr>
    </thead>
    <tbody id="tableBody">
     
    </tbody>
    <tfoot>
        <tr>
            <td colspan="8" style="text-align: right;"><strong>Subtotal (₹):</strong></td>
            <td><input type="text" id="subtotal_figure" class="total" readonly></td>
        </tr>
    </tfoot>
</table>

<h3 id="subtotal_words"></h3>

<!-- Buttons Section -->
<button class="btn btn-add" onclick="addRows(1)">➕ Add Row</button>
<button class="btn btn-clear" onclick="removeLastRow()">➖ Remove Row</button>
<button class="btn btn-clear" onclick="clearForm()">Clear</button>
<button class="btn btn-print" onclick="printForm()">Print</button>




<script>
    let srNo = 1;

    function addRows(count) {
        const tbody = document.getElementById("tableBody");
        for (let i = 0; i < count; i++) {
            const row = document.createElement("tr");
            row.innerHTML = `
                <td>${srNo++}</td>
                <td><input type="date" class="date" value=""></td>
                <td><input type="text" class="item"></td>
                <td><input type="text" class="size"></td>
                <td><input type="number" class="unit" oninput="calculateTotal(this)"></td>
                <td><input type="number" class="price" oninput="calculateTotal(this)"></td>
                <td><input type="number" class="discount_percent" oninput="calculateTotal(this)"></td>
                <td><input type="number" class="discount_rs" oninput="calculateTotal(this)"></td>
                <td><input type="text" class="total" readonly></td>
            `;
            tbody.appendChild(row);
        }
        setDefaultDate();
    }

    function removeLastRow() {
        const tbody = document.getElementById("tableBody");
        if (tbody.rows.length > 1) {
            tbody.removeChild(tbody.lastElementChild);
            srNo--;
            updateSubtotal();
        }
    }

    function setDefaultDate() {
        const globalDate = document.getElementById("global_date").value;
        const dateInputs = document.querySelectorAll(".date");
        dateInputs.forEach(input => {
            if (!input.value) input.value = globalDate;
        });
    }

    document.getElementById("global_date").valueAsDate = new Date();
    document.getElementById("global_date").addEventListener("change", setDefaultDate);

    function calculateTotal(elem) {
        const row = elem.closest("tr");
        const unit = parseFloat(row.querySelector(".unit").value) || 0;
        const price = parseFloat(row.querySelector(".price").value) || 0;
        const discountPercent = parseFloat(row.querySelector(".discount_percent").value) || 0;
        const discountRs = parseFloat(row.querySelector(".discount_rs").value) || 0;

        let total = unit * price;

        if (discountRs > 0) {
            total -= discountRs;
        } else if (discountPercent > 0) {
            total -= (total * discountPercent / 100);
        }

        row.querySelector(".total").value = total.toFixed(2);
        updateSubtotal();
    }

    function updateSubtotal() {
    const totals = document.querySelectorAll("#tableBody .total");
    let sum = 0;
    totals.forEach(t => {
        const val = parseFloat(t.value);
        if (!isNaN(val)) sum += val;
    });

    document.getElementById("subtotal_words").innerText =
        "Subtotal : " + convertNumberToWords(Math.round(sum)) + " only";

    document.getElementById("subtotal_figure").value = sum.toFixed(2);
}


    function convertNumberToWords(num) {
    if (num === 0) return "Zero";

    if (num > 999999) return "Amount too large";

    const a = ["", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", 
               "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", 
               "Eighteen", "Nineteen"];
    const b = ["", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];

    function numToWords(n, suffix) {
        let str = "";
        if (n > 19) {
            str += b[Math.floor(n / 10)] + " " + a[n % 10];
        } else {
            str += a[n];
        }
        if (n !== 0) str += " " + suffix + " ";
        return str;
    }

    let output = "";
    output += numToWords(Math.floor(num / 100000), "Lakh");
    output += numToWords(Math.floor((num / 1000) % 100), "Thousand");
    output += numToWords(Math.floor((num / 100) % 10), "Hundred");

    if (num > 100 && num % 100 !== 0) output += "and ";

    output += numToWords(num % 100, "");

    return output.trim();
}


    function printForm() {
        const rows = document.querySelectorAll("#itemTable tbody tr");
        rows.forEach(row => {
            const itemName = row.querySelector(".item").value.trim();
            if (itemName === "") row.style.display = "none";
        });
        window.print();
        rows.forEach(row => row.style.display = "");
    }

    function clearForm() {
        document.querySelectorAll("input").forEach(input => input.value = "");
        document.getElementById("tableBody").innerHTML = "";
        srNo = 1;
        addRows(3);
        updateSubtotal();
    }

  
    addRows(3);
</script>
</body>
</html>
