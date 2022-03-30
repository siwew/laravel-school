<?php

namespace App\helpers;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Pagination\LengthAwarePaginator;
use Response;
use Symfony\Component\HttpFoundation\Response as HttpCode;

class ApiResponse
{
    /**
     * @var int
     */
    protected $statusCode = HttpCode::HTTP_OK;

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }


    /**
     * @param $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }


    /**
     * @param       $data
     * @param array $header
     *
     * @return mixed
     */
    public function respond($data, $header = [])
    {
        if ($this->debugEnabled()) {
            $data = array_merge($data, $this->getDebug());
        }

        return Response::json($data, $this->statusCode, $header);
    }


    /**
     * api获取debug信息
     * @return bool
     */
    protected function debugEnabled(): bool
    {
        return app()->has('debugbar') && app('debugbar')->isEnabled();
    }


    protected function getDebug(): array
    {
        return ['_debugbar' => app('debugbar')->getData()];
    }


    /**
     * @param string $status
     * @param array $data
     * @param null  $code
     *
     * @return mixed
     */
    public function status(array $data, $status, $code = null)
    {
        if ($code) {
            $this->setStatusCode($code);
        }
        $code = [
            'status' => $status,
            'code' => $this->statusCode,
        ];
        $data = array_merge($data, $code);

        return $this->respond($data);
    }


    /**
     * @param        $message
     * @param int    $code
     * @param string $status
     *
     * @return mixed
     */
    public function failed($message, $code = 500, $status = 'error')
    {
        return $this->setStatusCode($code)->message($message, $status);
    }


    /**
     * @param $message
     *
     * @param string $status
     * @param null $code
     * @return mixed
     */
    public function message($message, $status = 'success', $code = null)
    {
        return $this->status([
            'msg' => $message,
        ], $status, $code);
    }


    /**
     * @param string $message
     *
     * @return mixed
     */
    public function internalError($message = 'Internal Error!')
    {
        return $this->failed($message, HttpCode::HTTP_INTERNAL_SERVER_ERROR);
    }


    /**
     * @param string $message
     *
     * @return mixed
     */
    public function created($message = 'created')
    {
        return $this->setStatusCode(HttpCode::HTTP_CREATED)
            ->message($message);
    }


    /**
     * @param $data
     * @param $status
     *
     * @return mixed
     */
    public function success($data, $status = "success")
    {
        return $this->status(compact('data'), $status);
    }


    public function noContent($code = HttpCode::HTTP_NO_CONTENT)
    {
        return $this->setStatusCode($code)->status([], '');
    }


    /**
     * @param string $message
     *
     * @return mixed
     */
    public function notFond($message = 'Not Fond!')
    {
        return $this->failed($message, HttpCode::HTTP_NOT_FOUND);
    }


    public function resource(Resource $resource)
    {
        $additional = [
            'code' => $this->statusCode,
        ];

        if ($this->debugEnabled()) {
            $additional += $this->getDebug();
        }
        $resource->additional($additional);

        return $resource;
    }


    public function resourceAdditional(Resource $resource, $more = [])
    {
        if ($resource->resource instanceof LengthAwarePaginator) {
            $total = $resource->resource->total();
            $resource->resource = collect($resource->resource->items());
            $more['total'] = $total;
        }

        $additional = [
            'code' => $this->statusCode,
        ];
        $additional += $more;

        if ($this->debugEnabled()) {
            $additional += $this->getDebug();
        }

        $resource->additional($additional);

        return $resource;
    }

    public function open($data, $msg = '', $status = 'SUCCESS', $code = 1)
    {
        $state = [
            'status' => $status,
            'code' => $code,
            'msg' => $msg,
        ];
        $data = array_merge($data, $state);

        return $this->respond($data);
    }
}