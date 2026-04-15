<?php include("header.php");
$conn = new mysqli("localhost", "root", "", "tailor_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$types = ['kurti', 'salwar', 'blouse', 'pant', 'shirt', 'lehenga'];
$typeCounts = [];
foreach ($types as $type) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM measurements WHERE LOWER(cloth_type) = ?");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $typeCounts[$type] = $res['count'] ?? 0;
    $stmt->close();
}
$monthsQuery = "SELECT DISTINCT DATE_FORMAT(receive_date, '%Y-%m') AS month FROM measurements WHERE receive_date != '0000-00-00' ORDER BY month DESC";
$monthsResult = $conn->query($monthsQuery);
$months = [];
while ($m = $monthsResult->fetch_assoc()) {
    $months[] = $m['month'];
}
$query = "SELECT * FROM measurements";
$result = $conn->query($query);
$records = [];
while ($row = $result->fetch_assoc()) {
    $row['raw_receive_date'] = $row['receive_date'];
    $row['raw_delivery_date'] = $row['delivery_date'];
    if (!empty($row['receive_date']) && $row['receive_date'] != '0000-00-00') {
        $row['receive_date'] = date("d-m-Y", strtotime($row['receive_date']));
    }
    if (!empty($row['delivery_date']) && $row['delivery_date'] != '0000-00-00') {
        $row['delivery_date'] = date("d-m-Y", strtotime($row['delivery_date']));
    }
    $records[] = $row;
} ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Measurement Records</title>
    <link rel="stylesheet" href="flatpickr.min.css">
    <link rel="stylesheet" href="rec.css">
    <script src="flatpickr.min.js"></script>
</head>

