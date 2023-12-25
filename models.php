<?php
function get_count_lots($con, $search) {
    $sql = "select * from lots as l
            where MATCH(l.title, l.lot_description) AGAINST('$search' IN BOOLEAN MODE)
            and l.winner_id is null and l.date_finish > now()";
    $res = mysqli_query($con, $sql);
    if ($res) {
        $records_count = mysqli_num_rows($res);
    } else {
        $records_count = 0;
    }
    return $records_count;
}

function get_found_lots($con, $search, $page_items, $offset) {
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

function get_query_lot($lot_id)
{
    /*$sql = "select l.title, l.lot_description, c.name, x.curr_price,
                    l.start_price, l.img, l.date_finish from lots as l
            inner join category as c 
                on l.category_id = c.id                                                               
            CROSS JOIN (SELECT CASE 
                                    WHEN b.lot_id = '$lot_id'
                                        THEN b.price_bet
                                        else null 
                                    END AS curr_price
                        FROM bets as b) x
            where l.id = '$lot_id'";*/
    $sql = "select l.title, l.lot_description, c.name as cat_name,  
                    l.start_price, l.img, l.date_finish, l.step from lots as l
            inner join categories as c 
                on l.category_id = c.id
            where l.id = '$lot_id'";

    return $sql;
}