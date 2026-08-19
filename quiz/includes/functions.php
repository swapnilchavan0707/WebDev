<?php

/**
 * Clean user input to prevent XSS attacks
 */
function cleanInput($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate unique Exam ID
 * Example: EXAM-65B2F1A9C3D2
 */
function generateExamId() {
    return "EXAM-" . strtoupper(uniqid());
}

/**
 * Calculate percentage score
 */
function calculatePercentage($score, $total) {
    if ($total == 0) return 0;
    return round(($score / $total) * 100, 2);
}

/**
 * Check if answer is correct
 * (Useful for result processing)
 */
function isCorrectAnswer($correct, $selected) {
    return strtoupper($correct) === strtoupper($selected);
}

/**
 * Format date/time
 */
function formatDateTime($datetime) {
    return date("d M Y, h:i A", strtotime($datetime));
}

/**
 * Redirect helper function
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Flash message system (simple session messages)
 */
function setFlash($key, $message) {
    $_SESSION['flash'][$key] = $message;
}

function getFlash($key) {
    if (isset($_SESSION['flash'][$key])) {
        $msg = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $msg;
    }
    return null;
}

?>