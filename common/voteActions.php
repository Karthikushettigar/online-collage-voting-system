<?php
require '../common/connect.php';

session_start();

define('ENCRYPTION_KEY', getenv('ENCRYPTION_KEY'));

if(isset($_SESSION['id']))
{
?>

<!-- Start Voting -->
<?php
if (isset($_POST['startElection'])) {
    $startElection = "UPDATE login SET voteStatus=1 WHERE id=?";
    $stmt = mysqli_prepare($conn, $startElection);
    mysqli_stmt_bind_param($stmt, 's', $_SESSION['id']);
    $result = mysqli_stmt_execute($stmt);
    $_SESSION['successMessage']="The Election has been started.";
    header("Location:../admin/admin.php");
    exit();
}
?>

<!-- Stop Voting -->
<?php
if (isset($_POST['stopElection'])) {
    $stopElection = "UPDATE login SET voteStatus=2 WHERE id=?";
    $stmt = mysqli_prepare($conn, $stopElection);
    mysqli_stmt_bind_param($stmt, 's', $_SESSION['id']);
    $result = mysqli_stmt_execute($stmt);
    $_SESSION['successMessage']="The Election has ended.";
    header("Location:../admin/admin.php");
    exit();
}
?>

<!-- Declare Result -->
<?php
if (isset($_POST['declareResults'])) {
    $declareResults = "UPDATE login SET voteStatus=3 WHERE id=?";
    $stmt = mysqli_prepare($conn, $declareResults);
    mysqli_stmt_bind_param($stmt, 's', $_SESSION['id']);
    $result = mysqli_stmt_execute($stmt);
    $_SESSION['successMessage']="The Election Results have been declared.";
    header("Location:../admin/admin.php");
    exit();
}
?>

<!-- Restart Election -->
<?php
if (isset($_POST['newElection'])) {
   
    $deleteCandidates = "DELETE FROM candidates";
    $resultCandidates = mysqli_query($conn, $deleteCandidates);
    
    $deleteCampaigns = "DELETE FROM campaign";
    $resultCampaigns = mysqli_query($conn, $deleteCampaigns);
    
    $resetVoteStatus = "UPDATE login SET voteStatus=0";
    $resultReset = mysqli_query($conn, $resetVoteStatus);
    $_SESSION['successMessage']="The Election will be restarted.";
    header("Location:../admin/admin.php");
    exit();
}
?>

<!-- Submit Vote -->
<?php
if (isset($_POST['submitVote'])) {
    $updateVoteFlag = "UPDATE login SET voteStatus=1 WHERE id=?";
    $stmt = mysqli_prepare($conn, $updateVoteFlag);
    mysqli_stmt_bind_param($stmt, 's', $_SESSION['id']);
    $result = mysqli_stmt_execute($stmt);

    // Encrypt
    function encrypt($data, $key) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($data, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }

    // Add Vote count
    foreach ($_POST as $key => $value) {
        if ($key != 'submitVote') {
            $query = "SELECT voteCount FROM candidates WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 's', $value);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $currentVoteCount);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            if ($currentVoteCount !== null) {
                // Decrypt current vote count
                list($encrypted_data, $iv) = explode('::', base64_decode($currentVoteCount), 2);
                $decryptedVoteCount = openssl_decrypt($encrypted_data, 'aes-256-cbc', ENCRYPTION_KEY, 0, $iv);

                // Increment vote count
                $newVoteCount = (int)$decryptedVoteCount + 1;
            } else {
                $newVoteCount = 1; 
            }

            // Encrypt updated vote count
            $encryptedVoteCount = encrypt($newVoteCount, ENCRYPTION_KEY);

            // Update encrypted vote count to database
            $query = "UPDATE candidates SET voteCount = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'ss', $encryptedVoteCount, $value);
            $result = mysqli_stmt_execute($stmt);
            if ($result) {
                echo "Vote for nominee with id $value updated successfully.<br>";
            } else {
                echo "Error updating vote for nominee with id $value: " . mysqli_error($conn) . "<br>";
            }
            mysqli_stmt_close($stmt);
        }
    }

    header("Location:../users/successVote.php");
    exit();
}
?>

<?php
}
else{
    header("Location:../login.php");
    exit();
}
?>
