<?php


namespace core\http;


class Request
{
    protected array $server = [];
    protected string $uri = '';
    protected $queryParams;
    protected $postParams;
    protected $method;
    protected $header = [];
    protected $body;
    protected $swooleRequest;

    /**
     * Request constructor.
     * @param array $server
     * @param string $uri
     * @param $queryParams
     * @param $postParams
     * @param $method
     * @param array $header
     * @param $body
     */
    public function __construct(array $server, string $uri, $queryParams, $postParams, $method, array $header, $body)
    {
        $this->server = $server;
        $this->uri = $uri;
        $this->queryParams = $queryParams;
        $this->postParams = $postParams;
        $this->method = $method;
        $this->header = $header;
        $this->body = $body;
    }

    public static function init(\Swoole\Http\Request $swooleRequest)
    {
        $server = $swooleRequest->server;
        $uri = $swooleRequest->server['request_uri'];
        $method = $swooleRequest->server['request_method'] ?? "GET";
        $body = $swooleRequest->rawContent();
        $request = new self($server, $uri, $swooleRequest->get, $swooleRequest->post, $method, $swooleRequest->header, $body);
        $request->swooleRequest = $swooleRequest;
        return $request;
    }

    /**
     * @return array
     */
    public function getServer(): array
    {
        return $this->server;
    }

    /**
     * @param array $server
     */
    public function setServer(array $server): void
    {
        $this->server = $server;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    /**
     * @return array
     */
    public function getQueryParams(): array
    {
        return $this->queryParams ?: [];
    }

    /**
     * @param mixed $queryParams
     */
    public function setQueryParams(mixed $queryParams): void
    {
        $this->queryParams = $queryParams;
    }

    /**
     * @return array
     */
    public function getPostParams(): array
    {
        return $this->postParams ?: [];
    }

    /**
     * @param mixed $postParams
     */
    public function setPostParams(mixed $postParams): void
    {
        $this->postParams = $postParams;
    }

    /**
     * @return mixed
     */
    public function getMethod(): mixed
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method): void
    {
        $this->method = $method;
    }

    /**
     * @return array
     */
    public function getHeader(): array
    {
        return $this->header;
    }

    /**
     * @param array $header
     */
    public function setHeader(array $header): void
    {
        $this->header = $header;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getSwooleRequest()
    {
        return $this->swooleRequest;
    }

    /**
     * @param mixed $swooleRequest
     */
    public function setSwooleRequest($swooleRequest): void
    {
        $this->swooleRequest = $swooleRequest;
    }

}