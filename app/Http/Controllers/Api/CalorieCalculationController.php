<?php

namespace App\Http\Controllers\Api;

use App\Services\CalorieCalculationService;
use Symfony\Component\HttpFoundation\Response;

class CalorieCalculationController extends BaseApiController
{
    public function __construct(CalorieCalculationService $service)
    {
        $this->_service = $service;
    }

    /**
     * Tính toán và lưu calorie dựa trên profile + goal hiện tại.
     */
    public function calculate()
    {
        $result = $this->_service->calculateAndSave();
        if ($result) {
            return $this->successResponse($result, 'Tính toán calo thành công.', Response::HTTP_CREATED);
        }
        return $this->errorResponse('Tính toán thất bại.');
    }

    /**
     * Lấy kết quả tính toán mới nhất.
     */
    public function latest()
    {
        $data = $this->_service->getLatest();
        return $this->successResponse($data);
    }

    /**
     * Lấy lịch sử tính toán calo.
     * GET /api/calorie/history
     */
    public function history()
    {
        $data = $this->_service->getHistory();
        return $this->successResponse($data);
    }
}
