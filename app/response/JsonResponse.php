<?php

namespace app\response;

use think\facade\Session;
use think\response\Json;

/**
 * Class JsonResponse
 * @package app\response
 */
trait JsonResponse
{
    /**
     * @var string 客户端 ID
     */
    protected string $clientId = 'X-Client-Id';

    /**
     * @var int 错误码
     */
    protected int $errorCode = 0;

    /**
     * @return int
     */
    protected function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * @param $errorCode
     * @return $this
     */
    protected function setErrorCode($errorCode): JsonResponse
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * 返回封装后的API数据到客户端
     * @param mixed $data 要返回的数据
     * @param array $headers 发送的Header信息
     * @return \think\response\Json
     */
    protected function succeed($data, array $headers = []): Json
    {
        return $this->response([
            'status' => 'success',
            'data' => $data,
        ], $headers);
    }

    /**
     * 返回异常数据到客户端
     * @param string $message
     * @param array $headers
     * @return \think\response\Json
     */
    protected function failed(string $message = '', array $headers = []): Json
    {
        return $this->response([
            'status' => 'failed',
            'errors' => [
                'code' => $this->getErrorCode(),
                'message' => $message,
            ],
        ], $headers);
    }

    /**
     * 返回 Json 数据格式
     * @param $data
     * @param array $headers
     * @return \think\response\Json
     */
    protected function response($data, array $headers = []): Json
    {
        $clientId = request()->header($this->clientId, md5(Session::getId()));

        return json($data, 200, array_merge($headers, [$this->clientId => $clientId]));
    }
}
