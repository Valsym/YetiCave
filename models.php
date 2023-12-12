<?php

function get_query_list_lots()
{
    $sql = "select title, c.name as cat_name, start_price, img, date_finish from lots
            inner join category as c 
                on category_id = c.id
            where winner_id is null and date_finish > now()                                     
            order by lots.id desc";
    return $sql;
}