<?php

/*
    clsPagination: PHP class for pagination

    Compatibility: PHP >= 4.1.0
    Usage:         see below
*/

class clsPagination {

    function clsPagination(
        $intTotalAmount, $strHRef, $intPerPage = 10
    ) {
        $this -> intTotalAmount = abs((int) $intTotalAmount);
        $this -> strHRef        = $strHRef;
        $this -> intPerPage     = abs((int) $intPerPage);
        if ( ! $this -> intPerPage ) {
            $this -> intPerPage = 10;
        }; # if
        $this -> intPageQty     = ceil(
            $this -> intTotalAmount / $this -> intPerPage
        );
        $this -> arrArrows      = array (
            'previous' => '<<', 'next' => '>>'
        );
        $this -> strOmission    = '...';
    } # function clsPagination

    var $intTotalAmount, $strHRef, $intPerPage;
    var $intPageQty, $arrArrows, $strOmission;

    function fncGet(
        $intMaxDisplayQty = 10, $strGetPostVar = 'pg', $strTagAextraAttrs = '', $strCurrentNumberTemplate = '<strong>@</strong>', $strTitleBeginning = ''
    ) {

        if ( isset ($_POST[$strGetPostVar]) ) {
            $this -> intPageNumber = abs((int) $_POST[$strGetPostVar]);
        } # if
        else {
            $this -> intPageNumber = abs((int) @ $_GET[$strGetPostVar]);
        }; # else
        if ( ! $this -> intPageNumber ) {
            $this -> intPageNumber = 1;
        } # if
        else {
            if ( $this -> intPageNumber > $this -> intPageQty ) {
                $this -> intPageNumber = $this -> intPageQty;
            }; # if
        }; # else

        if ( $this -> intPageQty < 2 ) {
            return '';
        }; # if

        $this -> intMaxDisplayQty = abs((int) $intMaxDisplayQty);
        if (
               $this -> intMaxDisplayQty < 3
            && 0 != $this -> intMaxDisplayQty
        ) {
            $this -> intMaxDisplayQty = 3;
        }; # if

        $this -> strGetPostVar              = $strGetPostVar;

        $this -> strTagAextraAttrs          = $strTagAextraAttrs;

        $this -> strCurrentNumberTemplate   = $strCurrentNumberTemplate;

        $this -> strTitleBeginning          = htmlspecialchars(
            $strTitleBeginning, ENT_QUOTES
        );

        $strReturn = $this -> fncGetTheArrow(
            'previous',
            $this -> intPageNumber > 1 ?
                $this -> intPageNumber - 1 : $this -> intPageQty
        );

        if (
               $this -> intPageQty <= $this -> intMaxDisplayQty
            || 0 == $this -> intMaxDisplayQty
        ) {
            $strReturn .= $this -> fncGetRange(1, $this -> intPageQty);
        } # if
        else {

            # basic logic {

            $intMiddleSize = max(
                1, ceil($this -> intMaxDisplayQty * 50 / 100)
            );

            $intLeftSize =
            $intLeftEnd  = max(
                  1
                , floor(($this -> intMaxDisplayQty - $intMiddleSize) / 2)
            );

            $strReturn .= $this -> fncGetRange(1, $intLeftEnd);

            $intRightSize = $this -> intMaxDisplayQty - $intLeftSize - $intMiddleSize;
            if ( $intRightSize < 1 ) {
                $intMiddleSize -= 1 - $intRightSize;
                $intRightSize = 1;
            }; # if

            $intRightStart = $this -> intPageQty - $intRightSize + 1;

            $intMiddleStart =
                $this -> intPageNumber - floor(($intMiddleSize - 1) / 2);

            if ( $intMiddleStart < $intLeftEnd + 1 ) {
                $intMiddleStart = $intLeftEnd + 1;
            }; # if

            $intMiddleEnd = $intMiddleStart + $intMiddleSize - 1;

            if ( $intMiddleEnd >= $intRightStart - 1 ) {
                $intMiddleEnd = $intRightStart - 1;
                $intMiddleStart = $intMiddleEnd - $intMiddleSize + 1;
            }; # if

            if ( $intMiddleStart > $intLeftEnd + 1 ) {
                $strReturn .= $this -> fncGetElementA(
                      $intLeftEnd + ceil(
                          ($intMiddleStart - $intLeftEnd) / 2
                      )
                    , $this -> strOmission
                );
            }; # if

            $strReturn .= $this -> fncGetRange(
                $intMiddleStart, $intMiddleEnd
            );

            if ( $intMiddleEnd < $intRightStart - 1 ) {
                $strReturn .= $this -> fncGetElementA(
                      $intMiddleEnd + floor(
                        ($intRightStart - $intMiddleEnd) / 2
                      )
                    , $this -> strOmission
                );
            }; # if

            if ( $intMiddleEnd < $this -> intPageQty ) {
                $strReturn .= $this -> fncGetRange(
                    $intRightStart, $this -> intPageQty
                );
            }; # if

            # } basic logic

        }; # else

        $strReturn .= $this -> fncGetTheArrow(
              'next'
            , $this -> intPageNumber < $this -> intPageQty ?
                  $this -> intPageNumber + 1 : 1
        );

        return "\n$strReturn\n";

    } # function fncGet

