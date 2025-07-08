<?php 
include "../assets/pages/_db.php"; // yahan mysqli connection hai

$diseaseName = "No ID provided";
$diseaseFullName = "";
$apiData = null;

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // sanitization

    // Prepare mysqli statement with ? instead of :id
    $stmt = $conn->prepare("SELECT * FROM disease WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id); // "i" means integer
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $diseaseName = htmlspecialchars($row['name']);
            $diseaseFullName = htmlspecialchars($row['full_name']);

            // Send API request
            $apiUrl = 'https://healthcare-ai-model-1.onrender.com/predict';
            $payload = json_encode(['disease_name' => $diseaseName]);

            $ch = curl_init($apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

            $response = curl_exec($ch);
            curl_close($ch);

            $apiData = json_decode($response, true);
        } else {
            $diseaseName = "Disease not found";
        }

        $stmt->close();
    } else {
        $diseaseName = "SQL error: " . $conn->error;
    }
}
?>

<html lang="en">

<head>
    <?php 
    include "../assets/pages/_page-head.php"; 
    header_path('../'); 
    ?>

    <style>
    .footer-container {
        display: flex;
        justify-content: space-evenly;
    }

    .ayurvedic-medicin {
        font-size: 25px;
        font-weight: 500;
        color: var(--heading-color);
        font-family: var(--heading-font);
    }

    @media screen and (max-width: 505px) {
        .hero {
            background: linear-gradient(120deg, color-mix(in srgb, var(--accent-color), transparent 95%) 50%, color-mix(in srgb, var(--accent-color), transparent 98%) 25%, transparent 50%)
        }
    }
    </style>
</head>

