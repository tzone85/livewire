<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ImageUpload extends Component
{
    use WithFileUploads;

    /**
     * @var \Livewire\TemporaryUploadedFile
     */
    public $image = [];

    public function save()
    {
        $this->validate([
            'image.*' => 'image|max:1024',  // 1MB max
        ]);

        foreach ($this->image as $image) {
            $image->store('public');

            // to save image with original name and not temp name in public directory
            // $this->image->storeAs('public', $this->image->getClientOriginalName());
        }
    }
    public function render()
    {
        return view('livewire.image-upload', [
            'images' => collect(Storage::files('public'))
                ->filter(function ($file) {
                    return in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), ['png', 'jpg', 'jpeg', 'gif', 'webp']);
                })
                ->map(function ($file) {
                    return Storage::url($file);
                })
        ]);
    }
}
