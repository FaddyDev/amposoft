<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../assets/phpmailer/src/Exception.php';
require '../assets/phpmailer/src/PHPMailer.php';
require '../assets/phpmailer/src/SMTP.php';

if(isset($_POST['contact_name']))
{
    //require 'PHPMailer/PHPMailerAutoload.php';
    $contact_email = $_POST['contact_email'] ?? '';
    $contact_name = $_POST['contact_name'];
    $contact_phone = $_POST['contact_phone'];
    $contact_message = $_POST['contact_message'];
    $message = '<p>Hi. 
                    <br>We have received contact email from;
                    <br><strong>Name:</strong> '.$contact_name.'
                    <br><strong>Phone:</strong> '.$contact_phone;
    if($contact_email !== '')
    {
        $message .= '<br><strong>Email:</strong> '.$contact_email.'
                    <br> The message is as shown below. Read then respond via phone or email.';
    }
    else
    {
        $message .= '<br> The message is as shown below. Read then respond via phone number.';
    }
    $message .=     '<br><br>'.$contact_message.'</p>';
                    ;
    $mail = new PHPMailer(true);

    try {
        //Server settings
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'mail.rentalware.co.ke';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'noreply@rentalware.co.ke';                     //SMTP username
        $mail->Password   = 'N0reply@1';                               //SMTP password
        $mail->SMTPSecure = '';//PHPMailer::ENCRYPTION_SMTPS;//'tls'            //Enable implicit TLS encryption
        $mail->Port       = 587;                                 //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
        //Recipients
        $mail->setFrom('noreply@rentalware.co.ke', 'Amposoft Solutions Website');
        $mail->addAddress('amposolutions@gmail.com', 'Amposoft Solutions Staff');     //Add a recipient
        if($contact_email !== '')
        {
            $mail->addReplyTo($contact_email, $contact_name);  //Reply to the user who sent the message
        }        
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');
    
        //Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $_POST['contact_subject'];
        $mail->Body    = $message;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        
        //$mail->msgHTML($message);
        if (!$mail->send()) {
            echo json_encode(['status'=>'fail','message'=>'Oops! There was an error. Click ok to retry.']);
        }
        else {
            echo json_encode(['status'=>'success','message'=>'Message sent! We will get back to you as soon as possible. Thank you.']);
        }
    } catch (Exception $e) {
        echo json_encode(['status'=>'fail','message'=>'Message could not be sent. Mailer Error: '.$mail->ErrorInfo]);
    }
}
?>