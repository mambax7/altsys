<?php declare(strict_types=1);

/**
 * "Unified" diff renderer.
 *
 * This class renders the diff in classic "unified diff" format.
 *
 * $Horde: framework/Text_Diff/Diff/Renderer/unified.php,v 1.2 2004/01/09 21:46:30 chuck Exp $
 */
class Text_Diff_Renderer_unified extends Text_Diff_Renderer
{
    /**
     * Number of leading context "lines" to preserve.
     */
    public $_leading_context_lines = 4;

    /**
     * Number of trailing context "lines" to preserve.
     */
    public $_trailing_context_lines = 4;

    /**
     * @param $xbeg
     * @param $xlen
     * @param $ybeg
     * @param $ylen
     * @return string
     */
    public function _blockHeader($xbeg, $xlen, $ybeg, $ylen)
    {
        if (1 != $xlen) {
            $xbeg .= ',' . $xlen;
        }

        if (1 != $ylen) {
            $ybeg .= ',' . $ylen;
        }

        return "@@ -$xbeg +$ybeg @@";
    }

    /**
     * @param $lines
     * @return string
     */
    public function _added($lines)
    {
        return $this->_lines($lines, '+');
    }

    /**
     * @param $lines
     * @return string
     */
    public function _deleted($lines)
    {
        return $this->_lines($lines, '-');
    }

    /**
     * @param $orig
     * @param $final
     * @return string
     */
    public function _changed($orig, $final)
    {
        return $this->_deleted($orig) . $this->_added($final);
    }
}
