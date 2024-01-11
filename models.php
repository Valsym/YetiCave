<?php
/**
 * Получает кол-во лотов найденных после полнотекстового поиска в БД,
 * для не завершенных и не просроченных лотов
 *
 * @param $con ресурс соединения с БД
 * @param $search строка поиска
 * @return int кол-во найденных лотов или 0
 */
function get_count_lots($con, $search)
{
    $sql = "select * from lots as l
            where MATCH(l.title, l.lot_description) AGAINST('$search' IN BOOLEAN MODE)
            and l.winner_id is null and l.date_finish > now()";
    $res = mysqli_query($con, $sql);
    if ($res) {
        $records_count = (int)mysqli_num_rows($res);
    } else {
        $records_count = 0;
    }
    return $records_count;
}

/**
 * Получает масиив данных по лотам, найденным в БД по полнотекстовому поиску
 *
 * @param $con ресурс соединения с БД
 * @param $search строка поиска
 * @param $page_items лимит вывода кол-ва найденных лотов на странице
 * @param $offset смещение
 * @return array результирующий массив с данными найденных лотов
 */
function get_found_lots($con, $search, $page_items, $offset)
{
    $sql = "select l.id, l.title, c.name as cat_name, l.start_price, 
                    l.img, l.date_finish from lots as l
            inner join categories as c 
                on l.category_id = c.id
            where MATCH(l.title, l.lot_description) AGAINST('$search' IN BOOLEAN MODE)
            and l.winner_id is null and l.date_finish > now()
            order by l.id desc
            limit $page_items offset $offset";
    $res = mysqli_query($con, $sql);
    if (!$res) {
        $goods = [];
    } else {
        $goods = mysqli_fetch_all($res, MYSQLI_NUM);
    }
    return $goods;
}

/**
 * Формирует строку запроса к БД для получения списка данных по лотам,
 * для не завершенных и не просроченных лотов
 * @return string
 */
function get_query_list_lots()
{
    $sql = "select lots.id, title, c.name as cat_name, start_price, 
                    img, date_finish from lots
            inner join categories as c 
                on category_id = c.id
            where winner_id is null and date_finish > now()                                     
            order by lots.id desc";
    return $sql;
}

/**
 * Формирует sql-запрос для получения данных по лоту
 *
 * @param $lot_id номер лота
 * @return string строка sql-запроса
 */
function get_query_lot($lot_id)
{
    $sql = "select l.id, l.title, l.lot_description, c.name as cat_name,  
                    l.start_price, l.img, l.date_finish, l.step, l.author_id from lots as l
            inner join categories as c 
                on l.category_id = c.id
            where l.id = '$lot_id'";

    return $sql;
}

/**
 * Получает текущую ставку по лоту
 *
 * @param $con ресурс соединения с БД
 * @param $lot_id номер лота
 * @return int|mixed текущая ставка или 0, если ставок не было
 */
function get_current_price($con, $lot_id)
{
    $sql = "select price_bet from bets where lot_id = $lot_id 
            order by price_bet desc limit 1";
    $res = mysqli_query($con, $sql);
//    print_r($res);
    $price_bet = 0;
    if ($res->num_rows) {
        [$price_bet] = mysqli_fetch_array($res);
    }
    return $price_bet;
}

/**
 * Получает массив данных по ставкам, параметрам лота и категории лота
 *
 * @param $con ресурс соединения с БД
 * @param $user_id номер юзера
 * @return array|string результирующий массив и строка ошибки
 */
function get_bets($con, $user_id)
{
    // Так выбираются все ставки по лоту
    $sql = "select  DATE_FORMAT(b.date_bet, '%d.%m.%y %H:%i') AS date_bet, 
       b.price_bet, l.title, l.date_finish, c.name as cat_name, l.id from bets as b
            inner join lots as l on l.id = b.lot_id 
            inner join categories as c on c.id = l.category_id 
            where b.user_id = $user_id
            order by date_bet desc";
// ANY_VALUE - для агрегации любых атрибутов отношения (в данной ф-ии не используется)
    // Так выбираются только последнюю ставку юзера по лоту
    $sql = "select  DATE_FORMAT(b.date_bet, '%d.%m.%y %H:%i') AS date_bet, 
       b.price_bet, l.title, l.date_finish, c.name as cat_name, l.id from bets as b
           inner join (select max(price_bet) as pb, lot_id from bets group by lot_id ) 
               as b2 on b2.lot_id=b.lot_id and b2.pb = b.price_bet
            inner join lots as l on l.id = b.lot_id 
            inner join categories as c on c.id = l.category_id 
            where b.user_id = $user_id
            order by date_bet desc";
// Взято отсюда https://stackoverflow.com/questions/1641718/how-to-select-unique-records-by-sql
// см. пункт 6
    $res = mysqli_query($con, $sql);
    if ($res) {
        return mysqli_fetch_all($res, MYSQLI_ASSOC);
    } else {
//        return [];
        $error = mysqli_error($con);
        return $error;
    }
}

/**
 * Получает историю ставок по лоту
 *
 * @param $con ресурс соединения с БД
 * @param $lot_id номер лота
 * @return array|string массив историй ставок или строка ошибки
 */
function get_history_bets($con, $lot_id)
{
    $sql = "select DATE_FORMAT(b.date_bet, '%d.%m.%y %H:%i') AS date_bet, b.price_bet, u.user_name 
     from bets as b 
         inner join users as u on b.user_id = u.id
         where b.lot_id = $lot_id";
    $res = mysqli_query($con, $sql);
    if ($res) {
        return mysqli_fetch_all($res, MYSQLI_ASSOC);
    } else {
//        return [];
        $error = mysqli_error($con);
        return $error;
    }
}