<body class="index-page scrolled" data-aos-easing="ease-in-out" data-aos-duration="600" data-aos-delay="0">
    <div id="liveAlertPlaceholder" class="position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>
    <header id="header" class="header d-flex align-items-center fixed-top">
        <div
            class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

            <a href="./" class="logo d-flex align-items-center me-auto me-xl-0">
                <img src="../assets/img/medi-Mind.png" alt="Logo">
                <h1 class="sitename">Medi-Mind</h1>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="../" class="">Home</a></li>
                    <li><a class="active">Disease</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>
        </div>
    </header>

    <main class="main" style="margin-top: 45px;">
        <section id="testimonials" class="testimonials section hero">
            <!-- Section Title -->
            <div class="container section-title aos-init" data-aos="fade-up">
                <h2><?php echo $diseaseFullName; ?></h2>
            </div>

            <div class="container">
                <div class="row g-5">
                    <!-- Ayurvedic Medicine -->
                    <div class="col-md-4 aos-init" data-aos="fade-up" data-aos-delay="100">
                        <div class="testimonial-item h-100">
                            <div class="text-center">
                                <h3>Ayurvedic Medicine</h3>
                                <p><span><?php echo $apiData['Ayurvedic_medicin']; ?></span></p>
                                <a class="btn btn-primary rounded-pill mt-2"
                                    href="https://www.youtube.com/results?search_query=<?php echo urlencode($apiData['Ayurvedic_medicin']); ?>"
                                    target="_blank">Watch on YouTube</a>
                            </div>
                        </div>
                    </div>

                    <!-- Exercise -->
                    <div class="col-md-4 aos-init" data-aos="fade-up" data-aos-delay="150">
                        <div class="testimonial-item h-100">
                            <div class="text-center">
                                <h3>Exercise</h3>
                                <p><span><?php echo $apiData['Exerise']; ?></span></p>
                                <a class="btn btn-primary rounded-pill mt-2"
                                    href="https://www.youtube.com/results?search_query=<?php echo urlencode($apiData['Exerise']); ?>"
                                    target="_blank">Watch on YouTube</a>
                            </div>
                        </div>
                    </div>

                    <!-- Home Remedies -->
                    <div class="col-md-4 aos-init" data-aos="fade-up" data-aos-delay="200">
                        <div class="testimonial-item h-100">
                            <div class="text-center">
                                <h3>Home Remedies</h3>
                                <p><span><?php echo $apiData['Home_remedies']; ?></span></p>
                                <a class="btn btn-primary rounded-pill mt-2"
                                    href="https://www.youtube.com/results?search_query=<?php echo urlencode($apiData['Home_remedies']); ?>"
                                    target="_blank">Watch on YouTube</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <!-- Chat Section -->
        <div class="container mt-5">
            <div class="card shadow-sm rounded p-4">
                <h4 class="mb-3">Chat With Medi-Mind</h4>
                <div id="chat-box"
                    style="height: 300px; overflow-y: auto; background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #ddd;">
                    <!-- Messages will appear here -->
                </div>
                <div class="mt-3 d-flex">
                    <input type="text" id="chat-input" class="form-control me-2" placeholder="Type your message...">
                    <button class="btn btn-primary" onclick="sendMessage()">Send</button>
                </div>
            </div>
        </div>
    </main>
    <footer id="footer" class="footer">

        <div class="container footer-top">
            <div class="row gy-4 footer-containerx d-flex justify-content-evenly">

                <!-- About Section -->
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="./" class="logo d-flex align-items-center">
                        <span class="sitename">Medi-Mind</span>
                    </a>
                    <div class="footer-contact pt-3">
                        <p>Gwalior, Madhya Pradesh</p>
                        <p>India - 474003</p>
                    </div>
                    <div class="social-links d-flex mt-4">
                        <a href="./"><i class="bi bi-twitter-x"></i></a>
                        <a href="./"><i class="bi bi-facebook"></i></a>
                        <a href="./"><i class="bi bi-instagram"></i></a>
                        <a href="./"><i class="bi bi-linkedin"></i></a>
                        <a href="./"><i class="bi bi-youtube"></i></a>
                        <a href="./"><i class="bi bi-telegram"></i></a>
                    </div>
                </div>

                <!-- Useful Links -->
                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="../">Home</a></li>
                        <li><a href="./">Diseases</a></li>
                    </ul>
                </div>

                <!-- Our Services -->
                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Our Services</h4>
                    <ul>
                        <li><a href="./">Chat Support</a></li>
                    </ul>
                </div>

            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright</span> <strong class="px-1 sitename">Medi Mind</strong> <span>All Rights
                    Reserved</span></p>
        </div>

    </footer>


    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center active"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="../vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendors/aos/aos.js"></script>
    <script src="../vendors/glightbox/js/glightbox.min.js"></script>
    <script src="../vendors/swiper/swiper-bundle.min.js"></script>
    <script src="../vendors/purecounter/purecounter_vanilla.js"></script>

    <!--Main JS File-->
    <script src="../assets/js/main.js"></script>

    <script defer=""
        src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon="{&quot;rayId&quot;:&quot;93e09cc54ae74826&quot;,&quot;serverTiming&quot;:{&quot;name&quot;:{&quot;cfExtPri&quot;:true,&quot;cfL4&quot;:true,&quot;cfSpeedBrain&quot;:true,&quot;cfCacheStatus&quot;:true}},&quot;version&quot;:&quot;2025.4.0-1-g37f21b1&quot;,&quot;token&quot;:&quot;68c5ca450bae485a842ff76066d69420&quot;}"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
    </script>

    <script>
    let isFirstMessage = true;

    function sendMessage() {
        const input = document.getElementById('chat-input');
        const chatBox = document.getElementById('chat-box');
        const message = input.value.trim();

        if (message === '') return;

        // Show user message
        const userMsg = document.createElement('div');
        userMsg.innerHTML = `<strong>You:</strong> ${message}`;
        userMsg.classList.add('mb-2');
        chatBox.appendChild(userMsg);
        input.value = '';
        chatBox.scrollTop = chatBox.scrollHeight;

        // Prepare payload
        const payload = {
            message
        };
        if (isFirstMessage) {
            payload.disease = "<?php echo addslashes($diseaseFullName); ?>";
            isFirstMessage = false;
        }

        // Call backend
        fetch('_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                const botMsg = document.createElement('div');
                botMsg.innerHTML = `<strong>Doctor:</strong> ${data.reply}`;
                botMsg.classList.add('mb-2', 'text-muted');
                chatBox.appendChild(botMsg);
                chatBox.scrollTop = chatBox.scrollHeight;
            })
            .catch(err => {
                console.error('Error:', err);
            });
    }

    // Enter key to submit
    document.getElementById('chat-input').addEventListener("keypress", function(e) {
        if (e.key === "Enter") {
            sendMessage();
        }
    });
    </script>
    <script>
    function showLiveAlert(message, type = "success") {
        const alertPlaceholder = document.getElementById("liveAlertPlaceholder");

        const wrapper = document.createElement("div");
        wrapper.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

        alertPlaceholder.append(wrapper);

        setTimeout(() => {
            const alert = bootstrap.Alert.getOrCreateInstance(wrapper.querySelector('.alert'));
            alert.close();
        }, 4000);
    }
    </script>
</body>

</html>