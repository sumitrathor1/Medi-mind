<?php 
include "../assets/pages/_db.php"; 
$diseaseName = "Invalid ID";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT name FROM diseases WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $diseaseName = htmlspecialchars($row['name']);
    } else {
        $diseaseName = "Disease not found";
    }
}
?>
<html lang="en">
<head>
    <?php 
    include "../assets/pages/_page-head.php"; 
    header_path('../'); 
    ?>

    <title>Single Symptoms Disease | Medi-Mind</title>

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

    <style>
    .footer-container {
        display: flex;
        justify-content: space-evenly;
    }

    .image-icon {
        width: 50px;
        margin-bottom: 10%;
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
                    <li><a href="#hero" class="active">Home</a></li>
                    <li><a href="#disease">Disease</a></li>
                    <li><a href="#contact"></a></li>
                    <li><a href="./contributor.php"></a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <a class="btn-getstarted" href="#disease">Get Started</a>

        </div>
    </header>

    <main class="main">
        <!-- Features Section -->
        <section id="disease" class="features section mt-5">

            <!-- Section Title -->
            <div class="container section-title aos-init" data-aos="fade-up">
                <h2>Disease</h2>
                <p>Here is the list of disease</p>
            </div><!-- End Section Title -->

            <section id="disease-cards" class="disease-cards section" style="padding:0px">

                <div class="container">
                    <div class="row gy-4">
                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="0">
                            <a href="./disease.php?id=0" style="text-decoration: none; color: inherit;">
                                <div class="disease-box orange">
                                    <img src="assets/img/acne.png" class="image-icon" alt="">
                                    <h4>Acne (मुंहासे)</h4>
                                    <p>Skin condition causing pimples, blackheads, and inflammation. (त्वचा की स्थिति
                                        जिसके कारण मुंहासे, ब्लैकहेड्स और सूजन हो जाती है।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="100">
                            <a href="./disease.php?id=1" style="text-decoration: none; color: inherit;">
                                <div class="disease-box blue">
                                    <i class="bi bi-flower1"></i>
                                    <h4>Allergies (एलर्जी)</h4>
                                    <p>Immune response to substances like pollen, food, or dust. (पराग, भोजन या धूल जैसे
                                        पदार्थों के प्रति प्रतिरक्षा प्रतिक्रिया।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="200">
                            <a href="./disease.php?id=2" style="text-decoration: none; color: inherit;">
                                <div class="disease-box green">
                                    <i class="bi bi-thermometer-low"></i>
                                    <h4>Common Cold (साधारण जुकाम)</h4>
                                    <p>Viral infection causing sneezing, cough, and sore throat. (वायरल संक्रमण जिससे
                                        छींक, खांसी और गले में खराश होती है।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="300">
                            <a href="./disease.php?id=3" style="text-decoration: none; color: inherit;">
                                <div class="disease-box red">
                                    <i class="bi bi-slash-circle"></i>
                                    <h4>Constipation (कब्ज)</h4>
                                    <p>Difficulty in bowel movements or infrequent stools. (मल त्यागने में कठिनाई या मल
                                        का अनियमित रूप से आना।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="400">
                            <a href="./disease.php?id=4" style="text-decoration: none; color: inherit;">
                                <div class="disease-box orange">
                                    <i class="bi bi-wind"></i>
                                    <h4>Cough (खांसी)</h4>
                                    <p>Reflex action to clear the airway of irritants or mucus. (वायुमार्ग से जलन या
                                        बलगम हटाने की रिफ्लेक्स क्रिया।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="500">
                            <a href="./disease.php?id=5" style="text-decoration: none; color: inherit;">
                                <div class="disease-box blue">
                                    <i class="bi bi-emoji-dizzy"></i>
                                    <h4>Dizzy (चक्कर आना)</h4>
                                    <p>Feeling faint, unsteady, or lightheaded; balance issues. (बेहोशी, अस्थिरता या
                                        चक्कर जैसा महसूस होना।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="600">
                            <a href="./disease.php?id=6" style="text-decoration: none; color: inherit;">
                                <div class="disease-box green">
                                    <i class="bi bi-thermometer-high"></i>
                                    <h4>Fever (बुखार)</h4>
                                    <p>Elevated body temperature due to infection or illness. (संक्रमण या बीमारी के कारण
                                        शरीर का तापमान बढ़ जाना।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="700">
                            <a href="./disease.php?id=7" style="text-decoration: none; color: inherit;">
                                <div class="disease-box red">
                                    <img src="assets/img/headache.png" class="image-icon" alt="">
                                    <h4>Headache (सिरदर्द)</h4>
                                    <p>Pain in the head or upper neck. (सिर या ऊपरी गर्दन में दर्द।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="800">
                            <a href="./disease.php?id=8" style="text-decoration: none; color: inherit;">
                                <div class="disease-box orange">
                                    <i class="bi bi-fire"></i>
                                    <h4>Heartburn / Acid Reflux (सीने में जलन)</h4>
                                    <p>Burning sensation in chest caused by stomach acid. (पेट के अम्ल के कारण सीने में
                                        जलन।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="900">
                            <a href="./disease.php?id=9" style="text-decoration: none; color: inherit;">
                                <div class="disease-box blue">
                                    <img src="assets/img/indigestion.png" class="image-icon" alt="">
                                    <h4>Indigestion (अजीर्ण)</h4>
                                    <p>Discomfort in upper abdomen during or after eating. (खाने के दौरान या बाद में
                                        ऊपरी पेट में असुविधा।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="1000">
                            <a href="./disease.php?id=10" style="text-decoration: none; color: inherit;">
                                <div class="disease-box green">
                                    <i class="bi bi-stars"></i>
                                    <h4>Itching (खुजली)</h4>
                                    <p>Irritation on the skin causing desire to scratch. (त्वचा पर जलन जो खुजाने की
                                        इच्छा उत्पन्न करती है।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="1100">
                            <a href="./disease.php?id=11" style="text-decoration: none; color: inherit;">
                                <div class="disease-box red">
                                    <i class="bi bi-person-walking"></i>
                                    <h4>Joints pain (जोड़ों का दर्द)</h4>
                                    <p>Pain or inflammation in the joints. (जोड़ों में दर्द या सूजन।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="1200">
                            <a href="./disease.php?id=12" style="text-decoration: none; color: inherit;">
                                <div class="disease-box orange">
                                    <i class="bi bi-emoji-expressionless"></i>
                                    <h4>Mild Anxiety (हल्का तनाव)</h4>
                                    <p>Feeling of worry or fear that is not overwhelming. (हल्का भय या चिंता महसूस
                                        होना।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="1300">
                            <a href="./disease.php?id=13" style="text-decoration: none; color: inherit;">
                                <div class="disease-box blue">
                                    <i class="bi bi-thermometer-sun"></i>
                                    <h4>Mild Fever (हल्का बुखार)</h4>
                                    <p>Slight increase in body temperature. (शरीर के तापमान में हल्की वृद्धि।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="1400">
                            <a href="./disease.php?id=14" style="text-decoration: none; color: inherit;">
                                <div class="disease-box green">
                                    <i class="bi bi-fire"></i>
                                    <h4>Minor Burn (छोटा जलन)</h4>
                                    <p>Superficial skin injury from heat or chemicals. (गर्मी या रसायन के कारण त्वचा की
                                        सतही चोट।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="1500">
                            <a href="./disease.php?id=15" style="text-decoration: none; color: inherit;">
                                <div class="disease-box red">
                                    <i class="bi bi-emoji-frown"></i>
                                    <h4>Mouth ulcer (मुंह के छाले)</h4>
                                    <p>Small painful sores in the mouth. (मुंह में छोटे और दर्दनाक घाव।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="1600">
                            <a href="./disease.php?id=16" style="text-decoration: none; color: inherit;">
                                <div class="disease-box orange">
                                    <img src="assets/img/Muscle-Pain.png" class="image-icon" alt="">
                                    <h4>Muscle Pain (मांसपेशियों में दर्द)</h4>
                                    <p>Soreness or discomfort in muscles. (मांसपेशियों में अकड़न या दर्द।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="1700">
                            <a href="./disease.php?id=17" style="text-decoration: none; color: inherit;">
                                <div class="disease-box blue">
                                    <i class="bi bi-droplet-half"></i>
                                    <h4>Nausea (मतली)</h4>
                                    <p>Feeling of wanting to vomit. (उल्टी जैसा महसूस होना।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="1800">
                            <a href="./disease.php?id=18" style="text-decoration: none; color: inherit;">
                                <div class="disease-box green">
                                    <i class="bi bi-emoji-frown"></i>
                                    <h4>Sore Throat (गले में खराश)</h4>
                                    <p>Pain or irritation in the throat. (गले में दर्द या जलन।)</p>
                                </div>
                            </a>
                        </div>

                        <div class="col-xl-3 col-md-6 aos-init" data-aos="zoom-in" data-aos-delay="1900">
                            <a href="./disease.php?id=19" style="text-decoration: none; color: inherit;">
                                <div class="disease-box red">
                                    <img src="assets/img/Swelling.png" class="image-icon" alt="">
                                    <h4>Swelling (सूजन)</h4>
                                    <p>Enlargement of a body part due to inflammation. (सूजन के कारण शरीर के भाग का बड़ा
                                        हो जाना।)</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>


            </section>
            <!-- /Features Cards Section -->

        </section><!-- /Features Section -->

        <!-- Features Cards Section -->
        <section class="features section">
            <?php 
                include "feedback.php";
            ?>
        </section>



        

        <!-- /Contact Section -->


    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center active"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="vendors/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendors/php-email-form/validate.js"></script>
    <script src="vendors/aos/aos.js"></script>
    <script src="vendors/glightbox/js/glightbox.min.js"></script>
    <script src="vendors/swiper/swiper-bundle.min.js"></script>
    <script src="vendors/purecounter/purecounter_vanilla.js"></script>

    <!--Main JS File-->
    <script src="assets/js/main.js"></script>

    <script defer=""
        src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
        integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
        data-cf-beacon="{&quot;rayId&quot;:&quot;93e09cc54ae74826&quot;,&quot;serverTiming&quot;:{&quot;name&quot;:{&quot;cfExtPri&quot;:true,&quot;cfL4&quot;:true,&quot;cfSpeedBrain&quot;:true,&quot;cfCacheStatus&quot;:true}},&quot;version&quot;:&quot;2025.4.0-1-g37f21b1&quot;,&quot;token&quot;:&quot;68c5ca450bae485a842ff76066d69420&quot;}"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
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

    <script>
    let clickCount = 0;
    let clickTimer;

    document.getElementById("heroImage").addEventListener("click", function() {
        clickCount++;

        // Start/reset timer
        if (!clickTimer) {
            clickTimer = setTimeout(() => {
                clickCount = 0;
                clearTimeout(clickTimer);
                clickTimer = null;
            }, 3000); // 3 seconds
        }

        if (clickCount === 4) {
            // Reset and redirect
            clearTimeout(clickTimer);
            window.location.href = "login.php"; // change to your login page
        }
    });
    </script>
    <script src="assets/js/feedback.js"></script>
</body>

</html>



<?php
include "connection.php";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize and collect form data
        $name = htmlspecialchars(trim($_POST['name']));
        $email = htmlspecialchars(trim($_POST['email']));
        $subject = htmlspecialchars(trim($_POST['subject']));
        $message = htmlspecialchars(trim($_POST['message']));

        // Insert data into database
        $sql = "INSERT INTO contact_form (name, email, subject, message) 
                VALUES (:name, :email, :subject, :message)";
        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':subject' => $subject,
            ':message' => $message
        ]);

           echo "
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showLiveAlert('Your message has been sent. Thank you!', 'success');
        });
    </script>
    ";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>