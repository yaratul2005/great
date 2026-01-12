<?php

namespace App\Helpers;

use App\Models\Setting;

class SMTPMailer {
    private $host;
    private $port;
    private $user;
    private $pass;
    private $from;
    private $project;
    private $conn;

    public function __construct() {
        $settingModel = new Setting();
        $this->host = $settingModel->get('smtp_host');
        $this->port = $settingModel->get('smtp_port');
        $this->user = $settingModel->get('smtp_user');
        $this->pass = $settingModel->get('smtp_pass');
        $this->from = $settingModel->get('smtp_from');
        $this->project = $settingModel->get('site_name') ?? 'Great Ten Tech';
    }

    public function send($to, $subject, $body) {
        if (!$this->host || !$this->user) {
            error_log("SMTP Error: Credentials missing.");
            return false;
        }

        try {
            $this->connect();
            $this->auth();
            $this->sendMail($to, $subject, $body);
            $this->disconnect();
            return true;
        } catch (\Exception $e) {
            error_log("SMTP Error: " . $e->getMessage());
            return false;
        }
    }

    private function connect() {
        $socketProtocol = ($this->port == 465) ? "ssl://" : "tcp://";
        $this->conn = fsockopen($socketProtocol . $this->host, $this->port, $errno, $errstr, 15);

        if (!$this->conn) {
            throw new \Exception("Could not connect to SMTP host: $errstr ($errno)");
        }
        $this->getResponse(); 
        $this->sendCommand("EHLO " . gethostname());
        
        if ($this->port == 587) {
            $this->sendCommand("STARTTLS");
            stream_socket_enable_crypto($this->conn, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            $this->sendCommand("EHLO " . gethostname());
        }
    }

    private function auth() {
        $this->sendCommand("AUTH LOGIN");
        $this->sendCommand(base64_encode($this->user));
        $this->sendCommand(base64_encode($this->pass));
    }

    private function sendMail($to, $subject, $body) {
        $this->sendCommand("MAIL FROM: <{$this->from}>");
        $this->sendCommand("RCPT TO: <$to>");
        $this->sendCommand("DATA");

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "To: $to\r\n";
        $headers .= "From: {$this->project} <{$this->from}>\r\n";
        $headers .= "Subject: $subject\r\n";

        $this->sendCommand($headers . "\r\n" . $body . "\r\n.");
    }

    private function sendCommand($cmd) {
        fputs($this->conn, $cmd . "\r\n");
        $response = $this->getResponse();
        
        // Basic error check (not exhaustive)
        if (substr($response, 0, 1) >= 4) {
            throw new \Exception("SMTP Command Failed: $cmd | Response: $response");
        }
    }

    private function getResponse() {
        $data = "";
        while ($str = fgets($this->conn, 515)) {
            $data .= $str;
            if (substr($str, 3, 1) == " ") { break; }
        }
        return $data;
    }

    private function disconnect() {
        if ($this->conn) {
            fputs($this->conn, "QUIT\r\n");
            fclose($this->conn);
        }
    }
}
