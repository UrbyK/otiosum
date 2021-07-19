<?php 
    include_once './header.php';
?>

<?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
    header("Location: ./index.php");
    exit();
}
?>



<div class="container h-100">
    <div class="row justify-content-center align-items-center h-100">
        <div class="card border-0">
            <div class="card-body">

                <!-- Check if the verification return an error -->
                <?php if (isset($_GET['status']) && $_GET['status'] == "verify" && isset($_GET['email']) && isset($_GET['token'])) {

                    include_once './src/inc/dbh.inc.php';
                    $pdo = pdo_connect_mysql();
                    $email = $_GET['email'];
                    $token = $_GET['token'];

                    // check if account with given email and token exists
                    $stmt = $pdo->prepare("SELECT * FROM account WHERE UPPER(email) LIKE UPPER(?) and token = ?");
                    $stmt->execute([$email, $token]);
                    if ($stmt->rowCount() == 1){
                        $user = $stmt->fetch();
                        // check if account has already been verified
                        if ($user['active'] != 1) {
                            $userID=$user['id'];
                            $stmt = $pdo->prepare("UPDATE account SET active = 1 WHERE id=$userID");
                            // updates the value in account
                            if($stmt->execute()) {
                                $datetime = date("Y-m-d H:i:s");
                                
                                // statement to insert account activation in securtiy 
                                $stmt = $pdo->prepare("INSERT INTO security(date_registered, account_id) VALUES(?, ?)");
                                if($stmt->execute([$datetime, $userID])){
                                    exit("<script>window.location.href='verify?status=success'</script>");
                                } else {
                                    exit("<script>window.location.href='verify?status=error'</script>");
                                }

                            } else {
                                exit("<script>window.location.href='verify?status=error'</script>");
                            }
                        } else {
                            exit("<script>window.location.href='login'</script>");
                        }

                    } else {
                        exit("<script>window.location.href='verify?status=error'</script>");
                    }
                } ?>

                <?php if (isset($_GET['status']) && $_GET['status'] == "error"): ?> 
                    <h2 class="alert alert-danger rounded">Zgodila se je napaka, pri validaciji računa prosimo, da poskusite kasneje ali pa kontakirajte podporo!</h2>
                <?php elseif (isset($_GET['status']) && $_GET['status'] == "verify-email"): ?>
                    <h2 class="alert alert-primary rounded">Na vaš elektronski naslov smo poslali pošto za potrditev računa!</h2>
                <!-- Check if verification is successful and redirect to login after 'X' amount of time -->
                <?php elseif(isset($_GET['status']) && $_GET['status'] == "success"): ?>
                    <h2 class="alert alert-success rounded">Uspešno ste aktivirali račun! Samodejno boste preusmerjeni na stran za prijavo čez: <span id="countdowntimer">15</span> sekund!</h2>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>



<?php
    include_once './footer.php';
?>

<script type="text/javascript">
    if(document.getElementById("countdowntimer")){
        var timeleft = 15;
        var countdowntimer = setInterval(function(){
            timeleft--;
            document.getElementById("countdowntimer").textContent = timeleft;
            if(timeleft === 0) {
                window.location.replace("login");
                clearInterval(countdowntimer);
            }
        },1000);
    }
</script>