    var $intPageNumber, $intMaxDisplayQty, $strGetPostVar, $strTagAextraAttrs, $strCurrentNumberTemplate, $strTitleBeginning;

    function fncGetRange($intStartNumber, $intEndNumber) {
        $strReturn = '';
        for ($n = $intStartNumber; $n <= $intEndNumber; $n++) {
            if ( $n == $this -> intPageNumber ) {
                $strReturn .= str_replace(
                    '@', $n, $this -> strCurrentNumberTemplate
                );
            } # if
            else {
                $strReturn .=
                    '&nbsp;' . $this -> fncGetElementA($n, $n) . ' ';
            }; # else
        }; # for
        return $strReturn;
    } # function fncGetRange

    function fncGetTheArrow($intArrowIndex, $intPageNumber) {
        return $this -> fncGetElementA(
            $intPageNumber, $this -> arrArrows[$intArrowIndex]
        );
    } # function fncGetTheArrow

    function fncGetElementA($intPageNum, $strText) {

        if ( 1 == $intPageNum ) {
            $strRelAttr = 'start ';
        } # if
        else {
            $strRelAttr = '';
        }; # else

        if ( 1 == $this -> intPageNumber ) {
            $strRevAttr = 'start ';
        } # if
        else {
            $strRevAttr = '';
        }; # else

        if ( $this -> intPageNumber - 1 == $intPageNum ) {
            $strRelAttr .= 'prev';
            $strRevAttr .= 'next';
        }; # if

        if ( $this -> intPageNumber + 1 == $intPageNum ) {
            $strRelAttr .= 'next';
            $strRevAttr .= 'prev';
        }; # if

        return
            '<a href="' . ( '?' == $this -> strHRef && 1 == $intPageNum ? '/' : $this -> strHRef ) . ( $intPageNum > 1 ? '&amp;' . $this -> strGetPostVar . '=' . $intPageNum : '' ) . '"' . (( $strRelAttr ) ? ' rel="' . trim($strRelAttr) . '"' : '') . (( $strRevAttr ) ? ' rev="' . trim($strRevAttr) . '"' : '') . ' title="' . $this -> strTitleBeginning . $intPageNum . '" ' . $this -> strTagAextraAttrs . '>' . htmlspecialchars($strText, ENT_QUOTES) . '</a>'
        ;

    } # function fncGetElementA

} # class clsPagination

/*
    Usage:

    if ( include_once 'clsPagination.php' ) {

        # 1. Define PER_PAGE constant
        define('PER_PAGE', 20);

        # 2. Get total amount of elements
        $intTotalAmountOfElements = 1000;

        # 3. Create Pagination object
        $objPagination = new clsPagination(
            $intTotalAmountOfElements   # total amount of elements
            , 'example.php?par=other'   # hyperlink except page number (? - required!)
            , PER_PAGE                  # per page [OPTIONAL; default value - 10]
        );

        # 4. Get Pagination
        $strPagination = $objPagination -> fncGet(
            17                      # max quantity of displayable numbers;
                                    # value 0 means "no limits"
                                    # [OPTIONAL; default value - 10]
            , 'page'                    # name of the variable containing
                                        # number of page
                                        # [OPTIONAL; default value - 'pg']
            , 'class=pagination'    # extra attributes for tag <a>
                                    # [OPTIONAL; default value - '']
            , '&nbsp;<b>page @</b>&nbsp;'    # template (using symbol @)
                                        # for number of the
                                        # current page displaying
                                        # [OPTIONAL; default
                                        # value - '<strong>@</strong>']
            , 'page #'              # title attribute beginning
                                    # [OPTIONAL; default value - '']
        );

        # 5. Display Pagination
        echo '<center>' . $strPagination . '</center>';

        # 6.
        # LIMIT PER_PAGE * ($objPagination -> intPageNumber - 1), PER_PAGE

        # 7. Display Pagination again
        echo '<center>' . $strPagination . '</center>';

    }; # if
*/

?>