<?php
/**
 * Custom configuration to hide specific sections from patient dashboard
 * 
 * This file hides:
 * - Billing widget
 * - Amendments card
 * 
 * To use this configuration, include this file in your globals.php or 
 * add these settings to your global configuration.
 */

// Hide Billing widget from patient dashboard
$GLOBALS['hide_billing_widget'] = true;

// Disable Amendments feature
$GLOBALS['amendments'] = false;

// Hide specific dashboard cards
// This will be handled by the hidden cards setting
$GLOBALS['hidden_cards'] = [
    'card_amendments',
    'card_billing'
];
