<?php
header('Content-Type: application/json');

// Server-side validation
if (empty($_POST['name']) || empty($_POST['email'])) {
  echo json_encode(['success' => false, 'error' => 'All fields are required']);
  exit;
}

$name = htmlspecialchars($_POST['name']);
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

if (!$email) {
  echo json_encode(['success' => false, 'error' => 'Invalid email format']);
  exit;
}

// Mock database (JSON file)
$dataFile = 'data.json';

// Initialize if file doesn't exist
if (!file_exists($dataFile)) {
  file_put_contents($dataFile, json_encode([]));
}

$existingData = json_decode(file_get_contents($dataFile), true);

// Check for duplicate email
foreach ($existingData as $record) {
  if ($record['email'] === $email) {
    echo json_encode(['success' => false, 'error' => 'Email already exists']);
    exit;
  }
}

// Save new record
$newRecord = [
  'id' => uniqid(),
  'name' => $name,
  'email' => $email,
  'timestamp' => date('Y-m-d H:i:s')
];
$existingData[] = $newRecord;

file_put_contents($dataFile, json_encode($existingData, JSON_PRETTY_PRINT));

echo json_encode(['success' => true, 'message' => 'Record added successfully']);
?>