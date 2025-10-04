<?php

// Simple test script to verify API endpoints
$baseUrl = 'http://localhost/sakai-vue/backend/backend-api/web';

echo "Testing Sakai Vue API Endpoints\n";
echo "===============================\n\n";

// Test API index
echo "1. Testing API Index:\n";
$response = file_get_contents($baseUrl . '/api');
$data = json_decode($response, true);
echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

// Test API health
echo "2. Testing API Health:\n";
$response = file_get_contents($baseUrl . '/api/health');
$data = json_decode($response, true);
echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

// Test Products list
echo "3. Testing Products List:\n";
$response = file_get_contents($baseUrl . '/api/products');
$data = json_decode($response, true);
echo "Response: " . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";

echo "API Test Completed!\n";
