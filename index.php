<!DOCTYPE html><?php include("header.php");
                $cn = mysqli_connect("localhost", "root", "", "tailor_db");
                mysqli_set_charset($cn, "utf8mb4");
                if (isset($_POST['submit'])) {
                    $customer_name = $_POST['customer_name'];
                    $phone = $_POST['phone'];
                    $cloth_type = $_POST['cloth_type'];
                    if (!empty($_POST['cloth_type'])) {
                        foreach ($_POST['cloth_type'] as $type) {
                            $length        = $_POST['length'][$type] ?? '';
                            $waist         = $_POST['waist'][$type] ?? '';
                            $sleeve        = $_POST['sleeve'][$type] ?? '';
                            $point         = $_POST['point'][$type] ?? '';
                            $neck          = $_POST['neck'][$type] ?? '';
                            $front         = $_POST['front'][$type] ?? '';
                            $back          = $_POST['back'][$type] ?? '';
                            $shoulder      = $_POST['shoulder'][$type] ?? '';
                            $chest_up      = $_POST['chest_up'][$type] ?? '';
                            $chest_down    = $_POST['chest_down'][$type] ?? '';
                            $seat_hip      = $_POST['seat_hip'][$type] ?? '';
                            $width_pohdai  = $_POST['width_pohdai'][$type] ?? '';
                            $cut           = $_POST['cut'][$type] ?? '';
                            $thighs        = $_POST['thighs'][$type] ?? '';
                            $knee          = $_POST['knee'][$type] ?? '';
                            $mori          = $_POST['mori'][$type] ?? '';
                            $sleeve_mori   = $_POST['sleeve_mori'][$type] ?? '';
                            $chanya_length = $_POST['chanya_length'][$type] ?? '';
                            $sleeve_length = $_POST['sleeve_length'][$type] ?? '';
                            $receive_date  = $_POST['receive_date'] ?? '';
                            $delivery_date = $_POST['delivery_date'] ?? '';
                            $remarks       = $_POST['remarks'][$type] ?? '';
                            $amt           = $_POST['amt'][$type] ?? '';
                            $q = mysqli_query($cn, "INSERT INTO `measurements`(`customer_name`, `phone`, `cloth_type`, `receive_date`, `delivery_date`,`length`, `waist`, `sleeve`, `point`, `neck`, `front`, `back`, `shoulder`,`chest_up`, `chest_down`, `seat_hip`, `width_pohdai`, `cut`, `thighs`,`knee`, `mori`, `sleeve_mori`, `chanya_length`, `sleeve_length`, `remarks`, `amt`)VALUES('$customer_name','$phone','$type','$receive_date','$delivery_date','$length','$waist','$sleeve','$point','$neck','$front','$back','$shoulder','$chest_up','$chest_down','$seat_hip','$width_pohdai','$cut','$thighs','$knee','$mori','$sleeve_mori', '$chanya_length','$sleeve_length','$remarks','$amt')");
                            if (!$q) {
                                die("Error inserting $type: " . mysqli_error($cn));
                            }
                        }
                        echo "<script>window.addEventListener('DOMContentLoaded', () => {const msg = document.getElementById('success-msg');if(msg){msg.style.display = 'block';setTimeout(() => {msg.style.display = 'none';window.location = 'index.php';}, 2500);}});</script>";
                    }
                } ?><html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tailor Measurement Entry</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="flatpickr.min.css">
</head>