<body>
    <div class="filter-card">
        <div class="filter-left">
            <div class="count-display"><?php $lastIndex = count($types) - 1;
                                        foreach ($types as $i => $t) {
                                            echo ucfirst($t) . ": {$typeCounts[$t]}";
                                            if ($i !== $lastIndex) echo " | ";
                                        } ?></div>
            <div class="filter-row row-1">
                <div class="filter-item wide search-item"><label for="searchInput">Search:</label><input type="text" id="searchInput" placeholder="Enter Name or Phone No"></div>
                <div class="filter-item wide cloth-item"><label for="clothTypeFilter">Cloth Type:</label><select id="clothTypeFilter">
                        <option value="">--Select--</option><?php foreach ($types as $t): ?><option value="<?= $t ?>"><?= ucfirst($t) ?></option><?php endforeach; ?>
                    </select></div>
                <div class="filter-item wide from-date-item from-date"><label for="fromDate">From Date:</label><input type="date" id="fromDate" placeholder="Select Date"></div>
            </div>
            <div class="filter-row row-2">
                <div class="filter-item narrow"><label for="monthFilter">Month Filter:</label><select id="monthFilter">
                        <option value="">--All Months--</option><?php foreach ($months as $m): ?><option value="<?= $m ?>"><?= $m ?></option><?php endforeach; ?>
                    </select></div>
                <div class="filter-item narrow"><label for="sortOrder">Sort:</label><select id="sortOrder">
                        <option value="desc">Newest → Oldest</option>
                        <option value="asc">Oldest → Newest</option>
                    </select></div>
                <div class="filter-item narrow"><label for="recordLimit">Records per Page:</label><select id="recordLimit">
                        <option value="25" selected>25</option>
                        <option value="50">50</option>
                        <option value="75">75</option>
                        <option value="all">All</option>
                    </select></div>
                <div class="filter-item wide to-date-item"><label for="toDate">To Date:</label><input type="date" id="toDate" placeholder="Select Date"></div>
            </div>
            <div class="filter-row row-3">
                <div class="button-group"><button id="editBtn">Edit</button><button id="shareBtn">Share</button><button id="clearBtn">Clear</button><button id="deleteBtn" class="delete-btn">Delete</button><button id="backupBtn">Backup</button><button id="printBtn">Print</button></div>
            </div>
        </div>
        <div class="filter-logo"><img src="dashboard-img.png" alt="Logo"></div>
    </div>
    </div>
    <div id="recordCount" style="font-weight:bold;margin-bottom:10px;"></div>
    <div id="tableContainer"></div>
    <form id="deleteForm" method="POST" action="delete.php" style="display:none;"><input type="hidden" name="delete_ids" id="delete_ids"></form>
    <div id="popup-msg" style="position: fixed;top: 50%;left: 50%;transform: translate(-50%, -50%);background: #28a745;color: #fff;padding: 12px 20px;border-radius: 6px;font-size: 15px;font-weight: 500;display: none;z-index: 10000;"></div>
    <div id="confirmPopup" class="popup">
        <div class="popup-content">
            <p>Are you sure you want to delete selected records?</p>
            <div class="popup-buttons"><button id="confirmYes">Yes</button><button id="confirmNo">No</button></div>
        </div>
    </div>
    <div id="editPopup" class="popup">
        <div class="popup-content"><span class="close-btn" onclick="closeEditPopup()">&times;</span>
            <h3>Edit Record</h3>
            <form id="editForm" method="POST" action="update.php">
                <div id="editFormContainer"></div>
                <div style="text-align:right;margin-top:15px;"><button type="submit">Update</button></div>
            </form>
        </div>
    </div>
    <script>
        let records = <?php echo json_encode($records); ?>;
        const fieldLabelMap = {
            "length": "લંબાઈ",
            "chest_up": "છાતી અપ",
            "chest_down": "છાતી ડાઉન",
            "waist": "કમર",
            "seat_hip": "બેઠક",
            "sleeve": "બાંય",
            "sleeve_length": "બાંય લંબાઈ",
            "neck": "ગળું",
            "width_pohdai": "પોહડાઈ",
            "shoulder": "ખભા",
            "cut": "કટ",
            "mori": "મોરી",
            "sleeve_mori": "બાંય મોરી",
            "point": "પોઇન્ટ",
            "front": "આગળ",
            "back": "પાછળ",
            "thighs": "સાથળ",
            "knee": "ગોઠણ",
            "chanya_length": "ચણિયા લંબાઈ"
        };
        const fieldLabels = {
            kurti: ["length", "chest_up", "chest_down", "waist", "seat_hip", "mori", "sleeve", "sleeve_mori", "neck", "width_pohdai", "shoulder", "cut"],
            salwar: ["length", "mori"],
            blouse: ["length", "chest_up", "chest_down", "waist", "sleeve", "sleeve_mori", "point", "front", "back", "shoulder"],
            pant: ["length", "thighs", "knee", "mori", "waist", "seat_hip", "chanya_length"],
            shirt: ["length", "chest_up", "chest_down", "sleeve_length", "sleeve_mori"],
            lehenga: ["length", "waist"]
        };
        const clothTypeFilter = document.getElementById("clothTypeFilter");
        const searchInput = document.getElementById("searchInput");
        const fromDate = document.getElementById("fromDate");
        const toDate = document.getElementById("toDate");
        const monthFilter = document.getElementById("monthFilter");
        const recordLimit = document.getElementById("recordLimit");
        const sortOrder = document.getElementById("sortOrder");
        const tableContainer = document.getElementById("tableContainer");
        const recordCount = document.getElementById("recordCount");
        const clearBtn = document.getElementById("clearBtn");
        const deleteBtn = document.getElementById("deleteBtn");
        const editBtn = document.getElementById("editBtn");
        const shareBtn = document.getElementById("shareBtn");
        const printBtn = document.getElementById("printBtn");
        const editPopup = document.getElementById("editPopup");
        const editFormContainer = document.getElementById("editFormContainer");
        const confirmPopup = document.getElementById("confirmPopup");
        [clothTypeFilter, searchInput, fromDate, toDate, monthFilter, recordLimit, sortOrder].forEach(el => {
            el.addEventListener("change", renderTable);
        });
        searchInput.addEventListener("input", renderTable);
        clearBtn.addEventListener("click", () => {
            clothTypeFilter.value = "";
            searchInput.value = "";
            fromDate.value = "";
            toDate.value = "";
            monthFilter.value = "";
            sortOrder.value = "desc";
            recordLimit.value = "25";
            fromPicker.clear();
            toPicker.clear();
            renderTable();
        });

        function htmlspecialchars(str) {
            if (typeof str !== "string") return str;
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }

        function renderTable() {
            tableContainer.innerHTML = "";
            recordCount.textContent = "";
            const selectedType = clothTypeFilter.value.toLowerCase();
            const search = searchInput.value.toLowerCase();
            const from = fromDate.value;
            const to = toDate.value;
            const selectedMonth = monthFilter.value;
            const limit = recordLimit.value;
            const sort = sortOrder.value;
            let extraHeaders = [];
            const allFields = ["length", "chest_up", "chest_down", "waist", "seat_hip", "mori", "sleeve", "sleeve_length", "neck", "width_pohdai", "shoulder", "cut", "sleeve_mori", "point", "front", "back", "thighs", "knee", "chanya_length"];
            if (!selectedType) {
                extraHeaders = [...allFields];
            } else {
                extraHeaders = fieldLabels[selectedType] || [];
            }
            const fieldLabelMap = {
                "length": {
                    en: "Length",
                    gu: "લંબાઈ"
                },
                "chest_up": {
                    en: "Chest Up",
                    gu: "છાતી અપ"
                },
                "chest_down": {
                    en: "Chest Down",
                    gu: "છાતી ડાઉન"
                },
                "waist": {
                    en: "Waist",
                    gu: "કમર"
                },
                "seat_hip": {
                    en: "Seat",
                    gu: "બેઠક"
                },
                "sleeve": {
                    en: "Sleeve",
                    gu: "બાંય"
                },
                "sleeve_length": {
                    en: "Sleeve Length",
                    gu: "બાંય લંબાઈ"
                },
                "neck": {
                    en: "Neck",
                    gu: "ગળું"
                },
                "width_pohdai": {
                    en: "Pohdai",
                    gu: "પોહડાઈ"
                },
                "shoulder": {
                    en: "Shoulder",
                    gu: "ખભા"
                },
                "cut": {
                    en: "Cut",
                    gu: "કટ"
                },
                "mori": {
                    en: "Mori",
                    gu: "મોરી"
                },
                "sleeve_mori": {
                    en: "Sleeve Mori",
                    gu: "બાંય મોરી"
                },
                "point": {
                    en: "Point",
                    gu: "પોઇન્ટ"
                },
                "front": {
                    en: "Front",
                    gu: "આગળ"
                },
                "back": {
                    en: "Back",
                    gu: "પાછળ"
                },
                "thighs": {
                    en: "Thighs",
                    gu: "સાથળ"
                },
                "knee": {
                    en: "Knee",
                    gu: "ગોઠણ"
                },
                "chanya_length": {
                    en: "Chanya Length",
                    gu: "ચણિયા લંબાઈ"
                },
                "customer_name": {
                    en: "Customer Name",
                    gu: "ગ્રાહકનું નામ"
                },
                "phone": {
                    en: "Phone",
                    gu: "મોબાઇલ નં."
                },
                "cloth_type": {
                    en: "Cloth Type",
                    gu: "કપડાનો પ્રકાર"
                },
                "receive_date": {
                    en: "Receive Date",
                    gu: "રિસીવ તારીખ"
                },
                "delivery_date": {
                    en: "Delivery Date",
                    gu: "ડિલિવરી તારીખ"
                }
            };
            const table = document.createElement("table");
            const thead = document.createElement("thead");
            const tbody = document.createElement("tbody");
            const headerRow = document.createElement("tr");
            ["Select", "Sr No.", "customer_name", "phone", "cloth_type", "receive_date", "delivery_date"].forEach(field => {
                const th = document.createElement("th");
                th.style.whiteSpace = "nowrap";
                if (fieldLabelMap[field] && fieldLabelMap[field].gu) {
                    th.innerHTML = `<span style="white-space: nowrap; font-size:15px;">${fieldLabelMap[field].en}</span><br><span style="font-size:14px; color:#555; white-space: nowrap; margin-top:5px; display:block;">${fieldLabelMap[field].gu}</span>`;
                } else {
                    let engText = "";
                    switch (field) {
                        case "Select":
                            engText = "Select";
                            break;
                        case "Sr No.":
                            engText = "Sr No.";
                            break;
                        case "customer_name":
                            engText = "Customer Name";
                            break;
                        case "phone":
                            engText = "Phone";
                            break;
                        case "cloth_type":
                            engText = "Cloth Type";
                            break;
                        case "receive_date":
                            engText = "Receive Date";
                            break;
                        case "delivery_date":
                            engText = "Delivery Date";
                            break;
                    }
                    th.textContent = engText;
                }
                if (field === "Select") th.classList.add("checkbox-col");
                headerRow.appendChild(th);
            });
            extraHeaders.forEach(f => {
                const th = document.createElement("th");
                th.style.whiteSpace = "nowrap";
                if (fieldLabelMap[f]) {
                    th.innerHTML = `<span style="white-space: nowrap; font-size:15px;">${fieldLabelMap[f].en}</span><br><span style="font-size:14px; color:#555; white-space: nowrap; margin-top:5px; display:block;">${fieldLabelMap[f].gu}</span>`;
                } else {
                    th.textContent = f;
                }
                th.dataset.field = f;
                headerRow.appendChild(th);
            });
            const thAmt = document.createElement("th");
            thAmt.style.whiteSpace = "nowrap";
            thAmt.innerHTML = `<span style="white-space: nowrap; font-size:15px;">Amount</span><br><span style="font-size:14px; color:#555; white-space: nowrap; margin-top:5px; display:block;">ભાવ</span>`;
            headerRow.appendChild(thAmt);
            const thRemarks = document.createElement("th");
            thRemarks.style.whiteSpace = "nowrap";
            thRemarks.innerHTML = `<span style="white-space: nowrap; font-size:15px;">Remarks</span><br><span style="font-size:14px; color:#555; white-space: nowrap; margin-top:5px; display:block;">નોંધ</span>`;
            headerRow.appendChild(thRemarks);
            thead.appendChild(headerRow);
            table.appendChild(thead);
            table.appendChild(tbody);
            tableContainer.appendChild(table);
            let filtered = records.filter(row => {
                const matchesType = !selectedType || row.cloth_type.toLowerCase() === selectedType;
                const matchesSearch = !search || row.customer_name.toLowerCase().includes(search) || row.phone.toLowerCase().includes(search);
                let matchesDate = true;
                if (from && to) {
                    matchesDate = (row.raw_receive_date >= from && row.raw_receive_date <= to) && (row.raw_delivery_date >= from && row.raw_delivery_date <= to);
                } else if (from) {
                    matchesDate = (row.raw_receive_date >= from) && (row.raw_delivery_date >= from);
                } else if (to) {
                    matchesDate = (row.raw_receive_date <= to) && (row.raw_delivery_date <= to);
                }
                const matchesMonth = !selectedMonth || (() => {
                    if (!row.receive_date) return false;
                    const parts = row.receive_date.split("-");
                    if (parts.length !== 3) return false;
                    const ymd = `${parts[2]}-${parts[1]}`;
                    return ymd === selectedMonth;
                })();
                return matchesType && matchesSearch && matchesDate && matchesMonth;
            });
            if (sort === "asc") filtered.sort((a, b) => a.raw_receive_date.localeCompare(b.raw_receive_date));
            else filtered.sort((a, b) => b.raw_receive_date.localeCompare(a.raw_receive_date));
            recordCount.textContent = `Total Records: ${filtered.length}`;
            let limited = (limit === "all") ? filtered : filtered.slice(0, parseInt(limit));
            limited.forEach((row, index) => {
                const tr = document.createElement("tr");
                const tdCheck = document.createElement("td");
                tdCheck.innerHTML = `<input type="checkbox" class="row-checkbox" value="${row.id}">`;
                tr.appendChild(tdCheck);
                const tdID = document.createElement("td");
                tdID.textContent = index + 1;
                tr.appendChild(tdID);
                ["customer_name", "phone", "cloth_type", "receive_date", "delivery_date"].forEach(f => {
                    const td = document.createElement("td");
                    td.textContent = row[f] || "";
                    tr.appendChild(td);
                });
                extraHeaders.forEach(f => {
                    const td = document.createElement("td");
                    td.textContent = row[f] || "";
                    tr.appendChild(td);
                });
                const tdAmt = document.createElement("td");
                tdAmt.textContent = row.amt || "";
                tr.appendChild(tdAmt);
                const tdRemarks = document.createElement("td");
                tdRemarks.textContent = row.remarks || "";
                tr.appendChild(tdRemarks);
                tbody.appendChild(tr);
            });
            table.appendChild(tbody);
            tableContainer.appendChild(table);
        }
        deleteBtn.addEventListener("click", () => {
            const checkboxes = document.querySelectorAll(".row-checkbox:checked");
            const popup = document.getElementById("popup-msg");
            if (!checkboxes.length) {
                popup.textContent = "Please select at least one record to delete.";
                popup.style.background = "#e02639ff";
                popup.style.display = "block";
                setTimeout(() => popup.style.display = "none", 2000);
                return;
            }
            confirmPopup.style.display = "flex";
            document.getElementById("confirmYes").onclick = () => {
                const ids = Array.from(checkboxes).map(cb => parseInt(cb.value));
                document.getElementById("delete_ids").value = ids.join(",");
                const formData = new FormData(document.getElementById("deleteForm"));
                fetch("delete.php", {
                    method: "POST",
                    body: formData
                }).then(res => res.json()).then(data => {
                    if (data.success) {
                        records = records.filter(r => !data.deletedIds.includes(parseInt(r.id)));
                        renderTable();
                        const types = ['kurti', 'salwar', 'blouse', 'pant', 'shirt', 'lehenga'];
                        const countDisplay = document.querySelector(".count-display");
                        countDisplay.textContent = types.map(t => {
                            const count = records.filter(r => r.cloth_type.toLowerCase() === t).length;
                            return `${t.charAt(0).toUpperCase() + t.slice(1)}: ${count}`;
                        }).join(" | ");
                        popup.textContent = "Records deleted successfully!";
                        popup.style.background = "#28a745";
                        popup.style.display = "block";
                        setTimeout(() => popup.style.display = "none", 2000);
                    } else {
                        popup.textContent = "Delete failed: " + (data.error || "Unknown error");
                        popup.style.background = "#dc3545";
                        popup.style.display = "block";
                        setTimeout(() => popup.style.display = "none", 2000);
                    }
                }).catch(err => {
                    popup.textContent = "Delete failed: " + err.message;
                    popup.style.background = "#dc3545";
                    popup.style.display = "block";
                    setTimeout(() => popup.style.display = "none", 2000);
                });
                confirmPopup.style.display = "none";
            };
            document.getElementById("confirmNo").onclick = () => confirmPopup.style.display = "none";
        });
        editBtn.addEventListener("click", () => {
            const checked = document.querySelectorAll(".row-checkbox:checked");
            if (checked.length !== 1) {
                alert("Select exactly one record to edit.");
                return;
            }

            function formatDMY(dateStr) {
                if (!dateStr || dateStr === '0000-00-00') return '';
                const [y, m, d] = dateStr.split('-');
                return `${d}-${m}-${y}`;
            }
            const rowId = checked[0].value;
            const record = records.find(r => r.id == rowId);
            const clothType = record.cloth_type.toLowerCase();
            const fields = fieldLabels[clothType] || [];
            let html = `<div class="edit-top-row"><div><label>Name</label><input type="text" name="customer_name" value="${record.customer_name}"></div><div><label>Phone No.</label><input type="text" name="phone" value="${record.phone}" pattern="[0-9]{10}" maxlength="10" required title="Please enter a valid 10-digit number"></div><div><label>Cloth Type</label><input type="text" name="cloth_type" value="${record.cloth_type}" readonly></div></div><div class="edit-dates"><div><label>Receive Date</label><input type="text" name="receive_date" value="${formatDMY(record.receive_date)}"></div><div><label>Delivery Date</label><input type="text" name="delivery_date" value="${formatDMY(record.delivery_date)}"></div></div>`;
            if (fields.length) {
                html += `<div class="edit-multi">`;
                const fieldLabelMap = {
                    "length": "લંબાઈ",
                    "chest_up": "છાતી અપ",
                    "chest_down": "છાતી ડાઉન",
                    "waist": "કમર",
                    "seat_hip": "બેઠક",
                    "sleeve": "બાંય",
                    "sleeve_length": "બાંય લંબાઈ",
                    "neck": "ગળું",
                    "width_pohdai": "પોહડાઈ",
                    "shoulder": "ખભા",
                    "cut": "કટ",
                    "mori": "મોરી",
                    "sleeve_mori": "બાંય મોરી",
                    "point": "પોઇન્ટ",
                    "front": "આગળ",
                    "back": "પાછળ",
                    "thighs": "સાથળ",
                    "knee": "ગોઠણ",
                    "chanya_length": "ચણિયા લંબાઈ"
                };
                fields.forEach(f => {
                    const engLabel = f.replace(/_/g, " ").replace(/\b\w/g, c => c.toUpperCase());
                    const gujLabel = fieldLabelMap[f] || "";
                    const finalLabel = gujLabel ? `${engLabel} / ${gujLabel}` : engLabel;
                    html += `<div><label>${finalLabel}</label><input type="text" name="${f}" value="${record[f]||''}"></div>`;
                });
                html += `<div><label>Amount/ ભાવ</label><input type="text" name="amt" value="${record.amt ? record.amt : '0'}"></div>`;
                html += `</div>`;
            }
            html += `<div class="edit-grid" style="display: block; font-weight: 500; color: #353535;"><label>Remarks</label><textarea name="remarks" rows="2" style="margin-top: 6px;">${record.remarks||''}</textarea></div>`;
            html += `<input type="hidden" name="id" value="${rowId}">`;
            editFormContainer.innerHTML = html;
            editPopup.style.display = "flex";
            flatpickr("#editForm input[name='receive_date']", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d-m-Y",
                allowInput: true
            });
            flatpickr("#editForm input[name='delivery_date']", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d-m-Y",
                allowInput: true
            });
        });

        function closeEditPopup() {
            document.getElementById("editPopup").style.display = "none";
        }
        document.getElementById("editForm").addEventListener("submit", async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const res = await fetch("update.php", {
                method: "POST",
                body: formData
            });
            const data = await res.json();
            const popup = document.getElementById("popup-msg");
            if (data.success) {
                popup.textContent = "Record updated successfully!";
                popup.style.background = "#28a745";
                popup.style.display = "block";
                const idx = records.findIndex(r => r.id == data.updatedRecord.id);
                if (idx !== -1) {
                    const updated = data.updatedRecord;
                    updated.raw_receive_date = updated.receive_date;
                    updated.raw_delivery_date = updated.delivery_date;

                    function formatDMY(dateStr) {
                        if (!dateStr || dateStr === '0000-00-00') return '';
                        const [y, m, d] = dateStr.split('-');
                        return `${d}-${m}-${y}`;
                    }
                    updated.receive_date = formatDMY(updated.receive_date);
                    updated.delivery_date = formatDMY(updated.delivery_date);
                    records[idx] = updated;
                }
                renderTable();
                editPopup.style.display = "none";
            } else {
                popup.textContent = "Update failed: " + data.error;
                popup.style.background = "#dc3545";
                popup.style.display = "block";
            }
            setTimeout(() => popup.style.display = "none", 2000);
        });
        const fromPicker = flatpickr("#fromDate", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d-m-Y",
            allowInput: true
        });
        const toPicker = flatpickr("#toDate", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d-m-Y",
            allowInput: true
        });
        printBtn.addEventListener("click", () => {
            const checkboxes = document.querySelectorAll(".row-checkbox:checked");
            if (checkboxes.length === 0) {
                alert("Please select at least one record to print.");
                return;
            }
            const fieldLabelMap = {
                "length": "લંબાઈ",
                "chest_up": "છાતી અપ",
                "chest_down": "છાતી ડાઉન",
                "waist": "કમર",
                "seat_hip": "બેઠક",
                "sleeve": "બાંય",
                "sleeve_length": "બાંય લંબાઈ",
                "neck": "ગળું",
                "width_pohdai": "પોહડાઈ",
                "shoulder": "ખભા",
                "cut": "કટ",
                "mori": "મોરી",
                "sleeve_mori": "બાંય મોરી",
                "point": "પોઇન્ટ",
                "front": "આગળ",
                "back": "પાછળ",
                "thighs": "સાથળ",
                "knee": "ગોઠણ",
                "chanya_length": "ચણિયા લંબાઈ"
            };
            const clothFields = {
                "Kurti": ["length", "chest_up", "chest_down", "waist", "seat_hip", "mori", "sleeve", "sleeve_mori", "neck", "width_pohdai", "shoulder", "cut"],
                "Salwar": ["length", "mori"],
                "Blouse": ["length", "chest_up", "chest_down", "waist", "sleeve", "sleeve_mori", "point", "front", "back", "shoulder"],
                "Pant": ["length", "thighs", "knee", "mori", "waist", "seat_hip", "chanya_length"],
                "Shirt": ["length", "chest_up", "chest_down", "sleeve_length", "sleeve_mori"],
                "Lehenga": ["length", "waist"]
            };
            const clothTypeMap = {
                "Kurti": "કુર્તિ",
                "Salwar": "સલવાર",
                "Blouse": "બ્લાઉઝ",
                "Pant": "પેન્ટ",
                "Shirt": "શર્ટ",
                "Lehenga": "ચણ્યો"
            };
            let printContent = "";
            checkboxes.forEach(cb => {
                const row = cb.closest("tr");
                const cells = row.querySelectorAll("td");
                const name = cells[2].innerText;
                const phone = cells[3].innerText;
                const clothTypeEng = cells[4].innerText;
                const clothType = clothTypeMap[clothTypeEng] || clothTypeEng;
                const receiveDate = cells[5].innerText;
                const deliveryDate = cells[6].innerText;
                const amt = cells[cells.length - 2].innerText;
                const remarks = cells[cells.length - 1].innerText;
                const fields = clothFields[clothTypeEng.charAt(0).toUpperCase() + clothTypeEng.slice(1).toLowerCase()] || [];
                let extraFields = "";
                fields.forEach(fn => {
                    const th = document.querySelector(`table thead th[data-field="${fn}"]`);
                    const value = th ? cells[[...th.parentNode.children].indexOf(th)]?.innerText || "" : "";
                    const label = fieldLabelMap[fn] || fn;
                    extraFields += `<p><strong>${label}:</strong> ${value}</p>`;
                });
                printContent += `<div class="biodata"><h2 style="text-align:center;">${name}</h2><p><strong>મોબાઇલ નંબર:</strong> ${phone}</p><p><strong>કપડાનો પ્રકાર:</strong> ${clothType}</p><p><strong>રિસીવ તારીખ:</strong> ${receiveDate}</p><p><strong>ડિલિવરી તારીખ:</strong> ${deliveryDate}</p>${extraFields}<p><strong>નોંધ:</strong> ${remarks}</p><p><strong>ભાવ:</strong> ${amt}</p></div><div style="page-break-after: always;"></div>`;
            });
            const originalContent = document.body.innerHTML;
            document.body.innerHTML = `<html><head><title>Print Records</title><style>body {font-family: Arial, sans-serif; margin: 30px; font-size: 22px;}.biodata {border: 2px solid #333; padding: 20px; border-radius: 10px; margin-bottom: 20px;}h2 {margin-top: 0;}p {margin: 5px 0; line-height: 1.4;}</style></head><body>${printContent}</body></html>`;
            window.print();
            document.body.innerHTML = originalContent;
            window.location.reload();
        });
        shareBtn.addEventListener("click", () => {
            const checked = document.querySelectorAll('.row-checkbox:checked');
            if (checked.length === 0) {
                alert("Please select at least one record to share.");
                return;
            }
            const clothTypeMap = {
                "Kurti": "કુર્તિ",
                "Salwar": "સલવાર",
                "Blouse": "બ્લાઉઝ",
                "Pant": "પેન્ટ",
                "Shirt": "શર્ટ",
                "Lehenga": "ચણ્યો"
            };
            const customerMap = {};
            checked.forEach(cb => {
                const row = cb.closest("tr");
                const cells = row.querySelectorAll("td");
                const name = cells[2].innerText.trim();
                let phone = cells[3].innerText.replace(/\D/g, '');
                if (!phone.startsWith("91")) phone = "91" + phone;
                const clothTypeEng = cells[4].innerText.trim();
                const clothTypeGuj = clothTypeMap[clothTypeEng] || clothTypeEng;
                const receiveDate = cells[5].innerText.trim();
                const deliveryDate = cells[6].innerText.trim();
                const remarks = cells[cells.length - 1].innerText.trim();
                const amt = cells[cells.length - 2].innerText.trim();
                const recordObj = {
                    clothType: clothTypeGuj,
                    receiveDate,
                    deliveryDate,
                    remarks,
                    amt
                };
                if (!customerMap[phone]) {
                    customerMap[phone] = {
                        name,
                        records: []
                    };
                }
                customerMap[phone].records.push(recordObj);
            });
            let message = "દ્રષ્ટિ ટેઇલર\n";
            let grandTotal = 0;
            Object.keys(customerMap).forEach(phone => {
                const customer = customerMap[phone];
                message += "==========================\n\n";
                message += `નામ                     : ${customer.name}\n`;
                message += `મોબાઇલ નંબર     : ${phone}\n`;
                const receiveDate = customer.records[0]?.receiveDate || "";
                const deliveryDate = customer.records[0]?.deliveryDate || "";
                if (receiveDate) message += `રિસીવ તારીખ      : ${receiveDate}\n`;
                if (deliveryDate) message += `ડિલિવરી તારીખ   : ${deliveryDate}\n`;
                message += "-----------------------------------\n";
                let totalAmt = 0;
                let salwarAmt = 0,
                    kurtiAmt = 0;
                let otherRecords = [];
                customer.records.forEach(rec => {
                    const amt = parseFloat((rec.amt || "0").replace("Rs", "").trim()) || 0;
                    totalAmt += amt;
                    if (rec.clothType === "સલવાર") {
                        salwarAmt += amt;
                    } else if (rec.clothType === "કુર્તિ") {
                        kurtiAmt += amt;
                    } else {
                        otherRecords.push({
                            type: rec.clothType,
                            amt
                        });
                    }
                });
                if (salwarAmt > 0 && kurtiAmt > 0) {
                    const dressAmt = salwarAmt + kurtiAmt;
                    message += `ડ્રેસ         : Rs ${dressAmt}\n`;
                } else {
                    if (salwarAmt > 0) {
                        message += `સલવાર   : Rs ${salwarAmt}\n`;
                    }
                    if (kurtiAmt > 0) {
                        message += `કુર્તિ         : Rs ${kurtiAmt}\n`;
                    }
                }
                otherRecords.forEach(r => {
                    if (r.type === "શર્ટ") {
                        message += `શર્ટ          : Rs ${r.amt}\n`;
                    } else if (r.type === "પેન્ટ") {
                        message += `પેન્ટ         : Rs ${r.amt}\n`;
                    } else if (r.type === "બ્લાઉઝ") {
                        message += `બ્લાઉઝ  : Rs ${r.amt}\n`;
                    } else if (r.type === "ચણ્યો") {
                        message += `ચણ્યો     : Rs ${r.amt}\n`;
                    }
                });
                message += "-----------------------------------\n";
                message += `કુલ બિલ   : Rs ${totalAmt}\n\n`;
                grandTotal += totalAmt;
            });
            const firstPhone = Object.keys(customerMap)[0];
            const url = `https://wa.me/${firstPhone}?text=${encodeURIComponent(message)}`;
            window.open(url, "_blank");
        });
        renderTable();
        window.addEventListener("load", () => {
            document.getElementById("confirmPopup").style.display = "none";
            document.getElementById("editPopup").style.display = "none";
            document.querySelectorAll(".row-checkbox").forEach(cb => cb.checked = false);
        });
    </script>
    <script>
        backupBtn.addEventListener("click", () => {
            if (records.length === 0) {
                alert("No records to backup!");
                return;
            }
            const clothTypeMap = {
                "Kurti": "કુર્તિ",
                "Salwar": "સલવાર",
                "Blouse": "બ્લાઉઝ",
                "Pant": "પેન્ટ",
                "Shirt": "શર્ટ",
                "Lehenga": "ચણ્યો"
            };
            const fieldLabelMap = {
                "customer_name": "ગ્રાહકનું નામ",
                "phone": "મોબાઇલ નંબર",
                "cloth_type": "કાપડનો પ્રકાર",
                "receive_date": "રિસીવ તારીખ",
                "delivery_date": "ડિલિવરી તારીખ",
                "length": "લંબાઈ",
                "waist": "કમર",
                "sleeve": "બાંય",
                "point": "પોઇન્ટ",
                "neck": "ગળું",
                "front": "આગળ",
                "back": "પાછળ",
                "shoulder": "ખભા",
                "chest_up": "છાતી અપ",
                "chest_down": "છાતી ડાઉન",
                "seat_hip": "બેઠક",
                "width_pohdai": "પોહડાઈ",
                "cut": "કટ",
                "thighs": "સાથળ",
                "knee": "ગોઠણ",
                "mori": "મોરી",
                "sleeve_mori": "બાંય મોરી",
                "chanya_length": "ચણિયા લંબાઈ",
                "sleeve_length": "બાંય લંબાઈ",
                "remarks": "નોંધ",
                "amt": "ભાવ"
            };
            const visibleFields = ["customer_name", "phone", "cloth_type", "receive_date", "delivery_date", "length", "waist", "sleeve", "point", "neck", "front", "back", "shoulder", "chest_up", "chest_down", "seat_hip", "width_pohdai", "cut", "thighs", "knee", "mori", "sleeve_mori", "chanya_length", "sleeve_length", "remarks", "amt"];
            let tableHTML = `<table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; border: 1px solid #000;"><thead><tr style="color: black; font-weight: bold; text-align: left; border: 1px solid #000;"><th style="border: 1px solid #000;">S.No</th>`;
            visibleFields.forEach(h => {
                let engLabel = h.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                let gujLabel = fieldLabelMap[h] || '';
                tableHTML += `<th style="border: 1px solid #000;">${engLabel} (${gujLabel})</th>`;
            });
            tableHTML += `</tr></thead><tbody>`;
            records.sort((a, b) => new Date(a.receive_date) - new Date(b.receive_date));
            records.forEach((row, index) => {
                tableHTML += `<tr style="border: 1px solid #000;">`;
                tableHTML += `<td style="border: 1px solid #000;">${index + 1}</td>`;
                visibleFields.forEach(h => {
                    let value = row[h];
                    if (h === "cloth_type") value = clothTypeMap[value] || value;
                    let cellStyle = "border: 1px solid #000;";
                    if (h === "receive_date" || h === "delivery_date") {
                        cellStyle += " white-space: nowrap;";
                    }
                    tableHTML += `<td style="${cellStyle}">${value || ''}</td>`;
                });
                tableHTML += `</tr>`;
            });
            tableHTML += `</tbody></table>`;
            const iframe = document.createElement('iframe');
            iframe.style.position = 'fixed';
            iframe.style.right = '0';
            iframe.style.bottom = '0';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = '0';
            document.body.appendChild(iframe);
            const doc = iframe.contentWindow.document;
            doc.open();
            doc.write(`<html><head><title>Measurements Backup</title></head><body style="font-family: Arial, sans-serif; padding: 20px; color: #000;"><div style="display: flex; justify-content: space-between; margin-bottom: 20px;"><h2 style="margin: 0;">Measurements Backup</h2><span style="align-self: center;">Date: ${(() => {const d = new Date();const day = String(d.getDate()).padStart(2, '0');const month = String(d.getMonth() + 1).padStart(2, '0');const year = d.getFullYear();return `${day}-${month}-${year}`;})()}</span></div>${tableHTML}</body></html>`);
            doc.close();
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
            setTimeout(() => {
                document.body.removeChild(iframe);
            }, 1000);
        });
    </script><?php include("footer.php"); ?>
</body>

</html>