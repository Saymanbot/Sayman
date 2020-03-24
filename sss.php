<?php
    $body = file_get_contents('php://input'); //Получаем в $body json строку
$arr = json_decode($body, true); //Разбираем json запрос на массив в переменную $arr
  
function cir_strrev($stroka){ //Так как функция strrev не умеет нормально переворачивать кириллицу, нужен костыль через массив. Создадим функцию
    preg_match_all('/./us', $stroka, $array); 
    return implode('',array_reverse($array[0]));
}
 
class TG {
  
    public $token = '1043515481:AAEv3rCKCnDC89dpHAVQGqak0OpFbjhimLA'; //Создаём публичную переменную для токена, который нужно отправлять каждый раз при использовании апи тг
  
    public function __construct($token) {
        $this->token = $token; //Забиваем в переменную токен при конструкте класса
    }
      
    public function send($id, $message) {   //Задаём публичную функцию send для отправки сообщений
        //Заполняем массив $data инфой, которую мы через api отправим до телеграмма
        $data = array(
            'chat_id'      => $id,
            'text'     => $message,
        );
        //Получаем ответ через функцию отправки до апи, которую создадим ниже
        $out = $this->request('sendMessage', $data);
        //И пусть функция вернёт ответ. Правда в данном примере мы это никак не будем использовать, пусть будет задаток на будущее
        return $out;
    }   
      
    public  function request($method, $data = array()) {
        $curl = curl_init(); //мутим курл-мурл в переменную. Для отправки предпочтительнее использовать курл, но можно и через file_get_contents если сервер не поддерживает
          
        curl_setopt($curl, CURLOPT_URL, 'https://api.telegram.org/bot' . $this->token .  '/' . $method);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST'); //Отправляем через POST
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); //Сами данные отправляемые
          
        $out = json_decode(curl_exec($curl), true); //Получаем результат выполнения, который сразу расшифровываем из JSON'a в массив для удобства
          
        curl_close($curl); //Закрываем курл
          
        return $out; //Отправляем ответ в виде массива
    }
//Сразу и создадим этот класс, который будет написан чуть позже
//Сюда пишем токен, который нам выдал бот
$tg = new tg('1043515481:AAEv3rCKCnDC89dpHAVQGqak0OpFbjhimLA');
  
$sms = $arr['message']['text']; //Получаем текст сообщения, которое нам пришло.
//О структуре этого массива который прилетел нам от телеграмма можно узнать из официальной документации.
  
//Сразу и id получим, которому нужно отправлять всё это назад
$tg_id = $arr['message']['chat']['id'];
  
//Перевернём строку задом-наперёд используя функцию cir_strrev
$sms_rev = cir_strrev($sms);
  
//Используем наш ещё не написанный класс, для отправки сообщения в ответ
$tg->send($tg_id, $sms_rev);
 
exit('ok'); //Обязательно возвращаем "ok", чтобы телеграмм не подумал, что запрос не дошёл

?>
