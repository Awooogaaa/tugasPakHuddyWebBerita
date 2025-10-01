<?php
session_start();
include 'koneksi.php';
$isLoggedIn = isset($_SESSION['username']);
if ($isLoggedIn) {
    session_destroy();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Logout</title>
    <meta http-equiv="refresh" content="6;url=login.php">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(to right, #74ebd5, #ACB6E5);
            color: white;
            text-align: center;
            padding-top: 100px;
            overflow: hidden;
        }
        .box {
            background-color: rgba(0,0,0,0.3);
            padding: 40px;
            border-radius: 12px;
            display: inline-block;
            box-shadow: 0 0 20px rgba(255,255,255,0.2);
            position: relative;
            z-index: 1;
            transition: transform 0.2s ease;
        }
        h2 {
            font-size: 28px;
        }
        p {
            font-size: 16px;
        }
        #countdown {
            font-size: 24px;
            font-weight: bold;
            margin-top: 10px;
        }

        /* Ledakan keren */
        .explosion {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300px;
            height: 300px;
            margin-left: -150px;
            margin-top: -150px;
            background: radial-gradient(circle, yellow 10%, orange 30%, red 60%, transparent 80%);
            border-radius: 50%;
            opacity: 0;
            animation: boom 1.2s ease forwards;
            z-index: 999;
            box-shadow: 0 0 60px 20px rgba(255, 100, 0, 0.6);
        }

        @keyframes boom {
            0% {
                transform: scale(0.5);
                opacity: 0.3;
            }
            40% {
                transform: scale(1.2);
                opacity: 1;
                filter: blur(2px);
            }
            100% {
                transform: scale(2);
                opacity: 0;
                filter: blur(6px);
            }
        }

        /* Shake efek */
        .shake {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0% { transform: translate(0px, 0px) rotate(0deg); }
            20% { transform: translate(-5px, 5px) rotate(-2deg); }
            40% { transform: translate(5px, -5px) rotate(2deg); }
            60% { transform: translate(-5px, 5px) rotate(-1deg); }
            80% { transform: translate(5px, -5px) rotate(1deg); }
            100% { transform: translate(0px, 0px) rotate(0deg); }
        }

        /* Partikel ledakan */
        .particle {
            position: absolute;
            width: 10px;
            height: 10px;
            background: gold;
            border-radius: 50%;
            animation: fly 1s ease-out forwards;
        }

        @keyframes fly {
            0% {
                transform: translate(0, 0) scale(1);
                opacity: 1;
            }
            100% {
                transform: translate(var(--x), var(--y)) scale(0.2);
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <div class="box" id="logoutBox">
        <?php if ($isLoggedIn): ?>
            <h2>Berhasil Logout</h2>
            <p>Kamu akan dialihkan ke halaman login dalam <span id="countdown">5</span> detik</p>
        <?php else: ?>
            <h2>Gagal Logout</h2>
            <p>Silakan login terlebih dahulu<br>Kamu akan dialihkan ke halaman login dalam <span id="countdown">5</span> detik</p>
        <?php endif; ?>
    </div>

    <div id="explosion" class="explosion" style="display: none;"></div>

    <script>
        let seconds = 5;
        const countdownEl = document.getElementById('countdown');

        const countdownInterval = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;
            if (seconds <= 0) {
                clearInterval(countdownInterval);
                showExplosion();
            }
        }, 1000);

        function showExplosion() {
            const boom = document.getElementById('explosion');
            const box = document.getElementById('logoutBox');
            boom.style.display = 'block';
            box.classList.add('shake');
            box.style.transform = 'scale(0.8)';

            // Tambahkan partikel ledakan
            for (let i = 0; i < 20; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                const angle = Math.random() * 2 * Math.PI;
                const radius = Math.random() * 150;
                const x = Math.cos(angle) * radius + 'px';
                const y = Math.sin(angle) * radius + 'px';
                particle.style.setProperty('--x', x);
                particle.style.setProperty('--y', y);
                particle.style.left = '50%';
                particle.style.top = '50%';
                particle.style.marginLeft = '-5px';
                particle.style.marginTop = '-5px';
                document.body.appendChild(particle);

                // Hapus partikel setelah animasi
                setTimeout(() => {
                    particle.remove();
                }, 1000);
            }
        }
    </script>
</body>
</html>
