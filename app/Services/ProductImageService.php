<?php

namespace App\Services;

use App\Repositories\ProductImage\IProductImageRepository;
use Illuminate\Support\Facades\Storage;

class ProductImageService extends BaseService
{
    private const IMAGE_DIR = 'foods';

    private FileService $fileService;

    public function __construct(IProductImageRepository $repo, FileService $fileService)
    {
        $this->repo        = $repo;
        $this->fileService = $fileService;
    }

    public function getByFood(string $foodId): array
    {
        return $this->repo->getByFood($foodId);
    }

    /**
     * Upload một file ảnh và lưu bản ghi vào product_images.
     * Chỉ lưu: directory, file_name, file_ext — giống cách source cũ làm.
     */
    public function upload(\Illuminate\Http\UploadedFile $file, string $foodId, int $sortOrder = 0): object
    {
        $uploaded = $this->fileService->upload($file, self::IMAGE_DIR);

        return $this->repo->create([
            'id'         => generateRandomString(),
            'food_id'    => $foodId,
            'directory'  => self::IMAGE_DIR,
            'file_name'  => $uploaded['name'],
            'file_ext'   => $uploaded['ext'],
            'is_primary' => 0,
            'sort_order' => $sortOrder,
            'created_at' => now(),
        ]);
    }

    /**
     * Xoá bản ghi + 3 file vật lý (lg/md/xs).
     */
    public function delete($id)
    {
        $record = $this->repo->find($id);

        if (!$record) {
            return false;
        }

        foreach (['lg', 'md', 'xs'] as $prefix) {
            $filePath = $record->directory . '/' . $prefix . '_' . $record->file_name . '.' . $record->file_ext;
            Storage::disk('public')->delete($filePath);
        }

        return $this->repo->deleteById($id);
    }
}
