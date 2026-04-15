<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/ico" href="favicon.ico">
    <title>Tailor Management System</title>
    <style>
        .site-header {
            background-color: #ffffff;
            padding: 20px 30px;
            font-family: 'Segoe UI', sans-serif;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            background-image: radial-gradient(circle, rgba(40, 94, 145, 0.15) 10%, transparent 10%), radial-gradient(circle, rgba(153, 39, 134, 0.15) 10%, transparent 10%);
            background-size: 40px 40px;
            background-position: 0 0, 20px 20px;
        }

        .site-header .container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            font-size: 1.8rem;
            font-weight: 700;
            color: #1f3a61ff;
            letter-spacing: 1px;
            transition: color 0.3s ease;
        }

        .logo:hover {
            color: #1c4d7eff;
        }

        .logo img {
            height: 50px;
            width: auto;
            margin-right: 10px;
            object-fit: contain;
        }

        .nav-menu a {
            color: #395a7cff;
            text-decoration: none;
            font-weight: 500;
            font-size: 17px;
            margin-left: 25px;
            padding: 6px 12px;
            border-radius: 6px;
            transition: color 0.3s ease, background-color 0.3s ease;
            background: none;
            border: none;
            cursor: pointer;
        }

        .nav-menu button {
            all: unset;
            display: inline-block;
            font-family: inherit;
            font-size: 17px;
            font-weight: 500;
            color: #395a7cff;
            margin-left: 25px;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: color 0.3s ease, background-color 0.3s ease;
        }

        .nav-menu a:hover,
        .nav-menu button:hover {
            color: #1d3557;
            background-color: #f0f6ff;
        }
    </style>
</head>

<body>
    <header class="site-header">
        <div class="container"><a href="#" class="logo"><img src="header-logo.jpg" alt="Tailor Logo">Tailor Management System</a>
            <nav class="nav-menu"><a href="index.php">Home</a><a href="rec.php" title="View, Edit & Manage Customer Orders!">Measurements</a></nav>
        </div>
    </header>
    <script>
        (function() {
            const targetHour = 12;
            const targetMinute = 0;

            function setCookie(name, value) {
                const now = new Date();
                const expire = new Date(now.getTime());
                expire.setSeconds(59);
                expire.setMilliseconds(999);
                document.cookie = `${name}=${value};expires=${expire.toUTCString()};path=/`;
            }

            function getCookie(name) {
                const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
                return match ? match[2] : null;
            }

            function showBackupReminder() {
                const now = new Date();
                if (now.getHours() === targetHour && now.getMinutes() === targetMinute) {
                    if (!getCookie('backupReminderClosed')) {
                        const reminder = document.createElement("div");
                        reminder.id = "backupReminder";
                        reminder.style.position = "fixed";
                        reminder.style.top = "20px";
                        reminder.style.right = "20px";
                        reminder.style.width = "250px";
                        reminder.style.background = "#faf60bff";
                        reminder.style.color = "#000";
                        reminder.style.border = "2px solid #030303ff";
                        reminder.style.padding = "15px 20px";
                        reminder.style.zIndex = "9999";
                        reminder.style.fontFamily = "Arial, sans-serif";
                        reminder.style.transition = "all 0.3s ease";
                        const styleTag = document.createElement("style");
                        styleTag.textContent = `@media (max-width: 600px) {#backupReminder {width: clamp(180px, 80vw, 220px);padding: 8px 12px;top: auto !important; left: 60% !important;right: auto !important;transform: translateX(-50%);font-size: 14px;}#backupReminder div {justify-content: space-between;align-items: center;}#backupReminder strong {font-size: 15px;}#backupReminder #closeReminder {font-size: clamp(20px, 5vw, 28px);padding: 2px 5px;margin-left: 8px;}#backupReminder p {margin-top: 6px;font-size: 13px;}}`;
                        document.head.appendChild(styleTag);
                        reminder.innerHTML = `<div style="display:flex; justify-content:space-between; align-items:center;"><strong style="font-size:16px;">Backup Reminder!</strong><span id="closeReminder" style="cursor:pointer; font-weight:bold; font-size:clamp(22px, 3vw, 30px);line-height:1; padding:5px;">&times;</span></div><p style="margin-top:8px; font-size:clamp(12px, 1.2vw, 16px);">It's time to take your backup.</p>`;
                        document.body.appendChild(reminder);
                        if (window.innerWidth <= 600) {
                            reminder.style.left = "50%";
                            reminder.style.right = "auto";
                            reminder.style.transform = "translateX(-50%)";
                        }
                        document.getElementById("closeReminder").onclick = () => {
                            document.body.removeChild(reminder);
                            setCookie('backupReminderClosed', '1');
                        };
                    }
                }
            }
            setInterval(showBackupReminder, 5000);
            showBackupReminder();
        })();
    </script>
</body>

</html>