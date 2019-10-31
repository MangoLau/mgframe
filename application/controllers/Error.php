<?php

use Frame\Core\MG;
use Api\Exception\CallMethodInvalidException;
use Api\Exception\OperationFailedException;
use Api\Exception\ParamMissException;
use Api\Exception\ParamInvalidException;
use Api\Exception\SignNotMatchException;
use Api\Exception\TokenInvalidException;
use Api\Exception\NeedLoginException;
/**
 * Error
 * @author MangoLau
 */
class ErrorController extends Common_PublicController
{
    /**
     * error action
     *
     * @param Exception $exception
     * @return bool
     * @throws Exception
     */
    public function errorAction($exception = null)
    {
        $exceptions = [];
        if (! $exception instanceof Exception) {
            $exception = new Yaf\Exception\LoadFailed('no exception');
        }
        //add exception
        $exceptions[] = $exception;

        try {
            throw $exception;
        } catch (Yaf\Exception\LoadFailed $e) {
            $code = 404;
            if (!empty($e->getPrevious())) {
                $exceptions[] = $e->getPrevious();
            }
        } catch (Api\Exception\CsrfException $e) {
            return $this->json(400, 'csrf check failed');
        } catch (Api\Exception\AuthException $e) {
            $uri = $e->getMessage();
            $this->isAjax ? $this->json(401, 'login') : $this->redirect($uri);
            return false;
        } catch (Api\Exception\AccessDeniedException $e) {
            if ($this->isAjax) {
                return $this->json(403, 'access denied');
            }
            $this->_view->display('error/403.phtml');
            return false;
        }  catch (ParamMissException $e) {
            $code = \ErrorCode::PARAM_MISS;
        } catch (ParamInvalidException $e) {
            $code = \ErrorCode::PARAM_INVALID;
        } catch (TokenInvalidException $e) {
            $code = \ErrorCode::TOKEN_INVALID;
        } catch (SignNotMatchException $e) {
            $code = \ErrorCode::SIGN_NOT_MATCH;
        } catch (NeedLoginException $e) {
            $code = \ErrorCode::NEED_LOGIN;
        } catch (OperationFailedException $e) {
            $code = \ErrorCode::OPERATION_FAILED;
        } catch (CallMethodInvalidException $e) {
            $code = \ErrorCode::CALL_MATHOD_INVALID;
        } catch (\Exception $e) {
            $code = 500;
        }
        if (strtolower(YAF\ENVIRON) !== 'dev') {
            $this->log($e, $code);
            if ($this->isAjax) {
                return $this->json($code, '');
            }
            $this->_view->display('error/'.$code.'.phtml');
            return false;
        }
        //show trace
        $this->getView()->exceptions = $exceptions;
        $this->yafAutoRender = true;
        Yaf\Dispatcher::getInstance()->autoRender(true);
    }

    /**
     * error log
     *
     * @param Exception $exception
     * @param int $code
     */
    private function log($exception , $code = 500)
    {
        //log 404
        if ($code == 404) {
            $path = ROOT_PATH.'/logs/404/'.date('Y/m');
            is_dir($path) OR mkdir($path, 0755, true);
            $msg = sprintf("[%s] %s %s \n",
                date('Y-m-d H:i:s'),
                Func::getClientIp(),
                $_SERVER['REQUEST_URI']
            );
            error_log($msg, 3, $path.'/'.date('d').'.log');
            return;
        }
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
