<?php
/**
 * @author MangoLau
 */

class ErrorCode
{
    // 0-100为系统统一错误
    /**
     * 成功
     */
    const SUCCESS               = 0;

    /**
     * 需要登录
     */
    const NEED_LOGIN            = 1;

    /**
     * token错误
     */
    const TOKEN_INVALID         = 2;

    /**
     * 签名错误
     */
    const SIGN_NOT_MATCH        = 3;

    /**
     * 调用方法错误
     */
    const CALL_MATHOD_INVALID   = 4;

    /**
     * 操作失败
     */
    const OPERATION_FAILED      = 5;

    /**
     * 参数缺失
     */
    const PARAM_MISS            = 6;

    /**
     * 参数错误
     */
    const PARAM_INVALID         = 7;
}