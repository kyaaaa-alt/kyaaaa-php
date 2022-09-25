<?php

declare(strict_types=1);

namespace Core\Conf\Kyaaaa;

class Request
{
    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    /**
     * Get POST data by key name
     *
     * @param string $key
     * @param $default
     *
     * @return null|mixed
     */
    public function post(string $key = null, $default = null)
    {
        if ($key == null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET data by key name
     *
     * @param string $key
     * @param $default
     *
     * @return null|mixed
     */
    public function get(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Get Request date by key name
     *
     * @param string $key
     * @param $default
     *
     * @return mixed|null
     */
    public function request(string $key, $default = null)
    {
        return $_REQUEST[$key] ?? $default;
    }

    /**
     * Check Ajax request
     *
     * @return bool
     */
    public function isAjax(): bool
    {
        return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
    }

    /**
     * Check POST request
     *
     * @return bool
     */
    public function isPost(): bool
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === self::METHOD_POST;
    }

    /**
     * Check GET request
     *
     * @return bool
     */
    public function isGet(): bool
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? '') === self::METHOD_GET;
    }

    /**
     * Get Method Request
     *
     * @return bool
     */
    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Get request uri
     *
     * @param $default
     *
     * @return null|string
     */
    public function getUri($default = null): ?string
    {
        return self::isAjax()
            ? $_SERVER['HTTP_REFERER'] ?? $default
            : $_SERVER['REQUEST_URI'] ?? $default;
    }
    /**
     * Get request uri segments
     *
     * @param null $segment
     *
     * @return string $uriSegments
     */
    public function getSegment($segment = null) {
        $uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        return $uriSegments[$segment];
    }
}