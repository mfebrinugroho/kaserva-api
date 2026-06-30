<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
  /**
   * Process a simulated core business transaction.
   */
  public function upload(UploadedFile $file, string $folder): string
  {
    $filename = Str::uuid() . '.' . $file->extension();

    return $file->storeAs($folder, $filename, 'public');
  }

  public function delete(?string $path): void
  {
    if ($path && Storage::disk('public')->exists($path)) {
      Storage::disk('public')->delete($path);
    }
  }

  public function replace(?string $oldPath, UploadedFile $file, string $folder): string
  {
    $this->delete($oldPath);

    return $this->upload($file, $folder);
  }
}
