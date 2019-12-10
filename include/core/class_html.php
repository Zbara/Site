<?php 
class OptimizeHTML
{
    public static function strip_spaces(/*string*/ $s)
    {
        #вырезаем пробелы в начале и в конце переводов строк
        return preg_replace('/ [\x20\t]*+      #возможные пробелы ПЕРЕД переносом строки
                               [\r\n]          #первый перенос строки
                               [\x03-\x20]*+   #возможные пробельные символы ПОСЛЕ переноса строки
                             /sxSX', "\r", $s);
    }

    #очень быстрый оптимизатор CSS кода
    public static function css(/*string*/ $s)
    {
        #вырезаем многострочные комментарии /* ... */
        if (strpos($s, '/*') !== false) $s = preg_replace('~/\*.*?\*/~sSX', ' ', $s);
        #вырезаем лишние пробелы
        if (preg_match('/[\x03-\x20]/sSX', $s))
        {
            /*
              IE7 хочет после закрывающей круглой скобки пробел перед цифрами и буквами, если его нет, то CSS становится разбитым, например:
              background:url(/img/cat.png)0 0 no-repeat;
            */
            $s = preg_replace('/\)[\x03-\x20]++(?=[-a-zA-Z\d])/sSX', ")\x01", $s); #fix for IE7
            $a = preg_split('/([{}():;,%!*=]++)/sSX', $s, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
            $s = implode('', array_map('trim', $a));
            $s = str_replace(")\x01", ') ', $s); #fix for IE7
            $s = preg_replace('/[\x03-\x20]++/sSX', ' ', $s);
            /*
              Относительные размеры (специфицируют значение размера относительно какого-либо исходного свойства размера)
                em: 'font-size' соответствующего шрифта; 
                ex: 'x-height' соответствующего шрифта; 
                px: пикселы, относительно устройства просмотра.
              Абсолютные единицы измерения (используются только тогда, когда известны физические свойства выводного устройства)
                in: inches/дюймы -- 1 дюйм равен 2.54 сантиметра.
                cm: сантиметры
                mm: миллиметры
                pt: points/пункты - пункт, используемый в  CSS2, равен 1/72 дюйма. 
                pc: picas/пики -- 1 пика равна 12 пунктам.
            */
            #converts '0px' to '0'
            $s = preg_replace('/ (?<![\d\.])
                                 0(?:em|ex|px|in|cm|mm|pt|pc|%)
                                 (?![a-zA-Z%])
                               /sxSX', '0', $s);
            #converts '#rrggbb' to '#rgb' or '#rrggbbaa' to '#rgba';
            #IE6 incorrect parse #rgb in entry, like 'filter: progid:DXImageTransform.Microsoft.Gradient(startColorStr=#ffffff, endColorStr=#c9d1d7, gradientType=0);'
            $s = preg_replace('/ :\# ([\da-fA-F])\1  #rr
                                     ([\da-fA-F])\2  #gg
                                     ([\da-fA-F])\3  #bb
                                     (?:([\da-fA-F])\4)?+  #aa
                                 (?![\da-fA-F])
                               /sxSX', ':#$1$2$3$4', $s);
        }
        return $s;
    }
    public static function html($s)
    {
        static $re_attrs_fast_safe =  '(?![a-zA-Z\d])  #statement, which follows after a tag
                                       #correct attributes
                                       (?>
                                           [^>"\']++
                                         | (?<=[\=\x03-\x20]|\xc2\xa0) "[^"]*+"
                                         | (?<=[\=\x03-\x20]|\xc2\xa0) \'[^\']*+\'
                                       )*
                                       #incorrect attributes
                                       [^>]*+';

        #заменяем содержимое тагов на врЕменные метки
        $s = preg_replace_callback('/<(pre|code|textarea|nooptimize)(' . $re_attrs_fast_safe . ')(>.*?<\/\\1)>/sxiSX', array('self', '_html_pre'), $s);

        #вырезаем лишние переносы строк после некоторых тагов (+0.005 sec.)
        #атомарную группировку в перечислении названий тагов не используем, т.к. в альтернативах есть "li" и "link"!
        $a = preg_split('/ (
                             (?> <\/?+(?:br|p|div|li|ol|ul|table|t[drh]|meta|link|h[1-6]|form|option|select|title|script|style|map|area|head|body|html)' . $re_attrs_fast_safe . '>
                               | <!--\[if [^\]]++ \]>
                               | <!\[endif\]-->
                             )
                             (?:<\/?+noindex>)?+
                           )
                         /sxiSX', $s, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $s = implode('', array_map('trim', $a));

        #вырезаем пробелы ПОСЛЕ открывающих тагов, если перед тагом есть пробел (+0.001 sec.)
        $s = preg_replace('/ (?<=[\x03-\x20])
                             <[a-z][a-z\d]*+ (?<!<input|<img) ' . $re_attrs_fast_safe . ' >
                             \K  #any previously matched characters not to be included in the final matched sequence
                             [\x03-\x20]++
                           /sxiSX', '', $s);
        #вырезаем пробелы ПЕРЕД закрывающими тагами, если после тага есть пробел (+0.001 sec.)
        $a = preg_split('/ (?<=[\x03-\x20])
                           (<\/[a-zA-Z][a-zA-Z\d]*+>)  #1
                           (?=[\x03-\x20])
                         /sxSX', $s, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $s = implode('', array_map('rtrim', $a));

        #вырезаем лишние пробелы в начале и в конце переводов строк (+0.002 sec.)
        $s = self::strip_spaces($s);

        #восстанавливаем врЕменные метки на содержимое тагов
        $s = self::_html_placeholder($s, $is_restore = true);
        return str_replace(array('<nooptimize>', '</nooptimize>'), '', $s);
    }

    private static function _html_pre(array &$m)
    {
        return '<' . $m[1] . $m[2] . self::_html_placeholder($m[3]) . '>';
    }

    private static function _html_placeholder(/*string*/ $s, $is_restore = false)
    {
        static $tags = array();
        if ($is_restore)
        {
            #d($tags);
            $s = strtr($s, $tags);
            $tags = array();
            return $s;
        }
        $key = "\x01" . count($tags) . "\x02";
        $tags[$key] = $s;
        return $key;
    }

    #вырезаем комментарии
    private static function _html_chunks(array &$m)
    {
        if (@$m[6] === 'style')
        {
            if (self::$_html_is_css) $m[7] = self::css($m[7]);
            return self::_html_placeholder('style=' . self::strip_spaces($m[7]));
        }

        #условные комментарии IE не вырезаем!
        if (@$m[5]) return $m[0];
        if (preg_match('/^<!--(?:[\x20-\x7e]{4,60}+$|\xc2\xa0|&nbsp;)/sSX', $m[0]) &&  #\xc2\xa0 = &nbsp;
            ! preg_match('/<[a-zA-Z][a-zA-Z\d]*+ [^>]*+ >/sxSX', $m[0])) return $m[0];
        return '';
    }

}

?>