<?php
$data = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR
    42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');

// Create a temp file and write the decoded image.
$temp = tmpfile();
fwrite($temp, $data);

// Get the path of the temp file.
$tempPath = stream_get_meta_data($temp)['uri'];

// Initialize the UploadedFile.
$imageName = uniqid().time().".png";
$file = new \Illuminate\Http\UploadedFile($tempPath, $imageName, null, null, true);

// Test if the UploadedFile works normally.
echo $file->getClientOriginalExtension(); // Shows 'png'

$file->storeAs('images', 'test.png'); // Creates image in '\storage\app\images'.

// Delete the temp file.
fclose($temp);