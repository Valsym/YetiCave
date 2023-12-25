--
-- База данных: `yeticave`
--

-- --------------------------------------------------------

USE yeticave;

-- --------------------------------------------------------

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `date_registration`, `email`, `user_name`, `user_password`, `contacts`) VALUES
                                                                                                       (1, now(), 'test@test.com', 'Константин', 'xyz', '89035268435'),
                                                                                                       (2, now()+1, 'test2@test.com', 'Петя', 'xyz123', '89265843312');

--
-- Дамп данных таблицы `category`
--

INSERT INTO `categories` (`id`, `name`, `codename`) VALUES
                                                        (1, 'Доски и лыжи', 'boards'),
                                                        (2, 'Крепления', 'mounts'),
                                                        (3, 'Ботинки', 'boots'),
                                                        (4, 'Одежда', 'clothes'),
                                                        (5, 'Инструменты', 'tools'),
                                                        (6, 'Разное', 'others');
-- --------------------------------------------------------

--
-- Дамп данных таблицы `lots`
--

INSERT INTO `lots` (title, lot_description, img, start_price, date_finish, step, category_id, author_id) values
                                                                                                             ('2014 Rossignol District Snowboard', 'Легкий, маневренный сноуборд, готовый дать жару в любом парке', 'img/lot-1.jpg', 10999, '2023-12-21', 500, 1, 1),
                                                                                                             ('DC Ply Mens 2016/2017 Snowboard', 'Легкий, маневренный сноуборд, готовый дать жару в любом парке', 'img/lot-2.jpg', 15999, '2023-12-14', 500, 1, 1),
                                                                                                             ('Крепления Union Contact Pro 2015 года размер L/XL', 'Хорошие крепления. Надежные и легкие', 'img/lot-3.jpg', 8000, '2023-12-13', 100, 2, 1),
                                                                                                             ('Ботинки для сноуборда DC Mutiny Charocal', 'Теплые и красивые ботинки', 'img/lot-4.jpg', 10999,'2024-01-20', 300, 3, 2),
                                                                                                             ('Куртка для сноуборда DC Mutiny Charocal', 'Легкая, теплая и прочная куртка', 'img/lot-5.jpg', 7500, '2024-01-10', 100, 4, 2),
                                                                                                             ('Маска Oakley Canopy', 'Желтые очки, все будет веселенькое', 'img/lot-6.jpg', 5400, '2023-12-08', 100, 6, 1);

-- --------------------------------------------------------

--
-- Дамп данных таблицы `bets`
--

INSERT INTO `bets` (price_bet, user_id, lot_id) value
    (10499, 2, 1),
    (11999, 2, 1);

-- --------------------------------------------------------
--
-- получить все категории;
--
select name as catergory_name from category;

-- --------------------------------------------------------
--
-- получить самые новые, открытые лоты.
-- Каждый лот должен включать название, стартовую цену,
-- ссылку на изображение, цену, название категории;
--
select title, start_price, img, c.name as cat_name from lots as l
inner join category as c
    on c.id = l.category_id
    where l.winner_id is null
order by date_creation;

-- --------------------------------------------------------
-- показать лот по его ID. Получите также название категории,
-- к которой принадлежит лот;
--
select l.title, c.name from lots as l
inner join category as c
    on c.id = l.category_id
    where l.id = 1;


-- --------------------------------------------------------
-- обновить название лота по его идентификатору;
--
update lots set title = 'new ttle'
    where lots.id = 1;

update lots set title = '2014 Rossignol District Snowboard'
where lots.id = 1;
-- --------------------------------------------------------
-- получить список ставок для лота по его идентификатору
-- с сортировкой по дате
--
select date_bet, price_bet, l.title, u.user_name from bets as b
inner join lots as l
    on l.id = b.lot_id
inner join user as u
    on u.id = b.user_id
where l.id = 1
order by b.date_bet desc;


CREATE FULLTEXT INDEX lot_ft_search ON lots(title, lot_description);
