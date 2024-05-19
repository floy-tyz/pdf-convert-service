<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;

trait ResponseStatusTrait
{
    /**
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function success(array $data = [], int $code = 200): JsonResponse
    {
        return $this->responseWithStatus(true, $data, $code);
    }

    /**
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function failed(array $data = [], int $code = 200): JsonResponse
    {
        return $this->responseWithStatus(false, $data, $code);
    }

    /**
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function errors(array $data = [], int $code = 200): JsonResponse
    {
        return $this->failed(['errors' => $data], $code);
    }

    /**
     * @param bool $success
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    protected function responseWithStatus(bool $success, array $data = [], int $code = 200): JsonResponse
    {
        $data = array_merge(['success' => $success], $data);

        return new JsonResponse($data, $code);
    }
}