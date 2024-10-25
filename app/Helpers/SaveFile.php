<?php

namespace App\Helpers;

use Exception;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use RuntimeException;

class SaveFile
{
    public string $fileName = '';
    protected ?Model $model;
    protected $file;
    protected string $directory;
    protected array $allowed;

    public function __construct($model, $file, $directory, $allowed = [])
    {
        $this->model = $model;
        $this->file = $file;
        $this->directory = $directory;
        $this->allowed = $allowed;
    }

    /**
     * @throws Exception
     */
    public function save($fileName = null)
    {
        $this->saveFile();
        $reflection = new ReflectionClass($this->model);

        return $this->model->photo()->updateOrCreate(
            [
                'photoable_id' => $this->model->id,
                'photoable_type' => $reflection->getShortName()
            ], [
            'file_name' => $fileName
        ]);
    }

    public function saveFile()
    {
        $extension = $this->file->getClientOriginalExtension();

        if (count($this->allowed) && !in_array(strtolower($extension), $this->allowed, true)) {
            throw new RuntimeException('File type not allowed');
        }

        $imageName = uniqid('', true) . '.' . $extension;

        $this->file->storeAs($this->directory . '/', $imageName);

        $this->fileName = $imageName;

        return $imageName;
    }
}
