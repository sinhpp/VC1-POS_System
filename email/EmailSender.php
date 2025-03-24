<?php

class EmailSender
{
    private $mailer;
    
    public function __construct()
    {
        // Load PHPMailer
        require_once __DIR__ . '/../vendor/autoload.php';
        
        $this->mailer = new \PHPMailer\PHPMailer\PHPMailer(true);
        
        // Configure SMTP settings
        $this->mailer->isSMTP();
        $this->mailer->Host = $_ENV['SMTP_HOST'] ?? 'smtp.example.com';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = $_ENV['SMTP_USERNAME'] ?? 'zenngii168@gamil.com';
        $this->mailer->Password = $_ENV['SMTP_PASSWORD'] ?? 'hdzj larg wckf ziyq';
        $this->mailer->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = $_ENV['SMTP_PORT'] ?? 587;
        
        // Set default sender
        $this->mailer->setFrom(
            $_ENV['MAIL_FROM_ADDRESS'] ?? 'asiashop@gmail.com', 
            $_ENV['MAIL_FROM_NAME'] ?? 'ASIA Shop'
        );
    }
    
    /**
     * Send an email
     * 
     * @param string|array $to Recipient email address(es)
     * @param string $subject Email subject
     * @param string $body Email body (HTML)
     * @param array $options Additional options (cc, bcc, attachments, etc.)
     * @return bool
     */
    public function send($to, $subject, $body, $options = [])
    {
        try {
            // Reset mailer
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            $this->mailer->clearCCs();
            $this->mailer->clearBCCs();
            
            // Set recipient(s)
            if (is_array($to)) {
                foreach ($to as $recipient) {
                    $this->mailer->addAddress($recipient);
                }
            } else {
                $this->mailer->addAddress($to);
            }
            
            // Set CC recipients
            if (!empty($options['cc'])) {
                if (is_array($options['cc'])) {
                    foreach ($options['cc'] as $cc) {
                        $this->mailer->addCC($cc);
                    }
                } else {
                    $this->mailer->addCC($options['cc']);
                }
            }
            
            // Set BCC recipients
            if (!empty($options['bcc'])) {
                if (is_array($options['bcc'])) {
                    foreach ($options['bcc'] as $bcc) {
                        $this->mailer->addBCC($bcc);
                    }
                } else {
                    $this->mailer->addBCC($options['bcc']);
                }
            }
            
            // Add attachments
            if (!empty($options['attachments'])) {
                foreach ($options['attachments'] as $attachment) {
                    $this->mailer->addAttachment($attachment);
                }
            }
            
            // Set email content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            
            // Create plain text version if not provided
            if (empty($options['altBody'])) {
                $this->mailer->AltBody = strip_tags($body);
            } else {
                $this->mailer->AltBody = $options['altBody'];
            }
            
            // Send email
            return $this->mailer->send();
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            // Log error
            error_log('Email sending failed: ' . $e->getMessage());
            return false;
        }
    }
}

