    <?php 

    if (isset($_POST['submit'])) {
        
    
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];

        $errors = [
        'firstNameError' => '',
        'lastNameError'  => '',
        'emailError'     => '',
    ];
        
        $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
        $lastName  = mysqli_real_escape_string($conn, $_POST['lastName']);
        $email     = mysqli_real_escape_string($conn, $_POST['email']);

        //echo $firstName . ' ' . $lastName . ' ' . $email;

        $sql = "INSERT INTO users(firstName, lastName, email)
            VALUES ('$firstName', '$lastName', '$email')";

    if(empty($firstName)){
        echo 'يرجى ادخال الاسم الاول';
    }elseif(empty($lastName)){
        echo 'يرجى ادخال الاسم الاخير';
    }elseif(empty($email)){
        echo 'يرجى ادخال الايميل';
    }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        echo 'يرجى ادخال إيميل صحيح';
    }else{
        if(mysqli_query($conn, $sql)){
            header('Location: index.php');
        }else{
            echo 'Error: ' . mysqli_error($conn);
        }
    }
        
    }