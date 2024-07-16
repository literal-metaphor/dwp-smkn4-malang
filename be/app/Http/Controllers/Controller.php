<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

abstract class Controller
{
    /**
     * Handle the file upload.
     *
     * @param \Illuminate\Http\Request $req The request object.
     * @param string $inputName The name of the input field in the request. Defaults to 'file'.
     * @param string $disk The disk in which the file will be stored. Defaults to 'public'.
     *
     * @return string The public basename of the uploaded file.
     */
    protected function uploadFile(Request $req, string $inputName = 'file', string $disk = 'public'): string
    {
        $req->validate([
            $inputName => 'required|file|mimes:jpg,jpeg,png,svg',
        ]);

        // Retrieve the file from the request
        $file = $req->file($inputName);

        // Store the file and get the stored filename
        $filename = $file->store('uploads', $disk);

        // Return the public basename of the stored file
        return basename($filename);
    }

    /**
     * Delete a file from storage.
     *
     * @param string $filename The name of the file to delete.
     * @param string $disk The disk in which the file is stored. Defaults to 'public'.
     *
     * @return bool Whether the file was successfully deleted.
     */
    protected function deleteFile(string $filename, string $disk = 'public'): bool
    {
        // Check if the file exists
        $path = Storage::disk($disk)->path("uploads/$filename");

        // Delete the file if it exists
        if (file_exists($path)) {
            return Storage::disk($disk)->delete("uploads/$filename");
        }

        // If the file does not exist, return false
        return false;
    }

    /**
     * Validate input and prevent overinput
     *
     * @param \Illuminate\Http\Request $request The request object.
     * @param array $rules The rules to validate.
     *
     * @return array
     */
    protected function validateRequest(Request $request, array $rules): array
    {
        $request->only(array_keys($rules));
        $request->validate($rules);
        return $request->all();
    }
}
