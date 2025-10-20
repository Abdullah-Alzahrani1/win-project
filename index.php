<?php
include './inc/db.php';  

$errors = [];
$user = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);

    if (empty($firstName)) {
        $errors['firstName'] = "يرجى إدخال الاسم الأول";
    }

    if (empty($lastName)) {
        $errors['lastName'] = "يرجى إدخال الاسم الأخير";
    }

    if (empty($email)) {
        $errors['email'] = "يرجى إدخال البريد الإلكتروني";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "البريد الإلكتروني غير صالح";
    }

    
    if (empty($errors)) {
        
        $stmt = $conn->prepare("INSERT INTO users (firstName, lastName, email) VALUES (?, ?, ?)");
        if ($stmt === false) {
            $errors['db'] = "خطأ في إعداد الاستعلام: " . $conn->error;
        } else {
            $stmt->bind_param("sss", $firstName, $lastName, $email);

            if ($stmt->execute()) {
               
                header("Location: index.php");
                exit();
            } else {
                $errors['db'] = "خطأ في حفظ البيانات: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}

include './inc/select.php'; 
include './inc/dbclose.php';

?> <script src="./js/confetti.js"></script>

<?php include_once './parts/header.php'; ?>

<div class="bg-info text-white text-center py-5 mb-5 shadow-sm">
    <div class="col-md-5 p-lg-5 mx-auto my-5">
        <h1 class="display-4 fw-normal">مشروع الفائز من عبدالله</h1>
        <p class="lead fw-normal">الوقت المتبقي</p>
        <h3 id="countdown"></h3>   
    </div>
</div>

<div class="container">
<div class="container" style="max-width: 600px;">
  <div class="card shadow-sm border-0 mb-5">
    <div class="card-body p-4">
      <h3 class="text-center mb-4 text-dark">الرجاء ادخل معلوماتك</h3>
      
      <form class="mt-3" action="index.php" method="POST">
        <div class="mb-3">
          <label for="firstName" class="form-label">الاسم الأول</label>
          <input type="text" name="firstName" class="form-control <?php echo !empty($errors['firstName']) ? 'is-invalid' : ''; ?>" id="firstName"
                 value="<?php echo isset($firstName) ? htmlspecialchars($firstName) : ''; ?>">
          <?php if (!empty($errors['firstName'])): ?>
            <div class="invalid-feedback"><?php echo $errors['firstName']; ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label for="lastName" class="form-label">الاسم الأخير</label>
          <input type="text" name="lastName" class="form-control <?php echo !empty($errors['lastName']) ? 'is-invalid' : ''; ?>" id="lastName"
                 value="<?php echo isset($lastName) ? htmlspecialchars($lastName) : ''; ?>">
          <?php if (!empty($errors['lastName'])): ?>
            <div class="invalid-feedback"><?php echo $errors['lastName']; ?></div>
          <?php endif; ?>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">البريد الالكتروني</label>
          <input type="text" name="email" class="form-control <?php echo !empty($errors['email']) ? 'is-invalid' : ''; ?>" id="email"
                 value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
          <?php if (!empty($errors['email'])): ?>
            <div class="invalid-feedback"><?php echo $errors['email']; ?></div>
          <?php endif; ?>
        </div>

        <?php if (!empty($errors['db'])): ?>
          <div class="alert alert-danger"><?php echo $errors['db']; ?></div>
        <?php endif; ?>

        <div class="d-grid gap-2 col-4 mx-auto mt-4">
          <button type="submit" name="submit" class="btn btn-primary btn-lg">إرسال المعلومات</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="loader-con text-center my-4">
  <div id="loader">
    <canvas id="circularLoader" width="200" height="200"></canvas>
  </div>
</div>

<div class="d-grid gap-2 col-6 mx-auto my-5">
   <button onclick="startConfetti();" type="button" id="winner" class="btn btn-success btn-lg">الفائز بالسحب هو</button>
</div>
  

<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalLabel">الفائز</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <h1>
          <?php 
          if (!empty($user)) {
              echo htmlspecialchars($user['firstName']) . ' ' . htmlspecialchars($user['lastName']);
          } else {
              echo 'لا يوجد فائز حالياً';
          }
          ?>
        </h1> 
      </div>
    </div>
  </div>
</div>

<div class="container my-5">
  <div class="row justify-content-center" id="cards">
    <?php if (!empty($user)): ?>
      <div class="col-md-4 col-sm-6 mb-4">
        <div class="card h-100 shadow-sm text-center">
          <div class="card-body">
            <h5 class="card-title text-success">
              <?php echo htmlspecialchars($user['firstName']) . ' ' . htmlspecialchars($user['lastName']); ?>
            </h5>
            <p class="card-text text-muted">
              البريد: <?php echo htmlspecialchars($user['email']); ?>
            </p>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</div>
 

<script>
window.onload = function() {
  var countDownDate = new Date("Oct 25, 2025 20:00:00").getTime();
  var x = setInterval(function() {
    var counter = document.querySelector("#countdown");
    var now = new Date().getTime();
    var distance = countDownDate - now;
    if (!counter) return;

    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    counter.innerHTML = days + " يوم " + hours + " ساعة " + minutes + " دقيقة " + seconds + " ثانية ";

    if (distance < 0) {
      clearInterval(x);
      counter.innerHTML = "لقد وصلت متاخرا";
    }
  }, 1000);
};

 </script>

<?php include_once './parts/footer.php'; ?>
