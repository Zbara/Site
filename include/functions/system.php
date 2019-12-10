<?php
/**
 * @param $param
 * @param int $time
 * @return false|string
 */
function rdate($param, $time = 0)
{
    if (intval($time) == 0) $time = time();
    $MonthNames = array("Січня", "Лютого", "Березня", "Квітня", "Травня", "Червня", "Липня", "Серпня", "Вересня", "Жовтня", "Листопада", "Грудня");
    if (strpos($param, 'M') === false) return date($param, $time);
    else return date(str_replace('M', $MonthNames[date('n', $time) - 1], $param), $time);
}

/**
 * @return int
 */
function unixTime()
{
    return (int)$_SERVER['REQUEST_TIME'];
}

/**
 * @param $c
 * @return mixed
 */
function array_get($c)
{

    foreach ($c as $key => $value) {
        $params_arr[$key] = security($value);
    }
    return $params_arr;
}

/**
 * @param $value
 * @return array|mixed|string
 */
function security($value)
{


    if (is_array($value)) {
        $value = array_map('security', $value);
    } else {
        if (!get_magic_quotes_gpc()) {
            $value = htmlspecialchars($value, ENT_QUOTES);
        } else {
            $value = htmlspecialchars(stripslashes($value), ENT_QUOTES);
        }
        $value = str_replace("\\", "\\\\", $value);
    }
    return $value;
}


/**
 * @param $var
 * @return mixed
 */
function load_lang($var){
    global $lang;
    return $lang[$var];

}


/**
 * @param $t
 * @param bool $d
 * @param bool $b
 * @param bool $g
 * @return bool|mixed|string
 */
function view($t, $d = false, $b = false, $g = false)
{
    /** @проверка на шаблон */
    if ($t == '' || !file_exists(root . '/templates/' . DIRECTORY_SEPARATOR . $t)) {
        echo 'Неможливо завантажити шаблон: ' . $t;
        return false;
    }
    /** @var  $c */
    $c = file_get_contents(root . '/templates/' . $t);

	/** обновляяем файл */
    $d['timeFile'] = filemtime(root . '/css/style.css');
    $d['lang_id'] = ($_COOKIE['lang']) ? $_COOKIE['lang'] : 'ua';
    
    
    /** выполняем удаление блоков */
    if (is_array($b)) {
        foreach ($b as $key_replace) {
            $replace_preg[] = "'\{{$key_replace}\}(.*?)\{/{$key_replace}\}'si";
        }
        $c = preg_replace($replace_preg, '', $c);
    }
    
    /** выполняем замену */
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $c = str_replace('{' . $k . '}', $v, $c);
        }
    }


    $c = preg_replace_callback("#\\{translate=(.+?)\\}#is",
        function ($match) {
            return load_lang($match[1]);
        },  $c);
    
    /** return */
    return $c;
}


/*
function array_column($input, $columnKey, $indexKey = null)
{
    if (!is_array($input)) {
        return false;
    }
    if ($indexKey === null) {
        foreach ($input as $i => &$in) {
            if (is_array($in) && isset($in[$columnKey])) {
                $in = $in[$columnKey];
            } else {
                unset($input[$i]);
            }
        }
    } else {
        $result = array();
        foreach ($input as $i => $in) {
            if (is_array($in) && isset($in[$columnKey])) {
                if (isset($in[$indexKey])) {
                    $result[$in[$indexKey]] = $in[$columnKey];
                } else {
                    $result[] = $in[$columnKey];
                }
                unset($input[$i]);
            }
        }
        $input = &$result;
    }
    return $input;
}
*/

function echoJson($params)
{
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($params, JSON_UNESCAPED_UNICODE);
}

function installationSelected($id, $options)
{
    $source = str_replace('value="' . $id . '"', 'value="' . $id . '" selected', $options);
    return $source;
}



function viewMain($tpl, $argm, $main, $arg)
{
    global $system;

    $admin = (!$system->auth->users['user_info']['user_group'] == 1) ? 'admin' : false;
    return view($main, ['content' => view($tpl, $argm, $arg),
        'first_name' => $system->auth->users['user_info']['user_first_name'],
        'last_name' => $system->auth->users['user_info']['user_last_name'],
        'auth_hash' => $system->auth->users['session']['session_hash'],
        'ts' => unixTime(),
        'lang_id' => ($_COOKIE['lang']) ? $_COOKIE['lang'] : 'ua',
        'timeFile' => filemtime(root . '/css/style.css'),
        'tegs' => $system->action->controller,
        'admin' => null,
        '/admin' => null], [$admin]);
} 





