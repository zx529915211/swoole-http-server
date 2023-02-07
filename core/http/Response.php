<?php


namespace core\http;


class Response
{
    protected $body;
    /**
     * @var \Swoole\Http\Response
     */
    protected $swooleResponse;

    /**
     * Response constructor.
     * @param $swooleResponse
     */
    public function __construct($swooleResponse)
    {
        $this->swooleResponse = $swooleResponse;
        $this->setHeader("Content-type", 'text/plain;charset=utf8');
    }

    public static function init(\Swoole\Http\Response $response)
    {
        return new self($response);
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
    public function setBody(mixed $body): void
    {
        $this->body = $body;
    }

    /**
     * 写入数据
     * @param string $data
     */
    public function write(string $data)
    {
        $this->swooleResponse->write($data);
    }

    /**
     * 设置状态码
     * @param int $code
     */
    public function setCode(int $code)
    {
        $this->swooleResponse->status($code);
    }


    /**
     * 设置响应头
     * @param string $key
     * @param string $value
     */
    public function setHeader(string $key, string $value)
    {
        $this->swooleResponse->header($key, $value);
    }

    /**
     * 跳转
     * @param string $url
     * @param int $code
     */
    public function redirect(string $url, int $code = 301)
    {
        $this->setCode($code);
//        $this->swooleResponse->redirect($url);
        $this->setHeader('Location', $url);
    }


    /**
     * 结束
     */
    public function end()
    {
        $ret = $this->getBody();
        if (is_array($ret) || is_object($ret)) {
            $this->setHeader('Content-type', "application/json;charset=utf8");
            $this->write(json_encode($ret));
        } else {
            $this->write($ret);
        }
        $this->swooleResponse->end();
    }

}