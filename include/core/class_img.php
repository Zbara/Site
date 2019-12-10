<?

/**
 * Class ttfTextOnImage
 */ 
class ttfTextOnImage
{
    public $jpegQuality = 100;

    public $ttfFontDir = '';

    private $ttfFont = false;
    private $ttfFontSize = false;

    private $hImage = false;
    private $hColor = false;

    /**
     * ttfTextOnImage constructor.
     * @param $imagePath
     */
    public function __construct($imagePath)
    {
        if (!is_file($imagePath) || !list(, , $type) = @getimagesize($imagePath)) return false;

        switch ($type) {
            case 1:
                $this->hImage = @imagecreatefromgif($imagePath);
                break;
            case 2:
                $this->hImage = @imagecreatefromjpeg($imagePath);
                break;
            case 3:
                $this->hImage = @imagecreatefrompng($imagePath);
                break;
            default:
                $this->hImage = false;
        }
    }

    public function __destruct()
    {
        if ($this->hImage) imagedestroy($this->hImage);
    }

    /**
     * @param $font
     * @param int $size
     * @param bool $color
     * @param bool $alpha
     * @return bool
     */
    public function setFont($font, $size = 14, $color = false, $alpha = false)
    {
        if (!is_file($font) && !is_file($font = $this->ttfFontDir . '/' . $font))
            return false;

        $this->ttfFont = $font;
        $this->ttfFontSize = $size;

        if ($color) $this->setColor($color, $alpha);
    }

    /**
     * @param $x
     * @param $y
     * @param $text
     * @param int $angle
     * @return bool
     */
    public function writeText($x, $y, $text, $angle = 0, $rgb, $width)
    {
        if (!$this->ttfFont || !$this->hImage || !$this->hColor) return false;
        $grey = imagecolorallocate($this->hImage, $rgb[0], $rgb[1], $rgb[2]);//обводка
        $center = round($width/2); //центр изображения
        $box = imagettfbbox($this->ttfFontSize, 0, $this->ttfFont, $text); //ширина текста
        $position = $center-round(($box[2]-$box[0])/2); //позиция начала текста
        imagettftext($this->hImage, $this->ttfFontSize, $angle, $position+1, $y + $this->ttfFontSize+1, $grey, $this->ttfFont, $text);
        imagettftext($this->hImage, $this->ttfFontSize, $angle, $position, $y + $this->ttfFontSize, $this->hColor, $this->ttfFont, $text);
    }

    /**
     * @param $marge_bottom
     * @param $marge_right
     * @param $stamp
     */
    public function writeImg($marge_bottom, $marge_right, $stamp)
    {
        $stamp = imagecreatefromjpeg($stamp);
        $sx = imagesx($stamp);
        $sy = imagesy($stamp);
        imagecopy($this->hImage, $stamp, $marge_right, $marge_bottom, 0, 0, imagesx($stamp), imagesy($stamp));
    }

    /**
     * @param $bWidth
     * @param $bHeight
     * @param $text
     * @return string
     */
    public function textFormat($bWidth, $bHeight, $text)
    {

        $strings = explode("\n",
            preg_replace('!([^\s]{24})[^\s]!su', '\\1 ',
                str_replace(array("\r", "\t"), array("\n", ' '), $text)));

        $textOut = array(0 => '');
        $i = 0;

        foreach ($strings as $str) {
            $words = array_filter(explode(' ', $str));

            foreach ($words as $word) {
                $sizes = imagettfbbox($this->ttfFontSize, 0, $this->ttfFont, $textOut[$i] . $word . ' ');
                if ($sizes[2] > $bWidth) $textOut[++$i] = $word . ' '; else $textOut[$i] .= $word . ' ';

                if ($i * $this->ttfFontSize >= $bHeight) break(2);
            }

            $textOut[++$i] = '';
            if ($i * $this->ttfFontSize >= $bHeight) break;
        }

        return implode("\n", $textOut);
    }

    /**
     * @param $color
     * @param bool $alpha
     * @return bool|int
     */
    public function setColor($color, $alpha = false)
    {
        if (!$this->hImage) return false;

        list($r, $g, $b) = array_map('hexdec', str_split(ltrim($color, '#'), 2));

        return $alpha === false ?
            $this->hColor = imagecolorallocate($this->hImage, $r + 1, $g + 1, $b + 1) :
            $this->hColor = imagecolorallocatealpha($this->hImage, $r + 1, $g + 1, $b + 1, $alpha);
    }

    /**
     * @param $target
     * @param bool $replace
     * @return bool
     */
    public function output($target, $replace = true)
    {
        if (is_file($target) && !$replace) return false;

        $ext = strtolower(substr($target, strrpos($target, ".") + 1));

        switch ($ext) {
            case "gif":
                imagegif($this->hImage, $target);
                break;

            case "jpg" :
            case "jpeg":
                imagejpeg($this->hImage, $target, $this->jpegQuality);
                break;

            case "png":
                imagepng($this->hImage, $target);
                break;

            default:
                return false;
        }
        return true;
    }
}