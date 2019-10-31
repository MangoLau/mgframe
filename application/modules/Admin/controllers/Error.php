<?php

use Frame\Core\MG;

/**
 * Error
 * @author MangoLau
 */
class ErrorController extends Common_PublicController
{
    /**
     * error action
     *
     * @return bool
     * @throws Exception
     */
    public function errorAction()
    {
        $req = $this->getRequest();
        $exception = $req->getException();
        //其它异常
        if ($this->yafAutoRender || !$this->isAjax) {
            $exceptions = [];
            if (! $exception instanceof Exception) {
                $exception = new Yaf\Exception\LoadFailed('no exception');
            }
            //add exception
            $exceptions[] = $exception;
            //show trace
            $view = $this->getView();
            $view->exceptions = $exceptions;
            $view->display('error/error.phtml');
        } else {
            //来自接口的错误
            if ($exception instanceof RuntimeException) {
                return $this->json($exception->getCode(), $exception->getMessage());
            }
            $this->log($exception);
            if ($exception instanceof Yaf\Exception\LoadFailed) { //接口不存在
                return $this->json(404, $exception->getMessage());
            } else {
                return $this->json(500, $exception->getMessage());
            }
        }
    }

    /**
     * error log
     *
     * @param Exception $exception
     * @param int $code
     */
    private function log($exception)
    {
        //log exception
        $msg = sprintf("%s:%s. in %s on line %s", 
            get_class($exception), 
            $exception->getMessage(), 
            $exception->getFile(), 
            $exception->getLine()
        );
        MG::log()->error($msg);
    }
   
}
