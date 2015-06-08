<?php

#-----------
function fncYdirectJsonNonfinanceRequestV4(
    $strLiveOrSandbox, # 'live' или 'sandbox'
    $strAuthMethod,    # 'by_token' или (только для 'live') 'by_certificate'
    $arrByTokenParams, # учитывается только для 'by_token';
                       # array ('login' => , 'application_id' => , 'token' => )
    $strByCertfctPath, # учитывается только для 'by_certificate'
    $strApiMethodName,
    $mixApiMethodParams = FALSE,
    $boolLive4 = FALSE
) {
    /*
        Возвращает array (
            'boolIsOkAnswer'
            'mixOkAnswer'    # если boolIsOkAnswer
            'strErrAuthor'   # 'yandex' или 'non_yandex' (если не boolIsOkAnswer)
            'arrError' => array ( # если не boolIsOkAnswer
                'error_code'
                'error_str'
                'error_detail'
            )
        )
    */

    # проверяем пришедшие параметры на правильность (((
    if (
        ! in_array($strLiveOrSandbox, array ('live', 'sandbox'))
    ||
        (
            ! in_array($strAuthMethod, array ('by_token', 'by_certificate'))
        ||
            (
                'by_certificate' == $strAuthMethod
            &&
                'sandbox' == $strLiveOrSandbox
            )
        )
    ||
        (
            'by_token' == $strAuthMethod
        &&
            ! isset ($arrByTokenParams['login'], $arrByTokenParams['application_id'], $arrByTokenParams['token'])
        )
    ) {
        # !!!!!!!!!!!!
        return array (
            'boolIsOkAnswer' => FALSE,
            'strErrAuthor' => 'non_yandex',
            'arrError' => array (
                'error_code'   => -100,
                'error_str'    => 'Непредвиденная ошибка в скрипте, работающем с Яндекс.Директом.',
                'error_detail' => 'В одну из функций переданы недопустимые параметры.',
            ),
        );
    } # if
    # ))) проверяем пришедшие параметры на правильность

    # доступна ли curl_init? (((
    if (
        ! function_exists('curl_init')
    ) {
        # !!!!!!!!!!!!
        return array (
            'boolIsOkAnswer' => FALSE,
            'strErrAuthor' => 'non_yandex',
            'arrError' => array (
                'error_code'   => -101,
                'error_str'    => 'Скрипту, работающему с Яндекс.Директом, не доступна необходимая функция.',
                'error_detail' => 'Не доступна функция, использующаяся для обращения к Яндекс.Директу.',
            ),
        );
    } # if
    # ))) доступна ли curl_init?

    $hndCurl = curl_init(
        'live' == $strLiveOrSandbox
    ?
        (
            $boolLive4
        ?
            'https://api.direct.yandex.ru/live/v4/json/'
        :
            'https://api.direct.yandex.ru/json-api/v4/'
        )
    :
        'https://api-sandbox.direct.yandex.ru/json-api/v4/'
    );

    # выполнилась ли curl_init? (((
    if (
        FALSE === $hndCurl
    ) {
        # !!!!!!!!!!!!
        return array (
            'boolIsOkAnswer' => FALSE,
            'strErrAuthor' => 'non_yandex',
            'arrError' => array (
                'error_code'   => -102,
                'error_str'    => 'В скрипте, работающем с Яндекс.Директом, не выполнилась необходимая функция.',
                'error_detail' => 'Не выполнилась функция, использующаяся для обращения к Яндекс.Директу.',
            ),
        );
    } # if
    # ))) выполнилась ли curl_init?

    # формируем массив для JSONа (((
    $arrForJson = array (
        'method' => $strApiMethodName,
        'locale' => 'ru',
    );
    if (
        $mixApiMethodParams !== FALSE
    ) {
        $arrForJson['param'] =
            # Пока не понял (04.01.2013), потому считаем hack.
            # См. http://api.yandex.ru/direct/doc/examples/php-sample-json.xml#campaign
            # См. http://forum.searchengines.ru/showpost.php?p=9434433&postcount=9
            fncYdirectTheParamUtf8Encoding(
                $mixApiMethodParams
            )
        ;
    } # if
    if (
        'by_token' == $strAuthMethod
    ) {
        $arrForJson['login']          = $arrByTokenParams['login'];
        $arrForJson['application_id'] = $arrByTokenParams['application_id'];
        $arrForJson['token']          = $arrByTokenParams['token'];
    } # if
    # ))) формируем массив для JSONа

    # пробуем сделать JSON (((

    $jsonForPost = json_encode($arrForJson);

    if (
        FALSE === $jsonForPost
    ) {
        # !!!!!!!!!!!!
        return array (
            'boolIsOkAnswer' => FALSE,
            'strErrAuthor' => 'non_yandex',
            'arrError' => array (
                'error_code'   => -103,
                'error_str'    => 'В скрипте, работающем с Яндекс.Директом, не удалось сформировать запрос к Яндексу.',
                'error_detail' => 'Не выполнилась функция, использующаяся для формирования запроса к Яндекс.Директу.',
            ),
        );
    } # if

    # ))) пробуем сделать JSON

    # задаём нужные curl_setopt (((

    curl_setopt($hndCurl, CURLOPT_POST,       TRUE);
    curl_setopt($hndCurl, CURLOPT_POSTFIELDS, $jsonForPost);

    if (
        'by_certificate' == $strAuthMethod
    ) {
        curl_setopt($hndCurl, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt($hndCurl, CURLOPT_SSLCERT, $strByCertfctPath . 'cert.crt');
        curl_setopt($hndCurl, CURLOPT_SSLKEY, $strByCertfctPath . 'private.key');
        curl_setopt($hndCurl, CURLOPT_CAINFO, $strByCertfctPath . 'cacert.pem');
    } # if
    else {
        curl_setopt($hndCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
    } # else

    curl_setopt($hndCurl, CURLOPT_CONNECTTIMEOUT, 120);
    curl_setopt($hndCurl, CURLOPT_TIMEOUT,        120);
    curl_setopt($hndCurl, CURLOPT_RETURNTRANSFER, TRUE);
#    curl_setopt($hndCurl, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($hndCurl, CURLOPT_USERAGENT,      'PHP script');

    # ))) задаём нужные curl_setopt

    # пытаемся отослать запрос
    $curl_exec_return = curl_exec($hndCurl);

    if (
        FALSE === $curl_exec_return
    ) {
        # !!!!!!!!!!!!
        return array (
            'boolIsOkAnswer' => FALSE,
            'strErrAuthor' => 'non_yandex',
            'arrError' => array (
                'error_code'   => -104,
                'error_str'    => 'Скрипту, работающему с Яндекс.Директом, не удалось достучаться к Яндексу.',
                'error_detail' => 'Не выполнилась функция, использующаяся для обращения к Яндекс.Директу.',
            ),
        );
    } # if

    curl_close($hndCurl);

    $arrAnswer = json_decode($curl_exec_return, TRUE);

    if (
        NULL === $arrAnswer
    ) {
        # !!!!!!!!!!!!
        return array (
            'boolIsOkAnswer' => FALSE,
            'strErrAuthor' => 'non_yandex',
            'arrError' => array (
                'error_code'   => -105,
                'error_str'    => 'Скрипту, работающему с Яндекс.Директом, не удалось разобрать ответ Яндекса.',
                'error_detail' => 'Не выполнилась функция, использующаяся для разбирания ответа Яндекса.',
            ),
        );
    } # if

    # анализируем полученный ответ
    if (
        isset ($arrAnswer['error_code'])
    ) {
        # !!!!!!!!!!!!
        return array (
            'boolIsOkAnswer' => FALSE,
            'strErrAuthor' => 'yandex',
            'arrError' => array (
                'error_code' => $arrAnswer['error_code'],
                'error_str' => $arrAnswer['error_str'],
                'error_detail' => @$arrAnswer['error_detail'],
            ),
        );
    } # if
    else {
        # !!!!!!!!!!!!
        return array (
            'boolIsOkAnswer' => TRUE,
            'mixOkAnswer'    => $arrAnswer,
        );
    } # else

} # function
#-----------

#-----------
function fncYdirectTheParamUtf8Encoding($mixed) {

    if (
        ! is_array($mixed)
    ) {
        if (
            is_string($mixed)
        ) {
            $mixed = utf8_encode($mixed);
        } # if
    } # if
    else {
        if (
            count($mixed)
        ) {
            foreach ( $mixed as $key => $value ) {
                if (
                    is_array($value)
                ) {
                    $mixed[$key] = fncYdirectTheParamUtf8Encoding($value);
                } # if
                elseif (
                    is_string($value)
                ) {
                    $mixed[$key] = utf8_encode($value);
                } # elseif
            } # foreach
        } # if
    } # else

    return $mixed;

} # function
#-----------

?>