<body>
    <div id="success-msg" style="position: fixed;top: 50%;left: 50%;transform: translateX(-50%);background: #199235ff;color: #fff;padding: 10px 20px;border-radius: 6px;font-size: 14px;font-weight: 500;z-index: 9999;white-space: nowrap;text-align: center;display: none;">Records Added Successfully.</div>
    <form method="POST" action="" enctype="multipart/form-data" accept-charset="UTF-8">
        <h1>Customer Measurement Entry</h1>
        <div class="form-row">
            <div><label>Customer Name/ નામ</label><input type="text" name="customer_name" required></div>
            <div><label>Phone/ મોબાઇલ નં.</label><input type="text" name="phone" pattern="[0-9]{10}" maxlength="10" required title="Please enter a valid 10-digit number"></div>
        </div>
        <div class="dropdown-wrapper"><label>Cloth Type/ કપડાનો પ્રકાર</label>
            <div class="custom-select" id="clothSelect"><span class="selected-text">--Select Cloth Type--</span>
                <div class="options"><label><input type="checkbox" name="cloth_type[]" value="Kurti"> Kurti/ કુર્તિ</label><label><input type="checkbox" name="cloth_type[]" value="Salwar"> Salwar/ સલવાર</label><label><input type="checkbox" name="cloth_type[]" value="Blouse"> Blouse/ બ્લાઉઝ</label><label><input type="checkbox" name="cloth_type[]" value="Pant"> Pant/ પેન્ટ</label><label><input type="checkbox" name="cloth_type[]" value="Shirt"> Shirt/ શર્ટ</label><label><input type="checkbox" name="cloth_type[]" value="Lehenga"> Lehenga/ ચણ્યો</label></div>
            </div>
        </div>
        <div class="cloth-fields" id="KurtiFields">
            <div class="field-group Kurti"><label>Length/ લંબાઈ</label><input type="text" name="length[Kurti]"></div>
            <div class="field-group Kurti"><label>Chest Up/ છાતી</label><input type="text" name="chest_up[Kurti]"></div>
            <div class="field-group Kurti"><label>Chest Down/ છાતી</label><input type="text" name="chest_down[Kurti]"></div>
            <div class="field-group Kurti"><label>Waist/ કમર</label><input type="text" name="waist[Kurti]"></div>
            <div class="field-group Kurti"><label>Seat/ બેઠક</label><input type="text" name="seat_hip[Kurti]"></div>
            <div class="field-group Kurti"><label>Mori/ મોરી</label><input type="text" name="mori[Kurti]"></div>
            <div class="field-group Kurti"><label>Sleeve/ બાંય</label><input type="text" name="sleeve[Kurti]"></div>
            <div class="field-group Kurti"><label>Sleeve Mori/ બાંય મોરી</label><input type="text" name="sleeve_mori[Kurti]"></div>
            <div class="field-group Kurti"><label>Neck/ ગડુ</label><input type="text" name="neck[Kurti]"></div>
            <div class="field-group Kurti"><label>Width/ પોહડાઈ</label><input type="text" name="width_pohdai[Kurti]"></div>
            <div class="field-group Kurti"><label>Shoulder/ ખભા</label><input type="text" name="shoulder[Kurti]"></div>
            <div class="field-group Kurti"><label>Cut/ કાપો</label><input type="text" name="cut[Kurti]"></div>
        </div>
        <div class="cloth-fields" id="SalwarFields">
            <div class="field-group salwar"><label>Length/ લંબાઈ</label><input type="text" name="length[Salwar]"></div>
            <div class="field-group salwar"><label>Mori/ મોરી</label><input type="text" name="mori[Salwar]"></div>
        </div>
        <div class="cloth-fields" id="BlouseFields">
            <div class="field-group blouse"><label>Length/ લંબાઈ</label><input type="text" name="length[Blouse]"></div>
            <div class="field-group blouse"><label>Chest Up/ છાતી</label><input type="text" name="chest_up[Blouse]"></div>
            <div class="field-group blouse"><label>Chest Down/ છાતી</label><input type="text" name="chest_down[Blouse]"></div>
            <div class="field-group blouse"><label>Waist/ કમર</label><input type="text" name="waist[Blouse]"></div>
            <div class="field-group blouse"><label>Sleeve/ બાંય</label><input type="text" name="sleeve[Blouse]"></div>
            <div class="field-group blouse"><label>Sleeve Mori/ બાંય મોરી</label><input type="text" name="sleeve_mori[Blouse]"></div>
            <div class="field-group blouse"><label>Point/ પોઈન્ટ</label><input type="text" name="point[Blouse]"></div>
            <div class="field-group blouse"><label>Front/ આગળ</label><input type="text" name="front[Blouse]"></div>
            <div class="field-group blouse"><label>Back/ પાછળ</label><input type="text" name="back[Blouse]"></div>
            <div class="field-group blouse"><label>Shoulder/ ખભા</label><input type="text" name="shoulder[Blouse]"></div>
        </div>
        <div class="cloth-fields" id="PantFields">
            <div class="field-group pant"><label>Length/ લંબાઈ</label><input type="text" name="length[Pant]"></div>
            <div class="field-group pant"><label>Thighs/ સાથળ</label><input type="text" name="thighs[Pant]"></div>
            <div class="field-group pant"><label>Knee/ ગોઠણ</label><input type="text" name="knee[Pant]"></div>
            <div class="field-group pant"><label>Mori/ મોરી</label><input type="text" name="mori[Pant]"></div>
            <div class="field-group pant"><label>Waist/ કમર</label><input type="text" name="waist[Pant]"></div>
            <div class="field-group pant"><label>Seat/ બેઠક</label><input type="text" name="seat_hip[Pant]"></div>
            <div class="field-group pant"><label>Chanya Length/ ચણ્યા લંબાઈ</label><input type="text" name="chanya_length[Pant]"></div>
        </div>
        <div class="cloth-fields" id="ShirtFields">
            <div class="field-group shirt"><label>Length/ લંબાઈ</label><input type="text" name="length[Shirt]"></div>
            <div class="field-group shirt"><label>Chest Up/ છાતી</label><input type="text" name="chest_up[Shirt]"></div>
            <div class="field-group shirt"><label>Chest Down/ છાતી</label><input type="text" name="chest_down[Shirt]"></div>
            <div class="field-group shirt"><label>Sleeve Length/ બાંય લંબાઈ</label><input type="text" name="sleeve_length[Shirt]"></div>
            <div class="field-group shirt"><label>Sleeve Mori/ બાંય મોરી</label><input type="text" name="sleeve_mori[Shirt]"></div>
        </div>
        <div class="cloth-fields" id="LehengaFields">
            <div class="field-group lehenga"><label>Length/ લંબાઈ</label><input type="text" name="length[Lehenga]"></div>
            <div class="field-group lehenga"><label>Waist/ કમર</label><input type="text" name="waist[Lehenga]"></div>
        </div>
        <div class="form-row">
            <div><label>Receive Date/ રિસીવ તારીખ</label><input type="text" class="date-field receive" name="receive_date"></div>
            <div><label>Deliver Date/ ડિલિવરી તારીખ</label><input type="text" class="date-field delivery" name="delivery_date"></div>
            <div><label><b>Total Amount</b></label><input type="text" id="totalAmount" value="Rs " readonly></div>
        </div>
        <div id="defaultFields">
            <div class="form-row remarks-row">
                <div class="remarks-block"><label>Remarks/ નોંધ</label><textarea name="remarks[General]" rows="2"></textarea></div>
            </div>
        </div>
        <div class="form-row total-row" id="totalWrapper"></div>
        <div class="form-actions"><button type="submit" name="submit" class="action-btn">Submit</button></div>
    </form>
    <script src="flatpickr.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const dropdown = document.getElementById("clothSelect");
            const selectedText = dropdown.querySelector(".selected-text");
            const optionsBox = dropdown.querySelector(".options");
            const checkboxes = optionsBox.querySelectorAll("input[type=checkbox]");
            const defaultBlock = document.getElementById("defaultFields");
            document.querySelectorAll(".date-field.receive").forEach(input => {
                flatpickr(input, {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d-m-Y",
                    defaultDate: "today",
                    minDate: "today",
                });
            });
            document.querySelectorAll(".date-field.delivery").forEach(input => {
                flatpickr(input, {
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "d-m-Y",
                    minDate: "today",
                });
            });
            dropdown.addEventListener("click", (e) => {
                if (e.target.tagName !== "INPUT") {
                    optionsBox.classList.toggle("show");
                    dropdown.classList.toggle("focus", optionsBox.classList.contains("show"));
                }
            });
            document.addEventListener("click", (e) => {
                if (!dropdown.contains(e.target)) {
                    optionsBox.classList.remove("show");
                    dropdown.classList.remove("focus");
                }
            });
            checkboxes.forEach(cb => {
                cb.addEventListener("change", () => {
                    const checked = Array.from(checkboxes).filter(c => c.checked);
                    selectedText.textContent = checked.length === 0 ? "--Select Cloth Type--" : checked.map(c => c.value).join(", ");
                    document.querySelectorAll(".cloth-fields").forEach(f => {
                        f.classList.remove("active");
                        f.querySelectorAll(".dynamic-date").forEach(d => d.remove());
                    });
                    defaultBlock.style.display = (checked.length === 0) ? "block" : "none";
                    checked.forEach(c => {
                        const block = document.getElementById(c.value + "Fields");
                        if (block) {
                            block.classList.add("active");
                            if (!block.querySelector(".cloth-title")) {
                                const title = document.createElement("h3");
                                title.textContent = c.value + " Measurements";
                                title.classList.add("cloth-title");
                                block.prepend(title);
                            }
                            const dynamicDiv = document.createElement("div");
                            dynamicDiv.classList.add("dynamic-date");
                            dynamicDiv.innerHTML = `<div class="form-row"><div class="remarks-block"><label>Remarks/ નોંધ</label><textarea name="remarks[${c.value}]" rows="2"></textarea></div>`;
                            block.appendChild(dynamicDiv);
                            dynamicDiv.querySelectorAll(".receive").forEach(input => {
                                flatpickr(input, {
                                    dateFormat: "Y-m-d",
                                    altInput: true,
                                    altFormat: "d-m-Y",
                                    defaultDate: "today",
                                    minDate: "today"
                                });
                            });
                            dynamicDiv.querySelectorAll(".delivery").forEach(input => {
                                flatpickr(input, {
                                    dateFormat: "Y-m-d",
                                    altInput: true,
                                    altFormat: "d-m-Y",
                                    minDate: "today"
                                });
                            });
                        }
                    });
                });
            });
            const form = document.querySelector("form");
            form.addEventListener("submit", (e) => {
                const checked = Array.from(checkboxes).filter(c => c.checked);
                if (checked.length === 0) {
                    e.preventDefault();
                    alert("Please select at least 1 cloth type.");
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const nameInput = document.querySelector('input[name="customer_name"]');
            const phoneInput = document.querySelector('input[name="phone"]');
            const suggestionBox = document.createElement("div");
            suggestionBox.style.position = "absolute";
            suggestionBox.style.border = "1px solid #ccc";
            suggestionBox.style.background = "#fff";
            suggestionBox.style.zIndex = "9999";
            suggestionBox.style.maxHeight = "150px";
            suggestionBox.style.overflowY = "auto";
            suggestionBox.style.display = "none";
            suggestionBox.style.width = nameInput.offsetWidth + "px";
            suggestionBox.style.fontSize = "14px";
            suggestionBox.style.color = "#000";
            suggestionBox.style.boxShadow = "0 2px 6px rgba(0,0,0,0.2)";
            suggestionBox.style.borderRadius = "4px";
            suggestionBox.style.backgroundColor = "#fff";
            nameInput.parentNode.appendChild(suggestionBox);
            nameInput.addEventListener("input", () => {
                const query = nameInput.value.trim();
                if (query.length < 1) {
                    suggestionBox.style.display = "none";
                    return;
                }
                fetch("getPhone.php?name=" + encodeURIComponent(query)).then(res => res.json()).then(data => {
                    suggestionBox.innerHTML = "";
                    if (data.suggestions && data.suggestions.length > 0) {
                        data.suggestions.forEach(item => {
                            const div = document.createElement("div");
                            div.style.padding = "5px 10px";
                            div.style.cursor = "pointer";
                            div.textContent = item.customer_name + (item.phone ? " [" + item.phone + "]" : "");
                            div.addEventListener("click", () => {
                                nameInput.value = item.customer_name;
                                phoneInput.value = item.phone || "";
                                suggestionBox.style.display = "none";
                            });
                            suggestionBox.appendChild(div);
                        });
                        suggestionBox.style.display = "block";
                    } else {
                        suggestionBox.style.display = "none";
                    }
                }).catch(err => console.error(err));
            });
            document.addEventListener("click", (e) => {
                if (!suggestionBox.contains(e.target) && e.target !== nameInput) {
                    suggestionBox.style.display = "none";
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const clothSections = document.querySelectorAll(".cloth-fields");
            const checkboxes = document.querySelectorAll('#clothSelect input[type="checkbox"]');
            clothSections.forEach(section => {
                const wrapper = document.createElement("div");
                wrapper.className = "form-row amount-row";
                wrapper.innerHTML = `<div class="amount-input"><label><b>Amount</b></label><input type="text" name="amt[${section.id.replace("Fields","")}]" class="amt-field" value="Rs " maxlength="15"></div>`;
                section.appendChild(wrapper);
            });

            function enforceRsPrefix(input) {
                if (!input.value.startsWith("Rs ")) {
                    input.value = "Rs ";
                }
                input.addEventListener("keydown", e => {
                    if (input.selectionStart < 3) {
                        input.setSelectionRange(3, 3);
                    }
                });
            }

            function updateTotal() {
                let sum = 0;
                let anyFilled = false;
                document.querySelectorAll(".amt-field").forEach(f => {
                    if (f.closest(".cloth-fields")?.classList.contains("active") || f.closest("#totalWrapper")) {
                        let val = f.value.replace("Rs ", "").trim();
                        if (val !== "" && !isNaN(val)) {
                            sum += parseFloat(val);
                            anyFilled = true;
                        }
                    }
                });
                const totalBox = document.getElementById("totalAmount");
                if (anyFilled) {
                    totalWrapper.style.display = "block";
                    totalBox.value = "Rs " + sum;
                } else {
                    totalWrapper.style.display = "none";
                    totalBox.value = "Rs ";
                }
            }
            document.addEventListener("input", e => {
                if (e.target.classList.contains("amt-field")) {
                    enforceRsPrefix(e.target);
                    updateTotal();
                }
            });
            document.addEventListener("focus", e => {
                if (e.target.classList.contains("amt-field")) {
                    enforceRsPrefix(e.target);
                }
            }, true);
            checkboxes.forEach(cb => {
                cb.addEventListener("change", () => {
                    const anyChecked = Array.from(checkboxes).some(c => c.checked);
                    if (!anyChecked) {
                        totalWrapper.style.display = "none";
                    } else {
                        updateTotal();
                    }
                });
            });
        });
    </script><?php include("footer.php"); ?>
</body>

</html>