/**
 * Тексты описаний ошибок
 */
$textErrors = [
    3 => "Помилка доступу. Ви намагаєтесь отримати доступ до методів адміністрування невалідним маркером",
    5 => "Пропущений обов'язковий параметр",
    6 => "Не https?:\\/\\/(m\\.)?vk\\.com\\/",
    7 => "Обов'язковий параметр для виконання методу пропущений або некоректний",
    8 => "Невідомий метод",
    9 => "Користувач не авторизований",
    10 => "Такий сесії не існує",
    11 => "Доступ заборонений",
    21 => "Не всі поля заповнені",
    22 => "Занадто багато запитів останнім часом. Можливо ви - Zf Grivachenko?",
    23 => "Доступ заборонено",
    24 => "Тікет не знайдено",
    25 => "Доступ до повноважень адміністратора для даного користувача заборонений",
    26 => "Доступ заборонено",
    27 => "Файл занадто великий",
    28 => "Несподівана помилка при завантаження зображення",
    34 => "Відповідь дуже коротка",
    47 => "Ошибка записи комментария в базу данных",
    48 => "Не нужно слать одни смайлики без текста. Они сбивают агентов поддержки. Спасибо!",
    49 => "invalid data url",
    51 => "Вы не имеете права создавать тикеты и отвечать в тикетах",
    52 => "Не допускается использование мата",
    29 => "Данный пользователь запретил показывать время своего последнего захода",
    30 => "Пользователь не найден",
    31 => "Введена не ссылка",
    32 => "Ссылка содержит не изображение",
    33 => "Ссылка содержит файл более 3МБ",
    40 => "Неверный идентификатор приложения",
    41 => "Просроченый access_token",
    42 => "Пользовательский access_token (user_token) не передан",
    44 => "Не удалось загрузить файл",
    45 => "Файл слишком большой",
    46 => "Ошибка! Вы не можете удалить это объявление!",
    50 => "Поисковой запрос пуст!",
    53 => "authKey/authId/userId is empty",
    54 => "authKey disabled",
    55 => "security error",
    69 => "Невідома помилка",
    70 => "Не всі поля заповнені",
    71 => "Некорректное приложение",
    72 => "Невірна пара логін / пароль",
    73 => "%system% NeedCaptcha",
    74 => "%system% NeedValidation",
    75 => "",
    76 => "",
    77 => "",
    120 => "Не заполнены все обязательные поля",
    121 => "Вы уже отвечали в опросе. Повторно нельзя.",
    140 => "",
    141 => "Вы не можете создать более 5 тем.",
    142 => "Не все поля заполнены",
    143 => "Некорректный CSS-код",
    144 => "Некорректный идентификатор темы",
    145 => "Тема не найдена",
    146 => "Ошибка доступа к теме",
    101 => "Метод відключений",
    501 => "У вас нет прав на исполнение этого метода",
    502 => "Ошибка при пересборке файла",
    4000 => "Такая пара уже есть",
    702 => "шибка подключение к ВК API "
];

/**
 * Возвращает текст ошибки по ID
 * @param  int $id Идентификатор ошибки
 * @return string  Текст ошибки
 */
function getErrorTextById($id) {
    global $textErrors;
    return $textErrors[$id] ?? "{e" . $id . "}";
};

function throwError($errorId, $extra = false) {

    $msg = ($errorId == 702) ? $extra : '';
    
    $data = [
        "errorId" => $errorId,
        "message" => getErrorTextById($errorId) . $msg,
        "params" => $_REQUEST
    ];

    if ($extra) {
        $data["extra"] = [];
        foreach ($extra as $key => $value) {
            $data["extra"][$key] = $value;
        };
    }; 

    output($data);
};


/**
 * Вывод данных в формате JSON
 * @param  mixed $data Данные
 */
function output($data) {
    $callback = $_REQUEST["callback"];
    $json = json_encode(["response" => $data], JSON_UNESCAPED_UNICODE);
    header("Content-type: " . ($callback ? "text/javascript" : "application/json") . "; charset=utf-8");
    $data = ($callback ? $callback . "(" : "") . $json . ($callback ? ");" : "");
    //header("Content-Length: " . mb_strlen($data));
    print $data;
 
};