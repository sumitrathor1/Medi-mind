document.addEventListener("DOMContentLoaded", function () {
  // Get all required DOM elements
  const radios = document.querySelectorAll('input[name="accountType"]');
  const proofUpload = document.getElementById("proofUpload");
  const proofInput = document.getElementById("proof");
  const passwordInput = document.getElementById("password");
  const warning = document.getElementById("passwordWarning");
  const form = document.getElementById("registerForm");
  const registerBtn = document.getElementById("registerBtn");
  const doctorFields = document.querySelectorAll(".doctor-field");
  const studentFields = document.querySelectorAll(".student-field");

  function toggleProofField() {
    const selected = document.querySelector(
      'input[name="accountType"]:checked'
    ).value;

    // Toggle proof field
    if (selected === "student" || selected === "doctor") {
      proofUpload.style.display = "block";
      proofInput.setAttribute("required", "required");
    } else {
      proofUpload.style.display = "none";
      proofInput.removeAttribute("required");
      proofInput.value = "";
    }

    // Doctor-specific fields
    document.querySelectorAll(".doctor-field").forEach((field) => {
      field.style.display = selected === "doctor" ? "block" : "none";
      const input = field.querySelector("input, select");
      if (input) {
        selected === "doctor"
          ? input.setAttribute("required", "required")
          : input.removeAttribute("required");
      }
    });

    // Student-specific fields
    document.querySelectorAll(".student-field").forEach((field) => {
      field.style.display = selected === "student" ? "block" : "none";
      const input = field.querySelector("input, select");
      if (input) {
        selected === "student"
          ? input.setAttribute("required", "required")
          : input.removeAttribute("required");
      }
    });
  }

  // Attach change listener to radio buttons
  radios.forEach((radio) => radio.addEventListener("change", toggleProofField));
  toggleProofField(); // Initial setup

  // File size validation (max 2 MB)
  proofInput.addEventListener("change", function () {
    const file = this.files[0];
    if (file && file.size > 2 * 1024 * 1024) {
      alert("File size must be less than 2 MB.");
      this.value = ""; // Clear file input
    }
  });

  // Password strength checker
  function isStrongPassword(password) {
    return (
      /.{8,}/.test(password) &&
      /[A-Z]/.test(password) &&
      /[a-z]/.test(password) &&
      /[0-9]/.test(password) &&
      /[!@#$%^&*(),.?":{}|<>]/.test(password)
    );
  }

  // Show/hide password warning live
  passwordInput.addEventListener("input", function () {
    if (!isStrongPassword(this.value)) {
      warning.style.display = "block";
    } else {
      warning.style.display = "none";
    }
  });
  // Form submit handler
  form.addEventListener("submit", function (e) {
    e.preventDefault();

    // Show loading gif on button
    registerBtn.disabled = true;
    const originalBtnText = registerBtn.innerHTML;
    registerBtn.innerHTML = `<img src="assets/img/loding.gif" alt="Loading" height="60">`;
    registerBtn.style.backgroundColor = "white";

    const password = passwordInput.value;

    // Password strength validation
    if (!isStrongPassword(password)) {
      warning.style.display = "block";
      return;
    }

    const formData = new FormData(form);

    fetch("assets/pages/api/_register.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Registration successful!");
          form.reset();
          toggleProofField(); // reset proof field visibility
          console.log(data.message);
        } else {
          alert("Error: " + data.message);
        }
      })
      .catch((err) => {
        console.error("Fetch error:", err);
        alert("Something went wrong. Please try again.");
      })
      .finally(() => {
        // Reset button to original state
        registerBtn.disabled = false;
        registerBtn.innerHTML = originalBtnText;
      });
  });
});