<?php require_once "../inc/config.inc"; ?>
<?php include ROOT_PATH . "inc/headtags.inc"; ?>

<?php 
  session_start();
  if (!isset($_SESSION['studentID'])) {
    $auth_only_nav = true;
  }
?>

<body>
  <?php include ROOT_PATH . "inc/header.inc"; ?>

  <style>
    body {
      background-color: #e6f7fc;
    }

    .resource-section {
      min-width: 75%;
      margin: 0 auto;
      padding: 3rem 1rem;
    }

    .intro-box {
      background-color: #fff;
      border-radius: 20px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      padding: 2rem;
      margin-bottom: 2rem;
      text-align: center;
    }

    .intro-box h1 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }

    .intro-box p {
      font-size: 1rem;
      color: #555;
    }

    .accordion-button {
      font-weight: 600;
    }

    .accordion-body img {
      max-width: 120px;
      height: auto;
      margin-bottom: 1rem;
    }

    .accordion-body a {
      display: inline-block;
      margin-top: 0.5rem;
    }

    .accordion-body {
      text-align: center;
    }
  </style>

  <main>
    <div class="resource-section">

      <!-- Intro Box -->
      <div class="intro-box">
        <h1>📚 Wellbeing Resources</h1>
        <p>Support services, campus programs, and success tools available at SCSU.</p>
      </div>

      <!-- Accordion Container -->
      <div class="accordion" id="resourcesAccordion">

        <!-- Counseling Services -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#counseling" aria-expanded="false" aria-controls="counseling">
              Counseling Services
            </button>
          </h2>
          <div id="counseling" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#resourcesAccordion">
            <div class="accordion-body">
              <p>Provides free, confidential mental health services to students including therapy and crisis support.</p>
              <img src="/SCSU-CSC400/public/images/counseling.png" alt="Counseling QR Code">
              <br>
              <a href="https://inside.southernct.edu/counseling" target="_blank" class="btn btn-outline-primary btn-sm">Visit Site</a>
            </div>
          </div>
        </div>

        <!-- Recovery, Alcohol and Drug Services -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingRecovery">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#recovery" aria-expanded="false" aria-controls="recovery">
              Recovery, Alcohol & Drug Services
            </button>
          </h2>
          <div id="recovery" class="accordion-collapse collapse" aria-labelledby="headingRecovery" data-bs-parent="#resourcesAccordion">
            <div class="accordion-body">
              <p>Offers free, confidential consultation and outreach services, self-assessment tools, and educational programs.</p>
              <img src="/SCSU-CSC400/public/images/Drug.png" alt="Recovery QR Code">
              <br>
              <a href="https://inside.southernct.edu/aod" target="_blank" class="btn btn-outline-primary btn-sm">Visit Site</a>
            </div>
          </div>
        </div>

        <!-- Health Services -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingHealth">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#health" aria-expanded="false" aria-controls="health">
              Health Services
            </button>
          </h2>
          <div id="health" class="accordion-collapse collapse" aria-labelledby="headingHealth" data-bs-parent="#resourcesAccordion">
            <div class="accordion-body">
              <p>Offers routine medical care, health assessments, immunizations, and consultations to SCSU students.</p>
              <img src="/SCSU-CSC400/public/images/health-services.png" alt="Health Services QR Code">
              <br>
              <a href="https://inside.southernct.edu/health-services" target="_blank" class="btn btn-outline-primary btn-sm">Visit Site</a>
            </div>
          </div>
        </div>

        <!-- Violence Prevention & Advocacy -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingViolence">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#violence" aria-expanded="false" aria-controls="violence">
              Violence Prevention & Advocacy
            </button>
          </h2>
          <div id="violence" class="accordion-collapse collapse" aria-labelledby="headingViolence" data-bs-parent="#resourcesAccordion">
            <div class="accordion-body">
              <p>Supports survivors of sexual violence and works to prevent relationship abuse through education and outreach.</p>
              <img src="/SCSU-CSC400/public/images/violence.png" alt="Violence Prevention QR Code">
              <br>
              <a href="https://inside.southernct.edu/violence-prevention" target="_blank" class="btn btn-outline-primary btn-sm">Visit Site</a>
            </div>
          </div>
        </div>

        <!-- Wellbeing Center -->
        <div class="accordion-item">
          <h2 class="accordion-header" id="headingWellbeing">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#wellbeing" aria-expanded="false" aria-controls="wellbeing">
              Wellbeing Center
            </button>
          </h2>
          <div id="wellbeing" class="accordion-collapse collapse" aria-labelledby="headingWellbeing" data-bs-parent="#resourcesAccordion">
            <div class="accordion-body">
              <p>Promotes holistic student wellness through workshops, events, and self-care initiatives.</p>
              <img src="/SCSU-CSC400/public/images/wellbeing.png" alt="Wellbeing QR Code">
              <br>
              <a href="https://inside.southernct.edu/wellbeing" target="_blank" class="btn btn-outline-primary btn-sm">Visit Site</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </main>
    <!-- Footer -->
  <div class="push"></div>
  <?php include ROOT_PATH . "inc/footer.inc"; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
